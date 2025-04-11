<template>
    <div
        v-if="!is_empty_obj(orderDetails) && orderDetails.orders.length > 0"
        style="z-index: 10"
    >
        <div
            class="bg-grey-lighten-4 border border-gray-200 pa-4 rounded d-flex justify-space-between align-center"
        >
            <p class="text-reset fs-16 fw-700 lh-1">
                {{ $t("order_summary") }}
            </p>
            <!-- <h4>{{ "order details : " + orderDetails }}</h4> -->
            <!-- Tolbor tololt -->
            <div
                v-if="
                    orderDetails.orders[0].payment_type == 'golomt' &&
                    orderDetails.orders[0].payment_status == 'unpaid'
                "
            >
                <v-btn @click="golomt">Төлбөр шалгах</v-btn>
            </div>
            <div
                v-if="orderDetails.orders[0].payment_status == 'paid'"
                class="fs-12 c-pointer"
            >
                Төлөгдсөн
            </div>
            <div v-show="hidePayment">
                <span class="text-end d-flex">
                    <div
                        @click="reOrder(orderDetails.orders)"
                        class="fs-12 c-pointer fw-500"
                    >
                        {{ $t("reorder") }}
                    </div>

                    <repayment-dialog
                        :show="RepaymentDialogShow"
                        :from="`user/purchase-history/${orderDetails.code}`"
                        :combined-order="orderDetails"
                        @close="rePaymentDialogClosed"
                    />
                    <div
                        v-if="orderDetails.orders[0].payment_status == 'unpaid'"
                        class="re-payment fs-12 c-pointer ml-4 fw-500"
                        @click.stop="RepaymentDialogShow = true"
                    >
                        {{ $t("pay_now") }}
                    </div>

                    <div
                        v-if="
                            orderDetails.orders[0].payment_status == 'unpaid' &&
                            orderDetails.orders[0].payment_type == 'golomt'
                        "
                        class="re-payment fs-12 c-pointer ml-4"
                        @click.stop="RepaymentDialogShow = true"
                    >
                        Төлбөр шалгах
                    </div>
                </span>
            </div>
        </div>

        <v-row class="mb-3">
            <v-col md="6" cols="12" class="pb-0 pb-md-3">
                <v-list dense>
                    <v-list-item
                        v-if="orderDetails.orders[0].payment_type == 'golomt'"
                    >
                        <v-row>
                            <v-col cols="">
                                <v-list-item-title
                                    class="fw-400"
                                    style="font-size: 15px"
                                    >Дансны дугаар :</v-list-item-title
                                >
                            </v-col>
                            <v-col cols="">
                                <v-list-item-title
                                    ><div
                                        class="flex justify-space-between fw-400"
                                        style="font-size: 15px"
                                    >
                                        1705240039
                                        <p
                                            @click="
                                                copyToClipboard('1705240039')
                                            "
                                        >
                                            <img
                                                :src="
                                                    static_asset(
                                                        `/assets/img/icons/copy-icon.svg`
                                                    )
                                                "
                                                alt=""
                                                height="15"
                                            />
                                        </p>
                                    </div>
                                </v-list-item-title>
                            </v-col>
                        </v-row>
                    </v-list-item>

                    <v-list-item>
                        <v-row>
                            <v-col>
                                <v-list-item-title
                                    class="fw-400"
                                    style="font-size: 15px"
                                    >{{ $t("order_code") }} :</v-list-item-title
                                >
                            </v-col>
                            <v-col>
                                <v-list-item-title
                                    class="fw-400"
                                    style="font-size: 15px"
                                >
                                    <div class="flex justify-space-between">
                                        {{ orderDetails.code }}
                                        <p
                                            @click="
                                                copyToClipboard(
                                                    orderDetails.code
                                                )
                                            "
                                        >
                                            <img
                                                :src="
                                                    static_asset(
                                                        `/assets/img/icons/copy-icon.svg`
                                                    )
                                                "
                                                alt=""
                                                height="15"
                                            />
                                        </p>
                                    </div>
                                </v-list-item-title>
                            </v-col>
                        </v-row>
                    </v-list-item>

                    <v-list-item>
                        <v-row>
                            <v-col cols="">
                                <v-list-item-title
                                    class="fw-400"
                                    style="font-size: 15px"
                                    >{{ $t("name") }} :</v-list-item-title
                                >
                            </v-col>
                            <v-col cols="6">
                                <v-list-item-title
                                    class="fw-400"
                                    style="font-size: 15px"
                                    >{{ orderDetails.user.name }}
                                </v-list-item-title>
                            </v-col>
                        </v-row>
                    </v-list-item>

                    <v-list-item>
                        <v-row>
                            <v-col cols="6">
                                <v-list-item-title
                                    class="fw-400"
                                    style="font-size: 15px"
                                    >{{ $t("email") }} :</v-list-item-title
                                >
                            </v-col>
                            <v-col cols="6">
                                <v-list-item-title
                                    class="fw-400"
                                    style="font-size: 15px"
                                    >{{
                                        orderDetails.user.email
                                    }}</v-list-item-title
                                >
                            </v-col>
                        </v-row>
                    </v-list-item>
                    <v-list-item>
                        <v-row
                            v-if="
                                orderDetails.orders[0].type_of_delivery !=
                                'pickup'
                            "
                        >
                            <v-col cols="6">
                                <v-list-item-title
                                    class="fw-400 align-self-baseline"
                                    style="font-size: 15px"
                                    >{{
                                        $t("shipping_address")
                                    }}
                                    :</v-list-item-title
                                >
                            </v-col>
                            <v-col cols="6">
                                <v-list-item-title
                                    style="display: flex; flex-wrap: wrap"
                                >
                                    <span
                                        class="d-block lh-1-6"
                                        style="font-size: 15px"
                                        >{{
                                            orderDetails.shipping_address
                                                .address
                                        }}
                                        {{
                                            orderDetails.shipping_address
                                                .postal_code
                                        }}
                                    </span>
                                    <span
                                        class="d-block lh-1-6"
                                        style="font-size: 15px"
                                    >
                                        {{
                                            orderDetails.shipping_address.city
                                        }},
                                        {{
                                            orderDetails.shipping_address.state
                                        }},
                                        {{
                                            orderDetails.shipping_address
                                                .country
                                        }}
                                    </span>
                                    <span
                                        class="lh-1-6"
                                        style="font-size: 15px"
                                        >{{
                                            ", " +
                                            orderDetails.shipping_address.phone
                                        }}</span
                                    >
                                </v-list-item-title>
                            </v-col>
                        </v-row>
                    </v-list-item>
                </v-list>
            </v-col>
            <v-col md="6" cols="12" class="pt-0 pt-md-3">
                <v-list dense>
                    <v-list-item
                        v-if="orderDetails.orders[0].payment_type == 'golomt'"
                    >
                        <v-row>
                            <v-col cols="6">
                                <v-list-item-title
                                    class="fw-400"
                                    style="font-size: 15px"
                                    >Дансны нэр :</v-list-item-title
                                >
                            </v-col>
                            <v-col cols="">
                                <v-list-item-title
                                    ><div
                                        class="flex justify-space-between fw-400"
                                        style="font-size: 15px"
                                    >
                                        ОРЕМХАН БОГД ХХК
                                        <p
                                            @click="
                                                copyToClipboard(
                                                    'ОРЕМХАН БОГД ХХК'
                                                )
                                            "
                                        >
                                            <img
                                                :src="
                                                    static_asset(
                                                        `/assets/img/icons/copy-icon.svg`
                                                    )
                                                "
                                                alt=""
                                                height="15"
                                            />
                                        </p>
                                    </div>
                                </v-list-item-title>
                            </v-col>
                        </v-row>
                    </v-list-item>

                    <v-list-item>
                        <v-row>
                            <v-col cols="6">
                                <v-list-item-title
                                    class="fw-400"
                                    style="font-size: 15px"
                                    >{{
                                        $t("total_order_amount")
                                    }}
                                    :</v-list-item-title
                                >
                            </v-col>
                            <v-col cols="6">
                                <v-list-item-title
                                    class="align-end"
                                    style="font-size: 15px"
                                    >{{
                                        format_price(orderDetails.grand_total)
                                    }}</v-list-item-title
                                >
                            </v-col>
                        </v-row>
                    </v-list-item>

                    <v-list-item>
                        <v-row>
                            <v-col cols="6">
                                <v-list-item-title
                                    class="fw-400"
                                    style="font-size: 15px"
                                    >{{
                                        $t("payment_method")
                                    }}
                                    :</v-list-item-title
                                >
                            </v-col>
                            <v-col cols="6">
                                <v-list-item-title
                                    class="align-end text-capitalize"
                                    style="font-size: 15px"
                                    >{{
                                        $t(orderDetails.orders[0].payment_type)
                                    }}</v-list-item-title
                                >
                            </v-col>
                        </v-row>
                    </v-list-item>

                    <!-- show offline payment data -->
                    <v-list-item
                        v-if="
                            orderDetails.orders[0].payment_type ===
                            'offline_payment'
                        "
                    >
                        <v-row>
                            <v-col cols="6">
                                <v-list-item-title class="fw-700"
                                    >{{
                                        $t("payment_details")
                                    }}
                                    :</v-list-item-title
                                >
                            </v-col>
                            <v-col cols="6">
                                <v-list-item-title
                                    class="align-end text-capitalize"
                                >
                                    <span
                                        >{{ $t("transaction_id") }}:
                                        {{
                                            $t(
                                                orderDetails.orders[0]
                                                    .manual_payment_data
                                                    .transactionId
                                            )
                                        }}</span
                                    >
                                    <span>
                                        {{ $t("paid_via") }}:
                                        {{
                                            $t(
                                                orderDetails.orders[0]
                                                    .manual_payment_data
                                                    .payment_method
                                            )
                                        }}
                                        <a
                                            :href="
                                                appUrl +
                                                '/public/' +
                                                orderDetails.orders[0]
                                                    .manual_payment_data.reciept
                                            "
                                            v-if="
                                                orderDetails.orders[0]
                                                    .manual_payment_data.reciept
                                            "
                                            target="_blank"
                                            rel="noopener noreferrer"
                                        >
                                            ({{ $t("receipt") }})
                                        </a>
                                    </span>
                                </v-list-item-title>
                            </v-col>
                        </v-row>
                    </v-list-item>
                    <!-- show offline payment data -->

                    <v-list-item>
                        <v-row>
                            <v-col cols="6">
                                <v-list-item-title
                                    class="fw-400"
                                    style="font-size: 15px"
                                    >{{
                                        $t("delivery_type")
                                    }}
                                    :</v-list-item-title
                                >
                            </v-col>
                            <v-col cols="6">
                                <v-list-item-title
                                    class="align-end text-capitalize"
                                    style="font-size: 15px"
                                    >{{
                                        orderDetails.orders[0]
                                            .type_of_delivery == "pickup"
                                            ? "pickup"
                                            : orderDetails.orders[0].delivery_type.replaceAll(
                                                  "_",
                                                  " "
                                              )
                                    }}</v-list-item-title
                                >
                            </v-col>
                        </v-row>
                    </v-list-item>

                    <v-list-item>
                        <v-row
                            v-if="
                                orderDetails.orders[0].type_of_delivery !=
                                'pickup'
                            "
                        >
                            <v-col cols="6">
                                <v-list-item-title
                                    class="fw-400 align-self-baseline"
                                    style="font-size: 15px"
                                    >{{
                                        $t("billing_address")
                                    }}
                                    :</v-list-item-title
                                >
                            </v-col>
                            <v-col cols="6">
                                <v-list-item-title
                                    class="text-start"
                                    style="display: flex; flex-wrap: wrap"
                                >
                                    <span
                                        class="d-block lh-1-6"
                                        style="font-size: 15px"
                                        >{{
                                            orderDetails.billing_address
                                                ?.address
                                        }},
                                        {{
                                            orderDetails.billing_address
                                                ?.postal_code
                                        }}
                                    </span>
                                    <span
                                        class="d-block lh-1-6"
                                        style="font-size: 15px"
                                    >
                                        {{
                                            orderDetails.billing_address?.city
                                        }},
                                        {{
                                            orderDetails.billing_address?.state
                                        }},
                                        {{
                                            orderDetails.billing_address
                                                ?.country
                                        }}</span
                                    >
                                    <span
                                        class="lh-1-6"
                                        style="font-size: 15px"
                                        >{{
                                            orderDetails.billing_address?.phone
                                        }}</span
                                    >
                                </v-list-item-title>
                            </v-col>
                        </v-row>
                        <v-row v-else>
                            <v-col cols="6">
                                <v-list-item-title
                                    class="fw-400 align-self-baseline"
                                    style="font-size: 15px"
                                    >{{
                                        $t("pickup_point")
                                    }}
                                    :</v-list-item-title
                                >
                            </v-col>
                            <v-col cols="6">
                                <v-list-item-title class="align-end">
                                    <span
                                        class="d-block lh-1-6"
                                        style="font-size: 15px"
                                        >{{
                                            orderDetails.orders[0].pickup_point
                                                ?.name
                                        }},
                                        {{
                                            orderDetails.orders[0].pickup_point
                                                ?.manager
                                        }}</span
                                    >
                                    <span
                                        class="d-block lh-1-6"
                                        style="font-size: 15px"
                                        >{{
                                            orderDetails.orders[0].pickup_point
                                                ?.location
                                        }}</span
                                    >
                                    <span
                                        class="lh-1-6"
                                        style="font-size: 15px"
                                        >{{
                                            orderDetails.orders[0].pickup_point
                                                ?.phone
                                        }}</span
                                    >
                                </v-list-item-title>
                            </v-col>
                        </v-row>
                    </v-list-item>
                </v-list>
            </v-col>
        </v-row>
        <v-sheet
            class=""
            color="white"
            elevation="0"
            v-for="(order, i) in orderDetails.orders"
            :key="i"
        >
            <order-package
                :order-details="order"
                :combined-order="orderDetails"
                :hidePayment="hidePayment"
            />
        </v-sheet>
    </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
import OrderPackage from "./OrderPackage.vue";
import RepaymentDialog from "../payment/RePaymentDialog.vue";
export default {
    mounted() {
        this.trigger();
    },
    components: { OrderPackage, RepaymentDialog },
    computed: {
        ...mapGetters("app", ["appUrl"]),
    },
    props: {
        orderDetails: { type: Object, default: () => {} },
        ordercode: { required: false },
        forward: { required: false },
    },
    data: () => ({
        RepaymentDialogShow: false,
        hidePayment: false,
        authorizeNet: {
            card_number: "",
            cvv: "",
            expiration_month: "",
            expiration_year: "",
        },
    }),
    methods: {
        ...mapActions("cart", ["addToCart"]),
        ...mapMutations("auth", ["updateCartDrawer"]),

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

        // re payment is started from here
        rePaymentDialogClosed() {
            this.RepaymentDialogShow = false;
        },
        trigger() {
            let interval;
            if (
                this.forward == 0 &&
                this.orderDetails.orders[0].payment_type == "qpay"
            ) {
                interval = setInterval(() => {
                    this.qpay();
                }, 10000);
            }
            if (this.forward == 1) {
                clearInterval(interval);
            }
        },
        async qpay() {
            const res = await this.call_api(
                "post",
                "user/order/isPaid/" + this.ordercode
            );
            this.hidePayment = res.data == "unpaid" ? true : false;
            if (res.data == "paid") {
                this.orderDetails.orders[0].payment_status = "paid";
            }
        },

        async golomt() {
            const res = await this.call_api(
                "get",
                "user/golomt/isPaid/" + this.ordercode
            );
            if (res.data == "paid") {
                this.orderDetails.orders[0].payment_status = "paid";
            }
        },

        copyToClipboard(text_text) {
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard
                    .writeText(text_text)
                    .then(() => {
                        setTimeout(
                            () =>
                                this.snack({
                                    message: "Амжилттай хуулагдлаа",
                                    color: "green",
                                }),
                            2000
                        );
                    })
                    .catch((err) => {
                        console.error("Failed to copy: ", err);
                    });
            } else {
                const textarea = document.createElement("textarea");
                textarea.value = text_text;
                textarea.style.position = "fixed";
                document.body.appendChild(textarea);
                textarea.select();
                try {
                    document.execCommand("copy");
                    this.copied = true;
                    setTimeout(() => (this.copied = false), 2000);
                } catch (err) {
                    console.error("Fallback: Failed to copy text: ", err);
                }
                document.body.removeChild(textarea);
            }
        },
    },
};
</script>
