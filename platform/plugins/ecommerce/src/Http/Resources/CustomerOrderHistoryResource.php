<?php

namespace Canopy\Ecommerce\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RvMedia;

class CustomerOrderHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        $product = $this->products[0] ?? [];

        $productPayload = [
            'id' => $product->id,
            'name' => $product->product_name,
            'title' => $product->product->title,
            'has_cause' => $product->product->owner->has_cause ?? false,
            'photo' => isset($product->product->owner->photo) ? RvMedia::url($product->product->owner->photo) : null,
        ];

        return [
            'id'          => $this->id,
            'order_code'  => get_order_code($this->id),
            'customer'    => [
                'id' => $this->user->id,
                'name' => $this->user->name ?? '',
                'avatar' => $this->user->avatar ? RvMedia::url($this->user->avatar) : null,
            ],
            'product'     => $productPayload,
            'status'      => $this->status,
            'order_date'  => $this->created_at,
            'amount'      => $this->amount,
            'amount_nice' => format_price($this->amount),
            'from'        => $this->from,
            'recipient' => $this->recepient,
            'audience' => $this->target_audience,
            'occasion' => $this->occasion,
            'is_speed_service' => $this->is_speed_service,
            'video' => $this->video ? RvMedia::url($this->video) : null,
        ];
    }
}
