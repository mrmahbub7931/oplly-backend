<?php

namespace Canopy\Ecommerce\Tables\Reports;

use Canopy\Ecommerce\Repositories\Interfaces\ProductInterface;
use Canopy\Payment\Enums\PaymentStatusEnum;
use Canopy\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Yajra\DataTables\DataTables;

class TopSellingProductsTable extends TableAbstract
{

    /**
     * @var string
     */
    protected $type = self::TABLE_TYPE_SIMPLE;

    /**
     * @var string
     */
    protected $view = 'core/table::simple-table';

    /**
     * TopSellingProductsTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param ProductInterface $productRepository
     */
    public function __construct(
        DataTables $table,
        UrlGenerator $urlGenerator,
        ProductInterface $productRepository
    ) {
        parent::__construct($table, $urlGenerator);
        $this->repository = $productRepository;
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        return $this->table
            ->eloquent($this->query())
            ->editColumn('id', function ($item) {
                if (!$item->is_variation) {
                    return $item->id;
                }

                return $item->original_product->id;
            })
            ->editColumn('name', function ($item) {
                if (!$item->is_variation) {
                    return Html::link($item->url, $item->name, ['target' => '_blank']);
                }

                $attributeText = '';
                $attributes = get_product_attributes($item->id);
                if (!empty($attributes)) {
                    $attributeText .= ' (';
                    foreach ($attributes as $index => $attribute) {
                        $attributeText .= $attribute->attribute_set_title . ': ' . $attribute->title;
                        if ($index < count($attributes) - 1) {
                            $attributeText .= ', ';
                        }
                    }
                    $attributeText .= ')';
                }

                return Html::link($item->original_product->url, $item->original_product->name, ['target' => '_blank'])->toHtml() . Html::tag('small', $attributeText);
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * @return mixed
     */
    public function query()
    {
        $query = $this->repository
            ->getModel()
            ->join('ec_order_product', 'ec_products.id', '=', 'ec_order_product.product_id')
            ->join('ec_orders', 'ec_orders.id', '=', 'ec_order_product.order_id')
            ->join('payments', 'payments.order_id', '=', 'ec_orders.id')
            ->where('payments.status', PaymentStatusEnum::COMPLETED)

            ->whereDate('ec_orders.created_at', '>=', now()->startOfMonth()->toDateString())
            ->whereDate('ec_orders.created_at', '<=', now()->endOfMonth()->toDateString())

            ->selectRaw(
                'ec_products.id, '.
                'ec_products.name, '.
                'ec_order_product.qty'
            )
            ->orderBy('ec_order_product.qty', 'DESC');

        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'   => [
                'name'      => 'ec_products.id',
                'title'     => 'ID',
                'width'     => '80px',
                'orderable' => false,
                'class'     => 'no-sort text-center',
            ],
            'name' => [
                'name'      => 'ec_products.name',
                'title'     => 'Talent',
                'orderable' => false,
                'class'     => 'text-left',
            ],
            'qty'          => [
                'name'      => 'ec_order_product.qty',
                'title'     => 'Requests',
                'orderable' => false,
                'class'     => 'text-center',
                'width'     => '60px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function renderTable($data = [], $mergeData = [])
    {
        if ($this->query()->count() == 0) {
            return view('core/dashboard::partials.no-data')->render();
        }
        return parent::renderTable($data, $mergeData);
    }
}
