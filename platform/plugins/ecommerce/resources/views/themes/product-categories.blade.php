<!-- Page Content Wrapper -->
<div class="page-content-wraper mt-5 pt-5">
  <!-- Bread Crumb -->
  <section class="content-page">

    <div class="container">
      <h1 class="mt-4 mb-3">All Categories</h1>
      <h3>Featured</h3>
      <div class="row">
        <div class="container">
          <div class="d-flex flex-wrap product-category-list-item my-3">
            @if ($featuredCategories->count() > 0)
              @foreach ($featuredCategories as $category)
                {{-- dd($category) --}}
                <div class="item m-1">
                  <div class="categories_box col_{{ $category->color }}">
                    <a href="{{ $category->url }}" class="p-0">
                      <img
                        src="{{ RvMedia::getImageUrl($category->image, 'thumb', false, RvMedia::getDefaultImage()) }}"
                        alt="{{ $category->name }}" class="product-category-item-thumb">
                      <div class="category_details">
                        <h4>{{ $category->name }}</h4>
                        <span>#{{ $category->name }}</span>
                      </div>
                    </a>
                  </div>
                </div>
              @endforeach
            @endif
          </div>


          <div class="product-category-list-item p-3 my-2">
            @if ($categories->count() > 0)
              {{-- dd($categories) --}}
              @foreach ($categories as $category)
                {{-- dd($category) --}}
                @if ($category->parent_id == 0 && $category->products->count() > 0)
                  <div class="item">
                    <div class="categories_item col_{{ $category->color }} mt-3">
                      <a href="{{ $category->url }}" class="p-0">
                        <div class="category_details">
                          <h5 class="color-primary font-weight-bold">{{ $category->name }}
                            @if ($category->products->count() > 0)
                              <small>{{ $category->products->count() }}</small>
                            @endif
                          </h5>
                        </div>
                      </a>
                      <ul class="row list-style-none list-inline">
                        @foreach ($categories as $childCategory)
                          @if ($childCategory->parent_id == $category->id && $childCategory->products->count() > 0)
                            <li class="col-12 col-md-4 col-lg-3">
                              <a href="{{ $childCategory->url }}" class="p-3">{{ $childCategory->name }}
                                <small>{{ $childCategory->products->count() }}</small>
                              </a>
                            </li>
                          @endif
                        @endforeach
                      </ul>
                    </div>
                  </div>
                @endif
              @endforeach
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
