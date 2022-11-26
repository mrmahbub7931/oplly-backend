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
      class="cat_slider cat_style1 carousel_slider owl-carousel owl-theme"
      data-loop="true"
      data-dots="false"
      data-autoplay="false"
      data-autoplay-timeout="4000"
      data-nav="true"
      data-margin="10"
      data-responsive='{"0":{"items": "2"}, "480":{"items": "2"}, "576":{"items": "3"}, "768":{"items": "4"}, "991":{"items": "5"}, "1199":{"items": "6"}}'
    >
      <div class="item" v-for="(item, i) in data" :key="i">
        <div class="categories_box" :class="`col_${item.color}`">
          <a :href="item.url">
            <img :src="item.image" :alt="item.name" />
            <div class="category_details">
              <h4 class="h4">{{ item.name }}</h4>
              <span>#{{ item.name }}</span>
            </div>
          </a>
        </div>
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
