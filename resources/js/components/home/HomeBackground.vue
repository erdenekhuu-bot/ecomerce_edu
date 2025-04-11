<template>
    <div class="mb-5">
        <div v-if="sliders.length == 0">
            <v-skeleton-loader type="image" height="310" />
        </div>

        <div v-else>
            <swiper
                :slides-per-view="1"
                :loop="true"
                @swiper="onSwiper"
                @slideChange="onSlideChange"
                class="custom-display"
            >
                <swiper-slide v-for="(image, i) in sliders" :key="i">
                    <a :href="image.link">
                        <img
                            :loading="loading"
                            :src="image.img"
                            class="custom-show"
                        />
                    </a>
                </swiper-slide>
            </swiper>
        </div>
    </div>
</template>

<script>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
export default {
    components: {
        Swiper,
        SwiperSlide,
    },
    setup() {
        const onSwiper = (swiper) => {
            return;
        };
        const onSlideChange = () => {
            return;
        };
        return {
            onSwiper,
            onSlideChange,
        };
    },

    data: () => ({
        loading: true,
        sliders: [],
    }),
    async created() {
        const res = await this.call_api("get", "setting/home/background");
        if (res.data.success) {
            this.sliders = res.data.data;
            this.loading = false;
        }
    },
    methods: {
        getUrl(arg) {
            return "public/" + arg;
        },
    },
};
</script>

<style scoped>
.custom-display {
    width: 100%;
    height: 500px;
    overflow: hidden;
}
.custom-show {
    object-fit: cover;
    width: 100%;
    height: 100%;
    flex-shrink: 1;
}
</style>
