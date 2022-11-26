@php Theme::set('pageName', __('Sign up')) @endphp

<!-- START LOGIN SECTION -->
<div class="login_register_wrap section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-6 col-md-10">
        <div class="login_wrap">
          <div class="padding_eight_all text-center">
            <div class="heading_s1">
              <h1>{{ __('Sign Up') }}</h1>
              <p class="mt-3 text-muted">Please sign in using available methods</p>
            </div>

            <div class="text-center">
              {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \Canopy\Ecommerce\Models\Customer::class) !!}
            </div>

            <form method="POST" action="{{ route('customer.register.post') }}">
              @csrf
              <div class="form-group nice--input">
                <label for="txt-name">{{ __('Your Full Name') }}</label>
                <input class="form-control" name="name" id="txt-name" type="text" value="{{ old('name') }}">
                @if ($errors->has('name'))
                  <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
              </div>
              <div class="form-group nice--input">
                <label for="txt-email">{{ __('Your Email') }}</label>
                <input class="form-control" name="email" id="txt-email" type="email" value="{{ old('email') }}"
                  {{-- placeholder="{{ __('Your Email') }}" --}}>
                @if ($errors->has('email'))
                  <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
              </div>
              <div class="form-group nice--input">
                <label for="txt-phone">{{ __('Your Phone number') }}</label>
                <input class="form-control" name="phone" id="txt-phone" type="text" value="{{ old('phone') }}"
                  {{-- placeholder="{{ __('Your Phone number') }}" --}}>
                @if ($errors->has('phone'))
                  <span class="text-danger">{{ $errors->first('phone') }}</span>
                @endif
              </div>
              <div class="form-group nice--input">
                <label for="txt-password">{{ __('Password') }}</label>
                <input class="form-control" type="password" name="password" id="txt-password" {{-- placeholder="{{ __('Password') }}" --}}>
                @if ($errors->has('password'))
                  <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
              </div>
              <div class="form-group nice--input">
                <label for="txt-password-confirmation">{{ __('Confirm Password') }}</label>
                <input class="form-control" type="password" name="password_confirmation" id="txt-password-confirmation"
                  {{-- placeholder="{{ __('Password') }}" --}}>
                @if ($errors->has('password_confirmation'))
                  <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                @endif
              </div>
              <div class="login_footer form-group">
                <div class="chek-form mx-auto">
                  <div class="custome-checkbox">
                    <input class="form-check-input" type="checkbox" name="agree_terms_policy" id="terms-policy"
                      value="1">
                    <label class="form-check-label" for="terms-policy"><span>{{ __('I agree to the') }} <a
                          href="/terms" class="text-primary" target="_blank">Terms</a> &amp; <a href="/privacy-policy"
                          class="text-primary" target="_blank">Privacy Policy</a></span></label>
                  </div>
                </div>
              </div>
              @if (setting('enable_captcha') && is_plugin_active('captcha'))
                {!! Captcha::display() !!}
              @endif
              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">{{ __('Sign up') }}</button>
              </div>
            </form>



            <div class="form-note text-center">{{ __('Already have an account?') }} <a
                href="{{ route('customer.login') }}">{{ __('Log in') }}</a></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END LOGIN SECTION -->
