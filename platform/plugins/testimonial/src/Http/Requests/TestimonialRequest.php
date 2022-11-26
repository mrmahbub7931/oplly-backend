<?php

namespace Canopy\Testimonial\Http\Requests;

use Canopy\Base\Enums\BaseStatusEnum;
use Canopy\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class TestimonialRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'    => 'required',
            'content' => 'required',
            'status'  => Rule::in(BaseStatusEnum::values()),
        ];
    }
}
