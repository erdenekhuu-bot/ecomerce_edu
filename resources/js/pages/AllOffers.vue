<template>
    <div class="pb-6">
        <v-container>
            <h1 class="mb-7 mt-4">{{ $t("all_offers") }}</h1>
            <v-row v-if="offers.length">
                <v-col cols="12" md="6" v-for="(offer, i) in offers" :key="i">
                    <div v-if="loading">
                        <v-skeleton-loader
                            type="image"
                            class=""
                            height="300"
                        ></v-skeleton-loader>
                    </div>
                    <v-card
                        outlined
                        class="text-center"
                        variant="outlined"
                        v-else
                    >
                        <router-link
                            :to="{
                                name: 'OfferDetails',
                                params: { offerSlug: offer.slug },
                            }"
                            class="d-block lh-0"
                        >
                            <img
                                class="img-fluid"
                                :src="offer.banner"
                                :alt="offer.title"
                                @error="imageFallback($event)"
                            />
                        </router-link>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
export default {
    head: {
        title: "All Offers",
    },
    data: () => ({
        loading: true,
        offers: [{}, {}],
    }),
    async created() {
        const res = await this.call_api("get", "all-offers");
        if (res.data.success) {
            this.offers = res.data.data;
            this.loading = false;
        }
    },
};
</script>
