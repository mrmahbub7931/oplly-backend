@php Theme::set('pageName', __('Sign up')) @endphp

<!-- START LOGIN SECTION -->
<div class="login_register_wrap section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-md-10">
                <div class="login_wrap">
                    @if (auth('customer')->user()->talent)
                        <div class="padding_eight_all text-center">
                            <div class="heading_s1">
                                <h1>{{ __('Looks like you are already a talent') }}</h1>
                                <p class="mt-3 text-muted">Invite your friend as talent</p>
                            </div>
                            <form method="POST" action="{{ route('customer.register.post') }}">
                                @csrf
                                <input type="hidden" name="referral" value="{{auth('customer')->user()->talent->id}}">
                                <input type="hidden" name="op" value="invite_talent">
                                <div class="form-group nice--input">
                                    <label for="txt-fname">{{ __('Their Full Name') }}</label>
                                    <input class="form-control" name="first_name" id="txt-fname" type="text" required
                                           value="{{ old('first_name') }}" placeholder="">
                                    @if ($errors->has('first_name'))
                                        <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                    @endif
                                </div>

                                <div class="form-group nice--input">
                                    <label for="txt-email">{{ __('Their Email') }}</label>
                                    <input class="form-control" name="email" id="txt-email" type="email" required
                                           value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>

                                <div class="row">
                                    <div class="form-group nice--input col-12 col-md-6">
                                        <label for="txt-channel">{{ __('Your Social Channel') }}</label>
                                        <select class="form-control" name="channel" id="txt-channel" required value="{{ old('channel') }}"
                                                placeholder="">
                                            <option value=""></option>
                                            <option value="facebook">Facebook</option>
                                            <option value="instagram">Instagram</option>
                                            <option value="twitter">Twitter</option>
                                            <option value="youtube">Youtube</option>
                                            <option value="linkedin">LinkedIn</option>
                                            <option value="other">Other</option>
                                        </select>
                                        @if ($errors->has('channel'))
                                            <span class="text-danger">{{ $errors->first('channel') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group nice--input col-12 col-md-6">
                                        <label for="txt-followers">{{ __('No of followers') }}</label>
                                        <input class="form-control" name="followers" id="txt-followers" type="text" required
                                               value="{{ old('followers') }}" placeholder="">
                                        @if ($errors->has('followers'))
                                            <span class="text-danger">{{ $errors->first('followers') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group nice--input">
                                    <label for="txt-handle">{{ __('Social Media Handle') }}</label>
                                    <input class="form-control" name="handle" id="txt-handle" type="text" required
                                           value="{{ old('handle') }}" placeholder="">
                                    @if ($errors->has('handle'))
                                        <span class="text-danger">{{ $errors->first('handle') }}</span>
                                    @endif
                                </div>
                                <div class="login_footer form-group">
                                    <div class="chek-form mx-auto">
                                        <div class="custome-checkbox">
                                            <input class="form-check-input" type="checkbox" name="agree_terms_policy" id="terms-policy"
                                                   value="1">
                                            <label class="form-check-label" for="terms-policy"><span>{{ __('I agree to the') }} <a
                                                        href="/terms" target="_blank">Tems</a> &amp; <a href="/privacy-policy" target="_blank">Privacy
                              Policy</a></span></label>
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

                            <div class="text-center">
                                {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \Canopy\Ecommerce\Models\Talent::class) !!}
                            </div>
                        </div>
                    @else
                        <div class="padding_eight_all text-center">
                            <div class="heading_s1">
                                <h1>{{ __('Become a Talent on Oplly') }}</h1>
                                <p class="mt-3 text-muted">Please complete all the fields below</p>
                            </div>
                            <form method="POST" action="{{ route('customer.register.post') }}">
                                @csrf
                                <div class="form-group nice--input">
                                    <label for="txt-fname">{{ __('Your Full Name') }}</label>
                                    <input class="form-control" name="first_name" id="txt-fname" type="text" required
                                           value="{{ old('first_name') }}" placeholder=">
                                  @if ($errors->has('first_name'))
                                        <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                    @endif
                                </div>

                                <div class="form-group nice--input">
                                    <label for="txt-email">{{ __('Your Email') }}</label>
                                    <input class="form-control" name="email" id="txt-email" type="email" required
                                           value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>

                                <div class="form-group nice--input">
                                    <label for="txt-phone">{{ __('Phone Number') }}</label>
                                    <input class="form-control" name="phone" id="txt-phone" type="text" required
                                           value="{{ old('phone') }}" placeholder="{{ __('Your Phone number') }}">
                                    @if ($errors->has('phone'))
                                        <span class="text-danger">{{ $errors->first('phone') }}</span>
                                    @endif
                                </div>
                                <div class="row">
                                    <div class="form-group nice--input col-12 col-md-6">
                                        <label for="txt-channel">{{ __('Your Social Channel') }}</label>
                                        <select class="form-control" name="channel" id="txt-channel" required value="{{ old('channel') }}"
                                                placeholder="">
                                            <option value=""></option>
                                            <option value="facebook">Facebook</option>
                                            <option value="instagram">Instagram</option>
                                            <option value="twitter">Twitter</option>
                                            <option value="youtube">Youtube</option>
                                            <option value="linkedin">LinkedIn</option>
                                            <option value="other">Other</option>
                                        </select>
                                        @if ($errors->has('channel'))
                                            <span class="text-danger">{{ $errors->first('channel') }}</span>
                                        @endif
                                    </div>
                                    <div class="form-group nice--input col-12 col-md-6">
                                        <label for="txt-followers">{{ __('No of followers') }}</label>
                                        <input class="form-control" name="followers" id="txt-followers" type="text" required
                                               value="{{ old('followers') }}" placeholder="">
                                        @if ($errors->has('followers'))
                                            <span class="text-danger">{{ $errors->first('followers') }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group nice--input">
                                    <label for="txt-handle">{{ __('Social Media Handle') }}</label>
                                    <input class="form-control" name="handle" id="txt-handle" type="text" required
                                           value="{{ old('handle') }}" placeholder="">
                                    @if ($errors->has('handle'))
                                        <span class="text-danger">{{ $errors->first('handle') }}</span>
                                    @endif
                                </div>
                                <div class="login_footer form-group">
                                    <div class="chek-form mx-auto">
                                        <div class="custome-checkbox">
                                            <input class="form-check-input" type="checkbox" name="agree_terms_policy" id="terms-policy"
                                                   value="1">
                                            <label class="form-check-label" for="terms-policy">
                                                <span>{{ __('I agree to the') }}
                                                    <a href="/terms" target="_blank">Tems</a> &
                                                    <a href="/privacy-policy" target="_blank">Privacy Policy</a>
                                                </span>
                                            </label>
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

                            <div class="text-center">
                                {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \Canopy\Ecommerce\Models\Talent::class) !!}
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
<!-- END LOGIN SECTION -->
