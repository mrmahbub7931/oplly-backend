<?php

namespace Canopy\Ecommerce\Http\Requests;

use Canopy\Support\Http\Requests\Request;

class DiscountRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'  => 'nullable|required_if:type,promotion|max:255',
            'code'   => 'nullable|required_if:type,coupon|max:20|unique:ec_discounts,code',
            'value'  => 'required|numeric|min:0',
            'target' => 'required',
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'title.required_if' => trans('plugins/ecommerce::discount.create_discount_validate_title_required_if'),
            'code.required_if'  => trans('plugins/ecommerce::discount.create_discount_validate_code_required_if'),
            'value.required'    => trans('plugins/ecommerce::discount.create_discount_validate_value_required'),
            'target.required'   => trans('plugins/ecommerce::discount.create_discount_validate_target_required'),
        ];
    }
}
