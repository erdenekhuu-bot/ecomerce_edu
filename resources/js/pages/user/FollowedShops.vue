<template>
    <div v-show="check" class="custom-window" @click="globalClose"></div>
    <!-- <div class="ps-lg-7 pt-4" style="font-family: OpenSans-Bold">
        <v-row
            v-if="shops.length > 0"
            class="row-cols-1 row-cols-sm-2 row-cols-md-3"
        >
            <v-col v-for="(shop, i) in shops" :key="i">
                <shop-box :is-loading="loading" :shop-details="shop" />
            </v-col>
        </v-row>
        <div class="text-center" v-else>
            <div>{{ $t("no_followed_shop_found") }}</div>
        </div>
    </div> -->
    <div class="ps-lg-7 pt-4" style="font-family: OpenSans-Bold">
        <h1 class="fs-24 fw-700 opacity-80 mb-5 mt-3">
            {{ $t("followed_shops") }}
        </h1>
        <div class="text-center">
            <div>{{ $t("no_followed_shop_found") }}</div>
        </div>
        <!-- <div v-if="loading">
            <div class="text-center">
                <div>{{ $t("loading_please_wait") }}</div>
            </div>
        </div>
        <div v-else>
            <v-card v-if="getProductQuerries.length > 0">
                <div
                    v-for="(conversation, i) in getProductQuerries"
                    :key="i"
                    :class="[
                        'py-2',
                        { 'border-bottom': i + 1 != getProductQuerries.length },
                    ]"
                >
                    <ConversationBox :conversation="conversation" />
                </div>
            </v-card>
            <div v-else class="text-center">
                <div>{{ $t("no_followed_shop_found") }}</div>
            </div>
        </div> -->
    </div>
</template>

<script>
import ShopBox from "../../components/shop/ShopBox.vue";
export default {
    data: () => ({
        loading: true,
        shops: [{}, {}, {}, {}, {}, {}],
    }),
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
    components: { ShopBox },
    async created() {
        const res = await this.call_api("get", "user/follow");
        if (res.data.success) {
            this.shops = res.data.data;
            this.loading = false;
        }
    },
};
</script>
