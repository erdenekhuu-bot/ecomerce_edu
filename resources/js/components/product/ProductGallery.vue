<template>
    <div>
        <template v-if="isLoading">
            <v-skeleton-loader
                type="image"
                class="mb-4"
                height="423"
            ></v-skeleton-loader>
            <v-row class="gutters-10">
                <v-col>
                    <v-skeleton-loader
                        type="image"
                        class="mb-2"
                        height="90"
                    ></v-skeleton-loader>
                </v-col>
                <v-col>
                    <v-skeleton-loader
                        type="image"
                        class="mb-2"
                        height="90"
                    ></v-skeleton-loader>
                </v-col>
                <v-col>
                    <v-skeleton-loader
                        type="image"
                        class="mb-2"
                        height="90"
                    ></v-skeleton-loader>
                </v-col>
                <v-col>
                    <v-skeleton-loader
                        type="image"
                        class="mb-2"
                        height="90"
                    ></v-skeleton-loader>
                </v-col>
            </v-row>
        </template>
        <div class="" v-show="!isLoading">
            <swiper
                :style="{
                    '--swiper-navigation-color': '#fff',
                    '--swiper-pagination-color': '#fff',
                }"
                :thumbs="{ swiper: thumbsSwiper }"
                :spaceBetween="10"
                :navigation="true"
                :modules="modules"
                class="mySwiper2 border-thin"
            >
                <swiper-slide v-for="(photo, i) in galleryImgaes" :key="i">
                    <ProductImageZoom
                        :imageSrc="
                            selectedVariation.image
                                ? selectedVariation.image
                                : photo
                        "
                    />
                </swiper-slide>
            </swiper>

            <swiper
                @swiper="setThumbsSwiper"
                :spaceBetween="10"
                :slidesPerView="4"
                :freeMode="true"
                :watchSlidesProgress="true"
                :modules="modules"
                class="mySwiper"
            >
                <swiper-slide v-for="(photo, i) in galleryImgaes" :key="i">
                    <img :src="photo" class="border-thin" />
                </swiper-slide>
            </swiper>
        </div>
    </div>
</template>

<script>
import { ref } from "vue";
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";

// zoom
import ProductImageZoom from "../../components/ProductImageZoom.vue";

// import required modules
import { FreeMode, Navigation, Thumbs } from "swiper/modules";
export default {
    props: {
        isLoading: { type: Boolean, default: true },
        galleryImgaes: { type: Array, required: true, default: () => [] },
        selectedVariation: { type: Object, default: () => {} },
    },
    components: {
        Swiper,
        SwiperSlide,
        ProductImageZoom,
    },
    setup() {
        const thumbsSwiper = ref(null);

        const setThumbsSwiper = (swiper) => {
            thumbsSwiper.value = swiper;
        };

        return {
            thumbsSwiper,
            setThumbsSwiper,
            modules: [FreeMode, Navigation, Thumbs],
        };
    },
};
</script>

<style scoped>
.swiper {
    width: 100%;
    height: 100%;
}

.swiper-slide {
    text-align: center;
    font-size: 18px;
    background: #fff;

    /* Center slide text vertically */
    display: flex;
    justify-content: center;
    align-items: center;
}

.swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.swiper {
    width: 100%;
    height: 423px;
    margin-left: auto;
    margin-right: auto;
}

.swiper-slide {
    background-size: cover;
    background-position: center;
}

.mySwiper2 {
    height: 80%;
    width: 100%;
}

.mySwiper {
    height: 20%;
    box-sizing: border-box;
    padding: 10px 0;
}

.mySwiper .swiper-slide {
    width: 25%;
    height: 100%;
    opacity: 0.4;
}

.mySwiper .swiper-slide-thumb-active {
    opacity: 1;
}

.swiper-slide img {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.py-2 {
    padding-top: 0.5rem;
    padding-bottom: 0.5rem;
}
</style>
