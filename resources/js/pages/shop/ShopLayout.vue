<template>
  <div>
    <div class="position-relative">
      <v-skeleton-loader
        type="image"
        height="360"
        class="loader"
        v-if="loading"
      />
      <swiper
        :slides-per-view=carouselOption.slidesPerView
        class=""
        v-else
      >
        <swiper-slide
          v-for="(banner, i) in shopDetails.banners"
          :key="i"
          class="lh-0"
        >
          <img
            :src="banner"
            class="h-150px h-md-360px img-fit"
            :alt="shopDetails.name"
            @error="imageFallback($event)"
          >
        </swiper-slide>
      </swiper>
      <div
        class="shop-info w-100"
        v-if="!loading"
      >
        <v-container class="d-md-flex">
          <div
            class="pa-3 pa-md-5 position-relative mb-md-5 flex-fill d-flex "
            style="margin-right:1px"
          >
            <div class="bg-white opacity-80 absolute-full"></div>
            <div class="d-md-flex position-relative align-center minw-0">
              <img
                :src="shopDetails.logo"
                :alt="shopDetails.name"
                class="h-90px"
                @error="imageFallback($event)"
              >
              <div class="ms-md-4 minw-0">
                <h1 class="fs-21">{{ shopDetails.name }}</h1>
                <div class="fs-12 opacity-80 text-truncate">
                  <span
                    v-for="(category, i) in shopDetails.categories.data"
                    :key="i"
                  >
                    {{ category.name }}<span v-if="(shopDetails.categories.data.length - i) != 1">,</span>
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="pa-3 pa-md-5 position-relative mb-md-5">
            <div class="bg-white opacity-80 absolute-full"></div>
            <div class="position-relative">
              <div>{{ $t('ratings') }}</div>
              <div class="d-flex my-2">
                <span class="me-2">{{ shopDetails.rating.toFixed(2) }}</span>
                <v-rating
                  class="lh-1-5"
                  background-color=""
                  empty-icon="las la-star"
                  full-icon="las la-star active"
                  half-icon="las la-star half"
                  hover
                  half-increments
                  readonly
                  size="12"
                  length="5"
                  :model-value="shopDetails.rating"
                >
                </v-rating>
                
                <span class="me-2 opacity-80">({{ shopDetails.reviews_count }} {{ $t('ratings') }})</span>
              </div>
              <template v-if="isThisFollowed(shopDetails.id)">
                <v-btn
                  elevation="0"
                  color="grey"
                  @click="removeFromFollowedShop(shopDetails.id)"
                  class="white-text darken-1"
                >
                  {{ $t('unfollow') }}
                </v-btn>
              </template>
              <template v-else>
                <v-btn
                  elevation="0"
                  color="primary"
                  @click="addNewFollowedShop(shopDetails.id)"
                >
                  {{ $t('follow') }}
                </v-btn>
              </template>
            </div>
          </div>
        </v-container>
      </div>
    </div>
    <div class="bg-grey-darken-4 py-2">
      <v-container class="py-1">
        <v-list
          class="d-flex bg-grey-darken-4"
          dark
        >
          <v-list-item
            class="flex-grow-0 flex-fill px-1 me-12 border-bottom border-2 border-transparent"
            active-class="text-white border-primary"
            :to="{ name: 'ShopDetails', params: {slug: $route.params.slug}}"
            exact
          >
            {{  $t('store') }}
          </v-list-item>
          <v-list-item
            class="flex-grow-0 flex-fill px-1 me-12 border-bottom border-2 border-transparent"
            active-class="white-text border-primary"
            :to="{ name: 'ShopCoupons', params: {slug: $route.params.slug}}"
          >
            {{  $t('coupons') }}
          </v-list-item>
          <v-list-item
            class="flex-grow-0 flex-fill px-1 border-bottom border-2 border-transparent"
            active-class="white-text border-primary"
            :to="{ name: 'ShopProducts', params: {slug: $route.params.slug}}"
          >
            {{  $t('all_products') }}
          </v-list-item>
        </v-list>
      </v-container>
    </div>
    <div>
      <router-view :key="$route.path"></router-view>
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";
export default {
  components: {
    Swiper,
    SwiperSlide,
  },
  data: () => ({
    loading: true,
    shopDetails: {},
    carouselOption: {
      slidesPerView: 1,
    },
  }),
  computed: {
    ...mapGetters("follow", ["isThisFollowed"]),
  },
  methods: {
    ...mapActions("follow", ["addNewFollowedShop", "removeFromFollowedShop"]),
  },
  async created() {
    const res = await this.call_api("get", `shop/${this.$route.params.slug}`);
    if (res.data.success) {
      this.shopDetails = res.data.data;
      this.loading = false;
    } else {
      this.snack({
        message: res.data.message,
        color: "red",
      });
      this.$router.push({ name: "404" });
    }
  },
};
</script>
<style scoped>
@media (min-width: 960px) {
  .shop-info {
    position: absolute;
    left: 0;
    bottom: 0;
    z-index: 1;
  }
}
</style>