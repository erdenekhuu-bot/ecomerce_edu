<template>
    <v-list color="primary">
        <v-list-item
            v-for="(route, i) in routes"
            :key="i"
            :to="{ name: route.to }"
            class="mb-0 border-none rounded-lg"
        >
            <v-icon class="me-4 d-flex align-center">
                <i :class="route.icon" class="fs-17"></i>
                <!-- <img
                    :src="static_asset(`${route.image}`)"
                    alt=""
                    class="fs-17"
                /> -->
            </v-icon>
            <!-- <v-icon class="d-flex align-center">
                <img :src="static_asset(`${route.image}`)" alt="" />
            </v-icon> -->

            <!-- <v-list-item>
                <v-list-item-title class="">
                    {{ route.label }}
                </v-list-item-title>
            </v-list-item> -->
            <v-row style="font-size: 12px">
                <v-col cols="1"></v-col>
                <v-col cols="">
                    {{ route.label }}
                </v-col>
            </v-row>
        </v-list-item>
    </v-list>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
    data: () => ({
        activeIndex: null,
    }),
    computed: {
        ...mapGetters("app", ["generalSettings", "getUnseenProductQuerries"]),
        ...mapGetters("affiliate", ["isAffiliatedUser"]),
        ...mapGetters("auth", ["isAuthenticated"]),
        routes() {
            return this.getMenuItems().sort((a, b) => a.order - b.order);
        },
    },
    methods: {
        ...mapActions("affiliate", ["fetchAffiliatedUser"]),
        hoverImage(index, isHovered) {
            this.hoveredIndex = isHovered ? index : null;
        },
        setActiveImage(index) {
            this.activeIndex = index;
        },
        getImage(route, index) {
            if (this.activeIndex === index) {
                return static_asset(route.activeimage);
            }
            return static_asset(route.image);
        },
        getMenuItems() {
            let items = [
                {
                    label: this.$i18n.t("dashboard"),
                    order: 10,
                    icon: "las la-th-large",
                    //  image: "/assets/img/icons/dashboard.svg",
                    // activeimage: "/assets/img/icons/dashboard-white.svg",
                    to: "DashBoard",
                },
                {
                    label: this.$i18n.t("purchase_history"),
                    order: 20,
                    icon: "las la-file-invoice-dollar",
                    //  image: "/assets/img/icons/history.svg",
                    //   activeimage: "/assets/img/icons/history-white.svg",
                    to: "PurchaseHistory",
                },
                {
                    label: "Үнийн санал",
                    order: 20,
                    icon: "las la-file-invoice-dollar",
                    //    image: "/assets/img/icons/history.svg",
                    //   activeimage: "/assets/img/icons/history-white.svg",
                    to: "OfferPrice",
                },
                // {
                //     label: this.$i18n.t("downloads"),
                //     order: 30,
                //     icon: "las la-cloud-download-alt",
                //     image: "/assets/img/icons/download.svg",
                //     to: "Downloads",
                // },
                {
                    label: this.$i18n.t("wishlist"),
                    order: 40,
                    icon: "la la-heart-o",
                    //   image: "/assets/img/icons/bookmark.svg",
                    //    activeimage: "/assets/img/icons/bookmark-white.svg",
                    to: "Wishlist",
                },
                {
                    label: this.$i18n.t("coupons"),
                    order: 50,
                    icon: "las la-tags",
                    //    image: "/assets/img/icons/coupon.svg",
                    to: "Coupon",
                },

                // {
                //     label: this.$i18n.t("AffiliatePaymentHistory"),
                //     order: 60,
                //     icon: "las la-link",
                //     to: "AffiliatePaymentHistory",
                // },
                // {
                //     label: this.$i18n.t("AffiliatePaymentWithdraw"),
                //     order: 60,
                //     icon: "las la-link",
                //     to: "AffiliatePaymentWithdraw",
                // },
                {
                    label: this.$i18n.t("manage_profile"),
                    order: 70,
                    icon: "las la-user",
                    //    image: "/assets/img/icons/profile.svg",
                    to: "Profile",
                },
            ];

            let wallet = {
                label: this.$i18n.t("my_wallet"),
                order: 40,
                icon: "las la-wallet",
                //   image: "/assets/img/icons/wallet.svg",
                activeimage: "/assets/img/icons/wallet-white.svg",
                to: "Wallet",
            };
            if (this.generalSettings.wallet_system == 1) {
                items.push(wallet);
            }

            let refund_request = {
                label: this.$i18n.t("refund_requests"),
                order: 21,
                icon: "las la-undo-alt",
                // image: "/assets/img/icons/refresh.svg",
                to: "RefundRequests",
            };
            if (this.is_addon_activated("refund")) {
                items.push(refund_request);
            }

            // let shop = {
            //     label: this.$i18n.t("followed_shops"),
            //     order: 35,
            //     icon: "las la-store-alt",
            //     image: "/assets/img/icons/follower.svg",
            //     to: "FollowedShops",
            // };

            // if (this.is_addon_activated("multi_vendor")) {
            //     items.push(shop);
            // }

            // conversation
            let conversation = {
                label:
                    this.$i18n.t("product_querries") +
                    " (" +
                    this.getUnseenProductQuerries +
                    ") ",
                order: 36,
                //     image: "/assets/img/icons/comment.svg",
                icon: "las la-comment",
                to: "Conversations",
            };
            if (
                this.generalSettings.conversation_system == 1 &&
                this.is_addon_activated("multi_vendor")
            ) {
                items.push(conversation);
            }

            // club points
            let clubPoint = {
                label: this.$i18n.t("earning_points"),
                order: 38,
                //   image: "/assets/img/icons/loyalty.png",
                icon: "las la-coins",
                to: "Earning",
            };
            if (this.generalSettings.club_point == 1) {
                items.push(clubPoint);
            }
            //affiliate
            let affiliate = {
                label: this.$i18n.t("Affiliate"),
                order: 60,
                icon: "las la-link",
                to: "Affiliate",
            };
            if (
                this.isAffiliatedUser &&
                this.generalSettings.affiliate_system == 1
            ) {
                items.push(affiliate);
            }
            return items;
        },
    },
    created() {
        if (this.isAuthenticated) {
            this.fetchAffiliatedUser();
        }
        this.getMenuItems();
    },
};
</script>
