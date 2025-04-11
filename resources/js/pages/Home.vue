<template>
    <!-- Show backround image full area -->
    <HomeBackground />

    <div v-show="check" class="custom-window" @click="globalClose"></div>

    <div>
        <!-- slider area -->
        <HomeSliders />

        <!-- popular categories -->
        <HomePopularCategories />

        <!-- product section 1 -->
        <HomeProductSectionOne />

        <!-- home banner 1 -->
        <HomeBannerSectionOne />

        <!-- shop section 1 -->
        <HomeShopSectionOne v-if="is_addon_activated('multi_vendor')" />

        <!-- home shop banner 1 -->
        <HomeShopBannerSectionOne v-if="is_addon_activated('multi_vendor')" />

        <!-- product section 2 -->
        <HomeProductSectionTwo />

        <!-- home banner 2 -->
        <HomeBannerSectiontwo />

        <!-- shop section 2 -->
        <HomeShopSectionTwo v-if="is_addon_activated('multi_vendor')" />

        <!-- home shop banner 2 -->
        <HomeShopBannerSectionTwo v-if="is_addon_activated('multi_vendor')" />

        <!-- shop section 3 -->
        <HomeShopSectionThree v-if="is_addon_activated('multi_vendor')" />

        <!-- product section 3 -->
        <HomeProductSectionThree />

        <!-- home banner 3 -->
        <HomeBannerSectionThree />

        <!-- shop section 4 -->
        <HomeShopSectionFour v-if="is_addon_activated('multi_vendor')" />

        <!-- product section 4 -->
        <HomeProductSectionFour />

        <!-- shop section 5 -->
        <HomeShopSectionFive v-if="is_addon_activated('multi_vendor')" />

        <!-- home shop banner 3 -->
        <HomeShopBannerSectionThree v-if="is_addon_activated('multi_vendor')" />

        <!-- product section 5 -->
        <HomeProductSectionFive />

        <!-- home banner 4 -->
        <HomeBannerSectionFour />

        <!-- product section 6 -->
        <HomeProductSectionSix />

        <div class="py-8 bg-grey-lighten-4 mt-8">
            <v-container>
                <HomeAboutText />
                <HomeAllCategories />
            </v-container>
        </div>
    </div>
</template>

<script>
import HomePopularCategories from "../components/home/HomePopularCategories.vue";
import HomeBackground from "../components/home/HomeBackground.vue";
import HomeSliders from "../components/home/HomeSliders.vue";

import HomeProductSectionFive from "../components/home/HomeProductSectionFive.vue";
import HomeProductSectionFour from "../components/home/HomeProductSectionFour.vue";
import HomeProductSectionOne from "../components/home/HomeProductSectionOne.vue";
import HomeProductSectionSix from "../components/home/HomeProductSectionSix.vue";
import HomeProductSectionThree from "../components/home/HomeProductSectionThree.vue";
import HomeProductSectionTwo from "../components/home/HomeProductSectionTwo.vue";

import HomeBannerSectionFour from "../components/home/HomeBannerSectionFour.vue";
import HomeBannerSectionOne from "../components/home/HomeBannerSectionOne.vue";
import HomeBannerSectionThree from "../components/home/HomeBannerSectionThree.vue";
import HomeBannerSectiontwo from "../components/home/HomeBannerSectionTwo.vue";

import HomeAboutText from "../components/home/HomeAboutText.vue";
import HomeAllCategories from "../components/home/HomeAllCategories.vue";

import HomeShopBannerSectionOne from "../components/home/HomeShopBannerSectionOne.vue";
import HomeShopBannerSectionThree from "../components/home/HomeShopBannerSectionThree.vue";
import HomeShopBannerSectionTwo from "../components/home/HomeShopBannerSectionTwo.vue";
import HomeShopSectionFive from "../components/home/HomeShopSectionFive.vue";
import HomeShopSectionFour from "../components/home/HomeShopSectionFour.vue";
import HomeShopSectionOne from "../components/home/HomeShopSectionOne.vue";
import HomeShopSectionThree from "../components/home/HomeShopSectionThree.vue";
import HomeShopSectionTwo from "../components/home/HomeShopSectionTwo.vue";

export default {
    computed: {
        check() {
            return this.$store.state.auth.modalWindow;
        },
    },
    methods: {
        globalClose() {
            this.$store.commit("auth/modalWindow", false);
        },
    },
    components: {
        HomeBackground,
        HomeSliders,
        HomePopularCategories,
        HomeProductSectionOne,
        HomeProductSectionTwo,
        HomeProductSectionThree,
        HomeProductSectionFour,
        HomeProductSectionFive,
        HomeProductSectionSix,
        HomeBannerSectionOne,
        HomeBannerSectiontwo,
        HomeBannerSectionThree,
        HomeBannerSectionFour,
        HomeAboutText,
        HomeAllCategories,
        HomeShopSectionOne,
        HomeShopBannerSectionOne,
        HomeShopSectionTwo,
        HomeShopBannerSectionTwo,
        HomeShopSectionThree,
        HomeShopSectionFour,
        HomeShopSectionFive,
        HomeShopBannerSectionThree,
    },
};
</script>
