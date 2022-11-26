@php $bkashStatus = get_payment_setting('status',BKASH_PAYMENT_METHOD_NAME); @endphp
<table class="table payment-method-item">
    <tbody>
    <tr class="border-pay-row">
        <td class="border-pay-col"><i class="fa fa-theme-payments"></i></td>
        <td style="width: 20%;">
            <img class="filter-black" src="{{ url('vendor/core/plugins/bkash/images/bkash.png') }}"
                 alt="Bkash pay">
        </td>
        <td class="border-right">
            <ul>
                <li>
                    <a href="https://www.bkash.com/" target="_blank">{{ __('Bkash') }}</a>
                    <p>{{ __('Customer can buy product and pay directly via :name', ['name' => 'Bkash']) }}</p>
                </li>
            </ul>
        </td>
    </tr>
    </tbody>
    <tbody class="border-none-t">
    <tr class="bg-white">
        <td colspan="3">
            <div class="float-left" style="margin-top: 5px;">
                <div
                    class="payment-name-label-group @if (get_payment_setting('status', BKASH_PAYMENT_METHOD_NAME) == 0) hidden @endif">
                    <span class="payment-note v-a-t">{{ trans('plugins/payment::payment.use') }}:</span> <label
                        class="ws-nm inline-display method-name-label">{{ get_payment_setting('name', BKASH_PAYMENT_METHOD_NAME) }}</label>
                </div>
            </div>
            <div class="float-right">
                <a class="btn btn-secondary toggle-payment-item edit-payment-item-btn-trigger @if ($bkashStatus == 0) hidden @endif">{{ trans('plugins/payment::payment.edit') }}</a>
                <a class="btn btn-secondary toggle-payment-item save-payment-item-btn-trigger @if ($bkashStatus == 1) hidden @endif">{{ trans('plugins/payment::payment.settings') }}</a>
            </div>
        </td>
    </tr>
    <tr class="paypal-online-payment payment-content-item hidden">
        <td class="border-left" colspan="3">
            {!! Form::open() !!}
            {!! Form::hidden('type', BKASH_PAYMENT_METHOD_NAME, ['class' => 'payment_type']) !!}
            <div class="row">
                <div class="col-sm-6">
                    <ul>
                        <li>
                            <label>{{ trans('plugins/payment::payment.configuration_instruction', ['name' => 'bkash']) }}</label>
                        </li>
                        <li class="payment-note">
                            <p>{{ trans('plugins/payment::payment.configuration_requirement', ['name' => 'bkash']) }}
                                :</p>
                            <ul class="m-md-l" style="list-style-type:decimal">
                                <li style="list-style-type:decimal">
                                    <a href="https://bkash.com" target="_blank">
                                        {{ __('Register an account on :name', ['name' => 'bkash']) }}
                                    </a>
                                </li>
                                <li style="list-style-type:decimal">
                                    <p>{{ __('After registration at :name, you will have App & Secret keys', ['name' => 'bkash']) }}</p>
                                </li>
                                <li style="list-style-type:decimal">
                                    <p>{{ __('Enter App Key, Secret Key into the box in right hand') }}</p>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-6">
                    <div class="well bg-white">
                        <div class="form-group">
                            <label class="text-title-field"
                                   for="nagad_name">{{ trans('plugins/payment::payment.method_name') }}</label>
                            <input type="text" class="next-input" name="payment_{{ BKASH_PAYMENT_METHOD_NAME }}_name"
                                   id="nagad_name" data-counter="400"
                                   value="{{ get_payment_setting('name', BKASH_PAYMENT_METHOD_NAME, __('Online payment via :name', ['name' => 'bkash'])) }}">
                        </div>
                        <p class="payment-note">
                            {{ trans('plugins/payment::payment.please_provide_information') }} <a target="_blank" href="https://bkash.com/">Bkash</a>:
                        </p>
                        <div class="form-group">
                            <label class="text-title-field" for="{{ BKASH_PAYMENT_METHOD_NAME }}_app_key">{{ __('App key') }}</label>
                            <input type="text" class="next-input"
                                   name="payment_{{ BKASH_PAYMENT_METHOD_NAME }}_app_key" id="{{ BKASH_PAYMENT_METHOD_NAME }}_app_key"
                                   value="{{ get_payment_setting('app_key', BKASH_PAYMENT_METHOD_NAME) }}">
                        </div>
                        <div class="form-group">
                            <label class="text-title-field" for="{{ BKASH_PAYMENT_METHOD_NAME }}_app_secret">{{ __('App Secret') }}</label>
                            <input type="text" class="next-input"
                                   name="payment_{{ BKASH_PAYMENT_METHOD_NAME }}_app_secret" id="{{ BKASH_PAYMENT_METHOD_NAME }}_app_secret"
                                   value="{{ get_payment_setting('app_secret', BKASH_PAYMENT_METHOD_NAME) }}">
                        </div>
                        <div class="form-group">
                            <label class="text-title-field" for="{{ BKASH_PAYMENT_METHOD_NAME }}_username">{{ __('Username') }}</label>
                            <input type="text" class="next-input"
                                   name="payment_{{ BKASH_PAYMENT_METHOD_NAME }}_username" id="{{ BKASH_PAYMENT_METHOD_NAME }}_username"
                                   value="{{ get_payment_setting('username', BKASH_PAYMENT_METHOD_NAME) }}">
                        </div>
                        <div class="form-group">
                            <label class="text-title-field" for="{{ BKASH_PAYMENT_METHOD_NAME }}_password">{{ __('Password') }}</label>
                            <input type="text" class="next-input"
                                   name="payment_{{ BKASH_PAYMENT_METHOD_NAME }}_password" id="{{ BKASH_PAYMENT_METHOD_NAME }}_password"
                                   value="{{ get_payment_setting('password', BKASH_PAYMENT_METHOD_NAME) }}">
                        </div>
                        <div class="form-group">
                            <label class="next-label">
                                <input type="checkbox" class="hrv-checkbox" value="1" name="payment_{{ BKASH_PAYMENT_METHOD_NAME }}_sandbox" @if (get_payment_setting('sandbox', BKASH_PAYMENT_METHOD_NAME)==1) checked @endif>
                                {{ trans('plugins/payment::payment.sandbox_mode') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 bg-white text-right">
                <button class="btn btn-warning disable-payment-item @if ($bkashStatus == 0) hidden @endif"
                        type="button">{{ trans('plugins/payment::payment.deactivate') }}</button>
                <button
                    class="btn btn-info save-payment-item btn-text-trigger-save @if ($bkashStatus == 1) hidden @endif"
                    type="button">{{ trans('plugins/payment::payment.activate') }}</button>
                <button
                    class="btn btn-info save-payment-item btn-text-trigger-update @if ($bkashStatus == 0) hidden @endif"
                    type="button">{{ trans('plugins/payment::payment.update') }}</button>
            </div>
            {!! Form::close() !!}
        </td>
    </tr>
    </tbody>
</table>
