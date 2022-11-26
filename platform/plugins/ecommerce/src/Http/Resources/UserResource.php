<?php

namespace Canopy\Ecommerce\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'id'          => $this->id,
            'name'        => $this->name,
            'email'       => $this->email,
            'phone'       => $this->phone,
            'avatar'      => $this->avatar_url,
            'dob'         => $this->dob,
            'role'        => $this->talent_id > 0 && 1 ? 'talent' : 'customer',
            'gender'      => $this->gender,
            'description' => $this->description,
        ];
    }
}
