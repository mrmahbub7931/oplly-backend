<?php

namespace Canopy\Dashboard\Http\Controllers;

use Assets;
use Exception;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\Factory;
use Canopy\Payment\Enums\PaymentStatusEnum;
use Canopy\Base\Http\Controllers\BaseController;
use Canopy\Base\Http\Responses\BaseHttpResponse;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Canopy\ACL\Repositories\Interfaces\UserInterface;
use Canopy\Ecommerce\Repositories\Interfaces\OrderInterface;
use Canopy\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Canopy\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;

class DashboardController extends BaseController
{

    /**
     * @var OrderInterface
     */
    protected $orderRepository;
    /**
     * @var DashboardWidgetSettingInterface
     */
    protected $widgetSettingRepository;

    /**
     * @var DashboardWidgetInterface
     */
    protected $widgetRepository;

    /**
     * @var UserInterface
     */
    protected $userRepository;

    /**
     * DashboardController constructor.
     * @param DashboardWidgetSettingInterface $widgetSettingRepository
     * @param DashboardWidgetInterface $widgetRepository
     * @param UserInterface $userRepository
     */
    public function __construct(
        DashboardWidgetSettingInterface $widgetSettingRepository,
        DashboardWidgetInterface $widgetRepository,
        UserInterface $userRepository,
        OrderInterface $order
    ) {
        $this->widgetSettingRepository = $widgetSettingRepository;
        $this->widgetRepository = $widgetRepository;
        $this->userRepository = $userRepository;
        $this->orderRepository = $order;
    }

    /**
     * @param Request $request
     * @return Factory|View
     */
    public function getDashboard(Request $request)
    {
        page_title()->setTitle(trans('core/dashboard::dashboard.title'));

        Assets::addScripts(['blockui', 'sortable', 'equal-height', 'counterup'])
            ->addScriptsDirectly('vendor/core/core/dashboard/js/dashboard.js')
            ->addStylesDirectly('vendor/core/core/dashboard/css/dashboard.css');

        do_action(DASHBOARD_ACTION_REGISTER_SCRIPTS);

        /**
         * @var Collection $widgets
         */
        $widgets = $this->widgetRepository->getModel()
            ->with([
                'settings' => function (HasMany $query) use ($request) {
                    $query->where('user_id', $request->user()->getKey())
                        ->select(['status', 'order', 'settings', 'widget_id'])
                        ->orderBy('order', 'asc');
                },
            ])
            ->select(['id', 'name'])
            ->get();
        $widgetData = apply_filters(DASHBOARD_FILTER_ADMIN_LIST, [], $widgets);

        ksort($widgetData);

        $availableWidgetIds = collect($widgetData)->pluck('id')->all();

        $widgets = $widgets->reject(function ($item) use ($availableWidgetIds) {
            return !in_array($item->id, $availableWidgetIds);
        });
        $count = [];
        $count['revenueToday'] = $this->orderRepository
            ->getModel()
            ->whereBetween('ec_orders.created_at', [now()->startOfDay()->toDateString(), now()->toDateString()])
            ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
            ->where('payments.status', PaymentStatusEnum::COMPLETED)
            ->sum('sub_total');
        
        $count['revenueWeek'] = $this->orderRepository
            ->getModel()
            ->whereBetween('ec_orders.created_at', [now()->startOfWeek()->toDateString(), now()->endOfWeek()->toDateString()])
            ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
            ->where('payments.status', PaymentStatusEnum::COMPLETED)
            ->sum('sub_total');

        $count['revenueMonth'] = $this->orderRepository
            ->getModel()
            ->whereBetween('ec_orders.created_at', [now()->startOfMonth()->toDateString(), now()->toDateString()])
            ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
            ->where('payments.status', PaymentStatusEnum::COMPLETED)
            ->sum('sub_total');
        
        $count['revenueYear'] = $this->orderRepository
            ->getModel()
            ->whereYear('ec_orders.created_at', date('Y'))
            ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
            ->where('payments.status', PaymentStatusEnum::COMPLETED)
            ->sum('sub_total');

        $count['requestDay'] = $this->orderRepository
            ->getModel()
            ->whereBetween('ec_orders.created_at', [now()->startOfDay()->toDateString(),now()->toDateString()])
            ->join('payments','payments.order_id','=','ec_orders.id')
            ->where('payments.status', PaymentStatusEnum::COMPLETED)
            ->count();

        $count['requestWeek'] = $this->orderRepository
            ->getModel()
            ->whereBetween('ec_orders.created_at', [now()->startOfWeek()->toDateString(),now()->endOfWeek()->toDateString()])
            ->join('payments','payments.order_id','=','ec_orders.id')
            ->where('payments.status', PaymentStatusEnum::COMPLETED)
            ->count();
        
        $count['requestMonth'] = $this->orderRepository
            ->getModel()
            ->whereBetween('ec_orders.created_at', [now()->startOfMonth()->toDateString(),now()->toDateString()])
            ->join('payments','payments.order_id','=','ec_orders.id')
            ->where('payments.status', PaymentStatusEnum::COMPLETED)
            ->count();
        
        $count['requestYear'] = $this->orderRepository
            ->getModel()
            ->whereYear('ec_orders.created_at',date('Y'))
            ->join('payments','payments.order_id','=','ec_orders.id')
            ->where('payments.status', PaymentStatusEnum::COMPLETED)
            ->count();

        $userWidgets = collect($widgetData)->pluck('view')->all();

        return view('core/dashboard::list', compact('widgets', 'userWidgets','count'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postEditWidgetSettingItem(Request $request, BaseHttpResponse $response)
    {
        try {
            $widget = $this->widgetRepository->getFirstBy([
                'name' => $request->input('name'),
            ]);

            if (!$widget) {
                return $response
                    ->setError()
                    ->setMessage(trans('core/dashboard::dashboard.widget_not_exists'));
            }
            $widgetSetting = $this->widgetSettingRepository->firstOrCreate([
                'widget_id' => $widget->id,
                'user_id'   => $request->user()->getKey(),
            ]);
            $widgetSetting->settings = array_merge((array)$widgetSetting->settings, [
                $request->input('setting_name') => $request->input('setting_value'),
            ]);
            $this->widgetSettingRepository->createOrUpdate($widgetSetting);
        } catch (Exception $ex) {
            return $response
                ->setError()
                ->setMessage($ex->getMessage());
        }

        return $response;
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postUpdateWidgetOrder(Request $request, BaseHttpResponse $response)
    {
        foreach ($request->input('items', []) as $key => $item) {
            $widget = $this->widgetRepository->firstOrCreate([
                'name' => $item,
            ]);
            $widgetSetting = $this->widgetSettingRepository->firstOrCreate([
                'widget_id' => $widget->id,
                'user_id'   => $request->user()->getKey(),
            ]);
            $widgetSetting->order = $key;
            $this->widgetSettingRepository->createOrUpdate($widgetSetting);
        }

        return $response->setMessage(trans('core/dashboard::dashboard.update_position_success'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function getHideWidget(Request $request, BaseHttpResponse $response)
    {
        $widget = $this->widgetRepository->getFirstBy([
            'name' => $request->input('name'),
        ], ['id']);
        if (!empty($widget)) {
            $widgetSetting = $this->widgetSettingRepository->firstOrCreate([
                'widget_id' => $widget->id,
                'user_id'   => $request->user()->getKey(),
            ]);
            $widgetSetting->status = 0;
            $widgetSetting->order = 99 + $widgetSetting->id;
            $this->widgetRepository->createOrUpdate($widgetSetting);
        }

        return $response->setMessage(trans('core/dashboard::dashboard.hide_success'));
    }

    /**
     * @param Request $request
     * @param BaseHttpResponse $response
     * @return BaseHttpResponse
     */
    public function postHideWidgets(Request $request, BaseHttpResponse $response)
    {
        $widgets = $this->widgetRepository->all();
        foreach ($widgets as $widget) {
            $widgetSetting = $this->widgetSettingRepository->firstOrCreate([
                'widget_id' => $widget->id,
                'user_id'   => $request->user()->getKey(),
            ]);
            if (array_key_exists(
                $widget->name,
                $request->input('widgets', [])
            ) && $request->input('widgets.' . $widget->name) == 1) {
                $widgetSetting->status = 1;
                $this->widgetRepository->createOrUpdate($widgetSetting);
            } else {
                $widgetSetting->status = 0;
                $widgetSetting->order = 99 + $widgetSetting->id;
                $this->widgetRepository->createOrUpdate($widgetSetting);
            }
        }

        return $response
            ->setNextUrl(route('dashboard.index'))
            ->setMessage(trans('core/dashboard::dashboard.hide_success'));
    }
}
