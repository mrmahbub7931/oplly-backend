<?php

namespace Canopy\Page\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Canopy\Base\Events\BeforeEditContentEvent;
use Canopy\Base\Events\CreatedContentEvent;
use Canopy\Base\Events\DeletedContentEvent;
use Canopy\Base\Events\UpdatedContentEvent;
use Canopy\Base\Http\Controllers\BaseController;
use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Base\Traits\HasDeleteManyItemsTrait;
use Canopy\Base\Supports\Helper;
use Canopy\Page\Forms\PageForm;
use Canopy\Page\Tables\PageTable;
use Canopy\Page\Http\Requests\PageRequest;
use Canopy\Page\Repositories\Interfaces\PageInterface;
use Exception;
use Illuminate\Http\Request;
use Canopy\Base\Forms\FormBuilder;
use Illuminate\View\View;
use Throwable;

class PageController extends BaseController
{

    use HasDeleteManyItemsTrait;

    /**
     * @var PageInterface
     */
    protected $pageRepository;

    /**
     * PageController constructor.
     * @param PageInterface $pageRepository
     */
    public function __construct(PageInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    /**
     * @param PageTable $dataTable
     * @return JsonResponse|View
     *
     * @throws Throwable
     */
    public function index(PageTable $dataTable)
    {
        page_title()->setTitle(trans('packages/page::pages.menu_name'));

        return $dataTable->renderTable();
    }

    /**
     * @return string
     */
    public function create(FormBuilder $formBuilder)
    {
        page_title()->setTitle(trans('packages/page::pages.create'));

        return $formBuilder->create(PageForm::class)->renderForm();
    }

    /**
     * @param PageRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function store(PageRequest $request, BaseHttpResponse $response)
    {
        $page = $this->pageRepository->createOrUpdate(array_merge($request->input(), [
            'user_id' => Auth::user()->getKey(),
        ]));

        event(new CreatedContentEvent(PAGE_MODULE_SCREEN_NAME, $request, $page));

        return $response->setPreviousUrl(route('pages.index'))
            ->setNextUrl(route('pages.edit', $page->id))
            ->setMessage(trans('core/base::notices.create_success_message'));
    }

    /**
     * @param Request $request
     * @param $id
     * @param FormBuilder $formBuilder
     * @return string
     */
    public function edit($id, FormBuilder $formBuilder, Request $request)
    {
        $page = $this->pageRepository->findOrFail($id);

        page_title()->setTitle(trans('packages/page::pages.edit') . ' "' . $page->name . '"');

        event(new BeforeEditContentEvent($request, $page));

        return $formBuilder->create(PageForm::class, ['model' => $page])->renderForm();
    }

    /**
     * @param $id
     * @param PageRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function update($id, PageRequest $request, BaseHttpResponse $response)
    {
        $page = $this->pageRepository->findOrFail($id);
        $page->fill($request->input());

        $page = $this->pageRepository->createOrUpdate($page);

        event(new UpdatedContentEvent(PAGE_MODULE_SCREEN_NAME, $request, $page));
        Helper::clearCache();
        return $response
            ->setPreviousUrl(route('pages.index'))
            ->setMessage(trans('core/base::notices.update_success_message'));
    }

    /**
     * @param Request $request
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function destroy(Request $request, $id, BaseHttpResponse $response)
    {
        try {
            $page = $this->pageRepository->findOrFail($id);
            $this->pageRepository->delete($page);

            event(new DeletedContentEvent(PAGE_MODULE_SCREEN_NAME, $request, $page));

            return $response->setMessage(trans('packages/page::pages.deleted'));
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
        return $this->executeDeleteItems($request, $response, $this->pageRepository, PAGE_MODULE_SCREEN_NAME);
    }
}
