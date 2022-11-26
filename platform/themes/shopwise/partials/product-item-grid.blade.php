<div class="product">
  <div class="product_img">
    <a href="{{ $product->url }}">
      @if ($product->owner)
        @if ($product->owner->has_cause)
          <span class="charity--label"><i class="fas fa-ribbon"></i></span>
        @endif
        <img src="{{ RvMedia::getImageUrl($product->owner->photo, 'medium', false, RvMedia::getDefaultImage()) }}"
          alt="{{ $product->name }}" />
      @else
        <img src="{{ RvMedia::getImageUrl($product->image, 'medium', false, RvMedia::getDefaultImage()) }}"
          alt="{{ $product->name }}" />
      @endif
    </a>

  </div>
  <div class="product_info">
    @if ($product->owner and $product->owner->allow_speed_service)
      <span class="fast--service label">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
              <path d="M392.708 179.2H275.802L384.175 8.533H247.642l-128 256h100.693L119.642 503.467 392.708 179.2z" fill="#ffdd09"/>
              <path d="M281.775 8.533h-34.133l-128 256h34.133l128-256z" fill="#fff"/>
              <path d="M350.042 179.2l-204.8 243.2-34.133 81.067L384.175 179.2h-34.133z" fill="#fd9808"/>
              <path d="M119.642 512c-1.707 0-3.413 0-4.267-.853-3.413-2.56-5.12-6.827-3.413-10.24l95.573-227.84h-87.893c-2.56 0-5.973-1.707-7.68-4.267s-1.707-5.973 0-8.533l128-256c1.707-2.56 4.267-4.267 7.68-4.267h136.533c3.413 0 5.973 1.707 7.68 4.267s1.707 5.973 0 8.533l-99.84 157.867h100.693c3.413 0 5.973 1.707 7.68 5.12 1.707 3.413.853 6.827-.853 9.387L126.468 509.44c-1.706 1.707-4.266 2.56-6.826 2.56zm13.653-256h87.04c2.56 0 5.12 1.707 6.827 3.413 1.707 2.56 1.707 5.12.853 7.68l-78.507 187.733 225.28-267.093h-98.987c-3.413 0-5.973-1.707-7.68-4.267s-1.707-5.973 0-8.533L368.815 17.067H252.762L133.295 256z"/>
          </svg>
          {{ get_ecommerce_setting('fast_service_label', 'Fast Service') }}
      </span>
    @else
      <span class="blank_label">&nbsp;</span>
    @endif

    <div class="product__info--left">
      <h6 class="product_title"><a href="{{ $product->url }}">{{ $product->name }}</a></h6>
      <p class="product_subtitle">{{ $product->title }}</p>
    </div>
    <div class="product_price">
      @if ($product->front_sale_price !== $product->price)
        <del>{{ format_price($product->price) }}</del>
      @endif

      <span class="price">{{ format_price($product->front_sale_price) }}</span>

    </div>
  </div>
</div>
