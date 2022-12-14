<?php

namespace Canopy\Newsletter\Http\Requests;

use Canopy\Newsletter\Enums\NewsletterStatusEnum;
use Canopy\Support\Http\Requests\Request;
use Illuminate\Validation\Rule;

class NewsletterRequest extends Request
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'email'  => 'required|email|unique:newsletters',
            'status' => Rule::in(NewsletterStatusEnum::values()),
        ];

        if (setting('enable_captcha') && is_plugin_active('captcha')) {
            $rules += ['g-recaptcha-response' => 'required|captcha'];
        }

        return $rules;
    }
}
