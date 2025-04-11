<template>
    <v-col>
        <div class="mt-4">
            <v-card class="mx-auto">
                <v-card-text>
                    <h1 class="fs-21 fw-700 opacity-80 mb-5">
                        {{ $t("all_notifications") }}
                    </h1>

                    <div v-for="(notification, index) in notifications">
                        <ul>
                            <li class="py-4">
                                {{ $t("order_code") }}:

                                <a
                                    @click="
                                        openOrderDetails(
                                            notification.data.order_code
                                        )
                                    "
                                >
                                    {{ notification.data.order_code }}
                                </a>

                                {{ $t(" has been ") }}

                                <b> {{ notification.data.status }} </b> <br />

                                <b>
                                    {{
                                        new Date(notification.created_at)
                                            .toJSON()
                                            .slice(0, 10)
                                            .split("-")
                                            .reverse()
                                            .join("-")
                                    }}
                                </b>
                            </li>
                        </ul>
                    </div>

                    <div class="text-start">
                        <v-pagination
                            v-model="currentPage"
                            @update:modelValue="fetNotification"
                            :length="totalPages"
                            prev-icon="las la-angle-left"
                            next-icon="las la-angle-right"
                            :total-visible="4"
                            elevation="0"
                            class="my-4"
                        ></v-pagination>
                    </div>
                </v-card-text>
            </v-card>
        </div>
    </v-col>
</template>

<script>
export default {
    data: () => ({
        loading: true,
        currentPage: 1,
        totalPages: 1,
        notifications: [],
    }),

    methods: {
        async fetNotification(page) {
            const res = await this.call_api(
                "get",
                `user/all-notification?page=${page}`
            );
            if (res.status == 200) {
                this.notifications = res.data.data.data;
                this.totalPages = res.data.data.total;
                this.currentPage = res.data.data.current_page;
            } else {
                this.snack({
                    message: this.$i18n.t("something_went_wrong"),
                    color: "red",
                });
            }
            this.loading = false;
        },
        openOrderDetails(orderCode) {
            this.$router.push({
                name: "OrderDetails",
                params: { code: orderCode },
            });
        },
    },
    created() {
        this.fetNotification();
    },
};
</script>

<style scoped>
ul li {
    list-style-type: none;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}
</style>
