<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title', __('Checkout')) </title>

    @if (theme_option('favicon'))
        <link rel="shortcut icon" href="{{ RvMedia::getImageUrl(theme_option('favicon')) }}">
    @endif

    {!! Html::style('vendor/core/core/base/libraries/font-awesome/css/fontawesome.min.css') !!}
    {!! Html::style('vendor/core/plugins/ecommerce/css/front-theme.css?v=1.0.2') !!}

    @if (setting('locale_direction', 'ltr') == 'rtl')
        {!! Html::style('vendor/core/plugins/ecommerce/css/front-theme-rtl.css?v=1.0.3') !!}
    @endif

    {!! Html::style('vendor/core/core/base/libraries/toastr/toastr.min.css') !!}

    {!! Html::script('vendor/core/plugins/ecommerce/js/checkout.js?v=1.0.3') !!}
    {!! Theme::header() !!}
</head>

<style>
    @media screen and (min-width: 331px) {
            ul.list-group.list_occasion--selector {
            margin: 0 auto;
            justify-content: center;
        }
    }
</style>

<body class="checkout-page" @if (setting('locale_direction', 'ltr') == 'rtl') dir="rtl" @endif>

    {{--<!-- START HEADER -->
    <header class="header_wrap  fixed-top header_with_topbar nav-fixed">

        <div class="navbar navbar-expand-lg navbar-dark bottom_header dark_skin main_menu_uppercase bg_dark @if (url()->current() === url(''))  @endif">
            <div class="container-fluid">
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
                    <div class="product_search_form">
                        <form action="{{ route('public.products') }}" method="GET">
                            <div class="input-group">
                                <input class="form-control" name="q" value="{{ request()->input('q') }}"
                                       placeholder="{{ __('Search') }}..." required type="text">
                                <button type="submit" class="search_btn"><i class="linearicons-magnifier"></i></button>
                            </div>
                        </form>
                    </div>
                @endif


                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    {!! Menu::renderMenuLocation('main-menu', ['view' => 'menu', 'options' => ['class' => 'navbar-nav ml-auto']]) !!}
                </div>

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
    </header>
    <!-- END HEADER -->
    --}}

    <div class="checkout-content-wrap">
        <div class="container">
            <div class="row">
                @yield('content')
            </div>
        </div>
    </div>

    {!! Html::script('vendor/core/plugins/ecommerce/js/utilities.js') !!}
    {!! Html::script('vendor/core/core/base/libraries/toastr/toastr.min.js') !!}

    {!! Theme::footer() !!}

    <script type="text/javascript">
        window.messages = {
            error_header: '{{ __('Error') }}',
            success_header: '{{ __('Success') }}',
        }
    </script>

    @if (session()->has('success_msg') || session()->has('error_msg') || isset($errors))
        <script type="text/javascript">
            $(document).ready(function () {
                @if (session()->has('success_msg'))
                    MainCheckout.showNotice('success', '{{ session('success_msg') }}');
                @endif
                @if (session()->has('error_msg'))
                    MainCheckout.showNotice('error', '{{ session('error_msg') }}');
                @endif
                @if (isset($errors))
                    @foreach ($errors->all() as $error)
                        MainCheckout.showNotice('error', '{{ $error }}');
                    @endforeach
                @endif
            });
        </script>
    @endif

</body>
</html>
