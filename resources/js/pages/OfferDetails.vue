<template>
  <div class="pb-6">
    <template v-if="loading">
      <v-skeleton-loader
        type="image"
        height="500"
      />
      <v-container>
        <v-row class="gutters-10 row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6">
          <v-col
            v-for="(j, i) in 12"
            :key="i"
          >
            <v-skeleton-loader
              type="image"
              class=""
              height="300"
            />
          </v-col>
        </v-row>
      </v-container>
    </template>
    <template v-else-if="!is_empty_obj(offer)">
      <img
        :src="offer.banner"
        :alt="offer.title"
        class="w-100 mw-100"
      >
      <v-container>
        <h1 class="text-center mt-4">{{ offer.title }}</h1>
        <Countdown
          :deadline="offer.end_date"
          class="mb-5"
        />
        <v-row class="gutters-10 row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6">
          <v-col
            v-for="(product, i) in offer.products.data"
            :key="i"
          >
            <product-box
              :product-details="product"
              :is-loading="loading"
            />
          </v-col>
        </v-row>
      </v-container>
    </template>
  </div>
</template>

<script>
import {Countdown} from 'vue3-flip-countdown'
import { useHead } from '@unhead/vue'

export default {

  data: () => {
    return {
      metaTitle: '',
      loading: true,
      offer: {},
    };
  },
  components: {
    // FlipCountdown,
    Countdown
  },
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
      `offer/${this.$route.params.offerSlug}`
    );
    if (res.data.success) {
      this.offer = res.data.data;
      this.metaTitle = res.data.data.title
    } else {
      this.snack({
        message: res.data.message,
        color: "red",
      });
      this.$router.push({ name: "404" });
    }
    this.loading = false;
  },
};
</script>