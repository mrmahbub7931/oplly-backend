<?php

namespace Canopy\Ecommerce\Tables;

use BaseHelper;
use Canopy\Base\Enums\BaseStatusEnum;
use Canopy\Ecommerce\Models\Occasion;
use Canopy\Ecommerce\Repositories\Interfaces\OccasionInterface;
use Canopy\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use RvMedia;
use Yajra\DataTables\DataTables;

class OccasionTable extends TableAbstract
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
     * OccasionTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param OccasionInterface $productCategoryRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        OccasionInterface $productCategoryRepository
    ) {
        $this->repository = $productCategoryRepository;
        $this->setOption('id', 'table-occasions');
        parent::__construct($table, $urlGenerator);

        if (!Auth::user()->hasAnyPermission([
            'occasions.edit',
            'occasions.destroy',
        ])) {
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
            ->editColumn('name', function ($item) {
                if (!Auth::user()->hasPermission('occasions.edit')) {
                    return $item->name;
                }

                return Html::link(route('occasions.edit', $item->id), $item->name);
            })
            ->editColumn('image', function ($item) {
                return Html::image(
                    RvMedia::getImageUrl($item->image, 'thumb', false, RvMedia::getDefaultImage()),
                    $item->name,
                    ['width' => 50]
                );
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            });

        return apply_filters(BASE_FILTER_GET_LIST_DATA, $data, $this->repository->getModel())
            ->addColumn('operations', function ($item) {
                return view('plugins/ecommerce::occasions.partials.actions', compact('item'))->render();
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
            'ec_occasions.id',
            'ec_occasions.name',
            'ec_occasions.status',
            'ec_occasions.order',
            'ec_occasions.image',
            'ec_occasions.created_at',
        ];

        $query = $model
            ->orderBy('ec_occasions.order', 'asc')
            ->select($select);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, $select));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'         => [
                'name'  => 'ec_occasions.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-left',
            ],
            'image'      => [
                'name'  => 'ec_occasions.image',
                'title' => trans('core/base::tables.image'),
                'width' => '70px',
                'class' => 'text-left',
            ],
            'name'       => [
                'name'  => 'ec_occasions.name',
                'title' => trans('core/base::tables.name'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name'  => 'ec_occasions.created_at',
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
                'class' => 'text-left',
            ],
            'status'     => [
                'name'  => 'ec_occasions.status',
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
                'class' => 'text-left',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function buttons()
    {
        $buttons = $this->addCreateButton(route('occasions.create'), 'occasions.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Occasion::class);
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(
            route('occasions.deletes'),
            'occasions.destroy',
            parent::bulkActions()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'ec_occasions.name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'ec_occasions.status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'select',
                'choices'  => BaseStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', BaseStatusEnum::values()),
            ],
            'ec_occasions.created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function renderTable($data = [], $mergeData = [])
    {
        if ($this->query()->count() === 0 &&
            !$this->request()->wantsJson() &&
            $this->request()->input('filter_table_id') !== $this->getOption('id')
        ) {
            return view('plugins/ecommerce::occasions.intro');
        }

        return parent::renderTable($data, $mergeData);
    }
}
