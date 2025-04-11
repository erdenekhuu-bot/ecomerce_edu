<template>
    <v-container class="py-0 px-0 px-md-4">
        <swiper :options="carouselOption" :slidesPerview="1" class="mb-4">
            <swiper-slide v-for="(banner, i) in banners" :key="i" class="">
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
            "setting/home/shop_banner_section_two"
        );
        if (res.data.success) {
            this.banners = res.data.data;
            this.loading = false;
        }
    },
};
</script>
