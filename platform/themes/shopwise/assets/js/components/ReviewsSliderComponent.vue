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
      class="review_slider carousel_slider owl-carousel owl-theme nav_style2"
      data-nav="true"
      data-dots="false"
      data-loop="true"
      data-autoplay="false"
      data-responsive='{"0":{"items": "1"}, "380":{"items": "1"}, "640":{"items": "2"}, "991":{"items": "2"}}'
    >
      <div class="reviews_box p-4" v-for="item in data" :key="item.id">
        <div class="comment_content">
          <div class="description">
            <p class="text-white">{{ item.comment }}</p>
          </div>
          <p class="customer_meta">
            <small class="comment-date text-small"
              >Reviewed by {{ item.user_name }} on {{ item.created_at }}</small
            >
          </p>
          <div class="rating_wrap">
            <div class="rating">
              <div
                class="product_rate"
                :style="{ width: item.star * 20 + '%' }"
              ></div>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
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
