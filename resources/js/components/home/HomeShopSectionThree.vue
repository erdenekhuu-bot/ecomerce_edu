<template>
  <div class="mb-5">
    <v-container class="py-0">
      <h2 class="mb-4">{{ title }}</h2>
      <div v-if="loading">
        <swiper
          ref="c1"
          class=""
          :options="carouselOption"
        >
          <swiper-slide
            v-for="(i) in 8"
            :key="i"
            class=""
          >
            <v-skeleton-loader
              type="image"
              height="186"
            ></v-skeleton-loader>
          </swiper-slide>
        </swiper>
      </div>
      <div v-else>
        <swiper
          ref="c2"
          :options="carouselOption"
          :slides-per-view=carouselOption.slidesPerView
          :space-between=carouselOption.spaceBetween
          :breakpoints= carouselOption.breakpoints
        >
          <swiper-slide
            v-for="(shop, i) in shops"
            :key="i"
            class=""
          >
            <shop-box
              :shop-details="shop"
              :is-loading="loading"
              box-style="four"
            />
          </swiper-slide>
        </swiper>
      </div>
    </v-container>
  </div>
</template>

<script>
import ShopBox from "../shop/ShopBox.vue";
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";
export default {
  components: { 
    ShopBox,
    Swiper,
    SwiperSlide,
   },
  data: () => ({
    loading: true,
    title: "",
    shops: [],
    carouselOption: {
      slidesPerView: 6,
      spaceBetween: 20,
      breakpoints: {
        0: {
          slidesPerView: 1,
          spaceBetween: 12,
        },
        // when window width is >= 320px
        599: {
          slidesPerView: 1,
          spaceBetween: 16,
        },
        // when window width is >= 480px
        960: {
          slidesPerView: 1,
          spaceBetween: 20,
        },
        // when window width is >= 640px
        1264: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        1904: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
      },
    },
  }),
  async created() {
    const res = await this.call_api("get", "setting/home/shop_section_three");
    if (res.data.success) {
      this.title = res.data.data.title;
      this.shops = res.data.data.shops.data;
      this.loading = false;
    }
  },
};
</script>
<style scoped>
h2 {
  font-size: 16px;
}
@media (min-width: 960px) {
  h2 {
    font-size: 24px;
  }
}
</style>