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
      class="product_slider carousel_slider owl-carousel owl-theme dot_style1"
      data-loop="true"
      data-margin="10"
      data-autoplay="false"
      data-autoplay-hover-pause="true"
      data-autoplay-timeout="4000"
      data-responsive='{"0":{"items": "2"}, "481":{"items": "2"}, "768":{"items": "4"}, "991":{"items": "6"}, "1279":{"items": "8"}}'
    >
      <div
        class="item product-slider--item"
        v-for="item in data"
        :key="item.id"
        v-if="data.length"
        v-html="item"
      ></div>
    </div>
  </div>
</template>

<script>
export default {
  data: function () {
    return {
      isLoading: true,
      data: [],
    };
  },
  props: {
    url: {
      type: String,
      default: () => null,
      required: true,
    },
  },
  mounted() {
    this.getData();
  },
  methods: {
    getData() {
      this.data = [];
      this.isLoading = true;
      axios
        .get(this.url)
        .then((res) => {
          this.data = res.data.data;
          this.isLoading = false;
        })
        .catch((res) => {
          this.isLoading = false;
          console.log(res);
        });
    },
  },
};
</script>
