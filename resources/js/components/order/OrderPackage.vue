<template>
    <div>
        <div
            class="bg-grey-lighten-4 border border-gray-200 pa-4 rounded d-flex justify-space-between align-center"
            v-if="is_addon_activated('multi_vendor')"
        >
            <router-link
                :to="{ name: 'ShopDetails', params: { slug: order.shop.slug } }"
                class="text-reset fs-16 fw-700 lh-1"
                >{{ order.shop.name }}</router-link
            >
            <!-- Zahialga tsustslah -->
            <div v-show="hidePayment">
                <span class="text-end d-flex">
                    <div
                        class="fs-12 text-red-darken-2 c-pointer"
                        v-if="
                            order.delivery_status == 'order_placed' &&
                            order.payment_status == 'unpaid'
                        "
                        @click="cancelOrder(order)"
                    >
                        {{ $t("cancel_order") }}
                    </div>
                    <div
                        class="fs-12 text-red-darken-2 c-pointer"
                        v-if="
                            is_addon_activated('refund') &&
                            !order.has_refund_request &&
                            order.payment_status == 'paid' &&
                            today <=
                                order.created_at +
                                    refundSettings.refund_request_time_period &&
                            refundSettings.refund_request_order_status.includes(
                                order.delivery_status
                            )
                        "
                        @click="refundRequest(order.id)"
                    >
                        {{ $t("request_refund") }}
                    </div>
                </span>
            </div>
        </div>

        <div
            class="grey lighten-4 border border-gray-200 pa-4 rounded d-flex justify-space-between align-center"
            v-else
        >
            <span class="fs-16 fw-700 lh-1">{{ $t("order_details") }}</span>
            <div
                class="fs-12 text-red c-pointer"
                v-if="
                    order.delivery_status == 'order_placed' &&
                    order.payment_status == 'unpaid'
                "
                @click="cancelOrder(order)"
            >
                {{ $t("cancel_order") }}
            </div>
            <div
                class="fs-12 text-red c-pointer"
                v-if="
                    is_addon_activated('refund') &&
                    !order.has_refund_request &&
                    order.payment_status == 'paid' &&
                    today <=
                        order.created_at +
                            refundSettings.refund_request_time_period &&
                    refundSettings.refund_request_order_status.includes(
                        order.delivery_status
                    )
                "
                @click="refundRequest(order.id)"
            >
                {{ $t("request_refund") }}
            </div>
        </div>
        <!-- shj -->
        <Steps :order-details="order" />

        <div class="grey lighten-4 pa-4" v-if="order.courier_name">
            <div class="fw-700 fs-17 mb-3 text-center">
                {{ $t("tracking_information") }}
            </div>
            <v-row class="border-top border-gray-300 border-end" no-gutters>
                <v-col
                    cols="12"
                    md="4"
                    class="border-bottom border-start border-gray-300"
                >
                    <div class="px-3 py-2 border-bottom border-gray-300 fw-600">
                        {{ $t("courier_name") }}
                    </div>
                    <div class="pa-3">{{ order.courier_name }}</div>
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                    class="border-bottom border-start border-gray-300"
                >
                    <div class="px-3 py-2 border-bottom border-gray-300 fw-600">
                        {{ $t("tracking_number") }}
                    </div>
                    <div class="pa-3">{{ order.tracking_number }}</div>
                </v-col>
                <v-col
                    cols="12"
                    md="4"
                    class="border-bottom border-start border-gray-300"
                >
                    <div class="px-3 py-2 border-bottom border-gray-300 fw-600">
                        {{ $t("tracking_url") }}
                    </div>
                    <div class="pa-3">
                        <a :href="order.tracking_url" target="_blank">{{
                            $t("track")
                        }}</a>
                    </div>
                </v-col>
            </v-row>
        </div>

        <div class="py-5">
            <v-data-table
                :headers="headers"
                :items="order.products.data"
                class=""
                hide-default-footer
                mobile-breakpoint="750"
                item-key="order_detail_id"
            >
                <template v-slot:[`item.serial`]="{ item }">
                    <span class="d-block fw-600">{{
                        order.products.data.indexOf(item) + 1
                    }}</span>
                </template>
                <template v-slot:[`item.product`]="{ item }">
                    <div class="d-flex align-center">
                        <img
                            :src="item.thumbnail"
                            :alt="item.name"
                            @error="imageFallback($event)"
                            class="size-70px flex-shrink-0"
                        />
                        <div class="flex-grow-1 ms-4">
                            <div class="text-truncate-2">{{ item.name }}</div>
                            <div class="" v-if="item.combinations.length > 0">
                                <span
                                    v-for="(
                                        combination, j
                                    ) in item.combinations"
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
                        </div>
                    </div>
                </template>
                <template v-slot:[`item.quantity`]="{ item }">
                    <span class="d-block fw-600">{{ item.quantity }}</span>
                </template>
                <template v-slot:[`item.unit_price`]="{ item }">
                    <span class="d-block fw-600">{{
                        format_price(item.price + item.tax)
                    }}</span>
                </template>
                <template v-slot:[`item.sub_price`]="{ item }">
                    <span class="d-block fw-600">
                        {{ format_price(item.price * item.quantity) }}
                    </span>
                </template>
                <template v-slot:[`item.discount`]="{ item }">
                    <span class="d-block fw-600">{{
                        item.discount_type == "₮"
                            ? format_price(item.discount)
                            : item.discount + item.discount_type
                    }}</span>
                </template>
                <template v-slot:[`item.total`]="{ item }">
                    <span class="d-block fw-600">{{
                        format_price(item.total)
                    }}</span>
                </template>
                <template
                    v-slot:[`item.review`]="{ item }"
                    v-if="
                        order.delivery_status == 'delivered' &&
                        currentUser.user_type == 'customer'
                    "
                >
                    <v-btn
                        @click="openReviewDialog(item.id)"
                        text
                        small
                        variant="flat"
                        class="px-2"
                    >
                        {{ $t("write_a_review") }}
                    </v-btn>
                </template>
            </v-data-table>
        </div>
        <v-row class="mb-5">
            <v-col xl="7" md="6" cols="12" order="2" order-md="1">
                <div v-if="order.payment_status == 'paid'" class="mt-5 ms-lg-5">
                    <img :src="paid_sticker" />
                </div>
                <div
                    v-else-if="order.payment_type == 'cash_on_delivery'"
                    class="mt-5 ms-lg-5"
                >
                    <img :src="cod_sticker" />
                </div>
            </v-col>
            <v-col xl="5" md="6" cols="12" order="1" order-md="2">
                <v-list dense>
                    <v-list-item class="fw-700">
                        <v-list-item-title
                            >{{ $t("sub_total") }} :</v-list-item-title
                        >
                        <v-list-item-title
                            class="align-end col-4 justify-end"
                            >{{
                                format_price(order.subtotal)
                            }}</v-list-item-title
                        >
                    </v-list-item>
                    <v-list-item class="fw-700">
                        <v-list-item-title>{{ $t("tax") }} :</v-list-item-title>
                        <v-list-item-title
                            class="align-end col-4 justify-end"
                            >{{ format_price(order.tax) }}</v-list-item-title
                        >
                    </v-list-item>
                    <v-list-item class="fw-700">
                        <v-list-item-title
                            >{{ $t("shipping_charge") }} :</v-list-item-title
                        >
                        <v-list-item-title
                            class="align-end col-4 justify-end"
                            >{{
                                format_price(order.shipping_cost)
                            }}</v-list-item-title
                        >
                    </v-list-item>
                    <v-list-item class="fw-700">
                        <v-list-item-title
                            >{{ $t("coupon_discount") }} :</v-list-item-title
                        >
                        <v-list-item-title
                            class="align-end col-4 justify-end"
                            >{{
                                order.discount_type == "₮"
                                    ? format_price(order.coupon_discount)
                                    : order.coupon_discount + "%"
                            }}</v-list-item-title
                        >
                    </v-list-item>
                    <v-list-item class="fw-700">
                        <v-list-item-title
                            >{{ $t("membership") }} :</v-list-item-title
                        >
                        <v-list-item-title
                            class="align-end col-4 justify-end"
                            >{{
                                format_price(order.membership)
                            }}</v-list-item-title
                        >
                    </v-list-item>
                    <v-list-item class="fw-700" v-show="sumDiscount == 0">
                        <v-list-item-title
                            >{{ $t("discount") }} :</v-list-item-title
                        >
                        <v-list-item-title
                            class="align-end col-4 justify-end"
                            >{{
                                format_price(order.discount)
                            }}</v-list-item-title
                        >
                    </v-list-item>
                </v-list>
                <div class="bg-grey-lighten-4 border border-gray-200 rounded">
                    <v-list-item class="fw-700">
                        <v-list-item-title
                            >{{ $t("total") }} :</v-list-item-title
                        >
                        <v-list-item-title
                            class="align-end col-4 justify-end px-0"
                            >{{
                                format_price(order.grand_total)
                            }}</v-list-item-title
                        >
                    </v-list-item>
                </div>
            </v-col>
        </v-row>
        <review-dialog ref="submitReview" />
        <ConfirmDialog ref="confirmCancel" />
        <Payment ref="makePayment" />
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import ConfirmDialog from "../../components/inc/ConfirmDialog.vue";
import helpers from "../../utils/helpers";
import ReviewDialog from "./ReviewDialog.vue";
import Steps from "./Steps.vue";
import Payment from "../payment/Payment.vue";

export default {
    mounted() {
        this.sumDiscount = this.orderDetails.products.data.reduce(
            (result, item) => result + item.discount,
            0
        );
    },
    components: { ReviewDialog, Steps, ConfirmDialog, Payment },
    props: {
        orderDetails: { type: Object, default: () => {} },
        combinedOrder: { type: Object, default: () => {} },
        hidePayment: { required: true },
    },
    data: () => ({
        cod_sticker: "",
        paid_sticker: "",
        sumDiscount: 0,
        cod_sticker: helpers.asset("/assets/img/cod_sticker.svg"),
        paid_sticker: helpers.asset("/assets/img/paid_sticker.svg"),
        today: new Date().getTime() / 1000,
        authorizeNet: {
            card_number: "",
            cvv: "",
            expiration_month: "",
            expiration_year: "",
        },
    }),
    computed: {
        ...mapGetters("auth", ["currentUser"]),
        order: {
            get() {
                return this.orderDetails;
            },
            set(newVal) {},
        },

        headers() {
            let headers = [
                {
                    title: "#",
                    align: "start",
                    sortable: false,
                    value: "serial",
                },
                {
                    title: this.$i18n.t("product"),
                    sortable: false,
                    value: "product",
                },
                {
                    title: this.$i18n.t("quantity"),
                    sortable: false,
                    value: "quantity",
                },
                {
                    title: this.$i18n.t("unit_price"),
                    sortable: false,
                    value: "unit_price",
                },
                {
                    title: this.$i18n.t("ded_une"),
                    sortable: false,
                    value: "sub_price",
                },
                // {
                //     title: this.$i18n.t("discount"),
                //     sortable: false,
                //     value: "discount",
                // },
                this.sumDiscount > 0
                    ? {
                          title: this.$i18n.t("discount"),
                          sortable: false,
                          value: "discount",
                      }
                    : {},
                {
                    title: this.$i18n.t("total"),
                    sortable: false,
                    align: "end",
                    value: "total",
                },
            ];

            if (this.order.delivery_status == "delivered") {
                headers.push({
                    title: "",
                    align: "end",
                    sortable: false,
                    value: "review",
                });
            }

            return headers;
        },
        ...mapGetters("app", ["refundSettings"]),
    },
    methods: {
        openReviewDialog(productId) {
            this.$refs.submitReview.open(productId);
        },
        cancelOrder(order) {
            this.$refs.confirmCancel
                .open(
                    this.$i18n.t("confirm_cancel"),
                    this.$i18n.t("are_you_sure_you_want_to_cancel_this_order")
                )
                .then((res) => {
                    if (res) {
                        this.cancelConfirmed(order.id);
                    }
                });
        },
        refundRequest(order_id) {
            this.$router.push({
                name: "CreateRefundRequest",
                params: { orderId: order_id },
            });
        },
        async cancelConfirmed(order_id) {
            this.snack({
                message: this.$i18n.t("cancelling_order"),
                timeout: -1,
            });
            const res = await this.call_api(
                "get",
                `user/order/cancel/${order_id}`
            );
            if (res.data.success) {
                this.snack({ message: res.data.message });
                this.order.delivery_status = "cancelled";
            } else {
                this.snack({ message: res.data.message, color: "red" });
            }
        },
    },
};
</script>
