<template>
  <div class="col-12">
    <div v-if="isLoading">
      <div class="half-circle-spinner">
        <div class="circle circle-1"></div>
        <div class="circle circle-2"></div>
      </div>
    </div>
    <div
      v-if="!isLoading"
      v-carousel
      class="sliding_banners carousel_slider owl-carousel owl-theme nav_style5"
      data-nav="true"
      data-dots="false"
      data-loop="true"
      data-autoplay="false"
      data-autoplay-timeout="4000"
      data-margin="20"
      data-responsive='{"0":{"items": "1"}, "380":{"items": "2"}, "640":{"items": "3"}, "991":{"items": "3"}}'
    >
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      isLoading: false,
    };
  },
};
</script>
