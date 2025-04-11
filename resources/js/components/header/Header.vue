<template>
    <header
        style="z-index: 10; font-family: 'OpenSans-SemiBold'"
        :class="[
            'header-sticky ',
            { 'sticky-top': generalSettings.sticky_header == 1 },
        ]"
    >
        <TopBar :loading="loading" :data="data" :style="topBar" />

        <LogoBar :loading="loading" :data="data" :style="LogoBar" />

        <HeaderMenu :loading="loading" :data="data" class="d-none d-md-block" />
    </header>
</template>

<script>
import { mapGetters } from "vuex";
import HeaderMenu from "./HeaderMenu.vue";
import LogoBar from "./LogoBar.vue";
import TopBar from "./TopBar.vue";
export default {
    mounted() {
        window.addEventListener("scroll", this.handleScroll);
    },
    beforeDestroy() {
        window.removeEventListener("scroll", this.handleScroll);
    },
    data: () => ({
        loading: true,
        data: {},
        topScroll: 0,
        middleTopScroll: 300,
        hideTopBar: false,
        hideLogoBar: false,
        logoScroll: 400,
        middleLogoScroll: 800,
    }),
    components: {
        TopBar,
        LogoBar,
        HeaderMenu,
    },
    computed: {
        ...mapGetters("app", ["generalSettings"]),
        topBar() {
            return {
                position: this.hideTopBar ? "fixed" : "relative",
                transform: this.hideTopBar
                    ? "translateY(-100%)"
                    : "translateY(0%)",
                transition: "0.1s ease-in-out",
            };
        },
        LogoBar() {
            return {
                position: this.hideLogoBar ? "fixed" : "relative",
                transform: this.hideLogoBar
                    ? "translateY(-100%)"
                    : "translateY(0%)",
                transition: "0.1s ease-in-out",
            };
        },
    },
    methods: {
        async getDetails() {
            const res = await this.call_api("get", `setting/header`);
            if (res.status === 200) {
                this.data = res.data;
                this.loading = false;
            }
        },
        handleScroll() {
            const currentScrollY = window.scrollY;
            this.hideTopBar =
                currentScrollY >= this.topScroll &&
                this.middleTopScroll <= currentScrollY
                    ? true
                    : false;
            this.hideLogoBar =
                currentScrollY >= this.logoScroll &&
                this.middleLogoScroll <= currentScrollY
                    ? true
                    : false;
        },
    },
    created() {
        this.getDetails();
    },
};
</script>
