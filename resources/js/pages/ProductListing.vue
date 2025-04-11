<template>
    <div
        v-show="check"
        class="custom-window custom-hide"
        @click="globalClose"
    ></div>
    <div>
        <v-container class="pt-20 pb-8">
            <v-row v-if="products.length" :style="{fontFamily: 'OpenSans-Regular'}">
                <b class="px-2">{{ $t('home') }}</b> /
                <b class="px-2">{{ $t("all_categories") }}</b> /
                <div>
                    <b class="px-2" v-if="queryParam.keyword">
                        {{ $t("search_results_for") }} "{{
                            queryParam.keyword
                        }}"
                    </b>
                    <b class="px-2" v-else>
                        {{ currentCategory.name }}
                    </b>
                </div>
            </v-row>
        </v-container>

        <v-container class="pt-0">
            <v-row no-gutters align="start">
                <v-col cols="auto" class="w-lg-270px sticky-top">
                    <div
                        :class="[
                            'border-end filter-drawer ',
                            {
                                'open c-scrollbar overflow-y-auto':
                                    filterDrawerOpen,
                            },
                        ]"
                    >
                        <div
                            class="border-bottom pa-5 d-lg-none d-flex align-center"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="18"
                                height="18"
                                viewBox="0 0 18 18"
                            >
                                <path
                                    id="Path_2643"
                                    data-name="Path 2643"
                                    d="M20,5H18.829a3,3,0,0,0-5.659,0H4A1,1,0,0,0,4,7h9.171a3,3,0,0,0,5.659,0H20a1,1,0,0,0,0-2ZM16,7a1,1,0,1,0-1-1A1,1,0,0,0,16,7ZM3,12a1,1,0,0,1,1-1H5.171a3,3,0,0,1,5.659,0H20a1,1,0,0,1,0,2H10.829a3,3,0,0,1-5.659,0H4A1,1,0,0,1,3,12Zm5,1a1,1,0,1,0-1-1A1,1,0,0,0,8,13ZM4,17a1,1,0,0,0,0,2h9.171a3,3,0,0,0,5.659,0H20a1,1,0,0,0,0-2H18.829a3,3,0,0,0-5.659,0Zm13,1a1,1,0,1,1-1-1A1,1,0,0,1,17,18Z"
                                    transform="translate(-3 -3)"
                                    fill="#2a2e34"
                                    fill-rule="evenodd"
                                />
                            </svg>
                            <span class="ms-4 fw-600 fs-14 lh-1">{{
                                $t("filters")
                            }}</span>
                            <button
                                type="button"
                                @click.stop="
                                    toggleFilterDrawer(!filterDrawerOpen)
                                "
                                class="ms-4 fw-600 fs-20 lh-1 ms-auto"
                            >
                                <i class="la la-close fs-20"></i>
                            </button>
                        </div>

                        <div class="pa-5 custom-grayscale">
                            <!-- Angilal -->
                            <div class="mb-5 menu-background" :style="{fontFamily: 'OpenSans-Medium'}">
                                <h2 class="fw-700 fs-14 mb-4">
                                    {{ $t("categories") }}
                                </h2>
                                <div>
                                    <ul class="list-unstyled ps-0">
                                        <template
                                            v-if="is_empty_obj(currentCategory)"
                                        >
                                            <li
                                                v-for="(
                                                    category, i
                                                ) in rootCategories"
                                                :key="i"
                                                class="my-2"
                                            >
                                                <router-link
                                                    :to="{
                                                        name: 'Category',
                                                        params: {
                                                            categorySlug:
                                                                category.slug,
                                                        },
                                                    }"
                                                    class="text-reset fs-14"
                                                    >{{
                                                        category.name
                                                    }}</router-link
                                                >
                                            </li>
                                        </template>
                                        <template v-else>
                                            <li class="my-2">
                                                <router-link
                                                    :to="{ name: 'Shop' }"
                                                    class="text-reset fs-14"
                                                >
                                                    <i
                                                        class="las la-angle-left fs-12 me-1"
                                                    ></i>
                                                    <span>{{
                                                        $t("all_categories")
                                                    }}</span>
                                                </router-link>
                                            </li>
                                            <li
                                                class="my-2"
                                                v-if="
                                                    !is_empty_obj(
                                                        parentCategory
                                                    )
                                                "
                                            >
                                                <router-link
                                                    :to="{
                                                        name: 'Category',
                                                        params: {
                                                            categorySlug:
                                                                parentCategory.slug,
                                                        },
                                                    }"
                                                    class="text-reset fs-14"
                                                >
                                                    <i
                                                        class="las la-angle-left fs-12 me-1"
                                                    ></i>
                                                    <span>{{
                                                        parentCategory.name
                                                    }}</span>
                                                </router-link>
                                            </li>
                                            <li
                                                :class="[
                                                    'my-2',
                                                    {
                                                        'ms-5':
                                                            childCategories.length ==
                                                            0,
                                                    },
                                                ]"
                                            >
                                                <router-link
                                                    :to="{
                                                        name: 'Category',
                                                        params: {
                                                            categorySlug:
                                                                currentCategory.slug,
                                                        },
                                                    }"
                                                    class="text-reset fs-14 fw-600"
                                                >
                                                    <i
                                                        class="las la-angle-down fs-12 me-1"
                                                        v-if="
                                                            childCategories.length >
                                                            0
                                                        "
                                                    ></i>
                                                    <span>{{
                                                        currentCategory.name
                                                    }}</span>
                                                </router-link>
                                            </li>
                                            <li
                                                class="my-2 ms-5"
                                                v-for="(
                                                    category, i
                                                ) in childCategories"
                                                :key="i"
                                            >
                                                <router-link
                                                    :to="{
                                                        name: 'Category',
                                                        params: {
                                                            categorySlug:
                                                                category.slug,
                                                        },
                                                    }"
                                                    class="text-reset fs-14"
                                                >
                                                    <span>{{
                                                        category.name
                                                    }}</span>
                                                </router-link>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>

                            <!-- Status -->
                            <div
                                class="mb-4 pt-4 border-top menu-background"
                                v-if="!isBrandRoute" :style="{fontFamily: 'OpenSans-Medium'}"
                            >
                                <p class="d-flex" style="justify-content: space-between">
                                <h4 class="fw-700 fs-14 mb-3">{{ $t('status') }}</h4>
                                </p>
                                <v-checkbox
                                    true-icon="las la-check"
                                    class="mt-1"
                                    hide-details
                                    label="Шинээр"
                                    @update:modelValue="sortUpdate('latest')"
                                ></v-checkbox>
                                <v-checkbox
                                    true-icon="las la-check"
                                    class="mt-1"
                                    hide-details
                                    label="Хямдрал"
                                    @update:modelValue="discountValueChange(1)"
                                ></v-checkbox>
                            </div>

                            <!-- Brand -->
                            <div
                                class="mb-4 pt-4 border-top menu-background"
                                v-if="!isBrandRoute" :style="{fontFamily: 'OpenSans-Medium'}"
                            >
                                <p class="d-flex" style="justify-content: space-between;">
                                    <h4 class="fw-700 fs-14 mb-3">{{ $t('brand') }}</h4>
                                    <h4 class="hovering-box" @click="toggleHeight(i)">−</h4>
                                </p>

                                <v-form
                                    class="border rounded flex-grow-1"
                                    @submit.stop.prevent="search()"
                                >
                                    <v-row align="center">
                                        <v-col
                                            cols="1"
                                            class="d-none d-md-block"
                                            style="margin: 5px 10px"
                                        >
                                            <button
                                                block
                                                elevation="0"
                                                @click.stop.prevent="search()"
                                            >
                                                <img
                                                    src="/public/assets/img/focus.png"
                                                    alt=""
                                                    width="20"
                                                />
                                            </button>
                                        </v-col>
                                        <v-col>
                                            <input
                                                style="width: 100%"
                                                :placeholder="$t('search')"
                                                type="text"
                                                hide-details="auto"
                                                v-model="searchKeyword"
                                                @keyup="ajaxSearch"
                                                required
                                                variant="plain"
                                            />
                                        </v-col>
                                    </v-row>
                                </v-form>
                                <div v-if="allBrands.length" :style="{height: heighting[i] ? '112px' : 'auto', overflow: 'hidden'}">
                                    <v-checkbox
                                        true-icon="las la-check"
                                        class="mt-1 "
                                        hide-details
                                        v-for="(brand, i) in allBrands"
                                        :key="i"
                                        :label="brand.name"
                                        @update:modelValue="
                                            brandChange(brand.id)
                                        "
                                    ></v-checkbox>
                                </div>
                            </div>
                            <!-- attributes -->
                            <div
                                class="mb-4 pt-4 border-top menu-background"
                                v-for="(attribute, i) in attributes"
                                :key="i" :style="{fontFamily: 'OpenSans-Medium'}"
                            >
                                <p class="d-flex" style="justify-content: space-between;">
                                <h4 class="fw-700 fs-14 mb-3">
                                    {{ attribute.name }}
                                </h4>
                                <h4 class="hovering-box" @click="toggleHeight(i)">{{ heighting[i] ? '+' : '−' }}</h4>
                                </p>
                                <div v-if="attribute.values.data.length" :style="{height: heighting[i] ? '112px' : 'auto', overflow: 'hidden'}">
                                    <v-checkbox
                                        true-icon="las la-check"
                                        class="mt-1 "
                                        hide-details
                                        v-for="(value, j) in attribute.values
                                            .data"
                                        :key="j"
                                        :label="value.name"
                                        @update:modelValue="
                                            attributeValueChange(value.id)
                                        "
                                    ></v-checkbox>
                                </div>
                            </div>

                            <!-- Une -->
                            <div v-if="min_range != max_range && products.length > 0" class="mb-4 pt-4 border-top menu-background">
                                <h2 class="fw-700 fs-14 mb-3">
                                    {{ $t("price") }}
                                </h2>
                                <div class="d-flex align-center">
                                    <div class="col">
                                        <v-text-field
                                            variant="plain"
                                            type="number"
                                            class="form-control form-control-sm min-max-field"
                                            v-model="valueMulti[0]"
                                            :placeholder="'Эхлэх үнэ'"
                                            hide-details
                                        ></v-text-field>
                                    </div>
                                    <div class="col px-2">
                                        <v-text-field
                                            variant="plain"
                                            type="number"
                                            class="form-control form-control-sm min-max-field"
                                            v-model="valueMulti[1]"
                                            :placeholder="'Эцсийн үнэ'"
                                            hide-details
                                        ></v-text-field>
                                    </div>
                                </div>
                                <div style="padding-top: 10px">
                                    <v-range-slider
                                        v-model="valueMulti"
                                        :min="min_range"
                                        :max="max_range"
                                        @update:modelValue="filterByPriceArray"
                                        step="1"
                                    />
                                </div>
                                <p
                                    style="
                                        display: flex;
                                        justify-content: space-between;
                                    "
                                >
                                    <b> {{ parseInt(valueMulti[0]) + " ₮" }}</b>
                                    <b> {{ parseInt(valueMulti[1]) + " ₮" }}</b>
                                </p>
                                <!-- <div class="col col-auto">
                                    <v-btn
                                        size="small"
                                        fab
                                        type="submit"
                                        color="primary"
                                        class="rounded ms-2"
                                        elevation="0"
                                        @click.native="filterByPriceRange"
                                        >{{ $t("go") }}</v-btn
                                    >
                                </div> -->
                            </div>
                        </div>
                    </div>
                </v-col>
                <v-col>
                    <div class="pt-5 ps-lg-7">
                        <v-row align="end" class="mb-3">
                            <v-col cols="12" v-if="brand_meta_detail.additional && brand_meta_detail.additional.trim() !== ''">
                                <div :style="{ height: '350px'}">
                                    <img :src="static_asset(`/${brand_meta_detail.additional}`)" alt="" :style="{width: '100%', height: '100%', objectFit: 'cover'}">
                                </div>
                            </v-col>

                            <v-col cols="12" v-show="brand_meta_detail.thumnail != null">
                                <div :style="{ display: 'flex', alignItems: 'center', background: '#F5F5F5', padding: '10px'}">
                                <img :src="static_asset(`/${brand_meta_detail.thumnail}`)" alt="" width="56">
                                    <p class="px-2" :style="{fontFamily: 'OpenSans-Bold'}">{{ brand_meta_detail.title }}</p>
                                </div>
                                <p :style="{padding: '10px', fontFamily: 'OpenSans-Regular'}">{{ brand_meta_detail.description }}</p>
                            </v-col>
                            <v-col cols="12" sm>
                                <!-- Title of category -->
                                <div class="d-flex align-center">
                                    <div>
                                        <h1
                                            class="fs-18"
                                            v-if="queryParam.keyword"
                                        >
                                            {{ $t("search_results_for") }} "{{
                                                queryParam.keyword
                                            }}"
                                        </h1>
                                        <h1
                                            class="fs-18"
                                            v-else-if="
                                                !is_empty_obj(currentCategory)
                                            "
                                        >
                                            {{ currentCategory.name }}
                                        </h1>
                                        <h1 class="fs-18" v-else>
                                            {{ $t("all_products") }}
                                        </h1>
                                        <p class="opacity-60 mb-0 fs-12">
                                            {{
                                                $t("total") +
                                                " " +
                                                totalProducts +
                                                " " +
                                                $t("products_found")
                                            }}
                                        </p>
                                    </div>
                                    <div class="d-lg-none ms-auto ms-sm-0">
                                        <button
                                            class="ms-4 pa-2 border-gray-300 rounded border d-flex justify-center align-center"
                                            @click.stop="
                                                toggleFilterDrawer(
                                                    !filterDrawerOpen
                                                )
                                            "
                                            type="button"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                width="18"
                                                height="18"
                                                viewBox="0 0 18 18"
                                            >
                                                <path
                                                    id="Path_2643"
                                                    data-name="Path 2643"
                                                    d="M20,5H18.829a3,3,0,0,0-5.659,0H4A1,1,0,0,0,4,7h9.171a3,3,0,0,0,5.659,0H20a1,1,0,0,0,0-2ZM16,7a1,1,0,1,0-1-1A1,1,0,0,0,16,7ZM3,12a1,1,0,0,1,1-1H5.171a3,3,0,0,1,5.659,0H20a1,1,0,0,1,0,2H10.829a3,3,0,0,1-5.659,0H4A1,1,0,0,1,3,12Zm5,1a1,1,0,1,0-1-1A1,1,0,0,0,8,13ZM4,17a1,1,0,0,0,0,2h9.171a3,3,0,0,0,5.659,0H20a1,1,0,0,0,0-2H18.829a3,3,0,0,0-5.659,0Zm13,1a1,1,0,1,1-1-1A1,1,0,0,1,17,18Z"
                                                    transform="translate(-3 -3)"
                                                    fill="#2a2e34"
                                                    fill-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </v-col>
                            <v-col cols="12" sm="auto">
                                <v-select
                                    v-model="sortingDefault"
                                    :items="sortingOptions"
                                    item-title="name"
                                    item-value="value"
                                    :menu-props="{ offsetY: true }"
                                    append-inner-icon="las la-angle-down fs-14"
                                    density="compact"
                                    flat
                                    solo
                                    variant="outlined"
                                    hide-details
                                    @update:modelValue="sortUpdate"
                                >
                                    <template v-slot:selection="{ item }">
                                        <span
                                            class="fs-13 d-flex align-center opacity-80"
                                        >
                                            <span class="opacity-60 mx-1"
                                                >{{ $t("sort_by") }}:</span
                                            >
                                            <span>{{ item.title }}</span>
                                        </span>
                                    </template>
                                </v-select>
                            </v-col>
                        </v-row>
                        <!-- buteegdehuunuudiin jagsaalt -->
                        <div class="mb-7">
                            <v-row
                                class="row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-xl-5 md-gutters-10"
                                v-if="products.length > 0"
                            >
                                <v-col
                                    v-for="(product, i) in products"
                                    :key="i"
                                >
                                    <product-box
                                        :product-details="product"
                                        :is-loading="loading"
                                    />
                                </v-col>
                            </v-row>
                            <div class="pa-4 text-center fs-20" v-else>
                                {{ $t("no_product_found") }}
                            </div>
                        </div>
                        <div class="text-center" v-if="totalPages > 1">
                            <v-pagination
                                v-model="queryParam.page"
                                class="my-4"
                                :length="totalPages"
                                prev-icon="las la-angle-left"
                                next-icon="las la-angle-right"
                                :total-visible="7"
                                elevation="0"
                                @update:modelValue="pageSwitch"
                            ></v-pagination>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
import ShowMore from "./../components/inc/ShowMore.vue";
import Menulist from "../components/menu/Menulist.vue";
import { useHead } from "@unhead/vue";
export default {
    mounted() {
        this.filterByPriceArray(this.valueMulti);
    },
    head: {
        title: "Product Listing Page",
    },
    data: () => ({
        isDiscounted: false,
        metaTitle: "",
        metaDescription: "",
        loading: true,
        filterDrawerOpen: false,
        totalProducts: 0,
        totalPages: 1,
        isBrandRoute: false,
        valueMulti: [0, 100],
        min_range: 0,
        max_range: 0,
        filtered: [],
        filterProduct: [],
        brand_meta_detail: {
            title: '',
            description: '',
            thumnail: null,
            additional: null,
        },
        queryParam: {
            page: 1,
            categorySlug: null,
            brandIds: [],
            attributeValues: [],
            keyword: null,
            sortBy: "popular",
            minPrice: 0,
            maxPrice: 0,
            discount: [],
        },
        attributes: [],
        allBrands: [],
        rootCategories: [],
        parentCategory: {},
        currentCategory: {},
        childCategories: [],
        products: [
            {},
            {},
            {},
            {},
            {},
            {},
            {},
            {},
            {},
            {},
            {},
            {},
            {},
            {},
            {},
            {},
            {},
            {},
            {},
        ],
        sortingDefault: {},
        heighting: [],
    }),
    components: {
        ShowMore,
        Menulist,
    },
    computed: {
         check() {
            return this.$store.state.auth.modalWindow;
        },
        sortingOptions() {
            return [
                { name: this.$i18n.t("most_popular"), value: "popular" },
                { name: this.$i18n.t("latest_first"), value: "latest" },
                { name: this.$i18n.t("oldest_first"), value: "oldest" },
                {
                    name: this.$i18n.t("higher_price_first"),
                    value: "highest_price",
                },
                {
                    name: this.$i18n.t("lower_price_first"),
                    value: "lowest_price",
                },
            ];
        },
    },
    watch: {
        metaTitle(newTitle) {
            this.updateHead(newTitle, this.metaDescription);
        },
        metaDescription(newDescription) {
            this.updateHead(this.metaTitle, newDescription);
        },
        valueMulti(newRange) {
            this.filterByPriceArray(newRange);
        },
    },

    methods: {
        globalClose() {
            this.$store.commit("auth/modalWindow", false);
        },
        toggleHeight(index) {
            this.heighting[index] = !this.heighting[index];
        },
        async ajaxSearch(event) {
            this.loadingSuggestion = true;
            this.showSuggestionContainer = false;
            const searchKey = event.target.value;

            if (searchKey.length > 0) {
                this.showSuggestionContainer = true;
                const res = await this.call_api(
                    "get",
                    `search.ajax/${searchKey}`
                );

                if (res.data.success) {
                    this.allBrands = res.data.brands;
                }
            } else {
                this.allBrands = this.filtered;
            }
        },
        updateHead(title, description) {
            useHead({
                title: title,

                meta: [{ name: "description", content: description }],
            });
        },

        pageSwitch(pageNumber) {
            this.$router
                .push({
                    query: {
                        ...this.$route.query,
                        page: this.queryParam.page,
                    },
                })
                .catch(() => {});

            this.getList({
                page: pageNumber,
            });
        },
        sortUpdate(sort) {
            this.queryParam.sortBy = sort;

            this.$router
                .push({
                    query: {
                        ...this.$route.query,
                        sortBy: this.queryParam.sortBy,
                    },
                })
                .catch(() => {});

            this.getList({
                sortBy: sort,
            });

            if (this.queryParam.sortBy !== "popular") {
                let selectedSort = this.sortingOptions.find(
                    (sort) => sort.value === this.queryParam.sortBy
                );
                this.sortingDefault = selectedSort;
            }
        },
        brandChange(id) {
            if (this.queryParam.brandIds.indexOf(id) > -1) {
                let index = this.queryParam.brandIds.indexOf(id);
                this.queryParam.brandIds.splice(index, 1);
            } else {
                this.queryParam.brandIds.push(id);
            }

            this.$router
                .push({
                    query: {
                        ...this.$route.query,
                        brandIds: this.queryParam.brandIds,
                    },
                })
                .catch(() => {});
            this.getList({});
        },
        attributeValueChange(id) {
            if (this.queryParam.attributeValues.indexOf(id) > -1) {
                let index = this.queryParam.attributeValues.indexOf(id);
                this.queryParam.attributeValues.splice(index, 1);
            } else {
                this.queryParam.attributeValues.push(id);
            }

            this.$router
                .push({
                    query: {
                        ...this.$route.query,
                        attributeValues: this.queryParam.attributeValues,
                    },
                })
                .catch(() => {});

            this.getList({});
        },

        discountValueChange(id) {
            if (this.queryParam.discount.indexOf(id) > -1) {
                let index = this.queryParam.discount.indexOf(id);
                this.queryParam.discount.splice(index, 1);
            } else {
                this.queryParam.discount.push(id);
            }
            this.$router
                .push({
                    query: {
                        ...this.$route.query,
                        discount: this.queryParam.discount,
                    },
                })
                .catch(() => {});

            this.getList({});
        },

        defaultValue() {
            this.getList({});
        },

        filterByPriceRange() {
            this.queryParam.minPrice = parseInt(this.valueMulti[0]);
            this.queryParam.maxPrice = parseInt(this.valueMulti[1]);
            let priceRange = {};
            priceRange.minPrice = this.queryParam.minPrice;
            priceRange.maxPrice = this.queryParam.maxPrice;
            this.$router
                .push({
                    query: {
                        ...this.$route.query,
                        ...priceRange,
                    },
                })
                .catch(() => {
                    return;
                });
            this.getList({});
        },
        filterByPriceArray([minPrice, maxPrice]) {
            this.products = this.filterProduct.filter((items) => {
                return (
                    items.base_price >= parseInt(minPrice) &&
                    parseInt(maxPrice) <= items.base_price
                );
            });
        },
        toggleFilterDrawer(status) {
            this.filterDrawerOpen = status;
        },
        async getList(obj) {
            this.loading = true;
            let params = { ...this.queryParam, ...obj };
            let url = "product/search?";
            url += `&page=${this.queryParam.page}`;
            url += params.categorySlug
                ? `&category_slug=${params.categorySlug}`
                : "";
            url += params.brandIds ? `&brand_ids=${params.brandIds}` : "";
            url += params.attributeValues
                ? `&attribute_values=${params.attributeValues}`
                : "";
            url += params.discount ? `&discount=${params.discount}` : "";
            url += params.keyword ? `&keyword=${params.keyword}` : "";
            url += params.sortBy ? `&sort_by=${params.sortBy}` : "";
            url += params.minPrice > 0 ? `&min_price=${params.minPrice}` : "";
            url += params.maxPrice > 0 ? `&max_price=${params.maxPrice}` : "";
            const res = await this.call_api("get", url);

            if (res.data.success) {
                this.min_range = Math.min(
                    ...res.data.products.data.map((item) => item.base_price)
                );
                this.max_range = Math.max(
                    ...res.data.products.data.map((item) => item.base_price)
                );
                this.filtered = res.data.allBrands.data;
                this.filterProduct = res.data.products.data;
                this.loading = false;
                this.metaTitle = res.data.metaTitle;
                this.products = res.data.products.data;
                this.attributes = res.data.attributes.data;
                this.allBrands = res.data.allBrands.data;
                this.brand_meta_detail.title = res.data.brandMetaTitle?.name;
                this.brand_meta_detail.description = res.data.brandMetaTitle?.meta_description;
                this.brand_meta_detail.thumnail = res.data.brandMetaThumbnail;
                this.brand_meta_detail.additional = res.data.brandMetaAdditional;
                this.rootCategories = res.data.rootCategories.data;
                this.parentCategory = res.data.parentCategory
                    ? res.data.parentCategory
                    : {};
                this.currentCategory = res.data.currentCategory
                    ? res.data.currentCategory
                    : {};
                this.childCategories = res.data.childCategories
                    ? res.data.childCategories.data
                    : [];
                this.totalPages = res.data.totalPage;
                this.totalProducts = res.data.total;
                this.queryParam.page = res.data.currentPage;
            }
        },
    },
    created() {
        this.isBrandRoute = this.$route.params.brandId
            ? true
            : this.isBrandRoute;
        this.queryParam.categorySlug =
            this.$route.params.categorySlug || this.queryParam.categorySlug;
        this.queryParam.keyword = this.$route.params.keyword;
        this.queryParam.brandIds =
            this.$route.params.brandId || this.queryParam.brandIds;

        this.queryParam.page = this.$route.query.page || this.queryParam.page;
        this.queryParam.sortBy =
            this.$route.query.sortBy || this.queryParam.sortBy;
        this.queryParam.minPrice =
            this.$route.query.minPrice || this.queryParam.minPrice;
        this.queryParam.maxPrice =
            this.$route.query.maxPrice || this.queryParam.maxPrice;
        this.queryParam.attributeValues =
            this.$route.query.attributeValues ||
            this.queryParam.attributeValues;
        this.sortingDefault = {
            name: this.$i18n.t("most_popular"),
            value: "popular",
        };

        this.getList({
            page: this.queryParam.page,
            categorySlug: this.queryParam.categorySlug,
            brandIds: this.queryParam.brandIds,
            attributeValues: this.queryParam.attributeValues,
            keyword: this.queryParam.keyword,
            sortBy: this.queryParam.sortBy,
            minPrice: this.queryParam.minPrice,
            maxPrice: this.queryParam.maxPrice,
            discount: this.queryParam.discount,
        });
    },
};
</script>
<style scoped>
.hovering-box {
    &:hover {
        cursor: pointer;
    }
}
@media (max-width: 1263px) {
    .sticky-top {
        position: static;
    }
    .filter-drawer {
        position: fixed;
        width: 350px;
        max-width: 100vw;
        height: 100vh;
        visibility: hidden;
        right: -350px;
        top: 0;
        bottom: 0;
        background: #fff;
        z-index: 1610;
        box-shadow: 0 0 50px rgb(0 0 0 / 16%);
        transition: all 0.3s;
        -webkit-transition: all 0.3s;
        width: 100%;
        right: 0;
        left: 0;
        height: 100vh;
    }
    .filter-drawer.open {
        right: 0;
        visibility: visible;
    }
}
@media (min-width: 1264px) {
    .w-lg-270px {
        width: 270px;
    }
}
.pt-20 {
    padding-top: 2rem;
}
.px-2 {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
}
.custom-grayscale {
    background-color: #f5f5f5;
}
.menu-background {
    background-color: #ffffff;
    padding: 0.75rem;
    &::first-letter {
        text-transform: uppercase;
    }
}

.expand-fade-enter-active, .expand-fade-leave-active {
    transition: max-height 0.5s ease-in-out;
    overflow: hidden;
}

.expand-fade-enter, .expand-fade-leave-to{
    max-height: 0;
}

.expand-fade-enter-to, .expand-fade-leave {
    max-height: 2000px;
}

</style>
