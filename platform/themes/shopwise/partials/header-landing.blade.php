<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta
    content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=5, user-scalable=1"
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


.featured--talents {
    text-align: center;
}

.featured--talents .cell.col-6 {
    /* position: relative; */
    padding-bottom: 50;
}

img.talent--image.mx-auto.mb-3 {
    max-width: 300px;
    max-height: 300px;
}

.featured--talents .row.mb-4 {
    width: 100%;
}

.featured--talents > .cell {
    width: 100%;
}

.cell {
    width: 100%;
}

  </style>

  {!! Theme::header() !!}
</head>

<body @if (BaseHelper::siteLanguageDirection()=='rtl' ) dir="rtl" @endif class="bg-light">
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



  @php
  if (is_plugin_active('ecommerce')) {
  $categories = get_product_categories(['status' =>
  \Canopy\Base\Enums\BaseStatusEnum::PUBLISHED], ['slugable', 'children',
  'children.slugable'], [], true);
  } else {
  $categories = [];
  }
  @endphp

  <!-- START HEADER -->
  <header
    class="header_wrap @if (theme_option('enable_sticky_header', 'yes') == 'yes') fixed-top header_with_topbar  @endif  nav-fixed">
    <div
      class="bottom_header dark_skin main_menu_uppercase bg_lightk @if (url()->current() === url('')) mb-4 @endif">
      <div class="container">
        <div class="nav_block">

          @if (theme_option('enable_sticky_header', 'yes') == 'yes')
          <a class="navbar-brand" href="{{ url('/') }}">
            <img
              src="{{ RvMedia::getImageUrl(theme_option('logo') ? theme_option('logo') : theme_option('logo_footer')) }}"
              alt="{{ theme_option('site_title') }}" />
          </a>
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
                                  <a class="dropdown-item" href="{{ route('talent.edit-account') }}">Profile</a>
                                  <a class="dropdown-item" href="{{ route('talent.requests') }}">Fans Requests</a>
                                  <div class="dropdown-divider"></div>
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

        </div>
    </header>
    <!-- END HEADER -->
