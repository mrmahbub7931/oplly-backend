@if (count($sliders) > 0)
  <div class="banner_section slide_medium shop_banner_slider staggered-animation-wrap">
    <div class="container-fluid px-0">
      <div class="row">
        <div class="col-12">
          <div id="carouselExampleControls" class="carousel slide light_arrow" data-ride="carousel">
            <div class="carousel-inner">
              @foreach ($sliders as $slider)
                <div class="carousel-item @if ($loop->first) active @endif background_bg"
                  data-img-src="{{ RvMedia::getImageUrl($slider->image, null, false, RvMedia::getDefaultImage()) }}">
                  <div class="banner_slide_content banner_content_inner">
                    <div class="col-lg-8 col-10 mx-auto">
                      <div class="banner_content overflow-hidden">

                        @if ($slider->title)
                          <h2 data-animation-delay="0.5s" class="text-center">
                            {{ $slider->title }}</h2>
                        @endif
                        @if ($slider->description)
                          <div class="mt-3 slider--description font-weight-light" data-animation-delay="0.5s">
                            {!! $slider->description !!}</div>
                        @endif
                        @if ($slider->link)
                          <a class="btn btn-primary btn-rounded text-uppercase" href="{{ $slider->link }}"
                            data-animation="slideInLeft" data-animation-delay="1.5s">{{ __('View more') }}</a>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
            @if ($sliders->count() > 1)
              <ol class="carousel-indicators indicators_style1">
                @foreach ($sliders as $slider)
                  <li data-target="#carouselExampleControls" data-slide-to="{{ $loop->index }}" @if ($loop->first) class="active" @endif></li>
                @endforeach
              </ol>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endif
