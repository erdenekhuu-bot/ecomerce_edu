<template>
    <div class="ps-lg-7 pt-4">
        <h1 class="fs-24 fw-700 opacity-80 mb-5 mt-3">{{ $t("my_points") }}</h1>
        <v-row class="mb-4">
            <v-col cols="12">
                <v-sheet
                    color="grey-darken-3"
                    rounded="rounded"
                    elevation="1"
                    height="150"
                    class="d-flex justify-center align-center white--text flex-column"
                >
                    <div class="fs-16 mb-3 text-capitalize">
                        {{ $t("exchange_rate") }}
                    </div>
                    <div class="fw-700 fs-30">
                        {{ generalSettings.club_point_convert_rate }}
                        {{ $t("points") }} = {{ format_price(1) }}
                        {{ $t("wallet_money") }}
                    </div>
                </v-sheet>
            </v-col>
        </v-row>
        <h2 class="fs-20 fw-700 opacity-80 mb-4">
            {{ $t("point_earning_history") }}
        </h2>
        <div>
            <point-dialog
                :show="pointDialogShow"
                @close="pointDialogClosed"
                :activeClubPoint="activeClubPoint"
            />

            <v-data-table
                :headers="headers"
                :items="earningHistories"
                class="border px-4 pt-3"
                :loading-text="$t('loading_please_wait')"
                hide-default-footer
                :loading="loading"
            >
                <template v-slot:[`item.order_code`]="{ item }">
                    <span class="fw-600">{{ item.order_code }}</span>
                </template>

                <template v-slot:[`item.points`]="{ item }">
                    <span class="fw-600"
                        >{{ item.points }} {{ $t("pts") }}</span
                    >
                </template>

                <template v-slot:[`item.converted`]="{ item }">
                    <v-btn
                        v-if="item.convert_status == 1"
                        class="c-initial"
                        size="x-small"
                        color="success"
                        elevation="0"
                        >{{ $t("yes") }}</v-btn
                    >
                    <v-btn
                        v-else
                        size="x-small"
                        color="error"
                        class="c-initial"
                        elevation="0"
                        >{{ $t("no") }}</v-btn
                    >
                </template>

                <template v-slot:[`item.date`]="{ item }">
                    <span class="fw-600">{{ item.created_at }}</span>
                </template>

                <template v-slot:[`item.action`]="{ item }">
                    <v-btn
                        v-if="item.convert_status == 0"
                        size="x-small"
                        color="warning"
                        elevation="0"
                        @click.stop="setActiveClubPoint(item)"
                        >{{ $t("convert_now") }}</v-btn
                    >
                    <v-btn
                        v-else
                        size="x-small"
                        color="success"
                        elevation="0"
                        >{{ $t("done") }}</v-btn
                    >
                </template>
            </v-data-table>

            <div class="text-center" v-if="totalPages > 1">
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
import { mapGetters } from "vuex";
import PointDialog from "../../components/club_point/PointDialog.vue";
export default {
    data: () => ({
        loading: true,
        pointDialogShow: false,
        activeClubPoint: null,
        currentPage: 1,
        totalPages: 1,
        earningHistories: [],
    }),
    components: {
        PointDialog,
    },
    computed: {
        ...mapGetters("app", ["generalSettings"]),
        headers() {
            return [
                {
                    title: this.$i18n.t("order_code"),
                    align: "start",
                    sortable: false,
                    value: "order_code",
                },
                {
                    title: this.$i18n.t("points"),
                    sortable: false,
                    value: "points",
                },
                {
                    title: this.$i18n.t("converted"),
                    sortable: false,
                    value: "converted",
                },
                {
                    title: this.$i18n.t("date"),
                    sortable: false,
                    align: "start",
                    value: "date",
                },
                {
                    title: this.$i18n.t("action"),
                    sortable: false,
                    align: "start",
                    value: "action",
                },
            ];
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
                `user/earning/history?page=${number}`
            );
            if (res.data.success) {
                this.earningHistories = res.data.data;
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

        pointDialogClosed() {
            this.pointDialogShow = false;
        },
        setActiveClubPoint(activeClubPoint) {
            this.activeClubPoint = activeClubPoint;
            this.pointDialogShow = true;
        },
    },
    created() {
        let page = this.$route.query.page || this.currentPage;
        this.getList(page);
    },
    mounted() {
        //
    },
};
</script>
