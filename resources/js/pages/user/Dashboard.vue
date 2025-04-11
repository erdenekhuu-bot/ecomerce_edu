<template>
    <div v-show="check" class="custom-window" @click="globalClose"></div>

    <!-- <banner
            :loading="false"
            :banner="$store.getters['app/banners'].dashboard_page_top"
        /> -->
    <v-container>
        <img
            :src="static_asset(`/assets/img/icons/topbar.jpg`)"
            class="img-fit h-50px w-100"
            alt=""
        />
        <div class="pt-2">
            <h1
                class="fs-21 fw-700 opacity-80 mb-5"
                style="font-family: OpenSans-Bold"
            >
                {{ $t("dashboard") }}
            </h1>
            <v-row v-if="generalSettings.wallet_system == 1">
                <v-col cols="12" sm="4">
                    <v-sheet
                        color="grey-darken-3"
                        rounded="rounded"
                        elevation="0"
                        height="130"
                        class="d-flex justify-center align-center white-text flex-column"
                    >
                        <div class="fs-14 mb-3 fw-700">
                            {{ $t("membership_discount") }}
                        </div>
                        <div class="fw-500 text-h4">
                            {{ membership_percent + "%" }}
                        </div>
                    </v-sheet>
                </v-col>
                <v-col cols="12" sm="4">
                    <v-sheet
                        color="grey-darken-3"
                        rounded="rounded"
                        elevation="0"
                        height="130"
                        class="d-flex justify-center align-center white-text flex-column"
                    >
                        <div class="fs-14 mb-3 fw-700">
                            {{ $t("wallet_balance") }}
                        </div>
                        <div class="fw-500 text-h4">
                            {{ format_price(currentUser.balance) }}
                        </div>
                        <div class="fw-700 fs-12 opacity-60">
                            {{ last_recharge.date }}
                        </div>
                    </v-sheet>
                </v-col>
                <v-col cols="12" sm="4">
                    <recharge-dialog
                        :show="rechargeDialogShow"
                        from="/user/dashboard"
                        @close="rechargeDialogClosed"
                    />
                    <v-btn
                        class="bg-grey-lighten-4 border-dashed border-gray-300 h-100 py-6"
                        elevation="0"
                        block
                        x-large
                        @click.stop="rechargeDialogShow = true"
                        border="dashed thin"
                    >
                        <span>
                            <div class="fs-14 mb-3 w-100">
                                {{ $t("recharge_wallet") }}
                            </div>
                            <i class="las la-plus la-3x opacity-70"></i>
                        </span>
                    </v-btn>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" md="4">
                    <v-sheet
                        class="bg-grey-lighten-5 border border-gray-200 mb-4 text-red d-flex justify-center flex-column pa-5"
                        rounded="rounded"
                        elevation="0"
                        height="130"
                    >
                        <div class="fw-500 text-h4 px-4">
                            {{ getCartCount }}
                        </div>
                        <div class="fw-700 fs-12 px-4">
                            {{ $t("products_in_your_cart") }}
                        </div>
                    </v-sheet>
                    <v-sheet
                        class="bg-grey-lighten-5 border border-gray-200 mb-4 text-blue d-flex justify-center flex-column pa-5"
                        rounded="rounded"
                        elevation="0"
                        height="130"
                    >
                        <div class="fw-500 text-h4 px-4">
                            {{ getTotalWishlisted }}
                        </div>
                        <div class="fw-700 fs-12 px-4">
                            {{ $t("products_in_your_wishlist") }}
                        </div>
                    </v-sheet>
                    <v-sheet
                        class="bg-grey-lighten-5 border border-gray-200 mb-4 text-green d-flex justify-center flex-column pa-5"
                        rounded="rounded"
                        elevation="0"
                        height="130"
                    >
                        <div class="fw-500 text-h4 px-4">
                            {{ total_order_products }}
                        </div>
                        <div class="fw-700 fs-12 px-4">
                            {{ $t("products_you_ordered") }}
                        </div>
                    </v-sheet>
                    <!-- <banner
                        :loading="false"
                        :banner="
                            $store.getters['app/banners'].dashboard_page_bottom
                        "
                    /> -->
                    <img
                        :src="static_asset(`/assets/img/icons/worksafe.jpg`)"
                        alt=""
                        class="img-fit"
                        height="426"
                    />
                </v-col>
                <v-col cols="12" md="4">
                    <v-sheet
                        class="border border-gray-200 mb-4 pa-2"
                        rounded="rounded"
                        height="426"
                        elevation="0"
                    >
                        <div class="fw-700 fs-16 border-bottom pb-2 mx-2 my-2">
                            {{ $t("recent_purchase_history") }}
                        </div>
                        <div class="h-350px overflow-auto c-scrollbar pa-2">
                            <div
                                v-for="(product, i) in purchasedProducts"
                                :key="i"
                                :class="['mt-3', { 'border-top pt-3': i != 0 }]"
                            >
                                <product-box
                                    :product-details="product"
                                    :is-loading="dashboardLoading"
                                    box-style="two"
                                    no-border
                                />
                            </div>
                        </div>
                    </v-sheet>
                    <v-sheet
                        class="border border-gray-200 mb-4 pa-2 my-wishlist"
                        rounded="rounded"
                        height="426"
                        elevation="0"
                    >
                        <div class="fw-700 fs-16 border-bottom pb-2 mx-2 my-2">
                            {{ $t("my_wishlist") }}
                        </div>
                        <div
                            v-if="
                                wislistLoaded && getWislistProducts.length > 0
                            "
                            class="h-340px overflow-auto c-scrollbar pa-2"
                        >
                            <div
                                v-for="(product, i) in getWislistProducts"
                                :key="i"
                                :class="['mt-3', { 'border-top pt-3': i != 0 }]"
                            >
                                <product-box
                                    :product-details="product"
                                    :is-loading="!wislistLoaded"
                                    box-style="two"
                                    no-border
                                />
                            </div>
                        </div>
                    </v-sheet>
                </v-col>
                <v-col cols="12" md="4">
                    <v-sheet
                        class="border border-gray-200 mb-4 pa-4"
                        rounded="rounded"
                        elevation="0"
                        height="275"
                    >
                        <div class="fw-700 fs-16 border-bottom pb-2">
                            {{ $t("default_shipping_address") }}
                        </div>
                        <div
                            class="mt-3 lh-1-8"
                            v-if="getDefaultBillingAddress"
                        >
                            <div>{{ getDefaultBillingAddress.address }}</div>
                            <div>
                                {{ getDefaultBillingAddress.postal_code }},
                                {{ getDefaultBillingAddress.city }},
                                {{ getDefaultBillingAddress.state }}
                            </div>
                            <div>{{ getDefaultBillingAddress.country }}</div>
                            <div>{{ getDefaultBillingAddress.phone }}</div>
                        </div>
                    </v-sheet>
                    <v-sheet
                        rounded="rounded"
                        elevation="0"
                        height="131"
                        class="mb-4"
                    >
                        <v-btn
                            class="bg-grey-lighten-4 border-dashed border-gray-300 h-100 py-6"
                            elevation="0"
                            block
                            x-large
                            @click.stop="addressDialogShow = true"
                            border="dashed thin"
                        >
                            <span>
                                <div class="fs-14 mb-3 w-100">
                                    {{ $t("add_new_address") }}
                                </div>
                                <i class="las la-plus la-3x opacity-70"></i>
                            </span>
                        </v-btn>
                    </v-sheet>
                    <address-dialog
                        :show="addressDialogShow"
                        @close="addressDialogClosed"
                    />

                    <v-sheet
                        class="border border-gray-200 mb-4 pa-2 recenty-viewed"
                        rounded="rounded"
                        height="405"
                        elevation="0"
                    >
                        <div class="fw-700 fs-16 border-bottom pb-2 mx-2 my-2">
                            {{ $t("recently_viewed") }}
                        </div>
                        <div class="h-340px overflow-auto c-scrollbar pa-2">
                            <div
                                v-for="(
                                    product, i
                                ) in getRecentlyViewedProducts"
                                :key="i"
                                :class="['mt-3', { 'border-top pt-3': i != 0 }]"
                            >
                                <product-box
                                    :product-details="product"
                                    :is-loading="!recentlyViewedLoaded"
                                    box-style="two"
                                    no-border
                                />
                            </div>
                        </div>
                    </v-sheet>
                </v-col>
            </v-row>
        </div>
    </v-container>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import AddressDialog from "../../components/address/AddressDialog.vue";
import RechargeDialog from "../../components/wallet/RechargeDialog.vue";
export default {
    data: () => ({
        rechargeDialogShow: false,
        addressDialogShow: false,
        dashboardLoading: true,
        total_order_products: 0,
        membership_percent: 0,
        purchasedProducts: [{}, {}, {}],
        recentlyViewedProducts: {
            loading: true,
            products: [],
        },
        last_recharge: {
            amount: 0,
            date: "",
        },
    }),
    components: {
        RechargeDialog,
        AddressDialog,
    },
    computed: {
        ...mapGetters("app", ["generalSettings"]),
        ...mapGetters("auth", ["currentUser"]),
        ...mapGetters("cart", ["getCartCount"]),
        ...mapGetters("wishlist", [
            "wislistLoaded",
            "getTotalWishlisted",
            "getWislistProducts",
        ]),
        ...mapGetters("address", ["getDefaultBillingAddress"]),
        ...mapGetters("recentlyViewed", [
            "recentlyViewedLoaded",
            "getRecentlyViewedProducts",
        ]),
        check() {
            return this.$store.state.auth.modalWindow;
        },
    },
    methods: {
        ...mapActions("auth", ["rechargeWallet"]),
        ...mapActions("address", ["fetchAddresses"]),
        ...mapActions("wishlist", ["fetchWislistProducts"]),
        ...mapActions("recentlyViewed", ["fetchRecentlyViewedProducts"]),
        async getDashboardData() {
            const res = await this.call_api("get", `user/dashboard`);
            if (res.data.success) {
                this.total_order_products = res.data.total_order_products;
                this.purchasedProducts =
                    res.data.recent_purchased_products.data;
                this.last_recharge = res.data.last_recharge;
            }
            this.dashboardLoading = false;
        },
        globalClose() {
            this.$store.commit("auth/modalWindow", false);
        },
        rechargeDialogClosed() {
            this.rechargeDialogShow = false;
        },
        addressDialogClosed() {
            this.addressDialogShow = false;
        },
        async getPercent() {
            const res = await this.call_api("get", "user/dashboard");
            this.membership_percent = res.data.sale_percent;
        },
    },
    created() {
        this.getPercent();
        this.getDashboardData();
        this.fetchAddresses();
        this.fetchWislistProducts();
        this.fetchRecentlyViewedProducts();
    },
    mounted() {
        this.rechargeWallet(this.$route.query.wallet_payment);
    },
};
</script>
<style scoped>
@media (min-width: 960px) and (max-width: 1263px) {
    .my-wishlist,
    .recenty-viewed {
        height: 365px !important;
    }
    .my-wishlist .c-scrollbar,
    .recenty-viewed .c-scrollbar {
        height: 297px;
    }
}
@media (min-width: 1264px) and (max-width: 1903px) {
    .my-wishlist,
    .recenty-viewed {
        height: 350px !important;
    }
    .my-wishlist .c-scrollbar,
    .recenty-viewed .c-scrollbar {
        height: 282px;
    }
}
</style>
