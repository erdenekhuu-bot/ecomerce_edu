<template>
    <div v-show="check" class="custom-window" @click="globalClose"></div>
    <div class="ps-lg-7 pt-4" style="font-family: OpenSans-Bold">
        <h1 class="fs-24 fw-700 opacity-80 mb-5 mt-3">
            {{ $t("purchase_history") }}
        </h1>
        <div>
            <v-data-table
                :headers="headers"
                :items="orders"
                class="border px-4 pt-3"
                :loading-text="$t('loading_please_wait')"
                hide-default-footer
                :loading="loading"
                item-class="c-pointer"
                @click:row="openOrderDetails"
            >
                <template v-slot:[`item.details`]="{ item }">
                    <v-row justify="center">
                        <span
                            class=""
                            style="
                                font-family: 'OpenSans-Medium';
                                font-size: 12px;
                            "
                            >{{ item.code }}</span
                        ></v-row
                    >
                    <!-- <span class="opacity-50 fs-13 fw-600">{{ item.date }}</span> -->
                </template>

                <template v-slot:[`item.info`]="{ item }">
                    <span
                        class=""
                        style="font-family: 'OpenSans-Medium'; font-size: 12px"
                        >{{ getProductsCount(item) }} {{ $t("products") }}</span
                    >
                    <!-- <span
                        v-if="is_addon_activated('multi_vendor')"
                        class="fs-13 opacity-60"
                        >{{ $t("from") }} {{ item.orders.length }}
                        {{ $t("shops") }}</span
                    > -->
                </template>

                <template v-slot:[`item.grand_total`]="{ item }">
                    <span
                        class=""
                        style="font-family: 'OpenSans-Medium'; font-size: 12px"
                        >{{ format_price(item.grand_total) }}</span
                    >
                    <!-- <span class="d-block fw-600">{{
                        format_price(item.grand_total)
                    }}</span> -->
                </template>

                <template v-slot:[`item.actions`]="{ item }">
                    <v-btn
                        @click="invoiceDownload(item)"
                        text
                        size="small"
                        variant="flat"
                        class="fs-18"
                    >
                        <i class="las la-arrow-circle-down"></i>
                    </v-btn>

                    <v-btn
                        @click="reOrder(item.orders)"
                        text
                        size="small"
                        variant="flat"
                        class="text-green fs-18"
                    >
                        <i class="las la-sync"></i>
                    </v-btn>

                    <v-btn
                        @click="openOrderDetails(item)"
                        text
                        size="small"
                        variant="flat"
                        class="text-blue fs-18"
                    >
                        <i class="las la-bars"></i>
                        <!-- {{ $t("view_details") }} -->
                    </v-btn>
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
import { mapActions, mapMutations } from "vuex";
export default {
    data: () => ({
        loading: true,
        currentPage: 1,
        totalPages: 1,
        orders: [],
        selectedOrder: {},
    }),
    computed: {
        headers() {
            return [
                {
                    title: this.$i18n.t("details"),
                    align: "center",
                    sortable: false,
                    value: "details",
                },
                {
                    title: this.$i18n.t("info"),
                    sortable: false,
                    value: "info",
                },
                {
                    title: this.$i18n.t("amount"),
                    sortable: false,
                    value: "grand_total",
                },
                {
                    title: this.$i18n.t("actions"),
                    sortable: false,
                    align: "center",
                    value: "actions",
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
        ...mapActions("cart", ["addToCart"]),
        ...mapMutations("auth", ["updateCartDrawer"]),

        getProductsCount(combinedOrder) {
            let count = 0;
            combinedOrder.orders.forEach((order) => {
                count += order.products.data.length;
            });
            return count;
        },
        globalClose() {
            this.$store.commit("auth/modalWindow", false);
        },

        async getList() {
            this.loading = true;
            const res = await this.call_api(
                "get",
                `user/orders?page=${this.currentPage}`
            );

            if (res.data.success) {
                this.orders = res.data.data;
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

        openOrderDetails(order) {
            this.$router.push({
                name: "OrderDetails",
                params: { code: order.code },
            });
        },

        async reOrder(orders) {
            orders.forEach((order) => {
                this.multipleShop(order);
            });
        },

        multipleShop(order) {
            order.products.data.forEach((product) => {
                this.addToCart({
                    variation_id: product.product_variation_id,
                    qty: product.quantity,
                });
            });
            this.checkout();
        },

        checkout() {
            this.$router.push({ name: "Checkout" }).catch((e) => {
                if (this.$route.name == "Checkout") {
                    this.updateCartDrawer(false);
                }
            });
        },

        async invoiceDownload(order) {
            order.orders.forEach(async (order) => {
                const res = await this.call_api(
                    "get",
                    `order/invoice-download/${order.id}`
                );
                if (res.data.success) {
                    const fileUrl = res.data.invoice_url; // Replace with the actual file URL
                    const link = document.createElement("a");
                    link.href = fileUrl;
                    link.download = res.data.invoice_name; // Replace with the desired file name
                    link.click();
                } else {
                    this.snack({
                        message: this.$i18n.t("something_went_wrong"),
                        color: "red",
                    });
                }
            });
        },
    },

    created() {
        let page = this.$route.query.page || this.currentPage;
        this.getList(page);
    },
};
</script>
