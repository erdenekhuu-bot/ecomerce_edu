<template>
    <div class="ps-lg-7 pt-4">
        <h1 class="fs-24 fw-700 opacity-80 mb-5 mt-3">
            {{ $t("pending_delivery") }}
        </h1>
        <div>
            <v-data-table
                :headers="headers"
                :items="pendingDeliveries"
                class="border px-4 pt-3"
                hide-default-footer
                item-class="c-pointer"
            >
                <template v-slot:item.code="{ item }">
                    <v-btn
                        @click="openOrderDetails(item.combined_order)"
                        small
                        variant="flat"
                        class="px-2"
                    >
                        {{ item.combined_order.code }}
                    </v-btn>
                </template>

                <template v-slot:[`item.grand_total`]="{ item }">
                    <span class="d-block fw-600">{{
                        format_price(item.grand_total)
                    }}</span>
                </template>

                <template v-slot:item.options="{ item }">
                    <a
                        @click="(dialog = true), (cancelOrderID = item.id)"
                        class="btn btn-soft-danger btn-icon btn-circle btn-sm hov-svg-white mr-2"
                    >
                        <i class="las la-times text-danger"></i>
                    </a>
                    <a
                        @click="openOrderDetails(item.combined_order)"
                        class="px-2 btn btn-soft-info btn-icon btn-circle btn-sm hov-svg-white"
                    >
                        <i class="las la-bars"></i>
                    </a>
                    <!--  -->
                    <v-dialog v-model="dialog" width="auto">
                        <v-card>
                            <v-card-title> </v-card-title>
                            <v-card-text>
                                <p>
                                    {{
                                        $t(
                                            "are_you_sure_you_want_to_cancel_this_order"
                                        )
                                    }}
                                </p>
                            </v-card-text>
                            <v-card-actions>
                                <div class="w-100 text-center mb-4">
                                    <v-btn
                                        class="mx-1"
                                        color="primary"
                                        variant="text"
                                        @click="caneclRequest"
                                    >
                                        {{ $t("yes") }}
                                    </v-btn>
                                    <v-btn
                                        variant="text"
                                        @click="dialog = false"
                                        class="danger"
                                    >
                                        {{ $t("no") }}
                                    </v-btn>
                                </div>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                    <!--  -->
                </template>
            </v-data-table>
        </div>

        <div class="text-start">
            <v-pagination
                v-model="currentPage"
                @update:modelValue="getOnTheWayDeliveries"
                :length="lastPage"
                prev-icon="las la-angle-left"
                next-icon="las la-angle-right"
                :total-visible="7"
                elevation="0"
                class="my-4"
            ></v-pagination>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        cancelOrderID: null,
        dialog: false,
        currentPage: 1,
        lastPage: 1,
        pendingDeliveries: [],
    }),

    computed: {
        headers() {
            return [
                {
                    title: this.$i18n.t("code"),
                    align: "start",
                    sortable: false,
                    value: "code",
                },

                {
                    title: this.$i18n.t("amount"),
                    sortable: false,
                    align: "start",
                    value: "grand_total",
                },
                {
                    title: this.$i18n.t("delivery_status"),
                    sortable: false,
                    align: "start",
                    value: "delivery_status",
                },
                {
                    title: this.$i18n.t("payment_status"),
                    sortable: false,
                    align: "start",
                    value: "payment_status",
                },
                {
                    title: this.$i18n.t("options"),
                    sortable: false,
                    align: "center",
                    value: "options",
                },
            ];
        },
    },
    methods: {
        async getOnTheWayDeliveries(page) {
            this.loading = true;
            const res = await this.call_api(
                "get",
                `delivery-boy/pending-deliveries?page=${page}`
            );
            if (res.status == 200) {
                this.pendingDeliveries = res.data.data.data;
                this.lastPage = res.data.data.last_page;
                this.currentPage = res.data.data.current_page;
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
                name: "DeliveryboyOrderDetails",
                params: { code: order.code },
            });
        },
        async caneclRequest() {
            this.dialog = false;
            const res = await this.call_api(
                "post",
                `delivery-boy/cancel-request`,
                {
                    order_id: this.cancelOrderID,
                }
            );
            this.snack({
                message: res.data.message,
                color: "green",
            });

            this.getOnTheWayDeliveries();
        },
    },
    created() {
        let page = this.$route.query.page || this.currentPage;
        this.getOnTheWayDeliveries(page);
    },
};
</script>
