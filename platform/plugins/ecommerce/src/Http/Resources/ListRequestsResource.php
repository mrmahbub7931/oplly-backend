<?php

namespace Canopy\Ecommerce\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use RvMedia;

class ListRequestsResource extends JsonResource
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
            'token' => $this->token,
            // 'user' => $this->user,
            'talent' => [
                'id' => $this->talent->id,
                'first_name' => $this->talent->first_name,
                'last_name' => $this->talent->last_name,
                'photo' => $this->talent->photo ? RvMedia::url($this->talent->photo) : null,
                'has_cause' => $this->talent->has_cause,
            ],
            'from' => $this->from,
            'recipient' => $this->recipient,
            'occasion'   => $this->occasion,
            'amount' => $this->amount,
            'formatted_amount' => format_price($this->amount),
            'currency' => $this->currency_id,
            'description' => $this->request,
            'audience' => $this->target_audience,
            'is_public' => $this->allow_public,
            'is_speed_service' => $this->is_speed_service,
            'status' => $this->status,
            'video' => $this->video ? RvMedia::url($this->video) : null,
        ];
    }
}
