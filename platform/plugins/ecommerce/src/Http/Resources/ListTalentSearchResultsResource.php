<?php

namespace Canopy\Ecommerce\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RvMedia;

class ListTalentSearchResultsResource extends JsonResource
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
            'id' => $this->id,
            'talent_id' => $this->owner->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'url'   => $this->url,
            'title' => $this->owner->title ?? '',
            // 'description' => $this->description,
            'price' => $this->price,
            'formatted_price' => format_price($this->price),
            'image' => $this->owner->photo ? RvMedia::url($this->owner->photo) : null,
            // 'member_since' => $this->created_at,
        ];
    }
}
