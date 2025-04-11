<template>
    <div class="">
        <swiper  class="mb-4" 
        :slides-per-view=carouselOption.slidesPerView
        >
            <swiper-slide v-for="(banner, i) in banners" :key="i" class="">
                <!-- <banner  :banner="banner.banner_image"/> -->
                <img :src=banner.banner_image alt="">
            </swiper-slide>
        </swiper>
    </div>
</template>

<script>
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";
export default {
  components: { 
    Swiper,
    SwiperSlide,
   },
    props: {
        isLoading: { type: Boolean, required: true, default: true },
        banners: { type: Array, required: true, default: [] }
    },
    data: () => ({
        carouselOption: {
            slidesPerView: 1,
        },
    }),
}
</script>