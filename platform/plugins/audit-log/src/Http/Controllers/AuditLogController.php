<?php

namespace Canopy\AuditLog\Http\Controllers;

use Canopy\AuditLog\Repositories\Interfaces\AuditLogInterface;
use Canopy\AuditLog\Tables\AuditLogTable;
use Canopy\Base\Events\DeletedContentEvent;
use Canopy\Base\Http\Controllers\BaseController;
use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Base\Traits\HasDeleteManyItemsTrait;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class AuditLogController extends BaseController
{

    use HasDeleteManyItemsTrait;

    /**
     * @var AuditLogInterface
     */
    protected $auditLogRepository;

    /**
     * AuditLogController constructor.
     * @param AuditLogInterface $auditLogRepository
     */
    public function __construct(AuditLogInterface $auditLogRepository)
    {
        $this->auditLogRepository = $auditLogRepository;
    }

    /**
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     * @throws Throwable
     */
    public function getWidgetActivities(BaseHttpResponse $response)
    {
        $limit = request()->input('paginate', 10);
        $histories = $this->auditLogRepository
            ->getModel()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        return $response
            ->setData(view('plugins/audit-log::widgets.activities', compact('histories', 'limit'))->render());
    }

    /**
     * @param AuditLogTable $dataTable
     * @return Factory|View
     * @throws Throwable
     */
    public function index(AuditLogTable $dataTable)
    {
        page_title()->setTitle(trans('plugins/audit-log::history.name'));

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
            $log = $this->auditLogRepository->findOrFail($id);
            $this->auditLogRepository->delete($log);

            event(new DeletedContentEvent(AUDIT_LOG_MODULE_SCREEN_NAME, $request, $log));

            return $response->setMessage(trans('core/base::notices.delete_success_message'));
        } catch (Exception $ex) {
            return $response
                ->setError()
                ->setMessage($ex->getMessage());
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
        return $this->executeDeleteItems($request, $response, $this->auditLogRepository, AUDIT_LOG_MODULE_SCREEN_NAME);
    }

    /**
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function deleteAll(BaseHttpResponse $response)
    {
        $this->auditLogRepository->getModel()->truncate();

        return $response->setMessage(trans('core/base::notices.delete_success_message'));
    }
}
