<!-- START SECTION CONTACT -->
<div class="section pt-0">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6">
            <div class="field_form">
                {!! Form::open(['route' => 'public.send.contact', 'class' => 'form--contact contact-form', 'method' => 'POST']) !!}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group nice--input">
                                <label for="contact_name" class="control-label required">{{ __('Name') }}</label>
                                <input type="text" class="form-control" name="name" value="{{ old('name') }}" id="contact_name" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group nice--input">
                                <label for="contact_email" class="control-label required">{{ __('Email') }}</label>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" id="contact_email" >
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group nice--input">
                                <label for="contact_address" class="control-label">{{ __('Address') }}</label>
                                <input type="text" class="form-control" name="address" value="{{ old('address') }}" id="contact_address">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group nice--input">
                                <label for="contact_phone" class="control-label">{{ __('Phone') }}</label>
                                <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" id="contact_phone">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group nice--input">
                                <label for="contact_subject" class="control-label">{{ __('Subject') }}</label>
                                <input type="text" class="form-control" name="subject" value="{{ old('subject') }}" id="contact_subject">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group nice--input">
                                <label for="contact_content" class="control-label required">{{ __('Message') }}</label>
                                <textarea name="content" id="contact_content" class="form-control" rows="5" >{{ old('content') }}</textarea>
                            </div>
                        </div>
                        @if (setting('enable_captcha') && is_plugin_active('captcha'))
                            <div class="form-group col-12">
                                {!! Captcha::display() !!}
                            </div>
                        @endif
                        <div class="col-md-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-fill-out">{{ __('Send Message') }}</button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="contact-message contact-success-message" style="display: none"></div>
                        <div class="contact-message contact-error-message" style="display: none"></div>
                    </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>
<!-- END SECTION CONTACT -->
