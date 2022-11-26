<?php

namespace Canopy\Ecommerce\Http\Requests;

use Canopy\Support\Http\Requests\Request;

class TalentEditRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'first_name'  => 'required|max:120|min:2',
            'email' => 'required|max:60|min:6|email|unique:ec_talent,email,' . $this->route('talent'),
        ];

        return $rules;
    }
}
