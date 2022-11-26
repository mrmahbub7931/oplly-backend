@if (strtoupper($currency) == 'BDT' && get_payment_setting('status', BKASH_PAYMENT_METHOD_NAME) == 1)
    <li class="list-group-item">
        <input class="magic-radio js_payment_method" type="radio" name="payment_method" id="payment_{{ BKASH_PAYMENT_METHOD_NAME }}"
               value="{{ BKASH_PAYMENT_METHOD_NAME }}" data-toggle="collapse" data-target=".payment_{{ BKASH_PAYMENT_METHOD_NAME }}_wrap"
               data-parent=".list_payment_method"
               @if (setting('default_payment_method') == BKASH_PAYMENT_METHOD_NAME) checked @endif
        >
        <label for="payment_{{ BKASH_PAYMENT_METHOD_NAME }}" class="text-left">{{ get_payment_setting('name', BKASH_PAYMENT_METHOD_NAME) }}</label>
        {{--<div class="payment_{{ BKASH_PAYMENT_METHOD_NAME }}_wrap payment_collapse_wrap collapse @if (setting('default_payment_method') == BKASH_PAYMENT_METHOD_NAME) show @endif">
            <p>{!! get_payment_setting('description', BKASH_PAYMENT_METHOD_NAME, __('Payment with Bkash')) !!}</p>
        </div>--}}
    </li>

@endif
