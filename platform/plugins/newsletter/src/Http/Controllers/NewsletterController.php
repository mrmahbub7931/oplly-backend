<?php

namespace Canopy\Newsletter\Http\Controllers;

use Canopy\Base\Events\DeletedContentEvent;
use Canopy\Base\Http\Controllers\BaseController;
use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Base\Traits\HasDeleteManyItemsTrait;
use Canopy\Newsletter\Repositories\Interfaces\NewsletterInterface;
use Canopy\Newsletter\Tables\NewsletterTable;
use Exception;
use Illuminate\Http\Request;

class NewsletterController extends BaseController
{
    use HasDeleteManyItemsTrait;

    /**
     * @var NewsletterInterface
     */
    protected $newsletterRepository;

    /**
     * NewsletterController constructor.
     * @param NewsletterInterface $newsletterRepository
     */
    public function __construct(NewsletterInterface $newsletterRepository)
    {
        $this->newsletterRepository = $newsletterRepository;
    }

    /**
     * @param NewsletterTable $dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function index(NewsletterTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/newsletter::newsletter.name'));

        return $dataTable->renderTable();
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
            $newsletter = $this->newsletterRepository->findOrFail($id);
            $this->newsletterRepository->delete($newsletter);

            event(new DeletedContentEvent(NEWSLETTER_MODULE_SCREEN_NAME, $request, $newsletter));

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
        return $this->executeDeleteItems(
            $request,
            $response,
            $this->newsletterRepository,
            NEWSLETTER_MODULE_SCREEN_NAME
        );
    }
}
