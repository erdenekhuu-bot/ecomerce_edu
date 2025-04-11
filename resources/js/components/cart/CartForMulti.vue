<template>
    <div>
        <v-list-item class="d-block pa-4 border-bottom side-cart-top">
            <div class="w-100 d-flex">
                <div class="d-flex" style="align-items: center">
                    <i class="la la-shopping-cart la-3x me-2" />
                    <div class="lh-1-4">
                        <div class="fs-16 fw-500">
                            {{ getCartCount }} {{ $t("items") }}
                        </div>
                    </div>
                </div>
                <button
                    class="ms-auto"
                    type="button"
                    @click.stop="updateCartDrawer(false)"
                >
                    <i class="la la-close fs-20" />
                </button>
            </div>
        </v-list-item>

        <div
            class="side-cart-content c-scrollbar px-2"
            v-if="getCartShops.length > 0"
        >
            <v-expansion-panels class="" v-model="panel" accordion flat>
                <v-expansion-panel v-for="(shop, i) in getCartShops" :key="i">
                    <div
                        :class="[
                            'd-flex align-center pa-2',
                            { 'border-top': i != 0 },
                        ]"
                    >
                        <v-checkbox
                            true-icon="las la-check"
                            hide-details
                            class="mt-0 pt-0"
                            :model-value="shop.selected"
                            @update:modelValue="
                                toggleCartShop({
                                    shop_id: shop.id,
                                    status: $event,
                                })
                            "
                        />
                        <v-expansion-panel-title
                            class="v-expansion-panel-title-cart ms-auto px-2 py-2"
                        >
                            <div class="d-flex align-center flex-grow-1">
                                <img
                                    :src="shop.logo"
                                    :alt="shop.name"
                                    class="size-40px flex-shrink-0 me-2"
                                />
                                <div>
                                    <span class="fw-500">{{ shop.name }}</span>
                                    <div class="fs-12 opacity-70 mt-1">
                                        {{
                                            format_price(
                                                getShopCartPrice(shop.id)
                                            )
                                        }}
                                    </div>
                                </div>
                            </div>
                            <template v-slot:actions>
                                <i class="las la-angle-down"></i>
                            </template>
                        </v-expansion-panel-title>
                    </div>

                    <v-expansion-panel-text class="border-top">
                        <min-order-progress
                            class="mt-3"
                            :shop-id="shop.id"
                            :cart-price="getShopCartPrice(shop.id)"
                            :min-order="getShopMinOrder(shop.id)"
                            v-if="getShopMinOrder(shop.id) > 0"
                        />

                        <div
                            class="overflow-y-scroll custom-scrollbar"
                            style="height: 250px"
                        >
                            <v-list dense class="">
                                <cart-items
                                    :cart-items="getShopProductsById(shop.id)"
                                />
                            </v-list>
                        </div>

                        <coupon-form class="mb-3" :shop-id="shop.id" />
                    </v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>
        </div>

        <div v-else class="px-5 py-2 side-cart-content">
            <div
                class="d-flex flex-column justify-center h-100 text-center pa-5"
            >
                <img
                    class="img-fluid"
                    :src="static_asset(`/assets/img/no-cart-item.jpg`)"
                    alt="$t('your_shopping_bag_is_empty_start_shopping')"
                />

                <div class="fs-20">
                    {{ $t("your_shopping_bag_is_empty_start_shopping") }}
                </div>
            </div>
        </div>

        <v-list-item class="my-4 side-cart-bottom d-block">
            <div v-show="getCartShops.length > 0 && triggerCheckLogin">
                <v-btn
                    elevation="0"
                    color="primary"
                    large
                    block
                    @click="routetoQuot"
                >
                    {{ $t("offer_price_take") }}
                </v-btn>
            </div></v-list-item
        >

        <v-list-item class="pa-4 border-top side-cart-bottom d-block">
            <v-btn
                elevation="0"
                color="primary"
                class=""
                large
                block
                @click="checkout"
            >
                {{ $t("checkout") }}
                {{ format_price(getCartPrice - getTotalCouponDiscount) }}
            </v-btn>
        </v-list-item>
        <!-- Modal window -->
        <v-dialog v-model="displaying" width="600px">
            <v-container>
                <v-form autocomplete="chrome-off" @submit.prevent="submitting">
                    <section class="alert-modal mb-4 rounded-lg">
                        <div style="display: flex; align-items: center">
                            <img
                                src="https://ecommerce.infitech.mn/assets/frontend/images/icons/info_icon.gif"
                                alt=""
                                width="20"
                            />
                            <p class="px-2">Санамж</p>
                        </div>
                        <p style="font-size: 12px" class="my-2">
                            Хэрэглэгч та доорх мэдээллийг үнэн зөв бөглөнө үү.
                            Үнэн зөв бөглөөгүйн улмаас үнийн санал илгээх
                            процесс удаашрах магадлалтайг анхаарна уу.
                        </p>
                    </section>
                    <div class="fs-13 fw-500">
                        <b>{{ $t("email_address") }}</b>
                    </div>
                    <v-text-field
                        type="email"
                        solo
                        variant="outlined"
                        v-model="form.email"
                        :rules="rules.email"
                    />
                    <v-row>
                        <v-col>
                            <div class="fs-13 fw-500">
                                {{ $t("country") }}
                            </div>
                            <v-autocomplete
                                v-model="form.country"
                                :items="countries"
                                hide-details="auto"
                                variant="outlined"
                                item-title="name"
                                item-value="id"
                                dense
                                autocomplete="off"
                                :custom-filter="countryChanged"
                                @update:modelValue="countryChanged"
                                :rules="rules.country"
                            ></v-autocomplete>
                        </v-col>
                        <v-col>
                            <div class="fs-13 fw-500">
                                {{ $t("state") }}
                            </div>
                            <v-autocomplete
                                v-model="form.state"
                                :items="filteredStates"
                                hide-details="auto"
                                variant="outlined"
                                item-title="name"
                                item-value="id"
                                dense
                                @update:modelValue="stateChanged"
                                :rules="rules.state"
                                :readonly="waiting"
                                :loading="waiting"
                                :disabled="disabling"
                            ></v-autocomplete>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <div class="fs-13 fw-500">
                                <b>{{ $t("organization") }}</b>
                            </div>
                            <v-text-field
                                variant="outlined"
                                type="text"
                                v-model="form.organization"
                                :rules="rules.organization"
                            />
                        </v-col>
                        <v-col>
                            <div class="fs-13 fw-500">
                                <b>{{ $t("name") }}</b>
                            </div>
                            <v-text-field
                                variant="outlined"
                                :rules="rules.name"
                                v-model="form.name"
                                type="text"
                            />
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col>
                            <div class="fs-13 fw-500">
                                <b>{{ $t("contact") + " 1" }}</b>
                            </div>
                            <v-text-field
                                :rules="rules.phone"
                                variant="outlined"
                                v-model="form.phone"
                                type="number"
                            />
                        </v-col>
                        <v-col>
                            <div class="fs-13 fw-500">
                                <b>{{ $t("contact") + " 2" }}</b>
                            </div>
                            <v-text-field
                                variant="outlined"
                                v-model="form.phone2"
                                :rules="rules.phone2"
                                type="text"
                            />
                        </v-col>
                    </v-row>
                    <div class="">
                        <div class="fs-13 fw-500">{{ $t("address") }}</div>
                        <v-textarea
                            hide-details="auto"
                            v-model="form.address"
                            rows="3"
                            required
                            variant="outlined"
                            no-resize
                            :rules="rules.address"
                        ></v-textarea>
                    </div>
                    <div class="text-right mt-4">
                        <v-btn
                            elevation="0"
                            type="submit"
                            color="primary"
                            :loading="checkoutLoading"
                            :disabled="checkoutLoading"
                        >
                            {{ $t("send") }}
                        </v-btn>
                        <v-overlay :value="checkoutLoading" z-index="99999">
                            <v-progress-circular
                                indeterminate
                                size="64"
                            ></v-progress-circular>
                        </v-overlay>
                    </div>
                </v-form>
            </v-container>
        </v-dialog>
    </div>
</template>

<script>
import { mapActions, mapGetters, mapMutations } from "vuex";
import CartItems from "./CartItems.vue";
import CouponForm from "./CouponForm.vue";
import MinOrderProgress from "./MinOrderProgress.vue";

export default {
    components: { CartItems, CouponForm, MinOrderProgress },
    data: () => ({
        panel: 0,
        couponCode: null,
        couponLoading: false,
        showModal: false,
        displaying: false,
        countriesLoaded: false,
        countries: [],
        waiting: false,
        filteredStates: [],
        checkoutLoading: false,
        disabling: true,
        user_list: [],
        form: {
            id: null,
            address: "",
            name: "",
            organization: "",
            country: "",
            state: "",
            city: "",
            phone: "",
            email: "",
            phone2: "",
            user_id: "",
        },
    }),
    computed: {
        errorMessages() {
            return {
                email_invalid: this.$t("email_invalid"),
                country_required: this.$t("country_required"),
                state_required: this.$t("state_required"),
                organization_required: this.$t("organization_required"),
                name_required: this.$t("name_required"),
                phone_required: this.$t("phone_required"),
                phone2_required: this.$t("phone2_required"),
                address_required: this.$t("address_required"),
            };
        },
        rules() {
            return {
                email: [
                    (value) =>
                        (/.+@.+\..+/.test(value) && !!value) ||
                        this.errorMessages.email_invalid,
                ],
                country: [
                    (value) => !!value || this.errorMessages.country_required,
                ],
                state: [
                    (value) => !!value || this.errorMessages.state_required,
                ],
                organization: [
                    (value) =>
                        !!value || this.errorMessages.organization_required,
                ],
                name: [(value) => !!value || this.errorMessages.name_required],
                phone: [
                    (value) => !!value || this.errorMessages.phone_required,
                ],
                phone2: [
                    (value) => !!value || this.errorMessages.phone2_required,
                ],
                address: [
                    (value) => !!value || this.errorMessages.address_required,
                ],
            };
        },
        ...mapGetters("cart", [
            "getCartCount",
            "getCartPrice",
            "getCartShops",
            "getShopMinOrder",
            "getShopCartPrice",
            "getShopProductsById",
            "getTotalCouponDiscount",
            "getCartClubPoints",
            "getCartTax",
            "getStandardTime",
            "getExpressTime",
            "getAllCouponCodes",
            "getSelectedCartIds",
            "checkShopMinOrder",
            "getIsDigital",
            "getPickupPoints",
            "getCartProducts",
        ]),
        ...mapGetters("app", [
            "generalSettings",
            "appUrl",
            "paymentMethods",
            "offlinePaymentMethods",
        ]),
        ...mapGetters("auth", ["currentUser", "triggerCheckLogin"]),
        ...mapGetters("address", [
            "getAddresses",
            "getDefaultShippingAddress",
            "getDefaultBillingAddress",
        ]),
        statePlaceholer() {
            return this.$i18n.t("select_a_state");
        },

        stateActive() {
            return this.form.country != "" ? false : true;
        },
    },
    methods: {
        ...mapActions("address", ["addAddress"]),
        ...mapActions("cart", [
            "updateQuantity",
            "toggleCartShop",
            "toggleCartItem",
            "removeFromCart",
            "removeMultipleFromCart",
        ]),
        ...mapMutations("auth", ["updateCartDrawer"]),
        ...mapMutations("address", ["setAddresses"]),

        async submitting() {
            let formData = new FormData();
            formData.append("delivery_type", "standart");
            let cartIds = this.getSelectedCartIds;
            cartIds.forEach((item, index) => {
                formData.append("cart_item_ids[]", item);
            });
            formData.append("state_id", this.form.state);
            formData.append("country_id", this.form.country);
            formData.append("email", this.form.email);
            formData.append("organization", this.form.organization);
            formData.append("name", this.form.name);
            formData.append("phone", this.form.phone);
            formData.append("phone2", this.form.phone2);
            formData.append("address", this.form.address);
            formData.append("user_id", this.form.user_id);

            if (this.getCartPrice > 0) {
                const res = await this.call_api(
                    "post",
                    "user/quote/quote-order/create",
                    formData
                );
                if (res.data.success) {
                    this.$store.commit("auth/modalWindow", false);
                    this.displaying = false;
                    this.$router
                        .push({
                            name: "OfferPrice",
                            query: { orderCode: res.data.order_code },
                        })
                        .catch(() => {});
                    this.removeMultipleFromCart(this.getSelectedCartIds);
                    setTimeout(() => {
                        this.resetCoupon();
                        this.removeMultipleFromCart(this.getSelectedCartIds);
                    }, 2000);
                } else {
                    this.checkoutLoading = false;
                    this.snack({
                        message: res.data.message,
                        color: "red",
                    });
                }
            }
        },
        async fetchCountries() {
            if (!this.countriesLoaded) {
                const res = await this.call_api("get", "all-countries");
                if (res.data.success) {
                    this.countriesLoaded = true;
                    this.countries = res.data.data;
                }
            }
        },
        async countryChanged() {
            if (!this.form.country) {
                return;
            }
            this.waiting = true;
            const res = await this.call_api(
                "get",
                `states/${this.form.country}`
            );
            if (res.data.success) {
                this.filteredStates = res.data.data;
                this.form.state = "";
                this.form.city = "";
                this.filteredCities = [];
            } else {
                this.snack({
                    message: this.$i18n.t("something_went_wrong"),
                    color: "red",
                });
            }
            setTimeout(() => {
                this.waiting = false;
                this.disabling = false;
            }, 2000);
        },
        async stateChanged() {
            const res = await this.call_api("get", `cities/${this.form.state}`);
            if (res.data.success) {
                this.filteredCities = res.data.data;
                this.form.city = "";
            } else {
                this.snack({
                    message: this.$i18n.t("something_went_wrong"),
                    color: "red",
                });
            }
        },
        async userList() {
            const res = await this.call_api("get", "user/users_list");
            if (res.data.success) {
                this.user_list = res.data.data;
            }
        },
        checkout() {
            if (this.getCartPrice > 0) {
                this.$router.push({ name: "Checkout" }).catch((e) => {
                    if (this.$route.name == "Checkout") {
                        this.updateCartDrawer(false);
                    }
                });
            } else {
                this.snack({
                    message: this.$i18n.t("please_select_a_cart_product"),
                    color: "red",
                });
                return;
            }
        },
        routetoQuot() {
            this.updateCartDrawer(false);
            this.displaying = true;
            //this.$store.commit("auth/modalWindow", true);
        },
    },
    created() {
        this.fetchCountries();
        if (this.currentUser.user_type == "staff") {
            this.userList();
        }
    },
};
</script>
<style scoped>
.side-cart-content {
    height: calc(100vh - 152px);
    max-height: calc(100vh - 242px);
    overflow-y: auto;
}
.alert-modal {
    background-color: #fff3cd;
    border: 1px solid #ffeeba;
    padding: 10px;
}
</style>
