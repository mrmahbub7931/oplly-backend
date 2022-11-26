@php
Theme::asset()->remove('app-js');

$originalProduct = $product;
$selectedAttrs = [];
$productImages = $product->images;
if ($product->is_variation) {
$product = get_parent_product($product->id);
$selectedAttrs = app(\Canopy\Ecommerce\Repositories\Interfaces\ProductVariationInterface::class)->getAttributeIdsOfChildrenProduct($originalProduct->id);
if (count($productImages) == 0) {
$productImages = $product->images;
}
} else {
$selectedAttrs = $product->defaultVariation->productAttributes->pluck('id')->all();
}

@endphp

@php Theme::set('pageName', $product->name) @endphp
<div class="section mt-0 mt-md-5">
  <div class="container product--container">
    <div class="row mb-4">
      <div class="col-12">
        <div class="product-header-slider slick_slider" data-slides-to-show="4" data-slides-to-scroll="1" data-infinite="false" data-responsive='[{"breakpoint":"1024", "settings":{"slidesToShow": "4"}}, {"breakpoint":"991", "settings":{"slidesToShow": "3"}}, {"breakpoint":"600", "settings":{"slidesToShow": "2"}}]'>
          <div class="product-header-slider-item">
            <a href="#" class="product_gallery_item active" data-image="{{ RvMedia::getImageUrl($product->owner->photo ?? $product->image) }}" data-zoom-image="{{ RvMedia::getImageUrl($product->owner->photo ?? $product->image) }}">
              <img src="{{ RvMedia::getImageUrl($product->owner->photo ?? $product->image, null, false, RvMedia::getDefaultImage()) }}" alt="{{ $product->name }}" />
              @if ($product->owner && $product->owner->video)
              <a class="toggle_video_modal" onclick="videoGalleryfn('{{ RvMedia::getImageUrl($product->owner->video, null, false) }}',null, null)">
                <i class="fas fa-play-circle"></i> Play Intro Video</a>
              @endif
            </a>
          </div>

          @if ($product->owner)
          @php
          $completedRequests = $product->owner->mainOrders->where('status', "completed")->where('allow_public',true)->take(4)
          @endphp
          @foreach ($completedRequests as $request)
          @if($request->video)
          <div class="product-header-slider-item">
            <div class="play_icon" onclick="videoGalleryfn('{{RvMedia::getImageUrl($request->video)}}','{{ $request->talent->first_name }}','{{$request->occasion->name}}')">
              <i class="fas fa-play-circle"></i>
            </div>
            <video src="{{ RvMedia::getImageUrl($request->video) }}" onclick="videoGalleryfn('{{RvMedia::getImageUrl($request->video)}}','{{ $request->talent->first_name }}','{{$request->occasion->name}}')"></video>
          </div>
          @endif
          @endforeach

          @endif

          <div class="product-header-slider-item">
            <a class="product_gallery_item follow-section follow__toggle" onclick="followToggle()">
                <div>
                  @if (is_added_to_wishlist($product->id))
                      <i id="follow__icon" class="fas fa-heart" ></i>
                      <div id="follow__div" class="h4">Followed</div>
                      <p>Follow the celebrity to receive all the updates</p>
                  @else
                      <i id="follow__icon" class="far fa-heart" ></i>
                      <div id="follow__div" class="h4">Follow</div>
                      <p>Follow the celebrity to receive all the updates</p>
                  @endif
                </div>
            </a>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-md-7">
        <div class="pr_detail">
          <div class="product_description">
            <div class="product_header--section">
              <div class="product_header--image">
                @if ($product->owner and $product->owner->has_cause)
                <span class="charity--label"><i class="fas fa-ribbon"></i></span>
                @endif
                @if ($product->owner)
                <img src="{{ RvMedia::getImageUrl($product->owner->photo, 'thumb', false, RvMedia::getDefaultImage()) }}" alt="{{ $product->name }}" />
                @else
                <img src="{{ RvMedia::getImageUrl($product->image, 'thumb', false, RvMedia::getDefaultImage()) }}" alt="{{ $product->name }}" />
                @endif
              </div>
              <div>
                <h1 class="product_title">{{ $product->name }}</h1>

                <h3 class="product_subtitle">{{ $product->owner->title ?? $product->title }}</h3>
              </div>
            </div>
            <div>
              @if (EcommerceHelper::isReviewEnabled())
              @php
              $countRating = get_count_reviewed_of_product($originalProduct->id);
              $avg_rating = get_average_star_of_product($originalProduct->id)
              @endphp

              <div class="total_rating rating_wrap">
                <div class="rating">
                  <div class="product_rate" style="width: {{ $avg_rating * 20 }}%">
                  </div>
                </div>
                <span class="rating_num">
                  @if ($avg_rating > 0)
                  {{ $avg_rating }}
                  @endif
                </span>
              </div>
              @endif

            </div>
          </div>
        </div>
        <div class="product_description--content pr-1 pr-md-2 pr-lg-4">
          @if ($product->owner)
          {!! clean($product->owner->bio, 'youtube') !!}
          @else
          {!! clean($product->description, 'youtube') !!}
          @endif
        </div>
        <div class="btn-actions mb-4 mt-2">
          <a class="follow__toggle p-r-md-0 btn btn-dark" onclick="followToggle()">
            <div class="d-flex justify-content-between">
              @if (is_added_to_wishlist($product->id))
                <i id="follow__icon" class="fas fa-heart" ></i>
                <div id="follow__count">Followed ( {{ count($product->likes) }} )</div>
              @else
                <i id="followBtn__icon" class="far fa-heart"></i>
                <div id="follow__count">Follow ( {{ count($product->likes) }} )</div>
              @endif
            </div>
          </a>

          <div class="product_share m-0 btn btn-dark">
            <a href="#" class="share__toggle"><i class="fas fa-share-alt"></i> Share</a>
            <ul class="social_icons">
              <li><a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($product->url) }}&title={{ rawurldecode($product->description) }}" target="_blank" title="{{ __('Share on Facebook') }}"><i class="ion-social-facebook"></i></a>
              </li>
              <li><a href="https://twitter.com/intent/tweet?url={{ urlencode($product->url) }}&text={{ rawurldecode($product->description) }}" target="_blank" title="{{ __('Share on Twitter') }}"><i class="ion-social-twitter"></i></a>
              </li>
              <li><a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($product->url) }}&summary={{ rawurldecode($product->description) }}&source=Linkedin" title="{{ __('Share on Linkedin') }}" target="_blank"><i class="ion-social-linkedin"></i></a>
              </li>
              <li><a href="https://api.whatsapp.com/send?text={{ urlencode($product->url) }}" title="{{ __('Share via Whatsapp') }}" target="_blank" data-action="share/whatsapp/share"><i class="fab fa-whatsapp"></i></a>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-5">
        @if ($product->owner and !$product->owner->hidden_profile)

        @if (!auth('customer')->check())

        <div class="mb-3">
          {{-- <a class="btn btn-block btn-primary btn-rounded"
                          href="{{ url('login?redirect_to=' . urlencode(request()->url())) }}">{{ __('Send Video Request') }}
          ({{ format_price($product->front_sale_price) }})</a> --}}
          <button type="button" class="btn btn-block btn-primary btn-rounded" data-toggle="modal" data-target="#loginModal">
            {{ __('Send Video Request') }} ({{ format_price($product->front_sale_price) }})
          </button>
        </div>
        {{-- <div class="mb-3">
                        <a class="btn btn-block btn-info btn-rounded"
                          href="{{ url('login?redirect_to=' . urlencode(request()->url())) }}">{{ __('Book Live') }}</a>
        <button type="button" class="btn btn-block btn-info btn-rounded" data-toggle="modal" data-target="#loginModal">
          {{ __('Book Live') }}
        </button>
      </div>--}}
      @else

      <form class="add-to-cart-form mb-3" method="POST" action="{{ route('public.cart.add-to-cart') }}">
        @csrf
        {!! apply_filters(ECOMMERCE_PRODUCT_DETAIL_EXTRA_HTML, null) !!}
        <input type="hidden" name="id" id="hidden-product-id" value="{{ $originalProduct->is_variation || !$originalProduct->defaultVariation->product_id ? $originalProduct->id : $originalProduct->defaultVariation->product_id }}" />
        <input type="hidden" name="qty" value="1" class="qty">

        <br>
        <div class="cart_btn">
          @if (EcommerceHelper::isQuickBuyButtonEnabled())
          <button class="btn btn-block btn-primary btn-addtocart" type="submit" name="checkout">{{ __('Send Video Request') }}
            ({{ format_price($product->front_sale_price) }})</button>
          @endif
          {{-- <a class="add_compare" href="#"><i class="icon-shuffle"></i></a> --}}

        </div>
      </form>
      {{-- <div class="mb-3">
                                      <a class="btn btn-block btn-success btn-rounded" href="#">{{ __('Send Message') }} ({{ format_price('2') }})</a>
    </div> --}}
    @if ($product->owner and $product->owner->allow_live and $product->owner->live_product_id)
    <form class="add-to-cart-form mb-3" method="POST" action="{{ route('public.cart.add-to-cart') }}">
      @csrf
      <input type="hidden" name="id" id="hidden-product-id" value="{{ $product->owner->live_product_id ?? 0}}" />
      <input type="hidden" name="qty" value="1" class="qty">
      <div class="cart_btn">
        <button class="btn btn-block btn-info btn-addtocart" type="submit" name="checkout">{{ __('Book Live') }}
          ({{ format_price($product->owner->liveProduct->front_sale_price ?? 0) }})</button>
      </div>
    </form>
    @endif
    @endif

    {{--@if ($product->owner and $product->owner->allow_business)
                    <div class="response_time mb-3">
                        <span>Business requests available from <b>{{ format_price($product->owner->business_price ?? 0) }}</b></span>
  </div>
  @endif--}}


  @else
  <form class="notify-when-back-form" action="{{ route('api.notify-when-back') }}" method="POST">
    <div class="not-available--message before-message">
      <b>{{ $product->owner->first_name ?? $product->name }}</b> decided to take some time off and does not accept any new requests
      at this time. No worries we can notify you once <b>{{ $product->owner->first_name ?? $product->name }}</b> is back,
      just drop in your email below
    </div>
    <div class="not-available--message success-message" style="display: none">
      Thanks, We will notify you once <b>{{ $product->owner->first_name ?? $product->name }}</b> is back.
    </div>
    <div class="notify-update--form">
      @if(auth('customer')->user())
      <input type="hidden" name="user_id" value="{{auth('customer')->user()->getAuthIdentifier()}}">
      <input type="hidden" name="talent_id" value="{{$product->owner->id ?? $product->talent_id ?? 0}}">
      @else
      <div class="form-group mb-3 nice--input">
        <label for="email">{{ __('My Email') }}</label>
        <input type="email" name="email" id="email" class="form-control address-control-item checkout-input" value="">
      </div>
      @endif
      <div class="cart_btn">
        <button class="btn btn-block btn-info btn-notify js-notify-when-back-button" type="submit" name="notify">{{ __('Notify me') }}</button>
      </div>
    </div>
  </form>
  @endif

  @if ($product->owner and $product->owner->allow_speed_service)
  <div class="response_time--new">
    <span class="text-white">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
        <path d="M392.708 179.2H275.802L384.175 8.533H247.642l-128 256h100.693L119.642 503.467 392.708 179.2z" fill="#ffdd09" />
        <path d="M281.775 8.533h-34.133l-128 256h34.133l128-256z" fill="#fff" />
        <path d="M350.042 179.2l-204.8 243.2-34.133 81.067L384.175 179.2h-34.133z" fill="#fd9808" />
        <path d="M119.642 512c-1.707 0-3.413 0-4.267-.853-3.413-2.56-5.12-6.827-3.413-10.24l95.573-227.84h-87.893c-2.56 0-5.973-1.707-7.68-4.267s-1.707-5.973 0-8.533l128-256c1.707-2.56 4.267-4.267 7.68-4.267h136.533c3.413 0 5.973 1.707 7.68 4.267s1.707 5.973 0 8.533l-99.84 157.867h100.693c3.413 0 5.973 1.707 7.68 5.12 1.707 3.413.853 6.827-.853 9.387L126.468 509.44c-1.706 1.707-4.266 2.56-6.826 2.56zm13.653-256h87.04c2.56 0 5.12 1.707 6.827 3.413 1.707 2.56 1.707 5.12.853 7.68l-78.507 187.733 225.28-267.093h-98.987c-3.413 0-5.973-1.707-7.68-4.267s-1.707-5.973 0-8.533L368.815 17.067H252.762L133.295 256z" />
      </svg>
      24Hr Service available
    </span>
  </div>
  @endif
</div>

</div>

<div class="row">

  <div class="col-12 col-md-5">




  </div>

</div>


<div class="row">
  <div class="col-12">
    <div class="large_divider clearfix"></div>
  </div>
</div>
<div class="row">
  <div class="col-12">

    @if (EcommerceHelper::isReviewEnabled())
    <div class="pp-box all--reviews hide">
      <div class="pp-box--content">
        <div id="all-reviews">
          <div class="comments">
            <h5 class="product_tab_title">
              {{ __(':count Reviews For :product', ['count' => $countRating, 'product' => $product->name]) }}
            </h5>
            <product-reviews-component url="{{ route('public.ajax.product-reviews', $product->id) }}">
            </product-reviews-component>
          </div>
        </div>

      </div>
    </div>
    @endif

    <div class="area_after_product">
      {!! str_replace('[TALENT]', $product->owner->first_name ?? $product->name, dynamic_sidebar('product_sidebar')) !!}
    </div>

    <!-- Product Carousel -->
    {{-- @php
          $relatedProducts = get_related_products($product);
        @endphp

        @if (!empty($relatedProducts))
          <div id="app">
            <div class="container product-carousel">
              <h3 class="page-title">{{ __('You may also be interested') }}</h3>
    <div id="new-tranding" class="product-item-4 owl-carousel owl-theme nf-carousel-theme1">
      @foreach ($relatedProducts as $related)
      @include('plugins/ecommerce::themes.includes.default-product', ['product' => $related])
      @endforeach

    </div>
  </div>
</div>
@endif --}}

<div class="section py-5 py-md-3">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="heading_tab_header">
          <div class="heading_s2 text-center">
            <h5>You might like</h5>
          </div>
          <div class="view_all">
            <a href="{{ route('public.products') }}" class="text_default"><span>{{ __('View more') }}</span></a>
          </div>
        </div>
      </div>
    </div>
    <div class="row" id="vue-related-products">
      <trending-products-component url="{{ route('public.ajax.trending-products') }}">
      </trending-products-component>
    </div>
  </div>
</div>


<div class="section py-5 py-md-3">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="heading_tab_header">
          <div class="heading_s2 text-center">
            <h5>Related Categories</h5>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <ul class="p-3 product-meta">
        <li>
          @foreach ($product->categories()->get() as $category)
          <a href="{{ $category->url }}">{{ $category->name }}</a>
          @endforeach
        </li>

      </ul>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12 col-md-9">

    <div class="reviews-box " id="list-reviews">
      <product-reviews-slider-component url="{{ route('public.ajax.product-reviews', $product->id) }}">
      </product-reviews-slider-component>
    </div>

  </div>
</div>

</div>
</div>
</div>
</div>
<!-- VideoGallery Modal POPUP -->
<div class="product-video-gallery-model">
  <div class="video-item">
    <div class="close-popup">
      <i class="far fa-times-circle"></i>
    </div>
    <video controls src="">
    </video>
    <div class="videoOverlayText">
      <p id="talentName"></p>
      <p id="videoOccasion"></p>
    </div>
  </div>
</div>
<!-- VideoGallery Modal POPUP -->
<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true" style="transform: translateY(15px)">
  <div class="modal-dialog" role="document" style="border: 1px solid white">
    <div class="modal-content">
      <div class="modal-header" style="background-color: #050510">
        <h5 class="modal-title" id="loginModal">{{ __('Sign In') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" style="background-color: #050510">
        <div class="text-center">
          <div class="heading_s1">
            <p class="text-muted">Please sign in using available methods</p>
          </div>

          <div class="text-center">
            {!! apply_filters(BASE_FILTER_AFTER_LOGIN_OR_REGISTER_FORM, null, \Canopy\Ecommerce\Models\Customer::class) !!}
          </div>

          <form method="POST" action="{{ route('customer.login.post') }}">
            @csrf
            <div class="form-group nice--input">
              <label for="txt-email">{{ __('Your Email') }}</label>
              <input class="form-control" name="email" id="txt-email" type="email" value="{{ old('email') }}" {{-- placeholder="{{ __('Your Email') }}" --}}>
              @if ($errors->has('email'))
              <span class="text-danger">{{ $errors->first('email') }}</span>
              @endif
            </div>
            <div class="form-group nice--input">
              <label for="txt-email">{{ __('Password') }}</label>
              <input class="form-control" type="password" name="password" id="txt-password" {{-- placeholder="{{ __('Password') }}" --}}>
              @if ($errors->has('password'))
              <span class="text-danger">{{ $errors->first('password') }}</span>
              @endif
            </div>
            {{-- <div class="login_footer form-group">
                                    <div class="chek-form">
                                        <div class="custome-checkbox">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember-me" value="1">
                                            <label class="form-check-label" for="remember-me"><span>{{ __('Remember me') }}</span></label>
        </div>
      </div>

    </div> --}}
    <div class="form-group">
      <button type="submit" class="btn mb-3 btn-block btn-primary">{{ __('Log in') }}</button>
      <a class="mt-3 text-muted" href="{{ route('customer.password.reset') }}">{{ __('Forgot password?') }}</a>
    </div>
    </form>



    <div class="form-note text-center text-muted">{{ __("Don't Have an Account?") }} <a href="{{ route('customer.register') }}">{{ __('Sign up now') }}</a></div>
  </div>
</div>
</div>
</div>
</div>
<script>
  (function($) {

    $('.share__toggle').on('click', function(e) {
      e.preventDefault;
      $(this).toggleClass('active');
    });

    $(window).on('load',function(e) {
      e.preventDefault;
      $.ajax({
          url: "{{ route('public.wishlist.display', $product->id) }}",
          method: 'GET',
          dataType: 'json',
          success: res =>{ 
            if(res.follow === true){
              $('#follow__icon').removeClass('far').addClass('fas');
              $('#follow__div').text("Followed");
              $('#followBtn__icon').removeClass('far').addClass('fas');
              $('#follow__count').text(' Followed ( '+res.count+' ) ');
            }
            else{
              $('#follow__icon').removeClass('fas').addClass('far');
              $('#follow__div').text("Follow");
              $('#followBtn__icon').removeClass('fas').addClass('far');
              $('#follow__count').text(' Follow ( '+res.count+' ) ');
            }
          }
      });
    });
  })(jQuery);
  
  // Script added for fan icon change and runtime data load.
  function followToggle() {
    $.ajax({
      url: "{{ route('public.wishlist.follow', $product->id) }}",
      method: 'POST',
      dataType: 'json',
      success: res =>{ 
        if($('#follow__icon').hasClass('far')){
          $('#follow__icon').removeClass('far').addClass('fas');
          $('#follow__div').text('Followed');
          $('#followBtn__icon').removeClass('far').addClass('fas');
          $('#follow__count').text(' Followed ( '+res.count+' ) ');
        }
        else{
          $('#follow__icon').removeClass('fas').addClass('far');
          $('#follow__div').text('Follow');
          $('#followBtn__icon').removeClass('fas').addClass('far');
          $('#follow__count').text(' Follow ( '+res.count+' ) ');
        }
      }
    });
  }
  
  // Script added for video gallery popop
  $('.product-video-gallery-model .close-popup').click(function() {
    $('.product-video-gallery-model').removeClass('product-video-gallery-model-show');
    $('body').removeClass('overlay');
    $('.product-video-gallery-model video').get(0).pause()
  });
  // Onclick function to get data for video url and captoin
  function videoGalleryfn(url,talentName,videoOccasion) {
    if (url != '') {
      console.log(talentName , videoOccasion);
      $('.product-video-gallery-model video').attr('src', url);
      $('.product-video-gallery-model').addClass('product-video-gallery-model-show');
      talentName !== null ?  $('.videoOverlayText #talentName').text(talentName) : $('.videoOverlayText #talentName').text('');
      videoOccasion !== null ? $('.videoOverlayText #videoOccasion').text('Requested for '+videoOccasion) : $('.videoOverlayText #videoOccasion').text('');
      talentName !== null ? $('.videoOverlayText').css({'padding': '5px 10%'}) : $('.videoOverlayText').css({'padding': '0'})
      $('body').addClass('overlay');
    }
  }
</script>