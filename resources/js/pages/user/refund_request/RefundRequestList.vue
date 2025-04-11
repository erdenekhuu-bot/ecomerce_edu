<template>
    <div v-show="check" class="custom-window" @click="globalClose"></div>
    <div class="ps-lg-7 pt-4" style="font-family: OpenSans-Bold">
        <h1 class="fs-24 fw-700 opacity-80 mb-5 mt-3">
            {{ $t("refund_requests") }}
        </h1>
        <div>
            <v-data-table
                :headers="headers"
                :items="refund_requests"
                class="border px-4 pt-3"
                :loading-text="$t('loading_please_wait')"
                hide-default-footer
                :loading="loading"
            >
                <template v-slot:[`item.order_code`]="{ item }">
                    <!-- <span class="d-block fw-600">{{ item.order_code }}</span> -->
                    <span class="" style="font-family: 'OpenSans-Medium'">{{
                        item.order_code
                    }}</span>
                </template>

                <template v-slot:[`item.shop`]="{ item }">
                    <span class="" style="font-family: 'OpenSans-Medium'">{{
                        item.shop
                    }}</span>
                </template>

                <template v-slot:[`item.products`]="{ item }">
                    <div
                        v-for="(refunditem, i) in item.refunditems"
                        :key="i"
                        class="mb-3"
                    >
                        <div class="text-truncate-2">
                            {{ refunditem.product.name }}
                        </div>
                        <div
                            class=""
                            v-if="refunditem.product.combinations.length > 0"
                        >
                            <span
                                v-for="(combination, j) in refunditem.product
                                    .combinations"
                                :key="j"
                                class="me-4 py-1 fs-12"
                            >
                                <span class="opacity-70">{{
                                    combination.attribute
                                }}</span>
                                :
                                <span class="fw-500">{{
                                    combination.value
                                }}</span>
                            </span>
                        </div>
                        <span class="opacity-70">{{ $t("quantity") }}</span> :
                        <span class="fw-500">{{ refunditem.quantity }}</span>
                    </div>
                </template>

                <template v-slot:[`item.amount`]="{ item }">
                    <span
                        class=""
                        style="font-family: 'OpenSans-Medium'; font-size: 12px"
                        >{{ format_price(item.amount) }}</span
                    >
                </template>

                <template v-slot:[`item.status`]="{ item }">
                    <v-btn
                        v-if="item.status == 0"
                        size="x-small"
                        color="info"
                        elevation="0"
                        >{{ $t("pending") }}</v-btn
                    >
                    <v-btn
                        v-else-if="item.status == 1"
                        size="x-small"
                        color="success"
                        elevation="0"
                        >{{ $t("accepted") }}</v-btn
                    >
                    <v-btn
                        v-else="item.status == 2"
                        size="x-small"
                        color="error"
                        elevation="0"
                        >{{ $t("rejected") }}</v-btn
                    >
                </template>
            </v-data-table>

            <div class="text-start" v-if="totalPages > 1">
                <v-pagination
                    v-model="currentPage"
                    @update:modelValue="getList"
                    :length="totalPages"
                    prev-icon="las la-angle-left"
                    next-icon="las la-angle-right"
                    :total-visible="7"
                    elevation="0"
                    class="my-4"
                ></v-pagination>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        loading: true,
        currentPage: 1,
        totalPages: 1,
        refund_requests: [],
    }),
    computed: {
        headers() {
            return [
                {
                    title: this.$i18n.t("order_code"),
                    align: "start",
                    sortable: false,
                    value: "order_code",
                },
                {
                    title: this.$i18n.t("shop"),
                    sortable: false,
                    value: "shop",
                },
                {
                    title: this.$i18n.t("products"),
                    sortable: false,
                    align: "start",
                    value: "products",
                },
                {
                    title: this.$i18n.t("amount"),
                    sortable: false,
                    align: "end",
                    value: "amount",
                },
                {
                    title: this.$i18n.t("status"),
                    sortable: false,
                    align: "end",
                    value: "status",
                },
            ];
        },
        check() {
            return this.$store.state.auth.modalWindow;
        },
    },
    watch: {
        currentPage() {
            this.$router
                .push({
                    query: {
                        ...this.$route.query,
                        page: this.currentPage,
                    },
                })
                .catch(() => {});
        },
    },
    methods: {
        async getList(number) {
            this.loading = true;
            const res = await this.call_api(
                "get",
                `user/refund-requests?page=${number}`
            );
            if (res.data.success) {
                this.refund_requests = res.data.data;
                this.totalPages = res.data.meta.last_page;
                this.currentPage = res.data.meta.current_page;
            } else {
                this.snack({
                    message: this.$i18n.t("something_went_wrong"),
                    color: "red",
                });
            }
            this.loading = false;
        },
        globalClose() {
            this.$store.commit("auth/modalWindow", false);
        },
    },
    created() {
        let page = this.$route.query.page || this.currentPage;
        this.getList(page);
    },
};
</script>
