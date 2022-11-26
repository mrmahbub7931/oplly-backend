@extends(Theme::getThemeNamespace() . '::views.ecommerce.customers.master')

@section('content')
  @php Theme::set('pageName', __('My Talent Details')) @endphp
  <div class="card">
    <div class="card-header">
      <h3>{{ __('Talent Information') }}</h3>
    </div>
    <div class="card-body">
      {!! Form::open(['route' => 'talent.edit-account', 'files' => true]) !!}
      <div class="row">
        <div class="col-12 col-md-6">
          <div class="form-group file-upload--avatar">
            <img
              src="{{ RvMedia::getImageUrl(auth('customer')->user()->talent->photo, null, false, RvMedia::getDefaultImage()) }}"
              alt="" />
            <div class="nice--upload--button">
              <div class="btn btn-secondary">Change Photo</div>
              <input id="photo" type="file" class="form-control" name="photo" value="">
            </div>
          </div>
          {!! Form::error('photo', $errors) !!}
        </div>
        <div class="col-12 col-md-6">
          <div class="form-group file-upload--video">
            <video width="200" height="200" controls="">
              <source src="{{ RvMedia::getImageUrl(auth('customer')->user()->talent->video, null, false) }}"
                type="video/mp4">
              Your browser does not support the video tag.
            </video>
            <div class="nice--upload--button">
              <div class="btn btn-secondary">Change Video</div>
              <input id="video" type="file" class="form-control" name="video" value="">
            </div>
          </div>
          {!! Form::error('video', $errors) !!}
        </div>
      </div>

      {{--<div class="form-group my-5 mx-auto">
        <div class="chek-form d-flex justify-content-center">
          <div class="custome-checkbox">
            <input class="form-check-input" type="checkbox" @if (auth('customer')->user()->talent->hidden_profile) checked @endif
              name="hidden_profile" id="hidden_profile">
            <label class="form-check-label text-muted"
              for="hidden_profile"><span>{{ __('Hide this profile from public') }}</span></label>
          </div>
        </div>
      </div>--}}

      <div class="form-group my-5 mx-auto text-center">
        <div class="toggle normal">
          <input id="normal" type="checkbox" name="hidden_profile" @if (auth('customer')->user()->talent->hidden_profile) checked @endif/>
          <label class="toggle-item" for="normal"></label>
          <span class="labels"></span>
        </div>
      </div>

      <div class="form-group nice--input mt-4">
        <label for="name">{{ __('Public Name') }}</label>
        <input id="name" type="text" class="form-control" name="name"
          value="{{ auth('customer')->user()->talent->first_name }} {{ auth('customer')->user()->talent->last_name }}">
      </div>
      {!! Form::error('name', $errors) !!}

      <div class="form-group nice--input">
        <label for="title">{{ __('Title') }}</label>
        <input id="title" type="text" class="form-control" name="title"
          value="{{ auth('customer')->user()->talent->title }}">
      </div>
      {!! Form::error('title', $errors) !!}

      <div class="form-group nice--input mt-4 text--countable @if ($errors->has('bio')) has-error @endif" data-limit="600">
        <label for="bio">{{ __('Bio/About') }}</label>
        <textarea id="bio" type="text" class="form-control"
          name="bio">{{ strip_tags(auth('customer')->user()->talent->bio) }}</textarea>
      </div>
      {!! Form::error('bio', $errors) !!}

      {{-- <div class="form-group mt-5">
        <div class="text-center mb-4">
          <h5 class="checkout-payment-title">{{ __('Provide searchable tags') }}</h5>
          <p class="text-muted">Add hashtags for you</p>
        </div>
      </div>
      <div class="form-group nice--input @if ($errors->has('email')) has-error @endif">
        <label for="tags">{{ __('Tags') }}</label>
        <input id="tags" type="text" class="form-control" value="{{ auth('customer')->user()->talent->tags }}"
          name="tags">
      </div>
      {!! Form::error('tags', $errors) !!} --}}

      <div class="text-center mb-4 mt-5">
        <h5 class="checkout-payment-title">{{ __('Services') }}</h5>
        <p class="text-muted">Manage your services and set prices</p>
      </div>

      <div class="form-group nice--input">
        <label for="price">{{ __('Price') }} ({{ get_application_currency()->symbol }})</label>
        <input id="price" type="number" class="form-control" name="price"
          value="{{ format_price(auth('customer')->user()->talent->price, null, true, false) }}">
      </div>
      {!! Form::error('price', $errors) !!}

      <div class="text-center mb-4 toggle--header mt-5" data-target="discount-section">
        <div class="chek-form">
          <div class="custome-checkbox">
            <input class="form-check-input" type="checkbox" name="allow_discount" id="allow_discount" @if (auth('customer')->user()->talent->allow_discount) checked @endif>
            <label class="form-check-label text-muted" for="allow_discount"><span></span></label>
          </div>
        </div>
        <div class="toggle--head--title">
          <h5 class="checkout-payment-title">{{ __('Discount') }}</h5>
          <p class="text-muted">Give discount to your fans</p>
        </div>
      </div>

      <div id="discount-section" class="toggle--section--content @if (auth('customer')->
        user()->talent->allow_discount) show @endif ">
        <div class="form-group nice--input">
          <label for="discount_percentage">{{ __('Discount (%)') }}</label>
          <input id="discount_percentage" type="number" class="form-control" name="discount_percentage"
            value="{{ auth('customer')->user()->talent->discount_percentage }}">
        </div>
        {!! Form::error('discount_percentage', $errors) !!}

        @if(auth('customer')->user()->talent->allow_discount == true && auth('customer')->user()->talent->discount_percentage > 0)
        <div class="form-group nice--input">
          <label for="talent_discount_price">{{ __('Discount Price') }} ({{ get_application_currency()->symbol }})</label>
          <input id="talent_discount_price" type="number" class="form-control" name="talent_discount_price" readonly
            value="{{ format_price(auth('customer')->user()->talent->discount_price, null, true, false) }}">
        </div>
        @endif
      </div>


      {{--<div class="form-group mb-3 mx-auto">
        <div class="chek-form d-flex justify-content-center">
          <div class="custome-checkbox">
            <input class="form-check-input" type="checkbox" @if (auth('customer')->user()->talent->allow_speed_service) checked @endif
              name="allow_speed_service" id="allow_speed_service">
            <label class="form-check-label text-muted"
              for="allow_speed_service"><span>{{ __('Enable 24 hour Speed Service') }}</span></label>
          </div>
        </div>
      </div>--}}

      <div class="text-center mb-4 toggle--header" data-target="">
        <div class="chek-form">
          <div class="custome-checkbox">
            <input class="form-check-input" type="checkbox" name="allow_speed_service" id="allow_speed_service"  @if (auth('customer')->user()->talent->allow_speed_service) checked @endif>
            <label class="form-check-label text-muted" for="allow_speed_service"><span></span></label>
          </div>
        </div>
        <div class="toggle--head--title">
          <h5 class="checkout-payment-title">{{ __('Speed Service') }}</h5>
          <p class="text-muted">Enable 24 hour Speed Service</p>
        </div>
      </div>

      {{-- <div class="text-center mb-4 toggle--header  mt-5" data-target="live-booking-section">
        <div class="chek-form">
          <div class="custome-checkbox">
            <input class="form-check-input" type="checkbox" name="allow_live" id="allow_live" @if (auth('customer')->user()->talent->allow_live) checked @endif>
            <label class="form-check-label text-muted" for="allow_live"><span></span></label>
          </div>
        </div>
        <div class="toggle--head--title">
          <h5 class="checkout-payment-title">{{ __('Live Bookings') }}</h5>
          <p class="text-muted">Manage settings for live bookings</p>
        </div>
      </div>

      <div id="live-booking-section" class="toggle--section--content @if (auth('customer')->
        user()->talent->allow_live) show @endif">
      </div> --}}

      <div class="text-center mb-4 toggle--header" data-target="business-section">
        <div class="chek-form">
          <div class="custome-checkbox">
            <input class="form-check-input" type="checkbox" name="allow_business" id="allow_business" @if (auth('customer')->user()->talent->allow_business) checked @endif>
            <label class="form-check-label text-muted" for="allow_business"><span></span></label>
          </div>
        </div>
        <div class="toggle--head--title">
          <h5 class="checkout-payment-title">{{ __('Business') }}</h5>
          <p class="text-muted">Enable bookings for corporate clients</p>
        </div>
      </div>

      <div id="business-section" class="toggle--section--content @if (auth('customer')->
        user()->talent->allow_business) show @endif ">
        <div class="form-group nice--input">
          <label for="business_price">{{ __('Price for businesses') }} ({{ get_application_currency()->symbol }})</label>
          <input id="business_price" type="number" class="form-control" name="business_price"
            value="{{ format_price(auth('customer')->user()->talent->business_price, null, true, false) }}">
        </div>
        {!! Form::error('business_price', $errors) !!}
      </div>

      <div class="text-center mb-4 toggle--header" data-target="causes-section">
        <div class="chek-form">
          <div class="custome-checkbox">
            <input class="form-check-input" type="checkbox" name="has_cause" id="has_cause" @if (auth('customer')->user()->talent->has_cause) checked @endif>
            <label class="form-check-label text-muted" for="has_cause"><span></span></label>
          </div>
        </div>
        <div class="toggle--head--title">
          <h5 class="checkout-payment-title">{{ __('Causes') }}</h5>
          <p class="text-muted">Enable Cause that your raising funds for</p>
        </div>
      </div>

      <div id="causes-section" class="toggle--section--content @if (auth('customer')->
        user()->talent->has_cause) show @endif">
        <div class="row">
          <div class="col-12 col-md-6">
            <div class="form-group nice--input">
              <label for="cause_start_date">{{ __('Start Date') }}</label>
              <input id="cause_start_date" type="date" class="form-control" name="cause_start_date"
                value="{{ auth('customer')->user()->talent->cause_start_date }}">
            </div>
            {!! Form::error('cause_start_date', $errors) !!}

          </div>
          <div class="col-12 col-md-6">
            <div class="form-group nice--input">
              <label for="cause_end_date">{{ __('End Date') }}</label>
              <input id="cause_end_date" type="date" class="form-control" name="cause_end_date"
                value="{{ auth('customer')->user()->talent->cause_end_date }}">
            </div>
            {!! Form::error('cause_end_date', $errors) !!}
          </div>
        </div>
      </div>
      <div class="form-group nice--input text-center mt-4">
        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
@endsection
