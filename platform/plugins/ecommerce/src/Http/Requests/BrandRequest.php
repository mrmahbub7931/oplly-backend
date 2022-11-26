<?php

namespace Canopy\Ecommerce\Http\Requests;

use Canopy\Base\Enums\BaseStatusEnum;
use Canopy\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class BrandRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'   => 'required',
            'slug'   => 'required',
            'order'  => 'required|integer|min:0|max:127',
            'status' => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
