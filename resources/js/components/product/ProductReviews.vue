<template>
    <div class="product-reviews">
        <v-row justify="center" align="center" class="pt-8 pb-16">
            <v-col class="text-center" cols="12" md="6">
                <div class="text-h3 opacity-60">
                    {{ reviewSummary.average.toFixed(2) }}
                </div>
                <div>{{ $t("out_of") }} 5.00</div>
                <v-rating
                    class="lh-1-5"
                    color=""
                    empty-icon="las la-star grey-text"
                    full-icon="las la-star active"
                    half-icon="las la-star half"
                    hover
                    half-increments
                    readonly
                    size="15"
                    length="5"
                    :model-value="reviewSummary.average"
                >
                </v-rating>
                <div class="fs-12 opacity-70 mt-1">
                    {{ reviewSummary.total_count }} {{ $t("ratings") }}
                </div>
            </v-col>
            <v-col class="text-center border-md-start" cols="12" md="6">
                <div class="w-260px mx-auto">
                    <div class="d-flex align-center">
                        <v-rating
                            class="lh-1-5"
                            background-color=""
                            empty-icon="las la-star"
                            full-icon="las la-star active"
                            half-icon="las la-star half"
                            hover
                            half-increments
                            readonly
                            size="12"
                            length="5"
                            :model-value="5"
                        >
                        </v-rating>
                        <v-progress-linear
                            class="ms-5"
                            background-color="#EEEEEE"
                            color="#B8B8B8"
                            :model-value="
                                parseInt(
                                    (reviewSummary.count_5 * 100) /
                                        reviewSummary.total_count
                                )
                            "
                            height="6"
                            rounded
                        ></v-progress-linear>
                        <span class="w-60px text-right fs-12 opacity-60">{{
                            reviewSummary.count_5
                        }}</span>
                    </div>
                    <div class="d-flex align-center">
                        <v-rating
                            class="lh-1-5"
                            background-color=""
                            empty-icon="las la-star"
                            full-icon="las la-star active"
                            half-icon="las la-star half"
                            hover
                            half-increments
                            readonly
                            size="12"
                            length="5"
                            :model-value="4"
                        >
                        </v-rating>
                        <v-progress-linear
                            class="ms-5"
                            background-color="#EEEEEE"
                            color="#B8B8B8"
                            :model-value="
                                parseInt(
                                    (reviewSummary.count_4 * 100) /
                                        reviewSummary.total_count
                                )
                            "
                            height="6"
                            rounded
                        ></v-progress-linear>
                        <span class="w-60px text-right fs-12 opacity-60">{{
                            reviewSummary.count_4
                        }}</span>
                    </div>
                    <div class="d-flex align-center">
                        <v-rating
                            class="lh-1-5"
                            background-color=""
                            empty-icon="las la-star"
                            full-icon="las la-star active"
                            half-icon="las la-star half"
                            hover
                            half-increments
                            readonly
                            size="12"
                            length="5"
                            :model-value="3"
                        >
                        </v-rating>

                        <v-progress-linear
                            class="ms-5"
                            background-color="#EEEEEE"
                            color="#B8B8B8"
                            :model-value="
                                parseInt(
                                    (reviewSummary.count_3 * 100) /
                                        reviewSummary.total_count
                                )
                            "
                            height="6"
                            rounded
                        ></v-progress-linear>
                        <span class="w-60px text-right fs-12 opacity-60">{{
                            reviewSummary.count_3
                        }}</span>
                    </div>
                    <div class="d-flex align-center">
                        <v-rating
                            class="lh-1-5"
                            background-color=""
                            empty-icon="las la-star"
                            full-icon="las la-star active"
                            half-icon="las la-star half"
                            hover
                            half-increments
                            readonly
                            size="12"
                            length="5"
                            :model-value="2"
                        >
                        </v-rating>
                        <v-progress-linear
                            class="ms-5"
                            background-color="#EEEEEE"
                            color="#B8B8B8"
                            :model-value="
                                parseInt(
                                    (reviewSummary.count_2 * 100) /
                                        reviewSummary.total_count
                                )
                            "
                            height="6"
                            rounded
                        ></v-progress-linear>
                        <span class="w-60px text-right fs-12 opacity-60">{{
                            reviewSummary.count_2
                        }}</span>
                    </div>
                    <div class="d-flex align-center">
                        <v-rating
                            class="lh-1-5"
                            background-color=""
                            empty-icon="las la-star"
                            full-icon="las la-star active"
                            half-icon="las la-star half"
                            hover
                            half-increments
                            readonly
                            size="12"
                            length="5"
                            :model-value="1"
                        >
                        </v-rating>
                        <v-progress-linear
                            class="ms-5"
                            background-color="#EEEEEE"
                            color="#B8B8B8"
                            :model-value="
                                parseInt(
                                    (reviewSummary.count_1 * 100) /
                                        reviewSummary.total_count
                                )
                            "
                            height="6"
                            rounded
                        ></v-progress-linear>
                        <span class="w-60px text-right fs-12 opacity-60">{{
                            reviewSummary.count_1
                        }}</span>
                    </div>
                </div>
            </v-col>
        </v-row>
        <div>
            <div
                class="border-md-top border-bottom d-md-flex align-center mb-3"
            >
                <span class="fw-700 d-inline-block mb-4 mb-md-0">{{
                    $t("product_reviews")
                }}</span>
                <div
                    class="ms-auto d-flex justify-space-between border-top border-md-top-0"
                >
                    <span
                        class="border-md-start border-start-0 d-block flex-grow-1"
                    >
                        <v-select
                            v-model="sortingDefault"
                            :items="sortingOptions"
                            item-title="name"
                            item-value="value"
                            :menu-props="{ offsetY: true }"
                            flat
                            solo
                            hide-details
                            @update:modelValue="sortUpdate"
                        >
                            <template v-slot:selection="{ item }">
                                <span
                                    class="fs-13 d-flex align-center opacity-80"
                                >
                                    <i
                                        class="las la-exchange-alt la-rotate-90 fs-17"
                                    ></i>
                                    <span class="opacity-60 mx-1"
                                        >{{ $t("sort") }}:</span
                                    >
                                    <span>{{ item.title }}</span>
                                </span>
                            </template>
                        </v-select>
                    </span>
                    <span class="border-start d-block flex-grow-1">
                        <v-select
                            v-model="filterDefault"
                            :items="filterOptions"
                            item-title="name"
                            item-value="value"
                            :menu-props="{ offsetY: true }"
                            flat
                            solo
                            hide-details
                            @update:modelValue="filterUpdate"
                        >
                            <template v-slot:selection="{ item }">
                                <span class="fs-13 d-flex align-center">
                                    <i
                                        class="las la-filter fs-17 opacity-80"
                                    ></i>
                                    <span class="opacity-60 mx-1">Filter:</span>
                                    <span>{{ item.title }}</span>
                                </span>
                            </template>
                        </v-select>
                    </span>
                </div>
            </div>
            <div>
                <template v-if="reviews.length > 0">
                    <ProductSingleReview
                        v-for="(review, i) in reviews"
                        :id-loading="loading"
                        :review="review"
                        :key="i"
                    />
                </template>
                <template v-else>
                    <div class="text-center pa-4">
                        {{ $t("no_reviews_found_for_this_product") }}
                    </div>
                </template>
            </div>
            <div class="text-end" v-if="totalPages > 1">
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
import ProductSingleReview from "./ProductSingleReview.vue";
export default {
    props: {
        isLoading: { type: Boolean, default: true },
        id: { type: Number, default: null },
        reviewSummary: { type: Object, required: true, default: () => {} },
    },
    data: () => ({
        loading: true,
        sortingDefaultValue: "latest",
        filterDefaultValue: "all",
        totalPages: 1,
        currentPage: 1,
        reviews: [],
    }),
    components: {
        ProductSingleReview,
    },
    computed: {
        sortingDefault: {
            get() {
                return { name: this.$i18n.t("latest_first"), value: "latest" };
            },
            set(newVal) {},
        },
        sortingOptions() {
            return [
                { name: this.$i18n.t("latest_first"), value: "latest" },
                { name: this.$i18n.t("oldest_first"), value: "oldest" },
                {
                    name: this.$i18n.t("higher_rating_first"),
                    value: "higer_rating",
                },
                {
                    name: this.$i18n.t("lower_rating_first"),
                    value: "lower_rating",
                },
            ];
        },
        filterDefault: {
            get() {
                return { name: this.$i18n.t("all_star"), value: "all" };
            },
            set(newVal) {},
        },
        filterOptions() {
            return [
                { name: this.$i18n.t("all_star"), value: "all" },
                { name: "5 " + this.$i18n.t("star"), value: "5" },
                { name: "4 " + this.$i18n.t("star"), value: "4" },
                { name: "3 " + this.$i18n.t("star"), value: "3" },
                { name: "2 " + this.$i18n.t("star"), value: "2" },
                { name: "1 " + this.$i18n.t("star"), value: "1" },
            ];
        },
    },
    methods: {
        async getList(pageNumber = 1) {
            let sortBy = this.sortingDefaultValue;
            let filterBy = this.filterDefaultValue;
            if (this.id !== null) {
                const res = await this.call_api(
                    "get",
                    `product/reviews/${this.id}?page=${pageNumber}&sortby=${sortBy}&filterby=${filterBy}`
                );
                if (res.data.success) {
                    this.loading = false;
                    this.reviews = res.data.data;
                    this.totalPages = res.data.meta.last_page;
                }
            }
        },
        sortUpdate(sort) {
            this.sortingDefaultValue = sort;
            this.getList();
        },
        filterUpdate(filter) {
            this.filterDefaultValue = filter;
            this.getList();
        },
    },
    watch: {
        id: function () {
            this.getList();
        },
    },
};
</script>
