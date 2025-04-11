<template>
    <div>
        <v-list-item class="d-block pa-4 border-bottom side-cart-top">
            <div class="w-100 d-flex">
                <div class="d-flex" style="align-items: center">
                    <i class="la la-shopping-cart la-3x me-2 text-primary" />
                    <div class="lh-1-4">
                        <div class="fs-16 fw-500">
                            {{ getCartCount }} {{ $t("items") }}
                        </div>
                    </div>
                </div>
                <button
                    class="ms-auto"
                    type="button"
                    @click.stop="updateCartDrawer(false)"
                >
                    <i class="la la-close fs-20" />
                </button>
            </div>
        </v-list-item>

        <div
            v-if="getCartProducts.length > 0"
            class="px-5 py-2 c-scrollbar side-cart-content"
        >
            <min-order-progress
                class="mt-3"
                :cart-price="getCartPrice"
                :min-order="getShopMinOrder()"
                v-if="getShopMinOrder() > 0"
            />

            <v-list dense class="">
                <cart-items :cart-items="getCartProducts" />
            </v-list>
        </div>

        <div v-else class="px-5 py-2 side-cart-content">
            <div
                class="d-flex flex-column justify-center h-100 text-center pa-5"
            >
                <img
                    class="img-fluid"
                    :src="static_asset(`/assets/img/no-cart-item.jpg`)"
                    alt="$t('your_shopping_bag_is_empty_start_shopping')"
                />
                <div class="fs-20">
                    {{ $t("your_shopping_bag_is_empty_start_shopping") }}
                </div>
            </div>
        </div>

        <v-list-item class="pa-4 border-top side-cart-bottom d-block">
            <coupon-form class="mb-3" />
            <v-btn
                style="color: #fff !important"
                elevation="0"
                color="primary"
                class=""
                large
                block
                @click="checkout"
            >
                {{ $t("checkout") }}
                {{ format_price(getCartPrice - getTotalCouponDiscount) }}
            </v-btn>
        </v-list-item>
    </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
import CartItems from "./CartItems.vue";
import CouponForm from "./CouponForm.vue";
import MinOrderProgress from "./MinOrderProgress.vue";
export default {
    components: { CartItems, CouponForm, MinOrderProgress },
    computed: {
        ...mapGetters("cart", [
            "getCartCount",
            "getCartPrice",
            "getShopMinOrder",
            "getCartProducts",
            "getTotalCouponDiscount",
        ]),
        ...mapGetters("auth", ["isAuthenticated", "cartDrawerOpen"]),
    },
    methods: {
        ...mapActions("cart", [
            "fetchCartProducts",
            "updateQuantity",
            "toggleCartItem",
            "removeFromCart",
        ]),
        ...mapMutations("auth", ["showLoginDialog", "updateCartDrawer"]),
        checkout() {
            if (this.getCartPrice > 0) {
                this.$router.push({ name: "Checkout" }).catch((e) => {
                    if (this.$route.name == "Checkout") {
                        this.updateCartDrawer(false);
                    }
                });
            } else {
                this.snack({
                    message: this.$i18n.t("please_select_a_cart_product"),
                    color: "red",
                });
                return;
            }
        },
    },
};
</script>
<style scoped>
.side-cart-content {
    height: calc(100vh - 205px);
    max-height: calc(100vh - 205px);
    overflow-y: auto;
}
</style>
