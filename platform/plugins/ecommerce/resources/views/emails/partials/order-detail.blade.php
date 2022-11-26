<div class="table">
    <table>
        <tr>
            <th style="text-align: left">
                {{ trans('plugins/ecommerce::products.form.product') }}
            </th>
            <th style="text-align: left">
            </th>
            <th style="text-align: left">
            </th>
            <th style="text-align: left">
                {{ trans('plugins/ecommerce::products.form.total') }}
            </th>
        </tr>

        @foreach ($order->products as $orderProduct)
        @php
        $product = get_products([
        'condition' => [
        'ec_products.status' => \Canopy\Base\Enums\BaseStatusEnum::PUBLISHED,
        'ec_products.id' => $orderProduct->product_id,
        ],
        'take' => 1,
        'select' => [
        'ec_products.id',
        'ec_products.name',
        'ec_products.price',
        'ec_products.sale_price',
        'ec_products.sale_type',
        'ec_products.start_date',
        'ec_products.end_date',
        'ec_products.sku',
        ],
        ])
        @endphp

        @if ($product)
        <tr>
            <td>
                {{ $product->name }}
                {{--}}@php $attributes = get_product_attributes($product->id) @endphp
                @if (!empty($attributes))
                (<small>
                    @foreach ($attributes as $attribute)
                    {{ $attribute->attribute_set_title }}: {{ $attribute->title }}@if (!$loop->last), @endif
                    @endforeach
                </small>)
                @endif--}}
            </td>

            <td>
            </td>

            <td>
            </td>

            <td>
                {{ format_price($orderProduct->qty * $orderProduct->price) }}
            </td>

        </tr>
        @endif
        @endforeach
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>
                {{ trans('plugins/ecommerce::products.form.sub_total') }}
            </td>
            <td>
                {{ format_price($order->sub_total) }}
            </td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>{{ trans('plugins/ecommerce::products.form.shipping_fee') }}
            </td>
            <td>
                {{ format_price($order->shipping_amount) }}
            </td>
        </tr>

        @if (EcommerceHelper::isTaxEnabled())
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>{{ trans('plugins/ecommerce::products.form.tax') }}
            </td>
            <td>
                {{ format_price($order->tax_amount) }}
            </td>
        </tr>
        @endif

        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>{{ trans('plugins/ecommerce::products.form.discount') }}
            </td>
            <td>
                {{ format_price($order->discount_amount) }}
            </td>
        </tr>

        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>

            <td>{{ trans('plugins/ecommerce::products.form.total') }}
            </td>
            <td>
                {{ format_price($order->amount) }}
            </td>
        </tr>
    </table><br>
</div>
