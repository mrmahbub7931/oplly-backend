<?php

namespace Canopy\Ecommerce\Http\Requests;

use Canopy\Support\Http\Requests\Request;

class CustomerCreateRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'                  => 'required|max:120|min:2',
            'email'                 => 'required|max:60|min:6|email|unique:ec_customers',
            'phone'                 => 'required|digits_between:4,14|numeric',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ];
    }
}
