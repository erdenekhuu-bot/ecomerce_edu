<template>
    <div class="mb-5">
        <v-container class="pt-md-6 pb-0 px-0 px-md-3">
            <v-row class="gutters-7 md-gutters-10 lh-0" v-if="loading">
                <v-col cols="12" lg="6" class="">
                    <v-skeleton-loader
                        type="image"
                        height="310"
                        class="loader"
                    ></v-skeleton-loader>
                </v-col>
                <v-col cols="6" lg="3" class="">
                    <v-skeleton-loader
                        type="image"
                        height="310"
                        class="loader"
                    ></v-skeleton-loader>
                </v-col>
                <v-col cols="6" lg="3" class="">
                    <v-skeleton-loader
                        type="image"
                        height="145"
                        class="right-first loader-half"
                    ></v-skeleton-loader>
                    <v-skeleton-loader
                        type="image"
                        height="145"
                        class="loader-half"
                    ></v-skeleton-loader>
                </v-col>
            </v-row>
            <v-row class="gutters-7 md-gutters-10 lh-0" v-else>
                <v-col cols="12" lg="6" class="">
                    <swiper
                        :spaceBetween="30"
                        :centeredSlides="true"
                        :autoplay="carouselOption.autoplay"
                        :modules="modules"
                        class="mySwiper"
                    >
                        <swiper-slide
                            v-for="(slider, i) in sliders.one"
                            :key="i"
                            class=""
                        >
                            <div class="custom-banner">
                                <banner :loading="false" :banner="slider" />
                            </div>
                        </swiper-slide>
                    </swiper>
                </v-col>
                <v-col cols="6" lg="3" class="">
                    <swiper
                        :spaceBetween="30"
                        :centeredSlides="true"
                        :autoplay="carouselOption.autoplay"
                        :modules="modules"
                        class="mySwiper"
                    >
                        <swiper-slide
                            v-for="(slider, i) in sliders.two"
                            :key="i"
                            class=""
                        >
                            <div class="custom-banner">
                                <banner :loading="false" :banner="slider" />
                            </div>
                        </swiper-slide>
                    </swiper>
                </v-col>
                <v-col
                    cols="6"
                    lg="3"
                    class="d-flex justify-space-between flex-column"
                >
                    <swiper
                        :spaceBetween="30"
                        :centeredSlides="true"
                        :autoplay="carouselOption.autoplay"
                        :modules="modules"
                        class="right-first w-100 mySwiper"
                    >
                        <swiper-slide
                            v-for="(slider, i) in sliders.three"
                            :key="i"
                            class=""
                        >
                            <div class="custom-banner">
                                <banner :loading="false" :banner="slider" />
                            </div>
                        </swiper-slide>
                    </swiper>
                    <swiper
                        :spaceBetween="30"
                        :centeredSlides="true"
                        :autoplay="carouselOption.autoplay"
                        :modules="modules"
                        class="w-100"
                    >
                        <swiper-slide
                            v-for="(slider, i) in sliders.four"
                            :key="i"
                            class=""
                        >
                            <div class="custom-banner">
                                <banner :loading="false" :banner="slider" />
                            </div>
                        </swiper-slide>
                    </swiper>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
// Import Swiper Vue.js components
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
        sliders: null,
        carouselOption: {
            slidesPerView: 1,
            spaceBetween: 0,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
        },
    }),
    async created() {
        const res = await this.call_api("get", "setting/home/sliders");
        if (res.data.success) {
            this.sliders = res.data.data;
            this.loading = false;
        }
    },
};
</script>

<style scoped>
.loader {
    height: 200px !important;
}
.loader-half {
    height: 92px !important;
}
.row.gutters-7 > [class*="col-"] {
    padding-top: 7px;
    padding-bottom: 7px;
}
.col-lg-6 {
    padding-left: 0 !important;
    padding-right: 0 !important;
}
.col-lg-3:nth-of-type(2) {
    padding-left: 0px;
}
.col-lg-3:nth-of-type(3) {
    padding-right: 0px;
}
.right-first {
    margin-bottom: 14px;
}
.row {
    margin-left: 0;
    margin-right: 0;
}
.v-application-is-rtl .col-lg-3:nth-of-type(2) {
    padding-left: 7px;
    padding-right: 0;
}
.v-application-is-rtl .col-lg-3:nth-of-type(3) {
    padding-right: 7px;
    padding-left: 0;
}
@media (min-width: 600px) {
}
@media (min-width: 960px) {
    .loader {
        height: 310px !important;
    }
    .loader-half {
        height: 145px !important;
    }
    .right-first {
        margin-bottom: 20px;
    }
    .row {
        margin-left: -10px;
        margin-right: -10px;
    }
    .row.md-gutters-10 > [class*="col-"] {
        padding-top: 10px;
        padding-bottom: 10px;
    }
    .col-lg-6 {
        padding-left: 10px !important;
        padding-right: 10px !important;
    }
    .col-lg-3:nth-of-type(2) {
        padding-left: 10px;
    }
    .col-lg-3:nth-of-type(3) {
        padding-right: 10px;
    }
    .v-application-is-rtl .col-lg-3,
    .v-application-is-rtl .col-lg-3 {
        padding-left: 10px !important;
        padding-right: 10px !important;
    }
}

@media (min-width: 1264px) {
}
</style>
