<?php

namespace Canopy\SimpleSlider\Http\Requests;

use Canopy\Support\Http\Requests\Request;

class SimpleSliderItemRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'simple_slider_id' => 'required',
            'title'            => 'max:255',
            'image'            => 'required',
            'order'            => 'required|integer|min:0|max:1000',
        ];
    }
}
