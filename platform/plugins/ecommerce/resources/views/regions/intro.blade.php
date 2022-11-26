@extends('core/base::layouts.master')
@section('content')
<div class="flexbox-grid">
  <div class="flexbox-content">
    <div class="body">
      <div class="box-wrap-emptyTmpl text-center col-12">
        <h1 class="mt20 mb20 ws-nm font-size-emptyDisplayTmpl">
          {{ trans('plugins/ecommerce::region.manage_regions') }}</h1>
        <p class="text-info-displayTmpl">
          {{ trans('plugins/ecommerce::region.incomplete_regions_intro_description') }}</p>
        <div class="empty-displayTmpl-btn">
          <a class="btn btn-primary btn-lg">{{ trans('plugins/ecommerce::region.create_new_region') }}</a>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
