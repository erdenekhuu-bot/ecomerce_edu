<template>
    <div class="logobar">
        <v-container class="">
            <div class="d-flex align-center">
                <div class="logo">
                    <router-link :to="{ name: 'Home' }" class="d-block">
                        <img :src="appLogo" :alt="appName" height="40" />
                    </router-link>
                </div>
                <div class="custom-width"></div>
                <div :class="['flex-grow-1 search-box', { open: openSearch }]">
                    <v-form
                        class="border rounded flex-grow-1"
                        style="font-family: 'OpenSans-Medium'"
                        @submit.stop.prevent="search()"
                    >
                        <v-row
                            align="center"
                            style="background: #f5f5f5; border-radius: 5px"
                            dense
                        >
                            <v-col
                                cols="1"
                                class="d-none d-md-block"
                                style="background: transparent"
                            >
                                <button
                                    block
                                    elevation="0"
                                    @click.stop.prevent="search()"
                                    class="custom-btn hide-button"
                                >
                                    <img
                                        src="/public/assets/img/focus.png"
                                        alt=""
                                    />
                                </button>
                            </v-col>
                            <v-col
                                cols="auto ms-1"
                                class="d-md-none"
                                style="background: #f5f5f5"
                            >
                                <v-btn
                                    icon
                                    @click.stop="toggleSearch(false)"
                                    style="box-shadow: none"
                                >
                                    <i
                                        class="las la-arrow-left fs-18 ts-05"
                                    ></i>
                                </v-btn>
                            </v-col>
                            <v-col class="opacity-70">
                                <input
                                    style="background: #f5f5f5; width: 100%"
                                    :placeholder="
                                        $t(
                                            'search_for_products_brands_and_more'
                                        )
                                    "
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

                    <div
                        class="bg-white shadow-lg position-absolute search_content_box"
                        v-if="showSuggestionContainer"
                    >
                        <div class="text-center py-4" v-if="loadingSuggestion">
                            <v-progress-circular
                                indeterminate
                                :width="3"
                                color="primary"
                            ></v-progress-circular>
                        </div>
                        <div v-else>
                            <div
                                v-if="suggestionNotFound"
                                class="text-center ma-8 fs-16"
                            >
                                {{ $t("sorry_nothing_found") }}
                            </div>
                            <div class="search_content" v-else>
                                <!-- Tags -->
                                <div class="" v-if="keywords.length">
                                    <div
                                        class="px-2 py-1 text-uppercase fs-10 text-right bg-grey-lighten-3"
                                    >
                                        {{ $t("popular_suggestions") }}
                                    </div>
                                    <ul class="list-unstyled px-5 py-2 fs-13">
                                        <li
                                            v-for="(keyword, i) in keywords"
                                            :key="i"
                                            class="py-1 text-capitalize"
                                            @click="popularSuggesation(keyword)"
                                        >
                                            {{ keyword }}
                                        </li>
                                    </ul>
                                </div>

                                <!-- Product Suggesations -->
                                <div class="" v-if="products.length">
                                    <div
                                        class="px-2 py-1 text-uppercase fs-10 text-right bg-grey-lighten-3"
                                    >
                                        {{ $t("products") }}
                                    </div>
                                    <ul class="list-unstyled px-5 py-2 fs-13">
                                        <li
                                            v-for="(product, i) in products"
                                            :key="i"
                                            class="py-1 d-flex align-center"
                                        >
                                            <img
                                                :src="product.thumbnail_image"
                                                :alt="product.name"
                                                @error="imageFallback($event)"
                                                class="img-fit size-50px"
                                            />
                                            <div class="ml-2">
                                                <h5
                                                    class="opacity-60 mb-1 fs-13"
                                                    @click="hideSearchContainer"
                                                >
                                                    <router-link
                                                        :to="{
                                                            name: 'ProductDetails',
                                                            params: {
                                                                slug: product.slug,
                                                            },
                                                        }"
                                                        class="text-reset"
                                                    >
                                                        {{ product.name }}
                                                    </router-link>
                                                </h5>
                                                <div class="order-2 fs-14 lh-1">
                                                    <template
                                                        v-if="
                                                            product.base_price >
                                                            product.base_discounted_price
                                                        "
                                                    >
                                                        <del
                                                            class="opacity-40"
                                                            >{{
                                                                format_price(
                                                                    product.base_price
                                                                )
                                                            }}</del
                                                        >
                                                        <span
                                                            class="fw-700 text-red"
                                                            >{{
                                                                format_price(
                                                                    product.base_discounted_price
                                                                )
                                                            }}</span
                                                        >
                                                    </template>
                                                    <template v-else>
                                                        <span
                                                            class="fw-700 text-red"
                                                            >{{
                                                                format_price(
                                                                    product.base_discounted_price
                                                                )
                                                            }}</span
                                                        >
                                                    </template>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <!-- category Suggesations -->
                                <div class="" v-if="categories.length">
                                    <div
                                        class="px-2 py-1 text-uppercase fs-10 text-right bg-grey-lighten-3"
                                    >
                                        {{ $t("category_suggestions") }}
                                    </div>
                                    <ul class="list-unstyled px-5 fs-13">
                                        <li
                                            v-for="(category, i) in categories"
                                            :key="i"
                                            class="py-1"
                                            @click="hideSearchContainer"
                                        >
                                            <router-link
                                                :to="{
                                                    name: 'Category',
                                                    params: {
                                                        categorySlug:
                                                            category.slug,
                                                    },
                                                }"
                                                class="text-reset text-capitalize"
                                                >{{
                                                    category.name
                                                }}</router-link
                                            >
                                        </li>
                                    </ul>
                                </div>

                                <!-- Brand Suggesations -->
                                <div class="" v-if="brands.length">
                                    <div
                                        class="px-2 py-1 text-uppercase fs-10 text-right bg-grey-lighten-3"
                                    >
                                        {{ $t("brands") }}
                                    </div>
                                    <ul class="list-unstyled px-5 fs-13">
                                        <li
                                            v-for="(brand, i) in brands"
                                            :key="i"
                                            class="py-1"
                                            @click="hideSearchContainer"
                                        >
                                            <router-link
                                                :to="{
                                                    name: 'Brand',
                                                    params: {
                                                        brandId: brand.id,
                                                    },
                                                }"
                                                class="text-reset text-capitalize"
                                                >{{ brand.name }}</router-link
                                            >
                                        </li>
                                    </ul>
                                </div>

                                <!-- Shop Suggesations -->
                                <div class="" v-if="shops.length">
                                    <div
                                        class="px-2 py-1 text-uppercase fs-10 text-right bg-grey-lighten-3"
                                    >
                                        {{ $t("Shops") }}
                                    </div>
                                    <ul class="list-unstyled px-5 py-2 fs-13">
                                        <li
                                            v-for="(shop, i) in shops"
                                            :key="i"
                                            class="py-1 d-flex align-center"
                                        >
                                            <img
                                                :src="shop.logo"
                                                :alt="shop.name"
                                                @error="imageFallback($event)"
                                                class="img-fit size-30px"
                                            />
                                            <div class="ml-3">
                                                <h5
                                                    class="opacity-60 mb-1 fs-13"
                                                    @click="hideSearchContainer"
                                                >
                                                    <router-link
                                                        :to="{
                                                            name: 'ShopDetails',
                                                            params: {
                                                                slug: shop.slug,
                                                            },
                                                        }"
                                                        class="text-reset"
                                                    >
                                                        {{ shop.name }}
                                                    </router-link>
                                                </h5>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <v-btn
                    style="height: 40px; width: 40px; min-width: 0 !important"
                    class="d-md-none border-gray-300 rounded-circle mx-auto mobile-search-button demo-print"
                    fab
                    variant="outlined"
                    small
                    @click.stop="toggleSearch(true)"
                    elevation="0"
                >
                    <i class="las la-search fs-18 ts-05"></i>
                </v-btn>
                <div class="custom-width"></div>
                <div class="d-none d-md-block" :style="{ fontSize: '13px' }">
                    <div class="d-flex align-center" v-if="!isAuthenticated">
                        <!-- <i class="las la-user fs-30 lh-1 me-3 opacity-70"></i> -->
                        <div class="mx-4">
                            <img
                                :src="
                                    static_asset(
                                        `/assets/img/icons/fi_user.svg`
                                    )
                                "
                                alt=""
                            />
                        </div>

                        <router-link
                            :to="{ name: 'Login' }"
                            class="text-reset opacity-80 fw-500"
                            >{{ $t("login") }}</router-link
                        >
                        <span class="mx-3 opacity-60">{{ $t("or") }}</span>
                        <router-link
                            :to="{ name: 'Registration' }"
                            class="text-reset opacity-80 fw-500"
                            >{{ $t("registration") }}</router-link
                        >
                    </div>

                    <!-- dashboard -->
                    <div
                        class="d-flex align-center"
                        :style="{ fontSize: '13px' }"
                        v-else
                    >
                        <!-- notification -->

                        <div
                            class="notification"
                            v-if="currentUser.user_type == 'customer'"
                        >
                            <i
                                class="las la-bell fs-30 lh-1 me-3 opacity-70"
                                id="menu-activator"
                                @click="fetNotification"
                            ></i>

                            <v-menu activator="#menu-activator">
                                <v-list>
                                    <v-list-item>
                                        <h2 class="text-center">
                                            {{ $t("notifications") }}
                                        </h2>
                                    </v-list-item>
                                    <v-divider class="mb-2"></v-divider>
                                    <div class="notifications-menu">
                                        <v-list-item
                                            v-for="(
                                                notification, index
                                            ) in notifications"
                                            :key="index"
                                            :value="index"
                                        >
                                            <v-list-item-title
                                                class="py-2 border-bottom"
                                            >
                                                <v-btn
                                                    @click="
                                                        openOrderDetails(
                                                            notification.data
                                                                .order_code
                                                        )
                                                    "
                                                    text
                                                    small
                                                    class="px-2"
                                                >
                                                    {{
                                                        notification.data
                                                            .order_code
                                                    }}
                                                    {{ $t(" has been ")
                                                    }}{{
                                                        notification.data.status
                                                    }}
                                                </v-btn>
                                            </v-list-item-title>
                                        </v-list-item>
                                    </div>
                                    <v-list-item
                                        class="text-center mt-2 border-top"
                                    >
                                        <router-link
                                            :to="{ name: 'Notification' }"
                                            class="text-reset fs-14"
                                        >
                                            <p class="text-center">
                                                {{
                                                    $t("view_all_notifications")
                                                }}
                                            </p>
                                        </router-link>
                                    </v-list-item>
                                </v-list>
                            </v-menu>
                        </div>
                        <!--  end of notification -->
                        <i class="las la-user fs-30 lh-1 me-3 opacity-70"></i>
                        <router-link
                            :to="{
                                name:
                                    currentUser.user_type == 'delivery_boy'
                                        ? 'DeliveryBoyDashboard'
                                        : 'DashBoard',
                            }"
                            class="text-reset opacity-80 fw-500"
                            >{{ $t("dashboard") }}</router-link
                        >
                        <span class="mx-3 opacity-60">{{ $t("or") }}</span>
                        <div
                            class="text-reset opacity-80 fw-500 c-pointer"
                            @click.stop="logout"
                        >
                            {{ $t("logout") }}
                        </div>
                    </div>
                </div>
            </div>
        </v-container>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
    props: {
        loading: { type: Boolean, required: true, default: true },
        data: {
            type: Object,
            default: {},
        },
    },
    data: () => ({
        openSearch: false,
        searchKeyword: "",

        loadingSuggestion: false,
        showSuggestionContainer: false,
        suggestionNotFound: false,
        keywords: [],
        categories: [],
        brands: [],
        products: [],
        shops: [],
        notifications: [],
    }),
    computed: {
        ...mapGetters("app", ["appLogo", "appName"]),
        ...mapGetters("auth", ["isAuthenticated", "currentUser"]),
        check() {
            return this.$store.state.app.checkWindow;
        },
    },
    methods: {
        ...mapActions(["auth/logout"]),
        ...mapActions("cart", ["resetCart"]),
        ...mapActions("wishlist", ["resetWishlist"]),

        openOrderDetails(orderCode) {
            this.$router.push({
                name: "OrderDetails",
                params: { code: orderCode },
            });
        },
        async fetNotification() {
            const res = await this.call_api("get", `user/notification`);
            if (res.data.success) {
                this.notifications = res.data.notifications;
            }
        },

        search() {
            this.showSuggestionContainer = false;
            this.$router
                .push({
                    name: "Search",
                    params:
                        this.searchKeyword.length > 0
                            ? { keyword: this.searchKeyword }
                            : {},
                    query: {
                        page: 1,
                    },
                })
                .catch(() => {});
        },

        hideSearchContainer() {
            this.showSuggestionContainer = false;
        },

        popularSuggesation(tag) {
            this.showSuggestionContainer = false;
            this.searchKeyword = tag;
            this.search();
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
                    this.suggestionNotFound = false;
                    this.loadingSuggestion = false;
                    this.keywords = res.data.keywords;
                    this.categories = res.data.categories;
                    this.brands = res.data.brands;
                    this.products = res.data.products.data;
                    this.shops = res.data.shops.data;
                } else {
                    this.loadingSuggestion = false;
                    this.suggestionNotFound = true;
                }
            }
        },

        async logout() {
            const res = await this.call_api("get", "auth/logout");
            this.$store.commit("auth/setCheckLogin", false);
            this["auth/logout"]();
            this.resetCart();
            this.resetWishlist();
            this.$router.push({ name: "Home" }).catch(() => {});
        },
        toggleSearch(status) {
            this.openSearch = status;
            this.$store.commit("app/getCheckWindow", false);
        },
        onClick: function (event) {
            let trigger = document.getElementsByClassName(
                ".search_content_box"
            );
            if (trigger !== event.target) {
                this.showSuggestionContainer = false;
            }
        },
        handleScroll(event) {
            return;
        },
    },
    mounted() {
        document.addEventListener("click", this.onClick);
        window.addEventListener("scroll", this.handleScroll);
    },
};
</script>
<style scoped>
.custom-btn {
    margin-left: 10px;
    margin-top: 5px;
    &:hover {
        background: inherit;
        color: inherit;
        border: inherit;
        cursor: default;
    }
}
.custom-width {
    width: 4%;
}
.logobar {
    /* margin: 10px 0; */
    /* min-height: 68px; */
    position: relative;
    z-index: 10;
}
.search_content_box {
    width: 100%;
}

@media (max-width: 768px) {
    .search_content_box {
        top: 60px;
    }
}
@media (max-width: 959px) {
    .search-box {
        position: absolute;
        width: calc(100% - 24px);
        padding: 9px 0;
        height: 100%;
        left: 12px;
        right: 12px;
        top: -100%;
        background: #fff;
        display: flex;
        align-items: center;
        z-index: 3;
        transition: top 0.3s;
        -webkit-transition: top 0.3s;
        background-color: #f5f5f5;
    }
    .search-box.open {
        top: 0px;
    }
}
@media (min-width: 960px) {
    .search-box {
        position: relative;
    }
}

.v-menu__content.theme-light.menuable__content__active {
    top: 100px !important;
    position: fixed;
    min-width: 350px !important;
}

.notifications-menu {
    min-height: 100px;
    max-height: 300px;
    overflow-y: auto;
}
</style>
