<?php

namespace Canopy\Ecommerce\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RvMedia;

class FavouritesResource extends JsonResource
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
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'talent' => [
                'id' => $this->product->owner->id,
                'name' => $this->product->name,
                'first_name' => $this->product->owner->first_name,
                'last_name' => $this->product->owner->last_name,
                'is_featured' => (bool) $this->product->owner->is_featured,
                'has_cause' => (bool) $this->owner->has_cause,
                'price' => $this->product->owner->price,
                'formatted_price' => format_price($this->product->price),
                'title' => $this->product->owner->title ?? '',
                'allow_speed_service' => (bool) $this->product->owner->allow_speed_service,
                'hidden_profile' => (bool) $this->product->owner->hidden_profile,
                'photo' => $this->product->owner->photo ? RvMedia::url($this->product->owner->photo) : null,
                'video' => $this->product->owner->video ? RvMedia::url($this->product->owner->video) : null,
            ]
        ];
    }
}
