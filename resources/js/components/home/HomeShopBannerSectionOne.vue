<template>
    <v-container class="py-0 px-0 px-md-4">
        <swiper :options="carouselOption"  class="mb-4"
                :slides-per-view=carouselOption.slidesPerView
                :space-between=carouselOption.spaceBetween
                :breakpoints= carouselOption.breakpoints
                >
            <swiper-slide v-for="(banner, i) in banners" :key="i" class="">
                <banner :loading="loading" :banner="banner"/>
            </swiper-slide>
        </swiper>
    </v-container>
</template>

<script>
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";

export default {
    components: {
    Swiper,
    SwiperSlide,
  },
    data: () => ({
        loading: true ,
        banners: [],
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
    async created(){
        const res = await this.call_api("get", "setting/home/shop_banner_section_one");
        if (res.data.success) {
            this.banners = res.data.data
            this.loading = false
        }
    }
}
</script>