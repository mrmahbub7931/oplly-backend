<?php

namespace Canopy\Ecommerce\Http\Requests;

use Canopy\Support\Http\Requests\Request;

class AddressRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'       => 'required|max:255',
            'email'      => 'email|nullable|max:60',
            'phone'      => 'required|numeric',
            'state'      => 'required|max:120',
            'city'       => 'required|max:120',
            'address'    => 'required|max:120',
            'is_default' => 'integer|min:0|max:1',
        ];
    }
}
