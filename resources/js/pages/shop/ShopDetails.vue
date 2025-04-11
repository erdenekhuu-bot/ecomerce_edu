<template>
  <v-container class="py-9">
    <shop-featured-product
      class="mb-5"
      :products="shopDetails.featured_products.data"
      :is-loading="loading"
    />

    <shop-banner-section-one
      class="mb-5"
      :banners="shopDetails.banner_section_one"
      :is-loading="loading"
    />

    <shop-new-products
      class="mb-5"
      :products="shopDetails.new_arrival_products.data"
      :is-loading="loading"
    />

    <shop-coupon-section
      class="mb-5"
      :coupons="shopDetails.latest_coupons.data"
      :is-loading="loading"
    />

    <shop-banner-section-two
      class="mb-5"
      :banners="shopDetails.banner_section_two"
      :is-loading="loading"
    />

    <shop-best-rated-products
      class="mb-5"
      :products="shopDetails.best_rated_products.data"
      :is-loading="loading"
    />

    <shop-banner-section-three
      class="mb-5"
      :banners="shopDetails.banner_section_three"
      :is-loading="loading"
    />

    <shop-best-selling-products
      class="mb-5"
      :products="shopDetails.best_selling_products.data"
      :is-loading="loading"
    />

    <shop-banner-section-two
      class="mb-5"
      :banners="shopDetails.banner_section_four"
      :is-loading="loading"
    />

    <router-link
      class="border rounded border-primary bg-soft-primary d-flex justify-center align-center text-reset pa-9"
      :to="{ name: 'ShopProducts', params: {slug: $route.params.slug}}"
    >
      <span class="fw-700 fs-21">{{ $t('view_all_products_of_this_shop') }}</span>
      <i class="la la-arrow-right la-2x ms-2"></i>
    </router-link>
  </v-container>
</template>

<script>
import ShopBannerSectionOne from "../../components/shop/ShopBannerSectionOne.vue";
import ShopBannerSectionThree from "../../components/shop/ShopBannerSectionThree.vue";
import ShopBannerSectionTwo from "../../components/shop/ShopBannerSectionTwo.vue";
import ShopBestRatedProducts from "../../components/shop/ShopBestRatedProducts.vue";
import ShopBestSellingProducts from "../../components/shop/ShopBestSellingProducts.vue";
import ShopCouponSection from "../../components/shop/ShopCouponSection.vue";
import ShopFeaturedProduct from "../../components/shop/ShopFeaturedProduct.vue";
import ShopNewProducts from "../../components/shop/ShopNewProducts.vue";
import { useHead } from "@unhead/vue";

export default {
  components: {
    ShopFeaturedProduct,
    ShopBannerSectionOne,
    ShopBannerSectionTwo,
    ShopBannerSectionThree,
    ShopNewProducts,
    ShopBestRatedProducts,
    ShopBestSellingProducts,
    ShopCouponSection,
  },
  data: () => ({
    metaTitle: "",
    loading: true,
    shopDetails: {
      featured_products: {
        data: [],
      },
      new_arrival_products: {
        data: [],
      },
      best_rated_products: {
        data: [],
      },
      best_selling_products: {
        data: [],
      },
      latest_coupons: {
        data: [{}, {}, {}],
      },
      banner_section_one: [{}, {}, {}],
      banner_section_two: [{}],
      banner_section_three: [{}, {}],
      banner_section_four: [{}],
    },
    carouselOption: {
      slidesPerView: 1,
    },
  }),
  watch:{
    metaTitle(newTitle){
      this.updateHead(newTitle);
    }
  },
  methods:{
    updateHead(title) {
      useHead({
        title: title,
      });
    }
  },
  async created() {
    const res = await this.call_api(
      "get",
      `shop/${this.$route.params.slug}/home`
    );
    if (res.data.success) {
      this.shopDetails = res.data.data;
      this.loading = false;
      this.metaTitle = this.$route.params.slug;
    }
  },
};
</script>