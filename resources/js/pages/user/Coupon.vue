<template>
    <div v-show="check" class="custom-window" @click="globalClose"></div>
    <div class="ps-lg-7 pt-4" style="font-family: OpenSans-Bold">
        <v-row
            v-if="coupons.length"
            class="row-cols-1 row-cols-sm-2 row-cols-md-3"
        >
            <v-col v-for="(coupon, i) in coupons" :key="i">
                <coupon-box :is-loading="loading" :coupon-details="coupon" />
            </v-col>
        </v-row>
    </div>
</template>

<script>
import CouponBox from "../../components/coupon/CouponBox.vue";
export default {
    data: () => ({
        loading: true,
        coupons: [{}, {}, {}, {}, {}, {}],
    }),
    components: { CouponBox },
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
    async created() {
        const res = await this.call_api("get", "user/coupons");
        if (res.data.success) {
            this.coupons = res.data.data.data;
            this.loading = false;
        }
    },
};
</script>
