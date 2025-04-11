<template>
    <div class="">
        <h2 class="mb-4">{{ $t('best_selling') }}</h2>
        <div v-if="isLoading">
            <swiper ref="c1" class="" :options="carouselOption" >
                <swiper-slide v-for="(i) in 8" :key="i"  class="">
                    <v-skeleton-loader type="image" height="186" ></v-skeleton-loader>
                </swiper-slide>
            </swiper>
        </div>
        <div v-else>
            <swiper ref="c2" 
            :slides-per-view=carouselOption.slidesPerView
                :grid="{fill: 'row' , rows: 2}"
                :space-between=carouselOption.spaceBetween
                :breakpoints= carouselOption.breakpoints
                :modules="modules"
            >
                <swiper-slide v-for="(product, i) in products" :key="i" class="">
                    <product-box :product-details="product" :is-loading="isLoading" box-style="three" />
                </swiper-slide>
            </swiper>
        </div>
    </div>
</template>

<script>
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";
  // import required modules
  import { Grid, Pagination } from 'swiper/modules';

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
    props: {
        isLoading: { type: Boolean, required: true, default: true },
        products: { type: Array, required: true, default: [] }
    },
    data: () => ({
        carouselOption: {
            slidesPerColumn: 2,
            slidesPerColumnFill: 'row',
            slidesPerView: 3,
            spaceBetween: 20,
            breakpoints: {
                0: {
                    slidesPerView: 1,
                    spaceBetween: 12,
                    slidesPerColumn: 2,
                    slidesPerColumnFill: 'row',
                },
                // when window width is >= 320px
                599: {
                    slidesPerView: 2,
                    spaceBetween: 16,
                    slidesPerColumn: 2,
                    slidesPerColumnFill: 'row',
                },
                // when window width is >= 480px
                960: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                    slidesPerColumn: 2,
                    slidesPerColumnFill: 'row',
                },
                // when window width is >= 640px
                1264: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                    slidesPerColumn: 2,
                    slidesPerColumnFill: 'row',
                },
                1904: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                    slidesPerColumn: 2,
                    slidesPerColumnFill: 'row',
                },
            }
        },
    }),
}
</script>
<style scoped>
    h2{
        font-size: 16px;
    }
    @media (min-width: 960px) {
        h2{
            font-size: 24px;
        }
    }
</style>