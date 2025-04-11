<template>
    <div
        :class="[
            boxStyle == 'two'
                ? 'product-box-two'
                : boxStyle == 'three'
                ? 'product-box-three'
                : boxStyle == 'four'
                ? 'product-box-four'
                : 'product-box-one',
        ]"
    >
        <div v-if="isLoading">
            <v-skeleton-loader
                type="image"
                :height="
                    boxStyle == 'two'
                        ? '70'
                        : boxStyle == 'three'
                        ? '150'
                        : boxStyle == 'four'
                        ? '130'
                        : '310'
                "
            />
        </div>
        <div
            :class="['overflow-hidden', { 'rounded border': !noBorder }]"
            v-else
        >
            <v-row
                align="center"
                no-gutters
                :class="[{ 'flex-nowrap': boxStyle != 'one' }]"
            >
                <v-col
                    :cols="boxStyle == 'one' ? '12' : 'auto'"
                    class="flex-shrink-0"
                >
                    <div class="position-relative">
                        <div
                            v-if="discount > 0 && boxStyle != 'two'"
                            class="discount-badge"
                        >
                            {{ $t("off") }} {{ discount }}%
                        </div>
                        <router-link
                            :to="{
                                name: 'ProductDetails',
                                params: { slug: productDetails.slug },
                            }"
                            class="text-reset d-block lh-0 text-center"
                        >
                            <img
                                :src="productDetails.thumbnail_image"
                                :alt="productDetails.name"
                                @error="imageFallback($event)"
                                :class="[
                                    'img-fit',
                                    boxStyle == 'two'
                                        ? 'size-70px'
                                        : boxStyle == 'three'
                                        ? 'size-150px'
                                        : boxStyle == 'four'
                                        ? 'size-130px'
                                        : 'h-180px',
                                ]"
                            />
                        </router-link>
                    </div>
                </v-col>
                <v-col
                    :cols="boxStyle == 'one' ? '12' : null"
                    class="minw-0 flex-shrink-0"
                >
                    <div
                        :class="[
                            'px-3 d-flex flex-column',
                            boxStyle == 'two' ? 'py-1' : 'py-2',
                        ]"
                    >
                        <div
                            :class="[
                                boxStyle == 'two'
                                    ? 'order-2 fs-14 lh-1'
                                    : 'fs-16 mb-2',
                            ]"
                        >
                            <template
                                v-if="
                                    productDetails.base_price >
                                    productDetails.base_discounted_price
                                "
                            >
                                <del class="opacity-40">{{
                                    format_price(productDetails.base_price)
                                }}</del>
                                <span class="ml-1 fw-700">{{
                                    format_price(
                                        productDetails.base_discounted_price
                                    )
                                }}</span>
                            </template>
                            <template v-else>
                                <span class="fw-700">{{
                                    format_price(
                                        productDetails.base_discounted_price
                                    )
                                }}</span>
                            </template>
                        </div>
                        <h5
                            :class="[
                                'opacity-60 fw-400 mb-2 lh-1-6',
                                boxStyle == 'two'
                                    ? 'text-truncate fs-12'
                                    : 'fs-13 text-truncate-2 h-40px',
                            ]"
                        >
                            <router-link
                                :to="{
                                    name: 'ProductDetails',
                                    params: { slug: productDetails.slug },
                                }"
                                class="text-reset"
                                :style="{
                                    fontFamily: 'OpenSans-SemiBold',
                                    fontSize: '12px',
                                }"
                                >{{ productDetails.name }}</router-link
                            >
                        </h5>
                        <div
                            v-if="
                                generalSettings.club_point == 1 &&
                                boxStyle != 'two' &&
                                boxStyle != 'four'
                            "
                            :class="[
                                boxStyle == 'two' || boxStyle == 'four'
                                    ? 'd-flex flex-row align-center max-w-80px club-badge rounded-sm mb-2'
                                    : 'd-flex flex-row align-center max-w-80px club-badge rounded-sm',
                            ]"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="18"
                                height="11.001"
                                viewBox="0 0 18 12.001"
                            >
                                <g
                                    id="Group_23890"
                                    data-name="Group 23890"
                                    transform="translate(-631 -822)"
                                >
                                    <path
                                        id="Subtraction_84"
                                        data-name="Subtraction 84"
                                        d="M7583,12a5.989,5.989,0,0,1-1.8-.274,7.1,7.1,0,0,0,0-11.45A5.927,5.927,0,0,1,7583,0a6,6,0,1,1,0,12Zm-1.885-.3A3.016,3.016,0,1,0,7580,11.2a6.1,6.1,0,0,0,1.113.5Z"
                                        transform="translate(-6940 822)"
                                        fill="#fff"
                                    />
                                    <path
                                        id="Subtraction_85"
                                        data-name="Subtraction 85"
                                        d="M7581.91,10.636a4.954,4.954,0,0,1-.807-.066,7.09,7.09,0,0,0,0-9.689,4.914,4.914,0,0,1,5.717,4.844A4.916,4.916,0,0,1,7581.91,10.636Zm-2.011-.43h0a4.912,4.912,0,0,1,0-8.961,6,6,0,0,1,0,8.961Z"
                                        transform="translate(-6938.91 822.274)"
                                        fill="#f5a100"
                                        opacity="0.7"
                                    />
                                    <path
                                        id="Subtraction_86"
                                        data-name="Subtraction 86"
                                        d="M7580.277,9.049v0a7.089,7.089,0,0,0,.35-.765,1.19,1.19,0,0,0,.392-.3,1.245,1.245,0,0,0,.3-.852,1.073,1.073,0,0,0-.213-.695c-.012-.014-.023-.028-.037-.042.021-.224.031-.449.031-.67,0-.083,0-.173-.005-.275a1.875,1.875,0,0,1,.555.375,1.556,1.556,0,0,1,.362.556,1.912,1.912,0,0,1,.125.7,1.906,1.906,0,0,1-1.861,1.968Zm-1.191-.144h0a2.076,2.076,0,0,1-.269-.113,1.7,1.7,0,0,1-.643-.62,2.2,2.2,0,0,1-.316-1.047l.8-.15a1.986,1.986,0,0,0,.327.94,1.293,1.293,0,0,0,.441.371,5.917,5.917,0,0,1-.339.619Zm.7-1.6h0V5.857a3.326,3.326,0,0,1-1-.381,1.487,1.487,0,0,1-.586-.588,1.764,1.764,0,0,1-.2-.851,1.747,1.747,0,0,1,.6-1.374,1.485,1.485,0,0,1,.407-.249,5.99,5.99,0,0,1,.357.613,1.06,1.06,0,0,0-.571.947,1.022,1.022,0,0,0,.229.679,1.567,1.567,0,0,0,.771.438V4.143a6.026,6.026,0,0,1,0,3.157Zm1.37-3.352h0a1.352,1.352,0,0,0-.293-.716.988.988,0,0,0-.3-.227,7.086,7.086,0,0,0-.609-1.152h.3V2.23a2,2,0,0,1,1.1.407,1.817,1.817,0,0,1,.626,1.187l-.821.124Z"
                                        transform="translate(-6936.998 822.274)"
                                        fill="#fff"
                                    />
                                    <circle
                                        id="Ellipse_666"
                                        data-name="Ellipse 666"
                                        cx="6"
                                        cy="6"
                                        r="6"
                                        transform="translate(631 822)"
                                        fill="#fff"
                                    />
                                    <circle
                                        id="Ellipse_667"
                                        data-name="Ellipse 667"
                                        cx="4.91"
                                        cy="4.91"
                                        r="4.91"
                                        transform="translate(632.09 823.09)"
                                        fill="#f5a100"
                                        opacity="0.7"
                                    />
                                    <path
                                        id="Path_25349"
                                        data-name="Path 25349"
                                        d="M43.364,56.224v-.542a2.936,2.936,0,0,1-.969-.267,1.689,1.689,0,0,1-.645-.62,2.22,2.22,0,0,1-.316-1.047l.8-.15a2.016,2.016,0,0,0,.327.941,1.159,1.159,0,0,0,.8.473V52.48a3.319,3.319,0,0,1-1-.38,1.468,1.468,0,0,1-.585-.587,1.871,1.871,0,0,1,.4-2.226,2.089,2.089,0,0,1,1.192-.433v-.38h.468v.38a1.963,1.963,0,0,1,1.1.406,1.806,1.806,0,0,1,.627,1.188l-.821.124a1.36,1.36,0,0,0-.294-.718,1.083,1.083,0,0,0-.612-.329v2.292a6.312,6.312,0,0,1,.795.234,1.964,1.964,0,0,1,.605.4,1.6,1.6,0,0,1,.36.556,2.046,2.046,0,0,1-.4,2.076,1.928,1.928,0,0,1-1.36.592v.551h-.468Zm0-6.707a1.178,1.178,0,0,0-.731.371,1.1,1.1,0,0,0-.04,1.391,1.566,1.566,0,0,0,.771.437Zm.468,5.494a1.178,1.178,0,0,0,.766-.4,1.245,1.245,0,0,0,.3-.852,1.067,1.067,0,0,0-.214-.7,1.849,1.849,0,0,0-.855-.47Z"
                                        transform="translate(593.424 775.651)"
                                        fill="#fff"
                                    />
                                </g>
                            </svg>
                            <div class="fs-13 ms-1">
                                {{ productDetails.earn_point }}
                            </div>
                        </div>
                        <div
                            class="d-flex align-center"
                            v-if="boxStyle != 'two'"
                        >
                            <div
                                :class="[
                                    boxStyle == 'three' || boxStyle == 'four'
                                        ? 'me-3'
                                        : 'flex-grow-1 me-1',
                                ]"
                            >
                                <template v-if="productDetails.stock">
                                    <button
                                        class="text-reset py-1 lh-1 align-center d-flex"
                                        @click="
                                            showAddToCartDialog({
                                                status: true,
                                                slug: productDetails.slug,
                                            })
                                        "
                                    >
                                        <i
                                            class="las la-shopping-cart fs-20 ts-05 me-1"
                                        ></i>
                                        <span class="fw-700 fs-13">{{
                                            $t("buy_now")
                                        }}</span>
                                    </button>
                                </template>
                                <template v-else>
                                    <span class="fw-700 fs-13 opacity-60">{{
                                        $t("out_of_stock")
                                    }}</span>
                                </template>
                            </div>
                            <div>
                                <template
                                    v-if="isThisWishlisted(productDetails.id)"
                                >
                                    <button
                                        class="pa-1 lh-1"
                                        @click="
                                            removeFromWishlist(
                                                productDetails.id
                                            )
                                        "
                                        type="button"
                                    >
                                        <i class="la la-heart ts-02 fs-18"></i>
                                    </button>
                                </template>
                                <template v-else>
                                    <button
                                        class="pa-1 lh-1"
                                        @click="
                                            addNewWishlist(productDetails.id)
                                        "
                                        type="button"
                                    >
                                        <i
                                            class="la la-heart-o ts-02 fs-18"
                                        ></i>
                                    </button>
                                </template>
                            </div>
                            <div v-if="generalSettings.product_comparison == 1">
                                <template
                                    v-if="
                                        isThisComparedListed(productDetails.id)
                                    "
                                >
                                    <v-tooltip top>
                                        <template v-slot:activator="{ props }">
                                            <button
                                                v-bind="props"
                                                class="pa-1 lh-1"
                                                @click="
                                                    RemoveComparedListProduct(
                                                        productDetails.id
                                                    )
                                                "
                                                type="button"
                                            >
                                                <i class="las la-times"></i>
                                            </button>
                                        </template>
                                        <span>{{
                                            $t("Remove from compare list")
                                        }}</span>
                                    </v-tooltip>
                                </template>
                                <template v-else>
                                    <v-tooltip top>
                                        <template v-slot:activator="{ props }">
                                            <button
                                                v-bind="props"
                                                class="pa-1 lh-1"
                                                @click="
                                                    addNewComparedList(
                                                        productDetails.id
                                                    )
                                                "
                                                type="button"
                                            >
                                                <i
                                                    class="las la-sync ts-02 fs-18"
                                                ></i>
                                            </button>
                                        </template>
                                        <span>{{
                                            $t("Add to compare list")
                                        }}</span>
                                    </v-tooltip>
                                </template>
                            </div>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
export default {
    props: {
        isLoading: { type: Boolean, required: true, default: true },
        boxStyle: { type: String, default: "one" },
        noBorder: { type: Boolean, default: false },
        productDetails: { type: Object, required: true, default: {} },
    },
    data: () => ({}),
    computed: {
        ...mapGetters("app", ["generalSettings"]),
        ...mapGetters("wishlist", ["isThisWishlisted"]),
        ...mapGetters("compareList", ["isThisComparedListed"]),
        ...mapGetters("cart", ["isThisInCart", "findCartItemByVariationId"]),
        discount() {
            return this.discount_percent(
                this.$props.productDetails.base_price,
                this.$props.productDetails.base_discounted_price
            );
        },
    },
    methods: {
        ...mapActions("wishlist", ["addNewWishlist", "removeFromWishlist"]),
        ...mapActions("compareList", [
            "addNewComparedList",
            "RemoveComparedListProduct",
        ]),
        ...mapActions("cart", ["addToCart", "updateQuantity"]),
        ...mapMutations("auth", ["showAddToCartDialog"]),
        addCart() {
            if (!this.$props.productDetails.is_variant) {
                this.addToCart({
                    variation_id: this.$props.productDetails.variations[0].id,
                    qty: this.$props.productDetails.min_qty,
                });
            }
        },
        updateCart(type, cart_id) {
            this.updateQuantity({
                type: type,
                cart_id: cart_id,
            });
        },
    },
};
</script>
