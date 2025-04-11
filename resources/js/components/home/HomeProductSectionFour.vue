<template>
    <div class="mb-5">
        <section class="py-0">
            <h2 class="mb-4">{{ title }}</h2>
            <div v-if="loading">
                <swiper ref="c1" class="" :options="carouselOption">
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
                    ref="c2"
                    :options="carouselOption"
                    :slides-per-view="carouselOption.slidesPerView"
                    :space-between="carouselOption.spaceBetween"
                    :breakpoints="carouselOption.breakpoints"
                    :grid="{ fill: 'row', rows: 2 }"
                    :modules="modules"
                >
                    <swiper-slide
                        v-for="(product, i) in products"
                        :key="i"
                        class=""
                    >
                        <product-box
                            :product-details="product"
                            :is-loading="loading"
                            box-style="four"
                        />
                    </swiper-slide>
                </swiper>
            </div>
        </section>
    </div>
</template>

<script>
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";
// import required modules
import { Grid, Pagination } from "swiper/modules";

export default {
    components: {
        Swiper,
        SwiperSlide,
    },
    setup() {
        return {
            modules: [Grid, Pagination],
        };
    },
    data: () => ({
        loading: true,
        title: "",
        products: [],
        carouselOption: {
            slidesPerColumn: 2,
            slidesPerColumnFill: "row",
            slidesPerView: 4,
            spaceBetween: 20,
            breakpoints: {
                0: {
                    slidesPerView: 1,
                    spaceBetween: 12,
                    slidesPerColumn: 2,
                    slidesPerColumnFill: "row",
                },
                // when window width is >= 320px
                599: {
                    slidesPerView: 2,
                    spaceBetween: 12,
                    slidesPerColumn: 2,
                    slidesPerColumnFill: "row",
                },
                // when window width is >= 480px
                960: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                    slidesPerColumn: 2,
                    slidesPerColumnFill: "row",
                },
                // when window width is >= 640px
                1264: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                    slidesPerColumn: 2,
                    slidesPerColumnFill: "row",
                },
                1904: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                    slidesPerColumn: 2,
                    slidesPerColumnFill: "row",
                },
            },
        },
    }),
    async created() {
        const res = await this.call_api(
            "get",
            "setting/home/product_section_four"
        );
        if (res.data.success) {
            this.title = res.data.data.title;
            this.products = res.data.data.products.data;
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
