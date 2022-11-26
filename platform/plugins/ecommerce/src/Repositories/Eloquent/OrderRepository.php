<?php

namespace Canopy\Ecommerce\Repositories\Eloquent;

use Canopy\Ecommerce\Repositories\Interfaces\OrderInterface;
use Canopy\Payment\Enums\PaymentStatusEnum;
use Canopy\Support\Repositories\Eloquent\RepositoriesAbstract;
use DB;

class OrderRepository extends RepositoriesAbstract implements OrderInterface
{
    /**
     * {@inheritDoc}
     */
    public function getRevenueData($startDate, $endDate, $select = [])
    {
        if (empty($select)) {
            $select = [
                DB::raw('DATE(payments.created_at) AS date'),
                DB::raw('SUM(COALESCE(payments.amount, 0) - COALESCE(payments.refunded_amount, 0)) as revenue'),
            ];
        }
        $data = $this->model
            ->join('payments', 'payments.id', '=', 'ec_orders.payment_id')
            ->whereDate('payments.created_at', '>=', $startDate)
            ->whereDate('payments.created_at', '<=', $endDate)
            ->where('payments.status', PaymentStatusEnum::COMPLETED)
            ->groupBy('date')
            ->select($select);

        return $this->applyBeforeExecuteQuery($data)->get();
    }

    /**
     * {@inheritDoc}
     */
    public function getRevenueDataForTalentId($talentId, $startDate, $endDate, $select = [])
    {
        if (empty($select)) {
            $select = [
                DB::raw('DATE(payments.created_at) AS date'),
                DB::raw('SUM(COALESCE(payments.amount, 0) - COALESCE(payments.refunded_amount, 0)) as revenue'),
            ];
        }
        $data = $this->model
            ->join('payments', 'payments.id', '=', 'ec_orders.payment_id')
            ->whereDate('payments.created_at', '>=', $startDate)
            ->whereDate('payments.created_at', '<=', $endDate)
            ->where('ec_orders.talent_id', $talentId)
            ->where('payments.status', PaymentStatusEnum::COMPLETED)
            ->groupBy('date')
            ->select($select);

        return $this->applyBeforeExecuteQuery($data)->get();
    }


    /**
     * {@inheritDoc}
     */
    public function countRevenueByDateRange($startDate, $endDate)
    {
        $data = $this->model
            ->join('payments', 'payments.id', '=', 'ec_orders.payment_id')
            ->where('payments.created_at', '>=', $startDate)
            ->where('payments.created_at', '<=', $endDate)
            ->where('payments.status', PaymentStatusEnum::COMPLETED);

        return $this
            ->applyBeforeExecuteQuery($data)
            ->sum(DB::raw('COALESCE(payments.amount, 0) - COALESCE(payments.refunded_amount, 0)'));
    }

    /**
     * @param int $talentID
     * @param string|null $startDate
     * @param string|null $endDate
     * @return float
     */
    public function countRevenueByTalentIdDateRange(
        int $talentID,
        ?string $startDate = null,
        ?string $endDate = null
    ): float {
        $data = $this->model
            ->join('payments', 'payments.id', '=', 'ec_orders.payment_id')
            ->where('payments.status', PaymentStatusEnum::COMPLETED)
            ->where('ec_orders.talent_id', $talentID);

        if ($startDate !== null && $endDate !== null) {
            $data->where('payments.created_at', '>=', $startDate)
                ->where('payments.created_at', '<=', $endDate);
        }

        return (float) $this
            ->applyBeforeExecuteQuery($data)
            ->sum(DB::raw('COALESCE(payments.amount, 0) - COALESCE(payments.refunded_amount, 0)'));
    }

    /**
     * @param int $talentID
     * @param string|null $startDate
     * @param string|null $endDate
     * @return int
     */
    public function countRequestsByTalentIdDateRange(
        int $talentID,
        ?string $startDate = null,
        ?string $endDate = null
    ): int {
        $data = $this->model
            ->join('payments', 'payments.id', '=', 'ec_orders.payment_id')
            ->where('is_finished', '1')
            ->where('ec_orders.talent_id', $talentID);

        if ($startDate !== null && $endDate !== null) {
            $data->where('payments.created_at', '>=', $startDate)
                 ->where('payments.created_at', '<=', $endDate);
        }

        return $this
            ->applyBeforeExecuteQuery($data)
            ->count();
    }

    public function countOrdersByStatusTalentId(string $status, ?int $talentId): int
    {
        $data = $this->model
            ->where('status', $status)
            ->where('is_finished', '1');

        if ($talentId !== null) {
            $data->where('talent_id', $talentId);
        }

        /* if ($startDate !== null && $endDate !== null) {
            $data->where('payments.created_at', '>=', $startDate)
                ->where('payments.created_at', '<=', $endDate);
        }*/

        return $this
            ->applyBeforeExecuteQuery($data)
            ->count();
    }
}
