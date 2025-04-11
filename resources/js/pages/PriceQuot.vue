<template>
    <v-form
        autocomplete="chrome-off"
        class="custom-form custom-hide"
        v-on:submit.prevent="submitting"
    >
        <div v-if="currentUser.user_type == 'staff'">
            <div class="fs-13 fw-500">{{ $t("Select User") }}</div>
            <v-autocomplete
                v-model="form.user_id"
                :items="user_list"
                hide-details="auto"
                variant="outlined"
                item-title="name"
                item-value="id"
                dense
                autocomplete="off"
                aria-required="true"
            ></v-autocomplete>
        </div>

        <div class="mt-2">
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
        </div>
        <v-row>
            <v-col>
                <div>
                    <div class="fs-13 fw-500">{{ $t("country") }}</div>
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
                </div>
            </v-col>
            <v-col>
                <div>
                    <div class="fs-13 fw-500">{{ $t("state") }}</div>
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
                    ></v-autocomplete></div
            ></v-col>
        </v-row>
        <v-row>
            <v-col>
                <div class="">
                    <div class="fs-13 fw-500">
                        <b>{{ $t("organization") }}</b>
                    </div>
                    <v-text-field
                        variant="outlined"
                        type="text"
                        v-model="form.organization"
                        :rules="rules.organization"
                    /></div
            ></v-col>
            <v-col>
                <div class="">
                    <div class="fs-13 fw-500">
                        <b>{{ $t("name") }}</b>
                    </div>
                    <v-text-field
                        variant="outlined"
                        :rules="rules.name"
                        v-model="form.name"
                        type="text"
                    /></div
            ></v-col>
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
</template>
<script>
import { mapActions, mapMutations, mapGetters } from "vuex";
export default {
    data() {
        return {
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
            rules: {
                email: [
                    (value) =>
                        (/.+@.+\..+/.test(value) && !!value) ||
                        this.$t("email_invalid"),
                ],
                country: [(value) => !!value || this.$t("country_required")],
                state: [(value) => !!value || this.$t("state_required")],
                organization: [
                    (value) => !!value || this.$t("organization_required"),
                ],
                name: [(value) => !!value || this.$t("name_required")],
                phone: [(value) => !!value || this.$t("phone_required")],
                phone2: [(value) => !!value || this.$t("phone2_required")],
                address: [(value) => !!value || this.$t("address_required")],
            },
        };
    },
    computed: {
        ...mapGetters("app", [
            "generalSettings",
            "appUrl",
            "paymentMethods",
            "offlinePaymentMethods",
        ]),
        ...mapGetters("auth", ["currentUser"]),
        ...mapGetters("address", [
            "getAddresses",
            "getDefaultShippingAddress",
            "getDefaultBillingAddress",
        ]),
        ...mapGetters("cart", [
            "getCartPrice",
            "getTotalCouponDiscount",
            "getCartClubPoints",
            "getCartTax",
            "getCartShops",
            "getStandardTime",
            "getExpressTime",
            "getAllCouponCodes",
            "getSelectedCartIds",
            "checkShopMinOrder",
            "getIsDigital",
            "getPickupPoints",
            "getCartProducts",
        ]),

        statePlaceholer() {
            return this.$i18n.t("select_a_state");
        },
        stateActive() {
            return this.form.country != "" ? false : true;
        },
    },

    created() {
        this.fetchCountries();
        if (this.currentUser.user_type == "staff") {
            this.userList();
        }
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
                this.checkoutLoading = true;

                const res = await this.call_api(
                    "post",
                    "user/quote/quote-order/create",
                    formData
                );
                if (res.data.success) {
                    this.$store.commit("auth/modalWindow", false);
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
                    this.snack({
                        message: res.data.message,
                        color: "red",
                    });
                }
                this.checkoutLoading = false;
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
    },
};
</script>
<style scoped>
.custom-form {
    position: fixed;
    top: 10%;
    left: 30%;
    width: 40%;
    z-index: 40;
    border-radius: 10px;
    background: rgb(241, 241, 241);
    padding: 20px;
}
:deep(.v-text-field input) {
    min-height: calc(100% / 20);
}
</style>
