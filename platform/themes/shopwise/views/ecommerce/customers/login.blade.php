@php Theme::set('pageName', __('Sign in')) @endphp

<div class="login_register_wrap section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-6 col-md-10">
        <div class="login_wrap">
          <div class="padding_eight_all text-center">
            <div class="heading_s1">
              <h1>{{ __('Sign In') }}</h1>
              <p class="mt-3 text-muted">Please sign in using available methods</p>
            </div>

            <div class="text-center">
              {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \Canopy\Ecommerce\Models\Customer::class) !!}
            </div>

            <form method="POST" action="{{ route('customer.login.post') }}">
              @csrf
              <div class="form-group nice--input">
                <label for="txt-email">{{ __('Your Email') }}</label>
                <input class="form-control" name="email" id="txt-email" type="email" required value="{{ old('email') }}" autocomplete="off"
                  {{-- placeholder="{{ __('Your Email') }}" --}}>
                @if ($errors->has('email'))
                  <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
              </div>
              <div class="form-group nice--input">
                <label for="txt-email">{{ __('Password') }}</label>
                <input class="form-control" type="password" name="password" id="txt-password" required autocomplete="off">
                @if ($errors->has('password'))
                  <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
              </div>
              {{-- <div class="login_footer form-group">
                                <div class="chek-form">
                                    <div class="custome-checkbox">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember-me" value="1">
                                        <label class="form-check-label" for="remember-me"><span>{{ __('Remember me') }}</span></label>
                                    </div>
                                </div>

                            </div> --}}
              <div class="form-group">
                <button type="submit" class="btn mb-3 btn-block btn-primary">{{ __('Log in') }}</button>
                <a class="mt-3 text-muted"
                  href="{{ route('customer.password.reset') }}">{{ __('Forgot password?') }}</a>
              </div>
            </form>



            <div class="form-note text-center text-muted">{{ __("Don't Have an Account?") }} <a
                href="{{ route('customer.register') }}">{{ __('Sign up now') }}</a></div>
          </div>
          <div id="app"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END LOGIN SECTION -->
