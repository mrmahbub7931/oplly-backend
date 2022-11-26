<!-- START SECTION SUBSCRIBE NEWSLETTER -->
<div class="section py-4 bg-dark">
  <div class="container">

    <div class="row align-items-center">
      <div class="col-md-6">
        <div class="newsletter_text text_white pl-0 text-left">
          <h6 class="h5 text-muted px-2">Get the latest updates, join our newsletter</h6>
          <p>{!! clean($description) !!}</p>
          <div class="newsletter_form2 rounded_input newsletter-form mx-width-300">
            <form method="post" action="{{ route('public.newsletter.subscribe') }}">
              @csrf
              <input name="email" type="email" class="form-control" placeholder="{{ __('Enter Your Email') }}">
              <button type="submit" class="btn btn-dark btn-radius">{{ __('Subscribe') }}</button>

              @if (setting('enable_captcha') && is_plugin_active('captcha'))
                {!! Captcha::display() !!}
              @endif
            </form>

            <div class="newsletter-message newsletter-success-message" style="display: none"></div>
            <div class="newsletter-message newsletter-error-message" style="display: none"></div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="newsletter_text no--icons text_white p-0 text-right">
          <h6 class="h5 text-muted px-2">{!! clean($title) !!}</h6>
          <p>{!! clean($description) !!}</p>
          <a href="/talent/signup" target="_blank" class="btn btn-white-outlined">Join as Celebrity</a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- START SECTION SUBSCRIBE NEWSLETTER -->
