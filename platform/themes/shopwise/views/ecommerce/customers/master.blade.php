<!-- START MAIN CONTENT -->
<div class="main_content">
  <div id="app"></div>
  <!-- START SECTION SHOP -->
  <div class="section">
    <div class="container">
      <div class="row">
        <div class="col-lg-9 col-12 mx-auto account--section">
          <div class="dashboard_menu">
            <ul class="nav nav-tabs flex-row" role="tablist">
              {{-- <li class="nav-item">
                <a class="nav-link @if (Route::currentRouteName() == 'customer.overview') active @endif"
                  href="{{ route('customer.overview') }}">{{-- <i class="ti-layout-grid2"></i> -- }}{{ __('Overview') }}</a>
              </li> --}}

              @if (auth('customer')->user()->talent)
              <li class="nav-item">
                <a class="nav-link @if (Route::currentRouteName() == 'talent.overview') active @endif"
                  href="{{ route('talent.overview') }}">{{-- <i class="ti-layout-grid2"></i> --}}{{ __('Overview') }}</a>
              </li>


                <li class="nav-item">
                  <a class="nav-link @if (Route::currentRouteName()=='talent.requests' ||
                    Route::currentRouteName()=='talent.requests.view' ) active @endif"
                    href="{{ route('talent.requests') }}">{{-- <i class="ti-shopping-cart-full"></i> --}}{{ __('Fan Requests') }}</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link @if (Route::currentRouteName()=='talent.edit-account' ) active @endif"
                    href="{{ route('talent.edit-account') }}">{{-- <i class="ti-id-badge"></i> --}}{{ __('My Profile') }}</a>
                </li>
                {{--
                <li class="nav-item">
                    <a class="nav-link @if (Route::currentRouteName()=='talent.bookings' ) active @endif"
                       href="{{ route('talent.bookings') }}">{{ __('My Live Bookings') }}</a>
                </li>
                --}}

                <li class="nav-item">
                  <a class="nav-link @if (Route::currentRouteName()=='talent.edit-bank-details'
                    ) active @endif"
                    href="{{ route('talent.edit-bank-details') }}">{{-- <i class="ti-id-badge"></i> --}}{{ __('Manage Banking') }}</a>
                </li>

                <li class="nav-item">
                  <a class="nav-link @if (Route::currentRouteName()=='talent.transaction-history'
                    ) active @endif"
                    href="{{ route('talent.transaction-history') }}">{{-- <i class="ti-id-badge"></i> --}}{{ __('Transaction History') }}</a>
                </li>
              @else
                <li class="nav-item">
                  <a class="nav-link @if (Route::currentRouteName()=='customer.orders' ||
                    Route::currentRouteName()=='customer.orders.view' ) active @endif"
                    href="{{ route('customer.orders') }}">{{-- <i class="ti-shopping-cart-full"></i> --}}{{ __('My Requests') }}</a>
                </li>

              @endif
              {{-- <li class="nav-item">
                      <a class="nav-link @if (Route::currentRouteName() == 'customer.address' || Route::currentRouteName() == 'customer.address.create' || Route::currentRouteName() == 'customer.address.edit') active @endif" href="{{ route('customer.address') }}"><i class="ti-location-pin"></i>{{ __('My Addresses') }}</a>
                            </li> --}}
              <li class="nav-item">
                <a class="nav-link @if (Route::currentRouteName()=='customer.edit-account' ) active @endif"
                  href="{{ route('customer.edit-account') }}">{{-- <i class="ti-id-badge"></i> --}}{{ __('Account') }}</a>
              </li>
              <li class="nav-item">
                <a class="nav-link @if (Route::currentRouteName()=='customer.change-password' ) active @endif"
                  href="{{ route('customer.change-password') }}">{{-- <i class="ti-id-badge"></i> --}}{{ __('Security') }}</a>
              </li>
              {{-- <li class="nav-item">
                                <a class="nav-link" href="{{ route('customer.logout') }}">{{-- <i class="ti-lock"></i> -- }}{{ __('Logout') }}</a>
                            </li> --}}
            </ul>
          </div>
          {{-- </div>
                <div class="col-lg-9 col-12 mx-auto"> --}}
          <div class="dashboard_content">
            @yield('content')
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- END SECTION SHOP -->
