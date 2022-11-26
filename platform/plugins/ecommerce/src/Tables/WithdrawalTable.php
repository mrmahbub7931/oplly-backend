<?php

namespace Canopy\Ecommerce\Tables;

use BaseHelper;
use Canopy\Ecommerce\Enums\WithdrawalStatusEnum;
use Canopy\Ecommerce\Models\Withdrawal;
use Canopy\Ecommerce\Repositories\Interfaces\WithdrawalInterface;
use Canopy\Table\Abstracts\TableAbstract;
use EcommerceHelper;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class WithdrawalTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * WithdrawalTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param WithdrawalInterface $withdrawalRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, WithdrawalInterface $withdrawalRepository)
    {
        $this->repository = $withdrawalRepository;
        $this->setOption('id', 'table-withdrawal');
        parent::__construct($table, $urlGenerator);

        if (!Auth::user()->hasPermission('withdrawals.edit')) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('talent', function ($item) {
                if (!Auth::user()->hasPermission('withdrawal.edit')) {
                    return $item->talent->first_name . ' ' . $item->talent->last_name;
                }

                return Html::link(
                    route('withdrawal.edit', $item->id),
                    $item->talent->first_name . ' ' . $item->talent->last_name
                );
            })

            ->editColumn('amount', function ($item) {
                return $item->amount;
            })

            ->editColumn('status', function ($item) {
                return $item->status;
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return $this->getOperations('withdrawal.edit', 'withdrawal.destroy', $item);
            })
            ->escapeColumns([])
            ->make(true);
    }


    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $model = $this->repository->getModel();
        $select = [
            'ec_withdrawals.id',
            'ec_withdrawals.status',
            'ec_withdrawals.talent_id',
            'ec_withdrawals.created_at',
            'ec_withdrawals.amount',
        ];

        $query = $model
            ->select($select)
            ->with(['talent']);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, $select));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        $columns = [
            'id'              => [
                'name'  => 'ec_withdrawals.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-left',
            ],
            'talent'         => [
                'name'  => 'ec_withdrawals.talent_id',
                'title' => trans('plugins/ecommerce::withdrawal.talent_label'),
                'class' => 'text-left',
            ],
            'amount'          => [
                'name'  => 'ec_withdrawals.amount',
                'title' => trans('plugins/ecommerce::withdrawal.amount'),
                'class' => 'text-center',
            ],
            'status'          => [
                'name'  => 'ec_withdrawals.status',
                'title' => trans('core/base::tables.status'),
                'class' => 'text-center',
            ],
            'created_at'      => [
                'name'  => 'ec_withdrawals.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
                'class' => 'text-left',
            ],
        ];

        return $columns;
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        $buttons = $this->addCreateButton(route('withdrawal.create'));

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Withdrawal::class);
    }


    public function renderTable($data = [], $mergeData = [])
    {
        if ($this->query()->count() === 0 &&
            !$this->request()->wantsJson() &&
            $this->request()->input('filter_table_id') !== $this->getOption('id')
        ) {
            return view('plugins/ecommerce::withdrawals.intro');
        }

        return parent::renderTable($data, $mergeData);
    }
}
