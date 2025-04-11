<template>
  <v-container class="py-9">
    <h1 class="fs-21 mb-3">{{ $t('all_coupons') }}</h1>
    <v-row class="row-cols-1 row-cols-sm-2 row-cols-lg-3">
      <v-col
        v-for="(coupon, i) in coupons"
        :key="i"
      >
        <coupon-box
          :is-loading="loading"
          :coupon-details="coupon"
        />
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import CouponBox from "../../components/coupon/CouponBox.vue";
export default {
  components: { CouponBox },
  data: () => ({
    loading: true,
    coupons: [{}, {}, {}, {}, {}, {}],
  }),
  async created() {
    const res = await this.call_api(
      "get",
      `shop/${this.$route.params.slug}/coupons`
    );
    if (res.data.success) {
      this.coupons = res.data.data.coupons.data;
      this.loading = false;
    }
  },
};
</script>