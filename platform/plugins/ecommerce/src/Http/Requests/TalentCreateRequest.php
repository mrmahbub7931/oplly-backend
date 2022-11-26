<?php

namespace Canopy\Ecommerce\Http\Requests;

use Canopy\Support\Http\Requests\Request;

class TalentCreateRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'   => 'required|max:120|min:2',
            // 'last_name'   => 'required|max:120|min:2',
            'email'        => 'required|max:60|min:6|email|unique:ec_talent',
            //'dob'          => 'required|date',
            'handle'       => 'required',
            'phone'        => 'required'
        ];
    }
}
