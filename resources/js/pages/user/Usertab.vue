<template>
    <div v-show="check" class="custom-window" @click="globalClose"></div>
    <div class="ps-lg-7 pt-4" style="font-family: OpenSans-Bold">
        <h1 class="fs-24 fw-700 opacity-80 mb-5 mt-3">
            {{ $t("offer_price") }}
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
                    <v-row>
                        <!-- <span class="d-block fw-600">{{ item.code }}</span> -->
                        <v-col>
                            <span
                                class=""
                                style="
                                    font-family: 'OpenSans-Medium';
                                    font-size: 12px;
                                "
                                >{{ item.code }}</span
                            ></v-col
                        >
                        <!-- <span class="opacity-50 fs-13 fw-600">{{
                            item.date
                        }}</span> -->
                    </v-row>
                </template>

                <template v-slot:[`item.info`]="{ item }">
                    <v-row>
                        <span
                            class=""
                            style="
                                font-family: 'OpenSans-Medium';
                                font-size: 12px;
                            "
                            >{{ getProductsCount(item) }}
                            {{ $t("products") }}</span
                        >
                        <!-- <span
                            v-if="is_addon_activated('multi_vendor')"
                            class="fs-13 opacity-60"
                            >{{ $t("from") }} {{ item.orders.length }}
                            {{ $t("shops") }}</span
                        > -->
                    </v-row>
                </template>

                <template v-slot:[`item.grand_total`]="{ item }">
                    <span
                        class=""
                        style="font-family: 'OpenSans-Medium'; font-size: 12px"
                        >{{ format_price(item.grand_total) }}</span
                    >
                </template>

                <template v-slot:[`item.quote_status`]="{ item }">
                    <span
                        :class="
                            '' +
                            (item.quote_status == 'admin_send' ||
                            item.quote_status == 'admin_change'
                                ? 'text-green'
                                : item.quote_status == 'user_send'
                                ? 'text-orange'
                                : 'text-red')
                        "
                        style="font-family: 'OpenSans-Medium'; font-size: 12px"
                        >{{
                            item.quote_status == "admin_send"
                                ? "Админ илгээсэн"
                                : item.quote_status == "user_send"
                                ? "Хүлээгдэж буй"
                                : item.quote_status == "admin_change"
                                ? "Зөвшөөрсөн"
                                : "Цуцлагдсан"
                        }}</span
                    >
                </template>
                <template v-slot:[`item.discount`]="{ item }">
                    <span
                        class=""
                        style="font-family: 'OpenSans-Medium'; font-size: 12px"
                        >{{
                            item.discount_type == "₮"
                                ? format_price(item.coupon_discount)
                                : item.coupon_discount + "%"
                        }}</span
                    >
                </template>
                <template v-slot:[`item.actions`]="{ item }">
                    <div class="d-flex">
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
                        <v-btn
                            text
                            size="small"
                            variant="flat"
                            @click="
                                (item.quote_status == 'admin_send' ||
                                    item.quote_status == 'admin_change') &&
                                item.is_buy == 0
                                    ? showDialog(item)
                                    : ''
                            "
                            class="text-green fs-18"
                        >
                            <i class="las la-arrow-circle-down"> </i>
                        </v-btn>
                    </div>
                    <QuotesDialog
                        :show="DialogShow"
                        :order_id="order_no"
                        from="/user/wallet"
                        @close="DialogClosed"
                    />
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
import QuotesDialog from "../../components/wallet/QuotesDialog.vue";
export default {
    components: {
        QuotesDialog,
    },
    data: () => ({
        loading: true,
        currentPage: 1,
        totalPages: 1,
        orders: [],
        selectedOrder: {},
        DialogShow: false,
        order_no: "",
    }),
    computed: {
        headers() {
            return [
                {
                    title: this.$i18n.t("details"),
                    align: "start",
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
                    title: this.$i18n.t("quote_status"),
                    sortable: false,
                    value: "quote_status",
                },
                {
                    title: this.$i18n.t("discount"),
                    sortable: false,
                    value: "discount",
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
        globalClose() {
            this.$store.commit("auth/modalWindow", false);
        },
        showDialog(code) {
            this.order_no = code.code;
            this.DialogShow = true;
        },

        getProductsCount(combinedOrder) {
            let count = 0;
            combinedOrder.orders.forEach((order) => {
                count += order.products.data.length;
            });
            return count;
        },

        async getList() {
            this.loading = true;
            const res = await this.call_api(
                "get",
                `user/quotes?page=${this.currentPage}`
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
                name: "QuoteDetails",
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
        DialogClosed() {
            this.DialogShow = false;
        },
        checkout() {
            this.$router.push({ name: "Checkout" }).catch((e) => {
                if (this.$route.name == "Checkout") {
                    this.updateCartDrawer(false);
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

<style scoped>
.la-arrow-circle-down:before {
    content: "\f0ab";
    display: inline-block;
    transform: rotate(-90deg);
}
</style>
