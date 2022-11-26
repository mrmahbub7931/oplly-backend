<?php

namespace Canopy\Ecommerce\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RvMedia;

class OccassionResource extends JsonResource
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
            'name' => $this->name,
            'icon' => isset($this->image) ? RvMedia::url($this->image) : null,
            'description' => $this->description,
            'show_standard' => (bool)$this->show_standard,
            'show_business' => (bool)$this->show_business,
        ];
    }
}
