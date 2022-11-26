<!-- START SECTION BANNER -->
<div class="section pb_20 small_pt">
  <div class="container">
    <div class="row">
      <sliding-banners-component>

          <div class="sale-banner mb-3 mb-md-4">
            <a class="hover_simple" href="{{ url((string) $url1) }}">
              <img src="{{ RvMedia::getImageUrl($image1) }}" alt="Image 1">
            </a>
          </div>

          <div class="sale-banner mb-3 mb-md-4">
            <a class="hover_simple" href="{{ url((string) $url2) }}">
              <img src="{{ RvMedia::getImageUrl($image2) }}" alt="Image 2">
            </a>
          </div>

          <div class="sale-banner mb-3 mb-md-4">
            <a class="hover_simple" href="{{ url((string) $url3) }}">
              <img src="{{ RvMedia::getImageUrl($image3) }}" alt="Image 3">
            </a>
        </div>
      </sliding-banners-component>
    </div>
  </div>
</div>
<!-- END SECTION BANNER -->
