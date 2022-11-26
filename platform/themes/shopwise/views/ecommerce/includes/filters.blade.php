@php
$brands = get_all_brands(['status' => \Canopy\Base\Enums\BaseStatusEnum::PUBLISHED], ['slugable'], ['products']);
$categories = get_product_categories(['status' => \Canopy\Base\Enums\BaseStatusEnum::PUBLISHED], ['slugable'], ['products'], true);
$tags = app(\Canopy\Ecommerce\Repositories\Interfaces\ProductTagInterface::class)->allBy(['status' => \Canopy\Base\Enums\BaseStatusEnum::PUBLISHED], ['slugable']);

Theme::asset()->usePath()->add('jquery-ui-css', 'css/jquery-ui.css');
Theme::asset()->container('footer')->usePath()->add('jquery-ui-js', 'js/jquery-ui.js', ['jquery']);
@endphp

<div class="widget">
    <div class="d-flex justify-content-between">
        <h5 class="widget_title">{{ __('Filter by Categories') }}</h5>
        @if (URL::current()!=route('public.products') || request()->input('min_price') || request()->input('max_price') || request()->query('allow_speed_service') == 1 || request()->query('rating5') == 1 || request()->query('rating4') == 1 || request()->query('rating3') == 1 || request()->query('rating2') == 1 || request()->query('rating1') == 1)
        <a href="{{ route('public.products') }}">Reset</a>
        @endif
    </div>
    <div class="custom_select">
        <select class="form-control form-control-sm" name="sort-byy" id="sort-byy" onchange="location = this.value;">
            <option value="{{ route('public.products') }}" @if (URL::current()==route('public.products')) selected @endif>{{ __('All Talent') }}</option>
            @foreach($categories as $category)
            <option value="{{ $category->url }}" @if (URL::current()==$category->url) selected @endif>{{ $category->name }} <span class="categories_num">({{ $category->products_count }})</span></option>
            @endforeach
        </select>
    </div>
    {{-- <ul class="widget_categories">
        <li @if (URL::current()==route('public.products')) class="active" @endif><a href="{{ route('public.products') }}">{{ __('All Talent') }}</a></li>
        @foreach($categories as $category)
        <li @if (URL::current()==$category->url) class="active" @endif><a href="{{ $category->url }}">{{ $category->name }} <span class="categories_num">({{ $category->products_count }})</span></a></li>
        @endforeach
    </ul> --}}
</div>

@if (count($brands) > 0)
<aside class="widget">
    <h5 class="widget_title">{{ __('Brands') }}</h5>
    <ul class="list_brand">
        @foreach($brands as $brand)
        <li>
            <div class="custome-checkbox">
                <input class="form-check-input submit-form-on-change" type="checkbox" name="brands[]" id="brand-{{ $brand->id }}" value="{{ $brand->id }}" @if (in_array($brand->id, request()->input('brands', []))) checked @endif>
                <label class="form-check-label" for="brand-{{ $brand->id }}"><span>{{ $brand->name }} ({{ $brand->products_count }})</span></label>
            </div>
        </li>
        @endforeach
    </ul>
</aside>

<aside class="widget widget--tags">
    <h5 class="widget_title">{{ __('Product Tags') }}</h5>
    <ul class="list_brand">
        @foreach($tags as $tag)
        <li>
            <div class="custome-checkbox">
                <input class="form-check-input submit-form-on-change" type="checkbox" name="tags[]" id="tag-{{ $tag->id }}" value="{{ $tag->id }}" @if (in_array($tag->id, request()->input('tags', []))) checked @endif>
                <label class="form-check-label" for="tag-{{ $tag->id }}"><span>{{ $tag->name }}</span></label>
            </div>
        </li>
        @endforeach
    </ul>
</aside>
@endif
<aside class="widget">
    <h5 class="widget_title">{{ __('By Price') }}</h5>
    <div class="filter_price">
        <div id="price_filter" data-min="0" data-max="{{ theme_option('max_filter_price', 1000) }}" data-min-value="{{ request()->input('min_price', 0) }}" data-max-value="{{ request()->input('max_price', theme_option('max_filter_price', 1000)) }}" data-price-sign="{{ get_application_currency()->symbol }}"></div>
        <div data-current-exchange-rate="{{ get_current_exchange_rate() }}"></div>
        <div data-is-prefix-symbol="{{ get_application_currency()->is_prefix_symbol }}"></div>
        <div class="price_range">
            <span>{{ __('Price') }}: <span id="flt_price"></span></span>
            <input class="product-filter-item product-filter-item-price-0" id="price_first" name="min_price" value="{{ request()->input('min_price', 0) }}" type="hidden">
            <input class="product-filter-item product-filter-item-price-1" id="price_second" name="max_price" value="{{ request()->input('max_price', theme_option('max_filter_price', 1000)) }}" type="hidden">
        </div>
    </div>
</aside>
<aside class="widget">
    <div class="text-center mb-4 toggle--header  mt-5" data-target="">
        <div class="chek-form">
            <div class="custome-checkbox">
                <input class="form-check-input submit-form-on-change" type="checkbox" @if(request()->query('allow_speed_service') == 1) checked @endif name="allow_speed_service" value="1" id="allow_speed_service">
                <label class="form-check-label text-white" for="allow_speed_service"><span>24hr Delivery</span></label>
            </div>
        </div>
    </div>
</aside>
<aside class="widget">
    <div class="mb-4 toggle--header mt-5" data-target="">
        <div class="chek-form custome-checkbox-rating">
            <div class="custome-checkbox mb-3">
                <input class="form-check-input submit-form-on-change" type="checkbox" @if(request()->query('rating5') == 1) checked @endif name="rating5" value="1" id="rating5">
                <label class="form-check-label" for="rating5">
                    ⭐⭐⭐⭐⭐
                </label>
            </div>
            <div class="custome-checkbox mb-3">
                <input class="form-check-input submit-form-on-change" type="checkbox" @if(request()->query('rating4') == 1) checked @endif name="rating4" value="1" id="rating4">
                <label class="form-check-label" for="rating4">
                    ⭐⭐⭐⭐
                </label>
            </div>
            <div class="custome-checkbox mb-3">
                <input class="form-check-input submit-form-on-change" type="checkbox" @if(request()->query('rating3') == 1) checked @endif name="rating3" value="1" id="rating3">
                <label class="form-check-label" for="rating3">
                    ⭐⭐⭐
                </label>
            </div>
            <div class="custome-checkbox mb-3">
                <input class="form-check-input submit-form-on-change" type="checkbox" @if(request()->query('rating2') == 1) checked @endif name="rating2" value="1" id="rating2">
                <label class="form-check-label" for="rating2">
                    ⭐⭐
                </label>
            </div>
            <div class="custome-checkbox mb-3">
                <input class="form-check-input submit-form-on-change" type="checkbox" @if(request()->query('rating1') == 1) checked @endif name="rating1" value="1" id="rating1">
                <label class="form-check-label" for="rating1">
                    ⭐
                </label>
            </div>
        </div>
    </div>
</aside>

<aside class="widget" style="border: none">
    {!! render_product_swatches_filter([
    'view' => Theme::getThemeNamespace() . '::views.ecommerce.attributes.attributes-filter-renderer'
    ]) !!}
</aside>
