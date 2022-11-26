<?php

namespace Canopy\Payment\Http\Requests;

use Canopy\Payment\Enums\PaymentMethodEnum;
use Canopy\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class CheckoutRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'payment_method' => 'required|' . Rule::in(PaymentMethodEnum::values()),
            'amount'         => 'required|min:0',
        ];
    }
}
