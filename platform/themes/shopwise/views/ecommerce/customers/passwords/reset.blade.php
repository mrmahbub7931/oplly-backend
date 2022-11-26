@php Theme::set('pageName', __('Reset Password')) @endphp

<!-- START LOGIN SECTION -->
<div class="login_register_wrap section">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-6 col-md-10">
        <div class="login_wrap">
          <div class="padding_eight_all text-center">
            <div class="heading_s1">
              <h3>{{ __('Reset Password') }}</h3>
            </div>
            <form method="POST" action="{{ route('customer.password.email') }}">
              @csrf
              <input type="hidden" name="token" value="{{ $token }}">
                <div class="form-group nice--input">
                    <label for="txt-email">{{ __('Your Email') }}</label>
                <input class="form-control" name="email" id="txt-email" type="email" required value="{{ old('email') }}"
                  >
                @if ($errors->has('email'))
                  <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
              </div>
                <div class="form-group nice--input">
                    <label for="txt-email">{{ __('Password') }}</label>
                <input class="form-control" type="password" name="password" required id="txt-password"
                  >
                @if ($errors->has('password'))
                  <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
              </div>
                <div class="form-group nice--input">
                    <label for="txt-email">{{ __('Confirm password') }}</label>
                <input class="form-control" type="password" name="password_confirmation" required id="txt-password-confirmation"
                  >
                @if ($errors->has('password_confirmation'))
                  <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                @endif
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">{{ __('Submit') }}</button>
              </div>

              @if (session('status'))
                <div class="text-success">
                  {{ session('status') }}
                </div>
              @endif
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- END LOGIN SECTION -->
