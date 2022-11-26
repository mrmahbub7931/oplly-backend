<?php

namespace Canopy\Ecommerce\Http\Controllers;

use Canopy\Base\Events\BeforeEditContentEvent;
use Canopy\Ecommerce\Http\Requests\FlashSaleRequest;
use Canopy\Ecommerce\Models\FlashSale;
use Canopy\Ecommerce\Repositories\Interfaces\FlashSaleInterface;
use Canopy\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Exception;
use Canopy\Ecommerce\Tables\FlashSaleTable;
use Canopy\Base\Events\CreatedContentEvent;
use Canopy\Base\Events\DeletedContentEvent;
use Canopy\Base\Events\UpdatedContentEvent;
use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Ecommerce\Forms\FlashSaleForm;
use Canopy\Base\Forms\FormBuilder;
use Illuminate\Support\Arr;

class FlashSaleController extends BaseController
{
    /**
     * @var FlashSaleInterface
     */
    protected $flashSaleRepository;

    /**
     * @param FlashSaleInterface $flashSaleRepository
     */
    public function __construct(FlashSaleInterface $flashSaleRepository)
    {
        $this->flashSaleRepository = $flashSaleRepository;
    }

    /**
     * @param FlashSaleTable $table
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(FlashSaleTable $table)
    {
        page_title()->setTitle(trans('plugins/ecommerce::flash-sale.name'));

        return $table->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ecommerce::flash-sale.create'));

        return $formBuilder->create(FlashSaleForm::class)->renderForm();
    }

    /**
     * @param FlashSaleRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(FlashSaleRequest $request, BaseHttpResponse $response)
    {
        $flashSale = $this->flashSaleRepository->createOrUpdate($request->input());

        event(new CreatedContentEvent(FLASH_SALE_MODULE_SCREEN_NAME, $request, $flashSale));

        $this->storeProducts($request, $flashSale);

        return $response
            ->setPreviousUrl(route('flash-sale.index'))
            ->setNextUrl(route('flash-sale.edit', $flashSale->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param FlashSaleRequest $request
     * @param FlashSale $flashSale
     * @return int
     */
    protected function storeProducts(FlashSaleRequest $request, FlashSale $flashSale)
    {
        $products = array_filter(explode(',', $request->input('products')));

        foreach ($products as $index => $productId) {
            if (!(int)$productId) {
                continue;
            }

            $extra = Arr::get($request->input('products_extra', []), $index);

            if (!$extra || !isset($extra['price']) || !isset($extra['quantity'])) {
                continue;
            }

            $extra['price'] = (float)$extra['price'];
            $extra['quantity'] = (int)$extra['quantity'];

            $flashSale->products()->sync([(int) $productId => $extra]);
        }

        return count($products);
    }

    /**
     * @param $id
     * @param Request $request
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $flashSale = $this->flashSaleRepository->findOrFail($id);

        event(new BeforeEditContentEvent($request, $flashSale));

        page_title()->setTitle(trans('plugins/ecommerce::flash-sale.edit') . ' "' . $flashSale->name . '"');

        return $formBuilder->create(FlashSaleForm::class, ['model' => $flashSale])->renderForm();
    }

    /**
     * @param int $id
     * @param FlashSaleRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, FlashSaleRequest $request, BaseHttpResponse $response)
    {
        /**
         * @var FlashSale
         */
        $flashSale = $this->flashSaleRepository->findOrFail($id);

        $flashSale->fill($request->input());

        $this->flashSaleRepository->createOrUpdate($flashSale);

        $this->storeProducts($request, $flashSale);

        event(new UpdatedContentEvent(FLASH_SALE_MODULE_SCREEN_NAME, $request, $flashSale));

        return $response
            ->setPreviousUrl(route('flash-sale.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param $id
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $flashSale = $this->flashSaleRepository->findOrFail($id);

            $this->flashSaleRepository->delete($flashSale);

            event(new DeletedContentEvent(FLASH_SALE_MODULE_SCREEN_NAME, $request, $flashSale));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $exception) {
            return $response
                ->setError()
                ->setMessage($exception->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Exception
     */
    public function deletes(Request $request, BaseHttpResponse $response)
    {
        $ids = $request->input('ids');
        if (empty($ids)) {
            return $response
                ->setError()
                ->setMessage(trans('core/base::notices.no_select'));
        }

        foreach ($ids as $id) {
            $flashSale = $this->flashSaleRepository->findOrFail($id);
            $this->flashSaleRepository->delete($flashSale);
            event(new DeletedContentEvent(FLASH_SALE_MODULE_SCREEN_NAME, $request, $flashSale));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
