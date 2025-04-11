<template>
    <div class="mb-5" :style="{ fontFamily: 'OpenSans-SemiBold' }">
        <v-container class="py-0 pe-0 pe-md-3 ps-3 rounded-lg">
            <div
                :style="{ fontFamily: 'OpenSans-Bold' }"
                class="d-flex justify-space-between align-center mb-4 pe-3 pe-md-0"
            >
                <h2 class="">{{ $t("popular_categories") }}</h2>
                <router-link
                    :to="{ name: 'AllCategories' }"
                    class="py-2 lh-1 d-inline-block"
                    :style="{ color: 'black' }"
                >
                    {{ $t("view_all") }}
                    <i class="las la-angle-right"></i>
                </router-link>
            </div>
            <div v-if="loading">
                <swiper class="" :options="carouselOption">
                    <swiper-slide v-for="i in 8" :key="i" class="">
                        <v-skeleton-loader
                            type="image"
                            height="186"
                        ></v-skeleton-loader>
                    </swiper-slide>
                </swiper>
            </div>
            <div v-else>
                <swiper
                    :slides-per-view="carouselOption.slidesPerView"
                    :space-between="carouselOption.spaceBetween"
                    :autoplay="carouselOption.autoplay"
                    :modules="modules"
                    :breakpoints="carouselOption.breakpoints"
                >
                    <swiper-slide
                        v-for="(category, i) in categories"
                        :key="i"
                        class=""
                    >
                        <router-link
                            class="rounded pa-2 pa-md-4 border text-center d-block text-reset"
                            :to="{
                                name: 'Category',
                                params: { categorySlug: category.slug },
                            }"
                        >
                            <img
                                :src="category.banner"
                                :alt="category.name"
                                @error="imageFallback($event)"
                                class="img-fluid"
                            />
                            <div
                                class="fs-13 opacity-80 text-truncate d-none d-md-block mt-3"
                            >
                                {{ category.name }}
                            </div>
                        </router-link>
                    </swiper-slide>
                </swiper>
            </div>
        </v-container>
    </div>
</template>

<script>
import { Autoplay, Navigation, Pagination } from "swiper/modules";
import { Swiper, SwiperSlide } from "swiper/vue";

export default {
    components: {
        Swiper,
        SwiperSlide,
    },
    setup() {
        return {
            modules: [Autoplay, Pagination, Navigation],
        };
    },

    data: () => ({
        loading: true,
        categories: [],
        carouselOption: {
            slidesPerView: 8,
            spaceBetween: 20,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            breakpoints: {
                0: {
                    slidesPerView: 4.5,
                    spaceBetween: 12,
                },
                // when window width is >= 320px
                599: {
                    slidesPerView: 5,
                    spaceBetween: 16,
                },
                // when window width is >= 480px
                960: {
                    slidesPerView: 6,
                    spaceBetween: 20,
                },
                // when window width is >= 640px
                1264: {
                    slidesPerView: 7,
                    spaceBetween: 20,
                },
                1904: {
                    slidesPerView: 8,
                    spaceBetween: 20,
                },
            },
        },
    }),
    async created() {
        const res = await this.call_api(
            "get",
            "setting/home/popular_categories"
        );
        if (res.data.success) {
            this.categories = res.data.data.data;
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
