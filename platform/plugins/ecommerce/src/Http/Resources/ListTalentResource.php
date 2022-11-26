<?php

namespace Canopy\Ecommerce\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RvMedia;

class ListTalentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->owner->id ?? $this->talent_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'url'   => $this->url,
            'title' => $this->owner->title ?? '',
            'price' => $this->price,
            'formatted_price' => format_price($this->price),
            'photo' => isset($this->owner->photo) ? RvMedia::url($this->owner->photo) : null,
            'has_cause' => (bool)($this->owner->has_cause ?? false)
        ];
    }
}
