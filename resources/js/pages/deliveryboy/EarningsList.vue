<template>
    <div class="ps-lg-7 pt-4">
        <h1 class="fs-24 fw-700 opacity-80 mb-5 mt-3">
            {{ $t("earning_history") }}
        </h1>
        <div>
            <v-data-table
                :headers="headers"
                :items="earningsList"
                class="border px-4 pt-3"
                hide-default-footer
                item-class="c-pointer"
            >
                <template v-slot:item.code="{ item }">
                    <v-btn
                        @click="openOrderDetails(item.code)"
                        variant="flat"
                        class="px-2"
                    >
                        {{ item.code }}
                    </v-btn>
                </template>
                <template v-slot:[`item.amount`]="{ item }">
                    <span class="d-block fw-600">{{
                        format_price(item.amount)
                    }}</span>
                </template>

                <template v-slot:item.options="{ item }">
                    <a
                        @click="openOrderDetails(item.code)"
                        small
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
                @update:modelValue="getEarningsList"
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
        loading: true,
        currentPage: 1,
        lastPage: 1,
        earningsList: [],
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
                    title: this.$i18n.t("date"),
                    sortable: false,
                    align: "start",
                    value: "date",
                },
                {
                    title: this.$i18n.t("amount"),
                    sortable: false,
                    align: "start",
                    value: "amount",
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
        async getEarningsList(page) {
            this.loading = true;
            const res = await this.call_api(
                "get",
                `delivery-boy/earnings-list?page=${page}`
            );
            if (res.status == 200) {
                this.earningsList = res.data.data;
                this.lastPage = res.data.meta.last_page;
                this.currentPage = res.data.meta.current_page;
            } else {
                this.snack({
                    message: this.$i18n.t("something_went_wrong"),
                    color: "red",
                });
            }
            this.loading = false;
        },
        openOrderDetails(code) {
            this.$router.push({
                name: "DeliveryboyOrderDetails",
                params: { code: code },
            });
        },
    },
    created() {
        let page = this.$route.query.page || this.currentPage;
        this.getEarningsList(page);
    },
};
</script>
