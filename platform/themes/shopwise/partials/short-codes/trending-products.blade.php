<!-- START SECTION SHOP -->
<div class="section py-5 py-md-3">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="heading_tab_header">
                    <div class="heading_s2 text-center">
                        <h4>{!! clean($title) !!}</h4>
                    </div>
                    <div class="view_all">
                        <a href="{{ route('public.products') }}"
                            class="text_default"><span>{{ __('View more') }}</span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <trending-products-component url="{{ route('public.ajax.trending-products') }}">
            </trending-products-component>
        </div>
    </div>
</div>
<!-- END SECTION SHOP -->
