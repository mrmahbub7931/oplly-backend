@php $shurjoStatus = get_payment_setting('status', SHURJO_PAYMENT_METHOD_NAME); @endphp
<table class="table payment-method-item">
    <tbody>
    <tr class="border-pay-row">
        <td class="border-pay-col"><i class="fa fa-theme-payments"></i></td>
        <td style="width: 20%;">
            <img class="filter-black" src="{{ url('vendor/core/plugins/shurjopay/images/shurjo.png') }}"
                 alt="VTC pay">
        </td>
        <td class="border-right">
            <ul>
                <li>
                    <a href="https://shurjopay.com" target="_blank">{{ __('ShurjoPay') }}</a>
                    <p>{{ __('Customer can buy product and pay directly via :name', ['name' => 'ShurjoPay']) }}</p>
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
                    class="payment-name-label-group @if (get_payment_setting('status', SHURJO_PAYMENT_METHOD_NAME) == 0) hidden @endif">
                    <span class="payment-note v-a-t">{{ trans('plugins/payment::payment.use') }}:</span> <label
                        class="ws-nm inline-display method-name-label">{{ get_payment_setting('name', SHURJO_PAYMENT_METHOD_NAME) }}</label>
                </div>
            </div>
            <div class="float-right">
                <a class="btn btn-secondary toggle-payment-item edit-payment-item-btn-trigger @if ($shurjoStatus == 0) hidden @endif">{{ trans('plugins/payment::payment.edit') }}</a>
                <a class="btn btn-secondary toggle-payment-item save-payment-item-btn-trigger @if ($shurjoStatus == 1) hidden @endif">{{ trans('plugins/payment::payment.settings') }}</a>
            </div>
        </td>
    </tr>
    <tr class="paypal-online-payment payment-content-item hidden">
        <td class="border-left" colspan="3">
            {!! Form::open() !!}
            {!! Form::hidden('type', SHURJO_PAYMENT_METHOD_NAME, ['class' => 'payment_type']) !!}
            <div class="row">
                <div class="col-sm-6">
                    <ul>
                        <li>
                            <label>{{ trans('plugins/payment::payment.configuration_instruction', ['name' => 'shurjopay']) }}</label>
                        </li>
                        <li class="payment-note">
                            <p>{{ trans('plugins/payment::payment.configuration_requirement', ['name' => 'shurjopay']) }}
                                :</p>
                            <ul class="m-md-l" style="list-style-type:decimal">
                                <li style="list-style-type:decimal">
                                    <a href="https://shurjopay.com.bd" target="_blank">
                                        {{ __('Register an account on :name', ['name' => 'shurjopay']) }}
                                    </a>
                                </li>
                                <li style="list-style-type:decimal">
                                    <p>{{ __('After registration at :name, you will have Public & Secret keys', ['name' => 'shurjopay']) }}</p>
                                </li>
                                <li style="list-style-type:decimal">
                                    <p>{{ __('Enter Public, Secret into the box in right hand') }}</p>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-6">
                    <div class="well bg-white">
                        <div class="form-group">
                            <label class="text-title-field"
                                   for="shurjo_name">{{ trans('plugins/payment::payment.method_name') }}</label>
                            <input type="text" class="next-input" name="payment_{{ SHURJO_PAYMENT_METHOD_NAME }}_name"
                                   id="shurjo_name" data-counter="400"
                                   value="{{ get_payment_setting('name', SHURJO_PAYMENT_METHOD_NAME, __('Online payment via :name', ['name' => 'shurjopay'])) }}">
                        </div>
                        <p class="payment-note">
                            {{ trans('plugins/payment::payment.please_provide_information') }} <a target="_blank" href="https://shurjopay.com.bd/">ShurjoPay</a>:
                        </p>
                        <div class="form-group">
                            <label class="text-title-field" for="{{ SHURJO_PAYMENT_METHOD_NAME }}_username">{{ __('Username') }}</label>
                            <input type="text" class="next-input"
                                   name="payment_{{ SHURJO_PAYMENT_METHOD_NAME }}_username" id="{{ SHURJO_PAYMENT_METHOD_NAME }}_username"
                                   value="{{ get_payment_setting('username', SHURJO_PAYMENT_METHOD_NAME) }}">
                        </div>
                        <div class="form-group">
                            <label class="text-title-field" for="{{ SHURJO_PAYMENT_METHOD_NAME }}_password">{{ __('Password') }}</label>
                            <input type="password" class="next-input" placeholder="••••••••" id="{{ SHURJO_PAYMENT_METHOD_NAME }}_password"
                                   name="payment_{{ SHURJO_PAYMENT_METHOD_NAME }}_password"
                                   value="{{ get_payment_setting('password', SHURJO_PAYMENT_METHOD_NAME) }}">
                        </div>
                        <div class="form-group">
                            <label class="text-title-field" for="{{ SHURJO_PAYMENT_METHOD_NAME }}_txnprefix">{{ __('Transaction Prefix') }}</label>
                            <input type="email" class="next-input" id="{{ SHURJO_PAYMENT_METHOD_NAME }}_txnprefix"
                                   name="payment_{{ SHURJO_PAYMENT_METHOD_NAME }}_txnprefix"
                                   value="{{ get_payment_setting('txnprefix', SHURJO_PAYMENT_METHOD_NAME) }}">
                        </div>

                        <div class="form-group">
                            <label class="next-label">
                                <input type="checkbox" class="hrv-checkbox" value="1" name="payment_{{ SHURJO_PAYMENT_METHOD_NAME }}_sandbox" @if (get_payment_setting('sandbox', SHURJO_PAYMENT_METHOD_NAME)==1) checked @endif>
                                {{ trans('plugins/payment::payment.sandbox_mode') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 bg-white text-right">
                <button class="btn btn-warning disable-payment-item @if ($shurjoStatus == 0) hidden @endif"
                        type="button">{{ trans('plugins/payment::payment.deactivate') }}</button>
                <button
                    class="btn btn-info save-payment-item btn-text-trigger-save @if ($shurjoStatus == 1) hidden @endif"
                    type="button">{{ trans('plugins/payment::payment.activate') }}</button>
                <button
                    class="btn btn-info save-payment-item btn-text-trigger-update @if ($shurjoStatus == 0) hidden @endif"
                    type="button">{{ trans('plugins/payment::payment.update') }}</button>
            </div>
            {!! Form::close() !!}
        </td>
    </tr>
    </tbody>
</table>
