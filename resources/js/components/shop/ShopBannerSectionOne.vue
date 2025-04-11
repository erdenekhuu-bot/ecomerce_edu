<template>
    <div class="">
        <swiper  class="mb-4"
        :slides-per-view=carouselOption.slidesPerView
        :space-between=carouselOption.spaceBetween
        >
            <swiper-slide v-for="(banner, i) in banners" :key="i" class="">
                <banner :loading="isLoading" :banner="banner"/>
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
            slidesPerView: 3,
            spaceBetween: 20,
            breakpoints: {
                0: {
                    slidesPerView: 1,
                    spaceBetween: 0
                },
                // when window width is >= 320px
                599: {
                    slidesPerView: 1,
                    spaceBetween: 0
                },
                // when window width is >= 480px
                960: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                // when window width is >= 640px
                1264: {
                    slidesPerView: 3,
                    spaceBetween: 20
                },
                1904: {
                    slidesPerView: 3,
                    spaceBetween: 20
                },
            }
        },
    }),
}
</script>