<?php

namespace Canopy\Ecommerce\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RvMedia;

class RequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {

        $product = $this->products[0] ?? [];

        $productPayload = [
            'id' => $product->id,
            'name' => $product->product_name,
            'title' => $product->product->title,
            'has_cause' => (bool) $product->product->owner->has_cause,
            'photo' => $product->product->owner->photo ? RvMedia::url($product->product->owner->photo) : null,
        ];

        return [
            'id' => $this->id,
            'token' => $this->token,
            'order_code'  => get_order_code($this->id),
            'user' => $this->user,
            'talent' => [
                'id' => $this->talent->id,
                'first_name' => $this->talent->first_name,
                'last_name' => $this->talent->last_name,
                'photo' => $this->talent->photo ? RvMedia::url($this->talent->photo) : null,
                'has_cause' => $this->talent->has_cause ?? false,
            ],
            'product'     => $productPayload,
            'description' => $this->request,
            'audience' => $this->target_audience,
            'is_public' => (bool) $this->allow_public,
            'is_speed_service' => (bool) $this->is_speed_service,
            'status'      => $this->status,
            'order_date'  => $this->created_at,
            'amount'      => $this->amount,
            'amount_nice' => format_price($this->amount),
            'currency' => $this->currency_id,
            'from'        => $this->from,
            'recipient' => $this->recepient,
            'occasion' => $this->occasion,
            'video' => $this->video ? RvMedia::url($this->video) : null,
        ];
    }
}
