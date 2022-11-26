<?php

namespace Canopy\Ecommerce\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Canopy\Base\Http\Responses\BaseHttpResponse;
use Canopy\Ecommerce\Enums\OrderStatusEnum;
use Canopy\Ecommerce\Http\Requests\UpdateOrderRequest;
use Canopy\Ecommerce\Http\Resources\CustomerOrderHistoryResource;
use Canopy\Ecommerce\Http\Resources\RequestResource;
use Canopy\Ecommerce\Repositories\Interfaces\CustomerInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderHistoryInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderInterface;
use Canopy\Ecommerce\Repositories\Interfaces\ProductInterface;
use OrderHelper;
use EmailHelper;
use Canopy\Payment\Enums\PaymentStatusEnum;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TalentRequestController extends Controller
{
    /**
     * @var CustomerInterface
     */
    protected $customerRepository;

    /**
     * @var ProductInterface
     */
    protected $productRepository;

    /**
     * @var OrderInterface
     */
    protected $orderRepository;

    /**
     * @var OrderHistoryInterface
     */
    protected $orderHistoryRepository;

    /**
     * PublicController constructor.
     *
     * @param CustomerInterface $customerRepository
     * @param ProductInterface $productRepository
     * @param OrderInterface $orderRepository
     */
    public function __construct(
        CustomerInterface     $customerRepository,
        ProductInterface      $productRepository,
        OrderInterface        $orderRepository,
        OrderHistoryInterface $orderHistoryRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->productRepository = $productRepository;
        $this->orderRepository = $orderRepository;
        $this->orderHistoryRepository = $orderHistoryRepository;
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function index(Request $request, BaseHttpResponse $response)
    {
        $orders = $this->orderRepository->getModel()
            ->where('is_finished', 1)
            ->orderBy('created_at', 'desc')
            ->with('user', 'products.product.owner')
            ->paginate(10);

        $statusPriorities = ['accepted', 'pending', 'completed', 'rejected'];
        $collection = CustomerOrderHistoryResource::collection($orders);
        $collection->sortBy(function ($order) use ($statusPriorities) {
            return array_search($order['status'], $statusPriorities);
        })->values()->all();

        return $response
            ->setData($collection)
            ->toApiResponse();
    }

    /**
     * @param                             $id
     * @param Request                     $request
     * @param BaseHttpResponse            $response
     * @return BaseHttpResponse|JsonResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function get($id, Request $request, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->findById($id);

        if (null === $order) {
            return new JsonResponse(['error' => 'Record not found'], 404);
        }

        return $response
            ->setData(new RequestResource($order))
            ->toApiResponse();
    }

    /**
     * @param int $id
     * @param UpdateOrderRequest $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|JsonResponse
     */
    public function update($id, UpdateOrderRequest $request, BaseHttpResponse $response)
    {
        $order = $this->orderRepository->findById($id);

        if (null === $order) {
            return new JsonResponse(['error' => 'Record not found'], 404);
        }
        $this->orderRepository->createOrUpdate($request->input(), ['id' => $id]);

        return $response
            ->setMessage('Details updated successfully')
            ->setData([
                'id' => $id,
              ])
            ->toApiResponse();
    }
    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function rejectRequest($id, BaseHttpResponse $response)
    {
        $talent = auth('api-customer')->user()->talent;
        $order = $this->orderRepository->getFirstBy([
            'id' => $id,
            'talent_id' => $talent->id,
        ], ['*']);

        if (!$order) {
            return $response
                ->setError()
                ->setMessage('Permission denied, not an owner')
                ->toApiResponse();
        }

        if (!in_array($order->status, [ PaymentStatusEnum::PENDING, OrderStatusEnum::PROCESSING ])) {
            return $response
                ->setError()
                ->setMessage('Cannot update status')
                ->toApiResponse();
        }

        $this->orderRepository->createOrUpdate([
            'status' => OrderStatusEnum::REJECTED
        ], compact('id'));

        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('talent_reject_request')) {
            OrderHelper::setEmailVariables($order);
            $mailer->sendUsingTemplate(
                'talent_reject_request',
                $order->user->email ?: $order->address->email
            );
        }

        $this->orderHistoryRepository->createOrUpdate([
            'action'      => 'reject_request',
            'description' => __(
                'Request was rejected by :talent',
                ['talent' => $talent->name]
            ),
            'order_id'    => $order->id,
        ]);

        return $response
            ->setData([
                'action' => 'reject_request',
                'status' => 'success'
            ])
            ->setMessage('Request was rejected')
            ->toApiResponse();
    }

    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function acceptRequest($id, BaseHttpResponse $response)
    {
        $talent = auth('api-customer')->user()->talent;
        $order = $this->orderRepository->getFirstBy([
            'id' => $id,
            'talent_id' => $talent->id,
        ], ['*']);

        if (!$order) {
            return $response
                ->setError()
                ->setMessage('Permission denied, not an owner')
                ->toApiResponse();
        }

        if (!in_array($order->status, [ PaymentStatusEnum::PENDING, OrderStatusEnum::PROCESSING ])) {
            return $response
                ->setError()
                ->setMessage('Cannot update status')
                ->toApiResponse();
        }

        $this->orderRepository->createOrUpdate([
            'status' => OrderStatusEnum::ACCEPTED
        ], compact('id'));

        $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('talent_accept_request')) {
            OrderHelper::setEmailVariables($order);
            $mailer->sendUsingTemplate(
                'talent_accept_request',
                $order->user->email ?: $order->address->email
            );
        }

        $this->orderHistoryRepository->createOrUpdate([
            'action'      => 'accept_request',
            'description' => __(
                'Request was accepted by the talent :talent',
                ['talent' => $talent->name]
            ),
            'order_id'    => $order->id,
        ]);

        return $response
            ->setData([
                'action' => 'accept_request',
                'status' => 'success'
            ])
            ->setMessage('Request was accepted')
            ->toApiResponse();
    }

    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function releaseRequest($id, BaseHttpResponse $response)
    {
        $talent = auth('api-customer')->user()->talent;
        $order = $this->orderRepository->getFirstBy([
            'id' => $id,
            'talent_id' => $talent->id,
        ], ['*']);

        if (!$order) {
            return $response
                ->setError()
                ->setMessage('Permission denied, not an owner')
                ->toApiResponse();
        }

        if (!in_array($order->status, [ OrderStatusEnum::ACCEPTED ])) {
            return $response
                ->setError()
                ->setMessage('Cannot update status')
                ->toApiResponse();
        }

        $this->orderRepository->createOrUpdate([
            'status' => OrderStatusEnum::RELEASED
        ], compact('id'));

        /* $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('talent_complete_request')) {
            OrderHelper::setEmailVariables($order);
            $mailer->sendUsingTemplate(
                'talent_complete_request',
                $order->user->email ?: $order->address->email
            );
        } */

        $this->orderHistoryRepository->createOrUpdate([
            'action'      => 'complete_request',
            'description' => __(
                'Request was fulfilled by :talent',
                ['talent' => $talent->name]
            ),
            'order_id'    => $order->id,
        ]);

        return $response
            ->setData([
                'action' => 'complete_request',
                'status' => 'success'
            ])
            ->setMessage('Request was released for review')
            ->toApiResponse();
    }

    /**
     * @param $id
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse|\Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Resources\Json\JsonResource
     */
    public function cancelRequest($id, BaseHttpResponse $response)
    {
        $talent = auth('api-customer')->user()->talent;
        $order = $this->orderRepository->getFirstBy([
            'id' => $id,
            'talent_id' => $talent->id,
        ], ['*']);

        if (!$order) {
            return $response
                ->setError()
                ->setMessage('Permission denied, not an owner')
                ->toApiResponse();
        }

        if (!in_array($order->status, [ OrderStatusEnum::PENDING ])) {
            return $response
                ->setError()
                ->setMessage('Cannot update status')
                ->toApiResponse();
        }

        $this->orderRepository->createOrUpdate([
            'status' => OrderStatusEnum::CANCELED
        ], compact('id'));

        /* $mailer = EmailHandler::setModule(ECOMMERCE_MODULE_SCREEN_NAME);
        if ($mailer->templateEnabled('talent_complete_request')) {
            OrderHelper::setEmailVariables($order);
            $mailer->sendUsingTemplate(
                'talent_complete_request',
                $order->user->email ?: $order->address->email
            );
        } */

        $this->orderHistoryRepository->createOrUpdate([
            'action'      => 'cancel_request',
            'description' => __(
                'Request was cancelled by customer'
            ),
            'order_id'    => $order->id,
        ]);

        return $response
            ->setData([
                'action' => 'cancel_request',
                'status' => 'success'
            ])
            ->setMessage('Request was cancelled')
            ->toApiResponse();
    }
}
