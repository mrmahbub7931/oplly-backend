<?php

namespace Canopy\Ecommerce\Http\Requests;

use Canopy\Support\Http\Requests\Request;

class ProductUpdateOrderByRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'value' => 'required|numeric',
        ];
    }
}
