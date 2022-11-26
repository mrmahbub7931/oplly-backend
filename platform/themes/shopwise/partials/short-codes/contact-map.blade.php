<div class="section pb_0">
  <div class="row">
    <div class="col-12">
      <div class="heading_s1">
        <h2>{{ __('Maps') }}</h2>
      </div>
      <div style="height: 400px; width: 100%; position: relative; text-align: right;">
        <div
          style="height: 400px; width: 100%; overflow: hidden; background: none!important;">
          <iframe width="100%" height="500"
            src="https://maps.google.com/maps?q={{ addslashes(theme_option('address')) }}%20&t=&z=13&ie=UTF8&iwloc=&output=embed"
            frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
        </div>
      </div>
    </div>
  </div>
</div>