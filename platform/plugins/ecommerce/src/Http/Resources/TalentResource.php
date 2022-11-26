<?php

namespace Canopy\Ecommerce\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RvMedia;

class TalentResource extends JsonResource
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
        $services = [
            [
                'name' => 'Personal Request',
                'type' => 'personal',
                'amount' => $this->owner->price,
                'amount_nice' => format_price($this->price),
            ]
        ];

        if ((bool)$this->owner->allow_business) {
            $services[] = [
                'name' => 'Business Request',
                'type' => 'corporate',
                'amount' => $this->owner->business_price,
                'amount_nice' => format_price($this->owner->business_price),
            ];
        }

        if ((bool)$this->owner->allow_live) {
            $services[] = [
                'name' => 'Book Live',
                'type' => 'live',
                'amount' => $this->owner->live_price,
                'amount_nice' => format_price($this->owner->live_price),
            ];
        }

        if ((bool)$this->owner->allow_discount) {
            $services[] = [
                'name' => 'Discount Percentage',
                'type' => 'discount',
                'amount' => $this->owner->discount_percentage,
                'amount_nice' => format_price($this->owner->discount_percentage),
            ];
        }

        return [
            'id' => $this->owner->id,
            'name' => $this->name,
            'first_name' => $this->owner->first_name,
            'last_name' => $this->owner->last_name,
            'is_featured' => (bool)$this->owner->is_featured,
            'has_cause' => (bool)$this->owner->has_cause,
            'services' => $services,
            'price' => $this->owner->price,
            'formatted_price' => format_price($this->price),
            'title' => $this->owner->title ?? '',
            // 'business_price' => $this->owner->business_price,
            // 'live_price' => $this->owner->live_price,
            'allow_business' => (bool)$this->owner->allow_business,
            'allow_chat' => (bool)$this->owner->allows_chat,
            'allow_speed_service' => (bool)$this->owner->allow_speed_service,
            'allow_live' => (bool)$this->owner->allow_live,
            'hidden_profile' => (bool)$this->owner->hidden_profile,
            'reviews' => $this->reviews->toArray(),
            // 'email' => $this->owner->email,
            // 'phone' => $this->owner->phone,
            'photo' => $this->owner->photo ? RvMedia::url($this->owner->photo) : null,
            'video' => $this->owner->video ? RvMedia::url($this->owner->video) : null,
            'bio' => $this->owner->bio ?? '',
        ];
    }
}
