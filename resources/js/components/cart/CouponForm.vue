<template>
    <v-row class="mt-0" :dense="!forCheckout" align="center">
        <v-col cols="auto" class="fs-13 fw-600 opacity-50 flex-shrink-0" v-if="!forCheckout">
            {{ $t("have_a_code") }}
        </v-col>
        <template v-if="getCouponCode(shopId) != null">
            <v-col :cols="forCheckout ? 8 : null" class="fw-500 opacity-80">
                <v-text-field :placeholder="$t('coupon_code')" class="text-field form-control form-control-sm white grey lighten-4" type="text" :value="getCouponCode(shopId)" hide-details="auto" required variant="plain" disabled ></v-text-field>
            </v-col>
            <v-col :cols="forCheckout ? 4 : 'auto'" class="fw-700">
                <v-btn elevation="0" color="grey" class="white-text darken-3 text-capitalize px-5" small @click="removeCoupon" >{{ $t('change') }}</v-btn>
            </v-col>
        </template>
        <template v-else>
            <v-col :cols="forCheckout ? 8 : null" class="fw-500 opacity-80">
                <v-text-field :placeholder="$t('coupon_code')" class="text-field form-control form-control-sm white" type="text" v-model="couponCode" hide-details="auto" required variant="plain"></v-text-field>
            </v-col>
            <v-col :cols="forCheckout ? 4 : 'auto'" class="fw-700">
                <v-btn elevation="0" color="grey" class="white-text darken-3 text-capitalize px-5" small @click="applyCoupon" :loading="couponLoading" :disabled="couponLoading">{{ $t('apply') }}</v-btn>
            </v-col>
        </template>
    </v-row>
</template>

<script>
import { mapGetters, mapActions, mapMutations } from "vuex";
export default {
    props: {
        shopId: { type: Number, required: false, default: null },
        forCheckout: { type: Boolean, default: false },
    },
    data: () => ({
        couponCode: null,
        couponLoading: false,
    }),
    computed: {
        ...mapGetters("cart", [
            "getCouponCode",
            "getSelectedCartIds",
            "getSelectedCartIdsByShopId"
        ]),
        ...mapGetters("auth", ["isAuthenticated"]),
    },
    methods: {
        ...mapActions("cart", [
            "saveCoupon",
            "resetCoupon",
        ]),
        ...mapMutations("auth", ["showLoginDialog"]),
        async applyCoupon() {
            if (this.isAuthenticated) {
                if (!this.couponCode) return;

                this.couponLoading = true;
                let data = {
                    coupon_code: this.couponCode,
                    shop_id: this.shopId,
                    cart_item_ids: this.shopId ? this.getSelectedCartIdsByShopId(this.shopId) : this.getSelectedCartIds,
                };
                const res = await this.call_api( "post", "checkout/coupon/apply", data );

                if (res.data.success) {
                    this.snack({ message: res.data.message });
                    this.saveCoupon({
                        shopId: this.shopId,
                        couponCode: this.couponCode,
                        couponDetails: res.data.coupon_details,
                    });
                } else {
                    this.snack({ message: res.data.message, color: "red" });
                }
                this.couponLoading = false;
            } else {
                this.showLoginDialog(true);
            }
        },
        removeCoupon() {
            this.couponCode = null;
            this.resetCoupon(this.shopId);
        },
    }
}
</script>