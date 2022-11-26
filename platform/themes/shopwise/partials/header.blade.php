<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=5, user-scalable=1"
    name="viewport" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Google Font -->
  <link
    href="https://fonts.googleapis.com/css?family={{ urlencode(theme_option('primary_font', 'Poppins')) }}:200,300,400,500,600,700,800,900&display=swap"
    rel="stylesheet">

  <style>
    :root {
      --color-1st: {
          {
          theme_option('primary_color', '#FF00B2')
        }
      }

      ;

      --color-2nd: {
          {
          theme_option('secondary_color', '#5eff00')
        }
      }

      ;
      --primary-font: '{{ theme_option('primary_font', 'Poppins') }}',
      sans-serif;
    }
    .with--watermark:before {
        content: '';
        background: url({{ RvMedia::getImageUrl(theme_option('logo_watermark'))}});
         }
  </style>
  {!! Theme::header() !!}

  {!! setting('pixel_code_field') !!}
</head>

<body @if (BaseHelper::siteLanguageDirection() == 'rtl') dir="rtl" @endif>
  @if (theme_option('preloader_enabled', 'no') == 'yes')
    <!-- LOADER -->
    <div class="preloader">
      <div class="lds-ellipsis">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </div>
    <!-- END LOADER -->
  @endif

  <div id="alert-container"></div>

  @if (is_plugin_active('newsletter') && theme_option('enable_newsletter_popup', 'yes') === 'yes')
    <div data-session-domain="{{ config('session.domain') ?? request()->getHost() }}"></div>
    <!-- Home Popup Section -->
    <div class="modal fade subscribe_popup" id="newsletter-modal" data-backdrop="static" data-keyboard="false"
      tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true"><i class="ion-ios-close-empty"></i></span>
            </button>
            <div class="row no-gutters">
              <div class="col-sm-5">
                @if (theme_option('newsletter_image'))
                  <div class="background_bg h-100"
                    data-img-src="{{ RvMedia::getImageUrl(theme_option('newsletter_image')) }}">
                  </div>
                @endif
              </div>
              <div class="col-sm-7">
                <div class="popup_content">
                  <div class="popup-text">
                    <div class="heading_s4">
                      <h4>{{ __('Subscribe and Get 25% Discount!') }}</h4>
                    </div>
                    <p>
                      {{ __('Subscribe to the newsletter to receive updates about new products.') }}
                    </p>
                  </div>
                  <form method="post" action="{{ route('public.newsletter.subscribe') }}" class="newsletter-form">
                    @csrf
                    <div class="form-group">
                      <input name="email" type="email" class="form-control rounded-0"
                        placeholder="{{ __('Enter Your Email') }}">
                    </div>

                    @if (setting('enable_captcha') && is_plugin_active('captcha'))
                      <div class="form-group">
                        {!! Captcha::display() !!}
                      </div>
                    @endif

                    <div class="chek-form text-left form-group">
                      <div class="custome-checkbox">
                        <input class="form-check-input" type="checkbox" name="dont_show_again" id="dont_show_again"
                          value="">
                        <label class="form-check-label"
                          for="dont_show_again"><span>{{ __("Don't show this popup again!") }}</span></label>
                      </div>
                    </div>
                    <div class="form-group">
                      <button class="btn btn-block text-uppercase rounded-0" type="submit"
                        style="background: #333; color: #fff;">{{ __('Subscribe') }}</button>
                    </div>

                    <div class="form-group">
                      <div class="newsletter-message newsletter-success-message" style="display: none"></div>
                      <div class="newsletter-message newsletter-error-message" style="display: none"></div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Screen Load Popup Section -->
  @endif

  @php
    if (is_plugin_active('ecommerce')) {
        $categories = get_product_categories(['status' => \Canopy\Base\Enums\BaseStatusEnum::PUBLISHED], ['slugable', 'children', 'children.slugable'], [], true);
    } else {
        $categories = [];
    }
  @endphp

  <!-- START HEADER -->
  <header class="header_wrap  fixed-top header_with_topbar nav-fixed">

    <div class="navbar navbar-expand-lg navbar-dark bottom_header dark_skin main_menu_uppercase bg_dark @if (url()->current() === url(''))  @endif">
      <div class="container-fluid">
        {{-- <button class="navbar-toggler side_navbar_toggler" type="button" data-toggle="collapse"
          data-target="#navbarSidetoggle" aria-expanded="false">
          <span class="ion-android-menu"></span>
        </button> --}}
        @if (theme_option('enable_sticky_header', 'yes') == 'yes')
          <a class="navbar-brand" href="{{ url('/') }}">
            <img
              src="{{ RvMedia::getImageUrl(theme_option('logo') ? theme_option('logo') : theme_option('logo_footer')) }}"
              alt="{{ theme_option('site_title') }}" />
            <span class="mobile__header-logo"
              style="background-image:url('{{ RvMedia::getImageUrl(theme_option('logo') ? theme_option('logo') : theme_option('logo_footer')) }}')"></span>
          </a>
        @endif

        @if (is_plugin_active('ecommerce'))
          <div class="product_search_form" id="search-app">
              <search-bar-component url="{{ route('api.search', '') }}"></search-bar-component>
            {{--<form action="{{ route('public.products') }}" method="GET">
              <div class="input-group">
                <input class="form-control" name="q" value="{{ request()->input('q') }}"
                  placeholder="{{ __('Search') }}..." required type="text">
                <button type="submit" class="search_btn"><i class="linearicons-magnifier"></i></button>
              </div>
            </form>--}}
          </div>
        @endif


        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          {!! Menu::renderMenuLocation('main-menu', ['view' => 'menu', 'options' => ['class' => 'navbar-nav ml-auto']]) !!}
        </div>
          @if (is_plugin_active('ecommerce'))
              @php $currencies = get_all_currencies(); @endphp
              @if (count($currencies) > 1)
                  <div class="language-wrapper choose-currency mr-3">
                      <div class="dropdown">
                          <button class="btn btn-secondary dropdown-toggle btn-select-language" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                              {{ get_application_currency()->title }}
                              <span class="language-caret"></span>
                          </button>
                          <ul class="dropdown-menu language_bar_chooser">
                              @foreach ($currencies as $currency)
                                  <li>
                                      <a href="{{ route('public.change-currency', $currency->title) }}" @if (get_application_currency_id() == $currency->id) class="active" @endif><span>{{ $currency->title }}</span></a>
                                  </li>
                              @endforeach
                          </ul>
                      </div>
                  </div>
              @endif
          @endif
        <ul class="header_list">
          @if (!auth('customer')->check())
            <li><a href="{{ route('customer.login') }}"><span
                  class="font-weight-bold text-white">{{ __('Signin') }}</span></a>
            </li>
          @else

            <li class="nav-item account_toggler dropdown">
              <a class="nav-link account__dropdown-toggle dropdown-toggle" href="#" id="accountDropdown" role="button"
                aria-haspopup="true" aria-expanded="false">
                  <span class="account--image">
                      @if (auth('customer')->user()->talent and auth('customer')->user()->talent->photo)
                          <img
                              src="{{ RvMedia::getImageUrl(auth('customer')->user()->talent->photo, 'thumb', false, RvMedia::getDefaultImage()) }}"
                              alt="" />
                      @else
                          {{--<img src="{{ RvMedia::getImageUrl(auth('customer')->user()->avatar, 'thumb', false, RvMedia::getDefaultImage()) }}" alt="" class="">--}}
                          <i class="profile--img fas fa-user" ></i>
                      @endif
                  </span>
              </a>

              <div class="dropdown-menu" aria-labelledby="accountDropdown">
                  <div class="account--info">
                      <span class="font-weight-bold text-white">{{ auth('customer')->user()->name }}</span>
                  </div>
                @if (auth('customer')->user()->talent)
                <a class="dropdown-item" href="{{ route('talent.overview') }}">Dashboard</a>
                      @if (auth('customer')->user()->talent->mainProduct)
                        <a class="dropdown-item" href="{{ auth('customer')->user()->talent->mainProduct->url }}">Profile</a>
                      @endif
                <a class="dropdown-item" href="{{ route('talent.requests') }}">Fans Requests</a>
                 <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="{{ route('talent.edit-account') }}">Profile Settings</a>
                @endif
                <a class="dropdown-item" href="{{ route('customer.edit-account') }}">Account</a>
                @if (auth('customer')->user()->talent)

                @else
                <a class="dropdown-item" href="{{ route('customer.orders') }}">My Requests</a>
                @endif
                <a class="dropdown-item" href="{{ route('customer.change-password') }}">Security</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item"
                  href="{{ route('customer.logout') }}"><span>{{ __('Signout') }}</span></a>
              </div>
            </li>
          @endif
        </ul>

      </div>



    </div>

    </div>
  </header>
  <!-- END HEADER -->
