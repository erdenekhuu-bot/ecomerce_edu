<template>
    <div class="ps-lg-7 pt-4">
        <h1 class="fs-24 fw-700 opacity-80 mb-5 mt-3">
            {{ $t("completed_delivery") }}
        </h1>
        <div>
            <v-data-table
                :headers="headers"
                :items="completedDeliveries"
                class="border px-4 pt-3"
                hide-default-footer
                item-class="c-pointer"
            >
                <template v-slot:item.combined_order.code="{ item }">
                    <v-btn
                        @click="openOrderDetails(item.combined_order)"
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
                        @click="openOrderDetails(item.combined_order)"
                        class="px-2 btn btn-soft-info btn-icon btn-circle btn-sm hov-svg-white"
                    >
                        <i class="las la-bars"></i>
                    </a>
                </template>
            </v-data-table>
        </div>
        <div class="text-start">
            <v-pagination
                v-model="currentPage"
                @update:modelValue="getCompletedDeliveries"
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
        currentPage: 1,
        lastPage: 1,
        completedDeliveries: [],
    }),

    computed: {
        headers() {
            return [
                {
                    title: this.$i18n.t("code"),
                    align: "start",
                    sortable: false,
                    value: "combined_order.code",
                },
                {
                    title: this.$i18n.t("date"),
                    sortable: false,
                    align: "start",
                    value: "created_at",
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
        async getCompletedDeliveries(page) {
            this.loading = true;
            const res = await this.call_api(
                "get",
                `delivery-boy/completed-deliveries?page=${page}`
            );

            if (res.status == 200) {
                this.completedDeliveries = res.data.data.data;
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
    },
    created() {
        let page = this.$route.query.page || this.currentPage;
        this.getCompletedDeliveries(page);
    },
};
</script>
