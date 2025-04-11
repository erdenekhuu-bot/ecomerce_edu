<template>
    <div
        v-show="check"
        class="custom-window custom-hide"
        @click="globalClose"
    ></div>
    <div class="hide-page">
        <PrintPage :data="footer_data" :productProps="productDetails" />
    </div>
    <div style="overflow-x: hidden" class="hide-scrollbar demo-print">
        <v-container
            class="pt-8 custom-hide"
            :style="{ fontFamily: 'OpenSans-Regular' }"
        >
            <v-row class="d-flex" :style="{ fontSize: '12px' }">
                <v-col>
                    <span v-for="item in productDetails.category">
                        <b class="px-2 fw-500">{{ item.name }}</b>
                        /
                    </span>
                    <b class="px-2 fw-500">{{ productDetails.name }}</b>
                </v-col>
                <div
                    class="nav-buttons mx-2"
                    :style="{
                        fontFamily: 'OpenSans-SemiBold',
                    }"
                    @click="triggerGlobal(0)"
                >
                    <img
                        :src="static_asset('/assets/img/icons/attach.svg')"
                        class="px-2"
                    />
                    <span class="opacity-70">{{ $t("copy_link") }}</span>
                </div>
                <div
                    class="nav-buttons mx-2"
                    :style="{
                        fontFamily: 'OpenSans-SemiBold',
                    }"
                >
                    <a
                        href="https://gmail.com"
                        target="_blank"
                        class="d-flex align-items-center text-black"
                    >
                        <img
                            :src="static_asset('/assets/img/icons/mail.svg')"
                            class="px-2"
                        />
                        <span class="opacity-70">{{ $t("email") }}</span>
                    </a>
                </div>
                <div
                    class="nav-buttons mx-2"
                    :style="{
                        fontFamily: 'OpenSans-SemiBold',
                    }"
                    @click="triggerGlobal(2)"
                >
                    <img
                        :src="static_asset('/assets/img/icons/print.svg')"
                        class="px-2"
                    />
                    <span class="opacity-70"> {{ $t("print") }}</span>
                </div>
            </v-row>
        </v-container>
        <v-container class="pt-7">
            <v-row align="start">
                <v-col lg="10" cols="12" class="main-bar">
                    <add-to-cart
                        class="mb-10"
                        :is-loading="detailsLoading"
                        :product-details="productDetails"
                    />
                    <div cols="12" class="mb-6">
                        <v-expansion-panels
                            v-model="panel"
                            class="product-details-expansion"
                            flat
                            multiple
                        >
                            <v-expansion-panel class="mb-3">
                                <v-expansion-panel-title
                                    collapse-icon="las la-arrow-circle-right"
                                    expand-icon="las la-arrow-circle-right"
                                >
                                    <div class="d-flex align-center">
                                        <!-- <img
                                            :src="
                                                static_asset(
                                                    `/assets/img/icons/document-text.svg`
                                                )
                                            "
                                        /> -->
                                        <svg
                                            id="_004-product-description"
                                            data-name="004-product-description"
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="20"
                                            height="20"
                                            viewBox="0 0 20 20"
                                        >
                                            <g
                                                id="Group_1264"
                                                data-name="Group 1264"
                                            >
                                                <path
                                                    id="Path_3166"
                                                    data-name="Path 3166"
                                                    d="M18.75,5H1.25A1.252,1.252,0,0,1,0,3.75V1.25A1.252,1.252,0,0,1,1.25,0h17.5A1.252,1.252,0,0,1,20,1.25v2.5A1.252,1.252,0,0,1,18.75,5ZM1.25.833a.417.417,0,0,0-.417.417v2.5a.417.417,0,0,0,.417.417h17.5a.417.417,0,0,0,.417-.417V1.25A.417.417,0,0,0,18.75.833Z"
                                                    fill="#f5a100"
                                                />
                                            </g>
                                            <g
                                                id="Group_1265"
                                                data-name="Group 1265"
                                                transform="translate(15 7.5)"
                                            >
                                                <path
                                                    id="Path_3167"
                                                    data-name="Path 3167"
                                                    d="M20.5,14A2.5,2.5,0,1,1,23,11.5,2.5,2.5,0,0,1,20.5,14Zm0-4.167A1.667,1.667,0,1,0,22.167,11.5,1.668,1.668,0,0,0,20.5,9.833Z"
                                                    transform="translate(-18 -9)"
                                                    fill="#f5a100"
                                                />
                                            </g>
                                            <g
                                                id="Group_1266"
                                                data-name="Group 1266"
                                                transform="translate(15 15)"
                                            >
                                                <path
                                                    id="Path_3168"
                                                    data-name="Path 3168"
                                                    d="M20.5,23A2.5,2.5,0,1,1,23,20.5,2.5,2.5,0,0,1,20.5,23Zm0-4.167A1.667,1.667,0,1,0,22.167,20.5,1.668,1.668,0,0,0,20.5,18.833Z"
                                                    transform="translate(-18 -18)"
                                                    fill="#f5a100"
                                                />
                                            </g>
                                            <g
                                                id="Group_1267"
                                                data-name="Group 1267"
                                                transform="translate(0 8.333)"
                                            >
                                                <path
                                                    id="Path_3169"
                                                    data-name="Path 3169"
                                                    d="M12.917,10.833H.417a.417.417,0,1,1,0-.833h12.5a.417.417,0,1,1,0,.833Z"
                                                    transform="translate(0 -10)"
                                                    fill="#f5a100"
                                                />
                                            </g>
                                            <g
                                                id="Group_1268"
                                                data-name="Group 1268"
                                                transform="translate(0 10.833)"
                                            >
                                                <path
                                                    id="Path_3170"
                                                    data-name="Path 3170"
                                                    d="M12.917,13.833H.417a.417.417,0,1,1,0-.833h12.5a.417.417,0,1,1,0,.833Z"
                                                    transform="translate(0 -13)"
                                                    fill="#f5a100"
                                                />
                                            </g>
                                            <g
                                                id="Group_1269"
                                                data-name="Group 1269"
                                                transform="translate(0 15.833)"
                                            >
                                                <path
                                                    id="Path_3171"
                                                    data-name="Path 3171"
                                                    d="M12.917,19.833H.417a.417.417,0,1,1,0-.833h12.5a.417.417,0,1,1,0,.833Z"
                                                    transform="translate(0 -19)"
                                                    fill="#f5a100"
                                                />
                                            </g>
                                            <g
                                                id="Group_1270"
                                                data-name="Group 1270"
                                                transform="translate(0 18.333)"
                                            >
                                                <path
                                                    id="Path_3172"
                                                    data-name="Path 3172"
                                                    d="M12.917,22.833H.417a.417.417,0,0,1,0-.833h12.5a.417.417,0,1,1,0,.833Z"
                                                    transform="translate(0 -22)"
                                                    fill="#f5a100"
                                                />
                                            </g>
                                        </svg>

                                        <h1
                                            class="fs-16 ms-5"
                                            style="font-family: 'OpenSans-Bold'"
                                        >
                                            {{ $t("description") }}
                                        </h1>
                                    </div>
                                </v-expansion-panel-title>
                                <v-expansion-panel-text>
                                    <div
                                        v-html="productDetails.description"
                                    ></div>
                                </v-expansion-panel-text>
                            </v-expansion-panel>
                            <v-expansion-panel class="mb-3">
                                <v-expansion-panel-title
                                    collapse-icon="las la-arrow-circle-right"
                                    expand-icon="las la-arrow-circle-right"
                                >
                                    <div class="d-flex align-center">
                                        <!-- <img
                                            :src="
                                                static_asset(
                                                    `/assets/img/icons/document-text.svg`
                                                )
                                            "
                                        /> -->
                                        <svg
                                            id="_004-product-description"
                                            data-name="004-product-description"
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="20"
                                            height="20"
                                            viewBox="0 0 20 20"
                                        >
                                            <g
                                                id="Group_1264"
                                                data-name="Group 1264"
                                            >
                                                <path
                                                    id="Path_3166"
                                                    data-name="Path 3166"
                                                    d="M18.75,5H1.25A1.252,1.252,0,0,1,0,3.75V1.25A1.252,1.252,0,0,1,1.25,0h17.5A1.252,1.252,0,0,1,20,1.25v2.5A1.252,1.252,0,0,1,18.75,5ZM1.25.833a.417.417,0,0,0-.417.417v2.5a.417.417,0,0,0,.417.417h17.5a.417.417,0,0,0,.417-.417V1.25A.417.417,0,0,0,18.75.833Z"
                                                    fill="#f5a100"
                                                />
                                            </g>
                                            <g
                                                id="Group_1265"
                                                data-name="Group 1265"
                                                transform="translate(15 7.5)"
                                            >
                                                <path
                                                    id="Path_3167"
                                                    data-name="Path 3167"
                                                    d="M20.5,14A2.5,2.5,0,1,1,23,11.5,2.5,2.5,0,0,1,20.5,14Zm0-4.167A1.667,1.667,0,1,0,22.167,11.5,1.668,1.668,0,0,0,20.5,9.833Z"
                                                    transform="translate(-18 -9)"
                                                    fill="#f5a100"
                                                />
                                            </g>
                                            <g
                                                id="Group_1266"
                                                data-name="Group 1266"
                                                transform="translate(15 15)"
                                            >
                                                <path
                                                    id="Path_3168"
                                                    data-name="Path 3168"
                                                    d="M20.5,23A2.5,2.5,0,1,1,23,20.5,2.5,2.5,0,0,1,20.5,23Zm0-4.167A1.667,1.667,0,1,0,22.167,20.5,1.668,1.668,0,0,0,20.5,18.833Z"
                                                    transform="translate(-18 -18)"
                                                    fill="#f5a100"
                                                />
                                            </g>
                                            <g
                                                id="Group_1267"
                                                data-name="Group 1267"
                                                transform="translate(0 8.333)"
                                            >
                                                <path
                                                    id="Path_3169"
                                                    data-name="Path 3169"
                                                    d="M12.917,10.833H.417a.417.417,0,1,1,0-.833h12.5a.417.417,0,1,1,0,.833Z"
                                                    transform="translate(0 -10)"
                                                    fill="#f5a100"
                                                />
                                            </g>
                                            <g
                                                id="Group_1268"
                                                data-name="Group 1268"
                                                transform="translate(0 10.833)"
                                            >
                                                <path
                                                    id="Path_3170"
                                                    data-name="Path 3170"
                                                    d="M12.917,13.833H.417a.417.417,0,1,1,0-.833h12.5a.417.417,0,1,1,0,.833Z"
                                                    transform="translate(0 -13)"
                                                    fill="#f5a100"
                                                />
                                            </g>
                                            <g
                                                id="Group_1269"
                                                data-name="Group 1269"
                                                transform="translate(0 15.833)"
                                            >
                                                <path
                                                    id="Path_3171"
                                                    data-name="Path 3171"
                                                    d="M12.917,19.833H.417a.417.417,0,1,1,0-.833h12.5a.417.417,0,1,1,0,.833Z"
                                                    transform="translate(0 -19)"
                                                    fill="#f5a100"
                                                />
                                            </g>
                                            <g
                                                id="Group_1270"
                                                data-name="Group 1270"
                                                transform="translate(0 18.333)"
                                            >
                                                <path
                                                    id="Path_3172"
                                                    data-name="Path 3172"
                                                    d="M12.917,22.833H.417a.417.417,0,0,1,0-.833h12.5a.417.417,0,1,1,0,.833Z"
                                                    transform="translate(0 -22)"
                                                    fill="#f5a100"
                                                />
                                            </g>
                                        </svg>

                                        <h1
                                            class="fs-16 ms-5"
                                            style="font-family: 'OpenSans-Bold'"
                                        >
                                            {{ $t("rating__reviews") }}
                                        </h1>
                                    </div>
                                </v-expansion-panel-title>
                                <v-expansion-panel-text class="">
                                    <ProductReviews
                                        :id="productDetails.id"
                                        :is-loading="detailsLoading"
                                        :review-summary="reviewSummary"
                                    />
                                </v-expansion-panel-text>
                            </v-expansion-panel>
                        </v-expansion-panels>
                    </div>
                    <div v-if="boughtTogetherProducts.length > 0" class="mb-5">
                        <h2 class="mb-3 fs-21 opacity-80">
                            {{ $t("frequently_bought_together") }}
                        </h2>
                        <swiper
                            :slides-per-view="carouselOption.slidesPerView"
                            :space-between="carouselOption.spaceBetween"
                            :breakpoints="carouselOption.breakpoints"
                        >
                            <swiper-slide
                                v-for="(product, i) in boughtTogetherProducts"
                                :key="i"
                                class=""
                            >
                                <product-box
                                    :product-details="product"
                                    :is-loading="togetherLoading"
                                />
                            </swiper-slide>
                        </swiper>
                    </div>
                    <div class="mb-5">
                        <h2 class="mb-3 fs-21 opacity-80">
                            {{ $t("more_items_to_explore") }}
                        </h2>
                        <swiper
                            :slides-per-view="carouselOption.slidesPerView"
                            :space-between="carouselOption.spaceBetween"
                            :breakpoints="carouselOption.breakpoints"
                        >
                            <swiper-slide
                                v-for="(product, i) in moreProducts"
                                :key="i"
                                class=""
                            >
                                <product-box
                                    :product-details="product"
                                    :is-loading="moreLoading"
                                />
                            </swiper-slide>
                        </swiper>
                    </div>
                </v-col>
                <v-col lg="2" cols="12" class="sticky-top right-bar">
                    <v-row>
                        <v-col lg="12" md="3" sm="4" cols="12">
                            <template v-if="is_addon_activated('multi_vendor')">
                                <template v-if="detailsLoading">
                                    <v-skeleton-loader
                                        type="image"
                                        height="158"
                                        class="mb-3"
                                    />
                                </template>
                                <div
                                    v-else
                                    class="border rounded px-3 py-2 mb-3 border-gray-300 bg-grey-lighten-5"
                                >
                                    <div
                                        class="fw-700 fs-12 mb-2"
                                        :style="{ fontFamily: 'OpenSans-Bold' }"
                                    >
                                        {{ $t("shop") }}
                                    </div>
                                    <div class="d-flex mb-2">
                                        <img
                                            :src="productDetails.shop.logo"
                                            :alt="productDetails.shop.name"
                                            class="flex-shrink-0 border rounded-circle shadow size-50px mb-2"
                                        />
                                        <div
                                            class="fs-13 fw-500 ms-3 mt-2 text-truncate-2 minw-0"
                                            :style="{
                                                fontFamily: 'OpenSans-Bold',
                                            }"
                                        >
                                            {{ productDetails.shop.name }}
                                            <span
                                                class="ml-2"
                                                v-if="
                                                    productDetails.shop
                                                        .isVarified
                                                "
                                            >
                                            </span>
                                        </div>
                                    </div>
                                    <div
                                        class="fs-12 mb-2 opacity-60 fw-600"
                                        :style="{
                                            fontFamily: 'OpenSans-SemiBold',
                                            display: 'flex',
                                        }"
                                    >
                                        <span class="">{{
                                            productDetails.shop.rating.toFixed(
                                                2
                                            )
                                        }}</span>
                                        <v-rating
                                            class="mx-1"
                                            background-color=""
                                            empty-icon="las la-star"
                                            full-icon="las la-star active"
                                            half-icon="las la-star half"
                                            hover
                                            half-increments
                                            readonly
                                            size="x-small"
                                            length="5"
                                            :model-value="
                                                productDetails.shop.rating
                                            "
                                        >
                                        </v-rating>
                                    </div>
                                    <router-link
                                        :to="{
                                            name: 'ShopDetails',
                                            params: {
                                                slug: productDetails.shop.slug,
                                            },
                                        }"
                                        class="fw-500 fs-11"
                                        style="color: black"
                                    >
                                        {{ $t("visit_store") }}
                                    </router-link>
                                </div>
                            </template>
                            <template v-if="detailsLoading">
                                <v-skeleton-loader
                                    type="image"
                                    height="200"
                                    class=""
                                />
                            </template>
                            <template v-else>
                                <banner
                                    :loading="false"
                                    :banner="
                                        $store.getters['app/banners']
                                            .product_page
                                    "
                                    class=""
                                />
                            </template>

                            <div
                                v-if="productDetails.has_warranty == 1"
                                class="bg-soft-primary border border-primary d-flex rounded py-3 mt-3"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="16.667"
                                    height="20"
                                    viewBox="0 0 16.667 20"
                                >
                                    <g
                                        id="Policy"
                                        transform="translate(-8.752 -4.644)"
                                    >
                                        <path
                                            id="Path_3102"
                                            data-name="Path 3102"
                                            d="M17.085,24.644a.7.7,0,0,1-.211-.033,17.254,17.254,0,0,1-6.395-4.9C8.608,17.09,8.647,11.575,8.841,7.654a.368.368,0,0,1,.133-.267.353.353,0,0,1,.28-.077,9.543,9.543,0,0,0,7.578-2.558.354.354,0,0,1,.509,0,9.507,9.507,0,0,0,7.578,2.558.353.353,0,0,1,.28.077.368.368,0,0,1,.133.267c.194,3.921.233,9.436-1.638,12.06a17.431,17.431,0,0,1-6.395,4.9A.7.7,0,0,1,17.085,24.644ZM9.558,8.063c-.172,3.779-.161,8.855,1.516,11.2A16.654,16.654,0,0,0,17.1,23.9a16.743,16.743,0,0,0,6.029-4.634c1.66-2.339,1.67-7.415,1.5-11.2A9.979,9.979,0,0,1,17.1,5.505,10,10,0,0,1,9.558,8.063Z"
                                            fill="#b8b8b8"
                                        />
                                        <path
                                            id="Path_3103"
                                            data-name="Path 3103"
                                            d="M25.523,30.137a.358.358,0,0,1-.258-.111L23.387,28.1a.359.359,0,1,1,.513-.5l1.613,1.652,3.405-3.724a.359.359,0,0,1,.531.484l-3.66,4a.376.376,0,0,1-.258.118Z"
                                            transform="translate(-9.323 -13.133)"
                                            fill="#b8b8b8"
                                        />
                                    </g>
                                </svg>
                                <span class="ms-2">
                                    <div class="fw-700">
                                        {{ $t("warranty_available") }}
                                    </div>
                                    <router-link
                                        :to="{
                                            name: 'CustomPage',
                                            params: {
                                                pageSlug: 'warranty-policy',
                                            },
                                        }"
                                        class="text-decoration-underline text-grey fs-12"
                                    >
                                        * {{ $t("view_warranty_policy") }}
                                    </router-link>
                                </span>
                            </div>
                            <div
                                v-if="productDetails.for_pickup == 1"
                                class="bg-grey-lighten-4 border border-gray-300 d-flex rounded px-4 py-3 mt-3"
                            >
                                <v-row justify="center">
                                    <v-col cols="3">
                                        <svg
                                            width="30"
                                            height="30"
                                            viewBox="0 -1 19 19"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M18.2092 14.1783C18.2251 14.772 18.0667 15.3341 17.7817 15.817C17.6234 16.102 17.4097 16.3633 17.1722 16.577C16.6259 17.0837 15.9055 17.3924 15.1059 17.4162C13.9501 17.4399 12.9288 16.8462 12.3667 15.9358C12.0659 15.4687 11.8838 14.9066 11.8759 14.3129C11.8522 13.3154 12.2955 12.4129 13.008 11.8191C13.5463 11.3758 14.2272 11.0987 14.9713 11.0829C16.7209 11.0433 18.1697 12.4287 18.2092 14.1783Z"
                                                stroke="#9B9B9B"
                                                stroke-width="1.5"
                                                stroke-miterlimit="10"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M13.8086 14.2728L14.6082 15.0328L16.2628 13.4336"
                                                stroke="#9B9B9B"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M2.50781 5.89062L9.49822 9.93603L16.4411 5.91435"
                                                stroke="#9B9B9B"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M9.5 17.1082V9.92773"
                                                stroke="#9B9B9B"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M17.1064 7.26024V11.7411C17.1064 11.7807 17.1065 11.8123 17.0985 11.8519C16.5444 11.369 15.8319 11.084 15.0402 11.084C14.296 11.084 13.6073 11.3453 13.061 11.7807C12.3327 12.3586 11.8735 13.2532 11.8735 14.2507C11.8735 14.8444 12.0398 15.4065 12.3327 15.8815C12.4039 16.0082 12.491 16.1269 12.586 16.2378L11.1373 17.0373C10.2348 17.544 8.76227 17.544 7.85977 17.0373L3.63228 14.694C2.67436 14.1636 1.89062 12.8336 1.89062 11.7411V7.26024C1.89062 6.16774 2.67436 4.83775 3.63228 4.30733L7.85977 1.96398C8.76227 1.45732 10.2348 1.45732 11.1373 1.96398L15.3648 4.30733C16.3227 4.83775 17.1064 6.16774 17.1064 7.26024Z"
                                                stroke="#9B9B9B"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </v-col>
                                    <v-col>
                                        <p
                                            class="fw-700"
                                            :style="{
                                                fontFamily: 'OpenSans-Medium',
                                                fontSize: '12px',
                                            }"
                                        >
                                            {{ $t("pickup_available") }}
                                        </p>
                                    </v-col>
                                    <ul
                                        class="mb-3"
                                        :style="{
                                            fontFamily: 'OpenSans-SemiBold',
                                        }"
                                    >
                                        <li>
                                            <router-link
                                                :to="{
                                                    name: 'CustomPage',
                                                    params: {
                                                        pageSlug:
                                                            'pickup-policy',
                                                    },
                                                }"
                                                class="text-grey fs-12"
                                                style="color: black"
                                            >
                                                {{ $t("view_pickup_policy") }}
                                            </router-link>
                                        </li>
                                    </ul>
                                </v-row>
                            </div>
                        </v-col>
                        <v-col lg="12" md="9" sm="8" cols="12">
                            <div class="mb-4">
                                <div
                                    class="mb-3 opacity-80"
                                    style="font-size: 18px"
                                >
                                    <b
                                        :style="{
                                            fontFamily: 'OpenSans-Bold',
                                        }"
                                        >{{ $t("related_products") }}</b
                                    >
                                </div>
                                <v-row
                                    class="row-cols-2 row-cols-md-3 row-cols-lg-1 gutters-10"
                                >
                                    <v-col
                                        v-for="(product, i) in relatedProducts"
                                        :key="i"
                                        class="py-2"
                                    >
                                        <product-box
                                            :product-details="product"
                                            :is-loading="relatedLoading"
                                            box-style="two"
                                        />
                                    </v-col>
                                </v-row>
                            </div>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
import { mapActions } from "vuex";
import AddToCart from "../components/product/AddToCart.vue";
import ProductReviews from "../components/product/ProductReviews.vue";
import PrintPage from "./PrintPage.vue";
// Import Swiper Vue.js components
import { Swiper, SwiperSlide } from "swiper/vue";
import { useHead } from "@unhead/vue";
export default {
    props: {
        category: { required: false },
        subcategory: { required: false },
        item: { required: false },
    },
    data: () => ({
        metaTitle: "",
        metaDescription: "",
        detailsLoading: true,
        productDetails: {},
        reviewSummary: { average: 0 },
        relatedLoading: true,
        relatedProducts: [{}, {}, {}, {}, {}],
        togetherLoading: true,
        boughtTogetherProducts: [{}, {}, {}, {}, {}],
        moreLoading: true,
        moreProducts: [{}, {}, {}, {}, {}],
        panel: [0, 1],
        footer_data: {},
        carouselOption: {
            slidesPerView: 5,
            spaceBetween: 20,
            breakpoints: {
                0: {
                    slidesPerView: 2,
                    spaceBetween: 12,
                },
                // when window width is >= 320px
                599: {
                    slidesPerView: 3,
                    spaceBetween: 16,
                },
                // when window width is >= 480px
                960: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
                // when window width is >= 640px
                1264: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
                1904: {
                    slidesPerView: 5,
                    spaceBetween: 20,
                },
            },
        },
    }),
    components: {
        ProductReviews,
        AddToCart,
        Swiper,
        SwiperSlide,
        PrintPage,
    },
    computed: {
        check() {
            return this.$store.state.auth.modalWindow;
        },
    },
    watch: {
        metaTitle(newTitle) {
            this.updateHead(newTitle, this.metaDescription);
        },
        metaDescription(newDescription) {
            this.updateHead(this.metaTitle, newDescription);
        },
    },
    methods: {
        ...mapActions("recentlyViewed", ["addNewRecentlyViewedProduct"]),
        globalClose() {
            this.$store.commit("auth/modalWindow", false);
        },
        async getDetails() {
            const res = await this.call_api(
                "get",
                `product/details/${this.$route.params.slug}`
            );
            if (res.data.success) {
                this.metaTitle = res.data.data.metaTitle;
                this.metaDescription = res.data.data.description.replace(
                    /<[^>]*>?/gm,
                    ""
                );
                this.productDetails = res.data.data;
                this.reviewSummary = this.productDetails.review_summary;
                this.getRelatedProducts(this.productDetails.id);
                this.getBoughtTogetherProducts(this.productDetails.id);
                this.getMoreProducts(this.productDetails.id);
                this.addNewRecentlyViewedProduct(this.productDetails.id);
            } else {
                this.snack({
                    message: res.data.message,
                    color: "red",
                });
                this.$router.push({ name: "404" });
            }
            this.detailsLoading = false;
        },
        async getRelatedProducts(id) {
            const res = await this.call_api("get", `product/related/${id}`);
            if (res.data.success) {
                this.relatedProducts = res.data.data;
                this.relatedLoading = false;
            }
        },
        async getBoughtTogetherProducts(id) {
            const res = await this.call_api(
                "get",
                `product/bought-together/${id}`
            );
            if (res.data.success) {
                this.boughtTogetherProducts = res.data.data;
                this.togetherLoading = false;
            }
        },
        async getMoreProducts(id) {
            const res = await this.call_api("get", `product/random/10/${id}`);
            if (res.data.success) {
                this.moreProducts = res.data.data;
                this.moreLoading = false;
            }
        },
        async productReferralCode(product_referral_code) {
            const res = await this.call_api("post", "product-refferal-code", {
                product_referral_code: product_referral_code,
                slug: this.$route.params.slug,
            });
        },
        async getFooter() {
            const res = await this.call_api("get", `setting/footer`);
            if (res.status === 200) {
                this.footer_data = res.data;
            }
        },
        triggerGlobal(arg) {
            switch (arg) {
                case 0:
                    this.copyToClipboard(
                        `${this.$store.state.app.appUrl}product/${this.productDetails.slug}`
                    );
                    break;
                case 1:
                    alert(arg);
                    break;
                case 2:
                    window.print();
                    break;
                default:
                    break;
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
                        return;
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
                    // setTimeout(() => (this.copied = false), 2000);
                    setTimeout(
                        () =>
                            this.snack({
                                message: "Амжилттай хуулагдлаа",
                                color: "green",
                            }),
                        (this.copied = false),
                        2000
                    );
                } catch (err) {
                    return;
                }
                document.body.removeChild(textarea);
            }
        },
        updateHead(title, description) {
            useHead({
                title: title,
                meta: [{ name: "description", content: description }],
            });
        },
    },
    async created() {
        this.getDetails();
        this.getFooter();
    },
    mounted() {
        const urlParams = new URLSearchParams(window.location.search);
        const product_referral_code = urlParams.get("product_referral_code");
        if (product_referral_code != null) {
            this.productReferralCode(product_referral_code);
        }
    },
};
</script>
<style scoped>
@media (max-width: 1263px) {
}
@media (min-width: 1264px) {
    .main-bar {
        max-width: calc(100% - 224px);
    }
    .right-bar {
        width: 224px;
        max-width: 224px;
    }
}
.nav-buttons {
    background: #f5f5f5;
    border-radius: 5px;
    display: flex;
    align-items: center;
    padding: 0 5px;
    font-size: 12px;
    height: 36px;
    &:hover {
        cursor: pointer;
    }
}
.px-2 {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
}
.mx-2 {
    padding-left: 0.5rem;
    padding-right: 0.5rem;
}
@media (max-width: 1264px) {
    .nav-buttons {
        display: none;
    }
}
</style>
