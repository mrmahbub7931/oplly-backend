<?php

namespace Canopy\Ecommerce\Tables;

use BaseHelper;
use Canopy\Ecommerce\Models\Region;
use Canopy\Ecommerce\Repositories\Interfaces\RegionInterface;
use Canopy\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class RegionTable extends TableAbstract
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
     * RegionTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param RegionInterface $regionRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, RegionInterface $regionRepository)
    {
        $this->repository = $regionRepository;
        $this->setOption('id', 'table-region');
        parent::__construct($table, $urlGenerator);

        if (!Auth::user()->hasPermission('regions.edit')) {
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
                if (!Auth::user()->hasPermission('region.edit')) {
                    return $item->talent->first_name . ' ' . $item->talent->last_name;
                }

                return Html::link(
                    route('region.edit', $item->id),
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
                return $this->getOperations('region.edit', 'region.destroy', $item);
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
            'ec_regions.id',
            'ec_regions.status',
            'ec_regions.talent_id',
            'ec_regions.created_at',
            'ec_regions.amount',
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
                'name'  => 'ec_regions.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-left',
            ],
            'talent'         => [
                'name'  => 'ec_regions.talent_id',
                'title' => trans('plugins/ecommerce::region.talent_label'),
                'class' => 'text-left',
            ],
            'amount'          => [
                'name'  => 'ec_regions.amount',
                'title' => trans('plugins/ecommerce::region.amount'),
                'class' => 'text-center',
            ],
            'status'          => [
                'name'  => 'ec_regions.status',
                'title' => trans('core/base::tables.status'),
                'class' => 'text-center',
            ],
            'created_at'      => [
                'name'  => 'ec_regions.created_at',
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
        $buttons = $this->addCreateButton(route('region.create'));

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Region::class);
    }


    public function renderTable($data = [], $mergeData = [])
    {
        if ($this->query()->count() === 0 &&
            !$this->request()->wantsJson() &&
            $this->request()->input('filter_table_id') !== $this->getOption('id')
        ) {
            return view('plugins/ecommerce::regions.intro');
        }

        return parent::renderTable($data, $mergeData);
    }

}
