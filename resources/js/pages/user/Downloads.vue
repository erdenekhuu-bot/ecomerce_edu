<template>
    <div v-show="check" class="custom-window" @click="globalClose"></div>
    <div class="ps-lg-7 pt-4" style="font-family: OpenSans-Bold">
        <h1 class="fs-24 fw-700 opacity-80 mb-5 mt-3">
            {{ $t("download_your_products") }}
        </h1>
        <div>
            <v-data-table
                :headers="headers"
                :items="products"
                class="border px-4 pt-3"
                :loading-text="$t('loading_please_wait')"
                hide-default-footer
                :loading="loading"
                item-class="c-pointer"
            >
                <template v-slot:[`item.name`]="{ item }">
                    <span class="d-block fw-600"> {{ item.name }}</span>
                </template>
                <template v-slot:[`item.image`]="{ item }">
                    <img :src="item.image" :class="['img-fit', 'size-80px']" />
                </template>

                <template v-slot:[`item.actions`]="{ item }">
                    <v-btn
                        @click="downloadProduct(item)"
                        text
                        size="small"
                        elevation="0"
                        class="px-2"
                    >
                        {{ $t("downloads") }}
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
export default {
    data: () => ({
        loading: true,
        currentPage: 1,
        totalPages: 1,
        products: [],
    }),
    computed: {
        headers() {
            return [
                {
                    title: this.$i18n.t("product"),
                    align: "start",
                    sortable: false,
                    value: "name",
                },
                {
                    title: this.$i18n.t("image"),
                    align: "start",
                    sortable: false,
                    value: "image",
                },

                {
                    title: this.$i18n.t("actions"),
                    sortable: false,
                    align: "end",
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
        globalClose() {
            this.$store.commit("auth/modalWindow", false);
        },
        async getList(number) {
            this.loading = true;
            const res = await this.call_api(
                "get",
                `user/orders/downloads?page=${number}`
            );
            if (res.data.success) {
                this.products = res.data.data;
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

        async downloadProduct(product) {
            const fileUrl = product.file_path; // Replace with the actual file URL
            const link = document.createElement("a");
            link.href = fileUrl;
            link.download = product.name; // Replace with the desired file name
            link.click();
        },
    },

    created() {
        let page = this.$route.query.page || this.currentPage;
        this.getList(page);
    },
};
</script>
