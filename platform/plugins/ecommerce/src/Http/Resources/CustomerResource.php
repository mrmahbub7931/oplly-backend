<?php

namespace Canopy\Ecommerce\Http\Resources;

use RvMedia;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'talent_id'   => $this->talent_id,
            'name'        => $this->name ?? $this->first_name . ' '. $this->last_name,
            'email'       => $this->email,
            'phone'       => $this->phone,
            'avatar'      => $this->avatar_url,
            'dob'         => $this->dob,
            'role'        => $this->talent_id > 0 && 1 ? 'talent' : 'customer',
            'gender'      => $this->gender,
            'description' => $this->description,
            'notifications' => [
                'allow_push' => $this->notifications->allow_push ?? false,
                'allow_email' => $this->notifications->allow_email ?? false,
                'allow_marketing' => $this->notifications->allow_marketing ?? false,
                'allow_news' => $this->notifications->allow_news ?? false
            ],
            'talent' => $this->talent_id > 0 ? [
                'name' => $this->talent->name ?? $this->name ?? $this->first_name . ' '. $this->last_name,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'is_featured' => (bool)$this->talent->is_featured,
                'has_cause' => (bool)$this->talent->has_cause,
                'price' => (double)$this->talent->price,
                'formatted_price' => format_price($this->talent->price),
                'title' => $this->talent->title ?? '',
                'business_price' => (double)$this->talent->business_price,
                'live_price' => $this->talent->live_price,
                'discount_percentage' => (double)$this->talent->discount_percentage,
                'allow_business' => (bool)$this->talent->allow_business,
                'allow_chat' => (bool)$this->talent->allow_chat,
                'allow_speed_service' => (bool)$this->talent->allow_speed_service,
                'allow_live' => (bool)$this->talent->allow_live,
                'hidden_profile' => (bool)$this->talent->hidden_profile,
                // 'email' => $this->owner->email,
                // 'phone' => $this->owner->phone,
                'photo' => $this->talent->photo ? RvMedia::url($this->talent->photo) : null,
                'video' => $this->talent->video ? RvMedia::url($this->talent->video) : null,
                'bio' => strip_tags($this->talent->bio),
                'banking' => [
                    'bank_account_name'=> $this->talent->bank_account_name,
                    'branch_name'=> $this->talent->branch_name,
                    'bank_name'=> $this->talent->bank_name,
                    'bank_country'=> $this->talent->bank_country,
                    'bank_account_no'=> $this->talent->bank_account_no,
                    'bank_iban'=> $this->talent->bank_iban,
                    'bank_swift'=> $this->talent->bank_swift,
                ]
            ] : null
        ];
    }
}
