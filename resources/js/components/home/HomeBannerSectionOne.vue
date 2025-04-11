<template>
    <v-container class="py-0 px-0 px-md-4">
        <swiper :options="carouselOption" :slides-per-view="1" class="mb-4">
            <swiper-slide v-for="(banner, i) in banners" :key="i">
                <div class="custom-banner">
                    <banner :loading="loading" :banner="banner" />
                </div>
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
        loading: true,
        banners: [],
        carouselOption: {
            slidesPerView: 1,
        },
    }),
    async created() {
        const res = await this.call_api(
            "get",
            "setting/home/banner_section_one"
        );
        if (res.data.success) {
            this.banners = res.data.data;
            this.loading = false;
        }
    },
};
</script>
