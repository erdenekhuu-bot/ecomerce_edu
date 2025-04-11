<template>
    <div v-show="check" class="custom-window" @click="globalClose"></div>
    <div class="ps-lg-7 pt-4" style="font-family: OpenSans-Bold">
        <h1 class="fs-24 fw-700 opacity-80 mb-5 mt-3">
            {{ $t("my_wishlist") }}
        </h1>
        <v-row
            v-if="getWislistProducts.length > 0"
            class="row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-5 gutters-10"
        >
            <v-col v-for="(product, i) in getWislistProducts" :key="i">
                <product-box
                    :product-details="product"
                    :is-loading="!wislistLoaded"
                />
            </v-col>
        </v-row>
        <div v-else class="text-center">
            <div>{{ $t("no_product_wishlisted") }}</div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
    data: () => ({
        currentPage: 1,
        totalPages: 1,
    }),
    computed: {
        ...mapGetters("wishlist", ["wislistLoaded", "getWislistProducts"]),
        check() {
            return this.$store.state.auth.modalWindow;
        },
    },
    methods: {
        globalClose() {
            this.$store.commit("auth/modalWindow", false);
        },
    },
};
</script>
