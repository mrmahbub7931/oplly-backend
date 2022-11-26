<?php

namespace Canopy\Ecommerce\Tables;

use BaseHelper;
use Canopy\Ecommerce\Models\Talent;
use Canopy\Ecommerce\Repositories\Interfaces\TalentInterface;
use Canopy\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class TalentTable extends TableAbstract
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
     * TalentTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param TalentInterface $TalentRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, TalentInterface $TalentRepository)
    {
        $this->repository = $TalentRepository;
        $this->setOption('id', 'table-talent');
        parent::__construct($table, $urlGenerator);

        if (!Auth::user()->hasAnyPermission(['talent.edit', 'talent.destroy'])) {
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
                if (!Auth::user()->hasPermission('talent.edit')) {
                    return $item->first_name . ' ' . $item->last_name;
                }

                return Html::link(route('talent.edit', $item->id), $item->first_name . ' ' . $item->last_name);
            })

            ->editColumn('email', function ($item) {
                return $item->email;
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
                return $this->getOperations('talent.edit', 'talent.destroy', $item);
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
            'ec_talent.id',
            'ec_talent.first_name',
            'ec_talent.last_name',
            'ec_talent.email',
            'ec_talent.gender',
            'ec_talent.created_at',
            'ec_talent.title',
            'ec_talent.bio',
            'ec_talent.status',
            'ec_talent.photo',
            'ec_talent.video',
            'ec_talent.verify_video',
            'ec_talent.dob',
        ];

        $query = $model->select($select);

        return $this->applyScopes(apply_filters(BASE_FILTER_TABLE_QUERY, $query, $model, $select));
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'         => [
                'name'  => 'ec_talent.id',
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
                'class' => 'text-left',
            ],
            'name'       => [
                'name'  => 'ec_talent.first_name',
                'title' => trans('core/base::forms.name'),
                'class' => 'text-left',
            ],
            'email'      => [
                'name'  => 'ec_talent.email',
                'title' => trans('plugins/ecommerce::talent.email'),
                'class' => 'text-left',
            ],
            'status'      => [
                'name'  => 'ec_talent.status',
                'title' => trans('plugins/ecommerce::talent.status'),
                'class' => 'text-left',
            ],
            'created_at' => [
                'name'  => 'ec_talent.created_at',
                'title' => trans('core/base::tables.created_at'),
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
        $buttons = $this->addCreateButton(route('talent.create'), 'talent.create');

        return apply_filters(BASE_FILTER_TABLE_BUTTONS, $buttons, Talent::class);
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('talent.deletes'), 'talent.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'ec_talent.first_name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'ec_talent.last_name'       => [
                'title'    => trans('core/base::tables.name'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'ec_talent.email'      => [
                'title'    => trans('core/base::tables.email'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'ec_talent.created_at' => [
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
            $this->request()->input('filter_table_id') !== $this->getOption('id')
        ) {
            return view('plugins/ecommerce::talent.intro');
        }

        return parent::renderTable($data, $mergeData);
    }

    /**
     * {@inheritDoc}
     */
    public function getDefaultButtons(): array
    {
        return [
            'export',
            'reload',
        ];
    }
}
