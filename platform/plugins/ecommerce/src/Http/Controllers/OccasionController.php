<?php

namespace Canopy\Ecommerce\Http\Controllers;

use Canopy\Base\Events\CreatedContentEvent;
use Canopy\Base\Events\DeletedContentEvent;
use Canopy\Base\Events\UpdatedContentEvent;
use Canopy\Base\Forms\FormBuilder;
use Canopy\Base\Http\Controllers\BaseController;
use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Ecommerce\Forms\OccasionForm;
use Canopy\Ecommerce\Http\Requests\OccasionRequest;
use Canopy\Ecommerce\Repositories\Interfaces\OccasionInterface;
use Canopy\Ecommerce\Tables\OccasionTable;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class OccasionController extends BaseController
{
    /**
     * @var OccasionInterface
     */
    protected $occasionRepository;

    /**
     * OccasionController constructor.
     * @param OccasionInterface $occasionRepository
     */
    public function __construct(OccasionInterface $occasionRepository)
    {
        $this->occasionRepository = $occasionRepository;
    }

    /**
     * @param OccasionTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(OccasionTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/ecommerce::occasions.name'));

        return $dataTable->renderTable();
    }

    /**
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('plugins/ecommerce::occasions.create'));

        return $formBuilder->create(OccasionForm::class)->renderForm();
    }

    /**
     * @param OccasionRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(OccasionRequest $request, BaseHttpResponse $response)
    {
        $occasion = $this->occasionRepository->createOrUpdate($request->input());
        event(new CreatedContentEvent(OCCASION_MODULE_SCREEN_NAME, $request, $occasion));

        return $response
            ->setPreviousUrl(route('occasions.index'))
            ->setNextUrl(route('occasions.edit', $occasion->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param int $id
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder)
    {
        $category = $this->occasionRepository->findOrFail($id);

        page_title()->setTitle(trans('plugins/ecommerce::occasions.edit') . ' "' . $category->name . '"');

        return $formBuilder->create(OccasionForm::class, ['model' => $category])->renderForm();
    }

    /**
     * @param int $id
     * @param OccasionRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, OccasionRequest $request, BaseHttpResponse $response)
    {
        $occasion = $this->occasionRepository->findOrFail($id);
        $occasion->fill($request->input());

        $this->occasionRepository->createOrUpdate($occasion);
        event(new UpdatedContentEvent(PRODUCT_CATEGORY_MODULE_SCREEN_NAME, $request, $occasion));

        return $response
            ->setPreviousUrl(route('occasions.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param int $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $occasion = $this->occasionRepository->findOrFail($id);

            $this->occasionRepository->delete($occasion);
            event(new DeletedContentEvent(PRODUCT_CATEGORY_MODULE_SCREEN_NAME, $request, $occasion));
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
            $occasion = $this->occasionRepository->findOrFail($id);
            $this->occasionRepository->delete($occasion);
            event(new DeletedContentEvent(PRODUCT_CATEGORY_MODULE_SCREEN_NAME, $request, $occasion));
        }

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
