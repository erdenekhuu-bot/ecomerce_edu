<template>
    <div>
        <v-app class="d-flex flex-column">
            <v-locale-provider isRTL>
                <Header />

                <v-main class="aiz-main-wrap">
                    <router-view
                        :key="
                            [
                                'ShopDetails',
                                'ShopCoupons',
                                'ShopProducts',
                            ].includes($route.name)
                                ? null
                                : $route.path
                        "
                    ></router-view>
                </v-main>

                <Footer :class="['mt-auto', { 'd-none': routerLoading }]" />

                <v-locale-provider isRTL>
                    <BottomChat />
                </v-locale-provider>

                <SidebarCart />
                <AddToCartDialog />
                <LoginDialog v-if="!isAuthenticated" />
                <MobileMenu class="d-lg-none user-side-nav" />
                <SnackBar />
            </v-locale-provider>
        </v-app>
    </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
import LoginDialog from "./auth/LoginDialog.vue";
import SidebarCart from "./cart/SidebarCart.vue";
import Footer from "./footer/Footer.vue";
import Header from "./header/Header.vue";
import BottomChat from "./inc/BottomChat.vue";
import MobileMenu from "./inc/MobileMenu.vue";
import SnackBar from "./inc/SnackBar.vue";
import AddToCartDialog from "./product/AddToCartDialog.vue";
import { useHead } from "@unhead/vue";

export default {
    components: {
        Header,
        Footer,
        BottomChat,
        SidebarCart,
        SnackBar,
        LoginDialog,
        MobileMenu,
        AddToCartDialog,
    },
    data: () => ({
        isRTL: " ",
        metaTitle: "",
        metaDescription: "",
    }),

    computed: {
        ...mapGetters("auth", ["isAuthenticated"]),
        ...mapGetters("cart", ["getTempUserId"]),
        ...mapGetters("app", [
            "appMetaTitle",
            "appMetaDescription",
            "userLanguageObj",
            "routerLoading",
        ]),
    },
    watch: {
        metaTitle(newTitle) {
            this.updateHead(newTitle, this.metaDescription);
        },
        metaDescription(newDescription) {
            this.updateHead(this.metaTitle, newDescription);
        },
    },
    methods: {
        ...mapActions("auth", ["getUser", "checkSocialLoginStatus"]),
        ...mapActions("cart", ["fetchCartProducts"]),
        ...mapMutations("auth", ["setSociaLoginStatus"]),
        changeRTL() {
            if (this.userLanguageObj.rtl == 1) {
                this.isRTL = "rtl";
                this.$vuetify.rtl = true;
            } else {
                this.isRTL = " ";
                this.$vuetify.rtl = false;
            }
        },
        async getTempCartData() {
            if (this.isAuthenticated && this.getTempUserId) {
                const res = await this.call_api("post", "temp-id-cart-update", {
                    temp_user_id: this.getTempUserId,
                });
                this.fetchCartProducts();
            }
        },
        updateHead(title, description) {
            useHead({
                title: title,

                meta: [{ name: "description", content: description }],
            });
        },
    },
    async created() {
        this.changeRTL();
        await this.getUser();
        setTimeout(() => {
            this.checkSocialLoginStatus();
            this.getTempCartData();
        }, 200);

        this.metaTitle = this.appMetaTitle;
        this.metaDescription = this.appMetaDescription;
    },
};
</script>

<style scoped>
.absolute-full {
    background: #fff;
    z-index: 10000;
}
</style>
