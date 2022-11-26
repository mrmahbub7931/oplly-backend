@php $nagadStatus = get_payment_setting('status', NAGAD_PAYMENT_METHOD_NAME); @endphp
<table class="table payment-method-item">
    <tbody>
    <tr class="border-pay-row">
        <td class="border-pay-col"><i class="fa fa-theme-payments"></i></td>
        <td style="width: 20%;">
            <img class="filter-black" src="{{ url('vendor/core/plugins/nagad/images/nagad.png') }}"
                 alt="Nagad pay">
        </td>
        <td class="border-right">
            <ul>
                <li>
                    <a href="https://nagad.com.bd" target="_blank">{{ __('Nagad') }}</a>
                    <p>{{ __('Customer can buy product and pay directly via :name', ['name' => 'Nagad']) }}</p>
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
                    class="payment-name-label-group @if (get_payment_setting('status', NAGAD_PAYMENT_METHOD_NAME) == 0) hidden @endif">
                    <span class="payment-note v-a-t">{{ trans('plugins/payment::payment.use') }}:</span> <label
                        class="ws-nm inline-display method-name-label">{{ get_payment_setting('name', NAGAD_PAYMENT_METHOD_NAME) }}</label>
                </div>
            </div>
            <div class="float-right">
                <a class="btn btn-secondary toggle-payment-item edit-payment-item-btn-trigger @if ($nagadStatus == 0) hidden @endif">{{ trans('plugins/payment::payment.edit') }}</a>
                <a class="btn btn-secondary toggle-payment-item save-payment-item-btn-trigger @if ($nagadStatus == 1) hidden @endif">{{ trans('plugins/payment::payment.settings') }}</a>
            </div>
        </td>
    </tr>
    <tr class="paypal-online-payment payment-content-item hidden">
        <td class="border-left" colspan="3">
            {!! Form::open() !!}
            {!! Form::hidden('type', NAGAD_PAYMENT_METHOD_NAME, ['class' => 'payment_type']) !!}
            <div class="row">
                <div class="col-sm-6">
                    <ul>
                        <li>
                            <label>{{ trans('plugins/payment::payment.configuration_instruction', ['name' => 'nagad']) }}</label>
                        </li>
                        <li class="payment-note">
                            <p>{{ trans('plugins/payment::payment.configuration_requirement', ['name' => 'nagad']) }}
                                :</p>
                            <ul class="m-md-l" style="list-style-type:decimal">
                                <li style="list-style-type:decimal">
                                    <a href="https://nagad.com.bd" target="_blank">
                                        {{ __('Register an account on :name', ['name' => 'nagad']) }}
                                    </a>
                                </li>
                                <li style="list-style-type:decimal">
                                    <p>{{ __('After registration at :name, you will have Public & Secret keys', ['name' => 'nagad']) }}</p>
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
                                   for="nagad_name">{{ trans('plugins/payment::payment.method_name') }}</label>
                            <input type="text" class="next-input" name="payment_{{ NAGAD_PAYMENT_METHOD_NAME }}_name"
                                   id="nagad_name" data-counter="400"
                                   value="{{ get_payment_setting('name', NAGAD_PAYMENT_METHOD_NAME, __('Online payment via :name', ['name' => 'nagad'])) }}">
                        </div>
                        <p class="payment-note">
                            {{ trans('plugins/payment::payment.please_provide_information') }} <a target="_blank" href="https://nagad.com.bd/">Nagad</a>:
                        </p>
                        <div class="form-group">
                            <label class="text-title-field" for="{{ NAGAD_PAYMENT_METHOD_NAME }}_public">{{ __('Public Key') }}</label>
                            <input type="text" class="next-input"
                                   name="payment_{{ NAGAD_PAYMENT_METHOD_NAME }}_public" id="{{ NAGAD_PAYMENT_METHOD_NAME }}_public"
                                   value="{{ get_payment_setting('public', NAGAD_PAYMENT_METHOD_NAME) }}">
                        </div>
                        <div class="form-group">
                            <label class="text-title-field" for="{{ NAGAD_PAYMENT_METHOD_NAME }}_secret">{{ __('Secret Key') }}</label>
                            <input type="password" class="next-input" placeholder="••••••••" id="{{ NAGAD_PAYMENT_METHOD_NAME }}_secret"
                                   name="payment_{{ NAGAD_PAYMENT_METHOD_NAME }}_secret"
                                   value="{{ get_payment_setting('secret', NAGAD_PAYMENT_METHOD_NAME) }}">
                        </div>
                        <div class="form-group">
                            <label class="text-title-field" for="{{ NAGAD_PAYMENT_METHOD_NAME }}_merchant_email">{{ __('Merchant Email') }}</label>
                            <input type="email" class="next-input" placeholder="{{ __('Email') }}" id="{{ NAGAD_PAYMENT_METHOD_NAME }}_merchant_email"
                                   name="payment_{{ NAGAD_PAYMENT_METHOD_NAME }}_merchant_email"
                                   value="{{ get_payment_setting('merchant_email', NAGAD_PAYMENT_METHOD_NAME) }}">
                        </div>
                        <div class="form-group">
                            <label class="text-title-field" for="{{ NAGAD_PAYMENT_METHOD_NAME }}_merchant_id">{{ __('Merchant Id') }}</label>
                            <input type="email" class="next-input" placeholder="{{ __('Merchant Id') }}" id="{{ NAGAD_PAYMENT_METHOD_NAME }}_merchant_id"
                                   name="payment_{{ NAGAD_PAYMENT_METHOD_NAME }}_merchant_id"
                                   value="{{ get_payment_setting('merchant_id', NAGAD_PAYMENT_METHOD_NAME) }}">
                        </div>
                        <div class="form-group">
                            <label class="text-title-field" for="{{ NAGAD_PAYMENT_METHOD_NAME }}_merchant_number">{{ __('Merchant Number') }}</label>
                            <input type="email" class="next-input" placeholder="{{ __('Merchant Number') }}" id="{{ NAGAD_PAYMENT_METHOD_NAME }}_merchant_number"
                                   name="payment_{{ NAGAD_PAYMENT_METHOD_NAME }}_merchant_number"
                                   value="{{ get_payment_setting('merchant_number', NAGAD_PAYMENT_METHOD_NAME) }}">
                        </div>

                        <div class="form-group">
                            <label class="next-label">
                                <input type="checkbox" class="hrv-checkbox" value="1" name="payment_{{ NAGAD_PAYMENT_METHOD_NAME }}_sandbox" @if (get_payment_setting('sandbox', NAGAD_PAYMENT_METHOD_NAME)==1) checked @endif>
                                {{ trans('plugins/payment::payment.sandbox_mode') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 bg-white text-right">
                <button class="btn btn-warning disable-payment-item @if ($nagadStatus == 0) hidden @endif"
                        type="button">{{ trans('plugins/payment::payment.deactivate') }}</button>
                <button
                    class="btn btn-info save-payment-item btn-text-trigger-save @if ($nagadStatus == 1) hidden @endif"
                    type="button">{{ trans('plugins/payment::payment.activate') }}</button>
                <button
                    class="btn btn-info save-payment-item btn-text-trigger-update @if ($nagadStatus == 0) hidden @endif"
                    type="button">{{ trans('plugins/payment::payment.update') }}</button>
            </div>
            {!! Form::close() !!}
        </td>
    </tr>
    </tbody>
</table>
