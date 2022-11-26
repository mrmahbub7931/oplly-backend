@if (Route::current() && Route::current()->uri() != "checkout/{token}" && Route::current()->uri() != "talent/signup")
{!! dynamic_sidebar('footer_sidebar') !!}
@endif
<footer class="footer_dark">
    <div class="bottom_footer">
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-9">
                    <p class="mb-md-0 text-center text-md-left">
                        @if (theme_option('logo_footer') || theme_option('logo'))
                            <div class="footer_logo mb-2">
                                <a href="{{ url('/') }}">
                                    <img src="{{ RvMedia::getImageUrl(theme_option('logo_footer') ? theme_option('logo_footer') : theme_option('logo')) }}"
                                        alt="{{ theme_option('site_title') }}" />
                                </a>
                                {!! Menu::renderMenuLocation('footer-menu', ['view' => 'menu', 'options' => ['class' => 'nav uc-upp']]) !!}
                            </div>
                        @endif
                        <small class="text-dark d-block">{{ theme_option('copyright') }}</small>
                    </p>
                </div>
                <div class="col-12 col-md-3">
                    <div class="widget text-right">
                        <ul class="social_icons social_white">
                            @if (theme_option('facebook'))
                                <li><a href="{{ theme_option('facebook') }}" class="ss_sc_facebook" target="_blank"><i
                                            class="ion-social-facebook"></i></a></li>
                            @endif
                            @if (theme_option('twitter'))
                                <li><a href="{{ theme_option('twitter') }}" class="ss_sc_twitter" target="_blank"><i
                                            class="ion-social-twitter"></i></a></li>
                            @endif
                            @if (theme_option('youtube'))
                                <li><a href="{{ theme_option('youtube') }}" class="ss_sc_youtube" target="_blank"><i
                                            class="ion-social-youtube-outline"></i></a></li>
                            @endif
                            @if (theme_option('instagram'))
                                <li><a href="{{ theme_option('instagram') }}" class="ss_sc_instagram"
                                        target="_blank"><i class="ion-social-instagram-outline"></i></a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>

@if (is_plugin_active('ecommerce') && EcommerceHelper::isCartEnabled())
    <div id="remove-item-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Warning') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Are you sure you want to remove this product from cart?') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-fill-out" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="button"
                        class="btn btn-fill-line confirm-remove-item-cart">{{ __('Yes, remove it!') }}</button>
                </div>
            </div>
        </div>
    </div>
@endif

<a href="#" class="scrollup" style="display: none;"><i class="ion-ios-arrow-up"></i></a>

<script>
    window.siteUrl = "{{ url('') }}";
</script>

{!! Theme::footer() !!}
{!! setting('hotjar_code_field') !!}
</body>

</html>
