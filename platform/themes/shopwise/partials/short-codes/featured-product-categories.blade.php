<!-- START SECTION CATEGORIES -->
<div class="section py-5 py-md-3">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="heading_tab_header">
          <div class="heading_s2">
            <h4>{!! clean($title) !!}</h4>
          </div>
          <div class="view_all">
            <a href="{{ route('public.categories') }}" class="text_default"><span>{{ __('View more') }}</span></a>
          </div>
        </div>
      </div>
    </div>
    <div class="row align-items-center">
      <featured-product-categories-component url="{{ route('public.ajax.featured-product-categories') }}">
      </featured-product-categories-component>
    </div>
  </div>
</div>
<!-- END SECTION CATEGORIES -->
