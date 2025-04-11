<template>
    <v-container class="py-6">
        <div
            class="rounded border border-primary bg-soft-primary py-7 px-16 d-md-flex justify-space-between align-center mb-5"
        >
            <h3 class="text-h5 fw-700 mb-5 mb-md-0">
                {{ $t("already_a_seller") }}
            </h3>
            <a
                :href="$store.getters['app/appUrl'] + '/login'"
                class="btn primary px-12 py-3 rounded white--text fs-16"
                target="_blank"
                >{{ $t("login") }}</a
            >
        </div>
        <banner
            :loading="false"
            :banner="$store.getters['app/banners'].shop_registration_page"
            class="mb-15"
        />
        <v-row>
            <v-col cols="12" lg="6" md="8" sm="10" class="mx-auto">
                <h1 class="text-h3 fw-700 mb-8">
                    {{ $t("register_your_shop") }}
                </h1>
                <v-form lazy-validation v-on:submit.prevent="register()">
                    <v-card class="border" elevation="0">
                        <v-card-title>
                            <span class="text-h6 fw-600 opacity-80">{{
                                $t("personal_information")
                            }}</span>
                        </v-card-title>
                        <v-card-text>
                            <div class="mb-4">
                                <div class="mb-1 fs-13 fw-500">
                                    {{ $t("full_name") }}
                                </div>
                                <v-text-field
                                    variant="plain"
                                    class="text-field"
                                    :placeholder="$t('full_name')"
                                    type="text"
                                    v-model="form.name"
                                    :error-messages="nameErrors"
                                    @blur="v$.form.name.$touch()"
                                    hide-details="auto"
                                    required
                                    outlined
                                ></v-text-field>
                            </div>
                            <div class="mb-4">
                                <div class="mb-1 fs-13 fw-500">
                                    {{ $t("phone_number") }}
                                </div>
                                <vue-tel-input
                                    v-model="form.phone"
                                    v-bind="mobileInputProps"
                                    :onlyCountries="availableCountries"
                                    @validate="phoneValidate"
                                >
                                    <template slot="arrow-icon"
                                        ><span class="vti__dropdown-arrow"
                                            >&nbsp;▼</span
                                        ></template
                                    >
                                </vue-tel-input>
                                <div
                                    class="v-text-field__details mt-2 pl-3"
                                    v-if="v$.form.phone.$error"
                                >
                                    <div
                                        class="v-messages theme--light error--text"
                                        role="alert"
                                    >
                                        <div class="v-messages__wrapper">
                                            <div class="v-messages__message">
                                                {{
                                                    $t("this_field_is_required")
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="v-text-field__details mt-2 pl-3"
                                    v-if="
                                        !v$.form.phone.$error &&
                                        form.showInvalidPhone
                                    "
                                >
                                    <div
                                        class="v-messages theme--light error--text"
                                        role="alert"
                                    >
                                        <div class="v-messages__wrapper">
                                            <div class="v-messages__message text-red">
                                                {{
                                                    $t(
                                                        "phone_number_must_be_valid"
                                                    )
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="fs-13 fw-500">
                                    {{ $t("email_address") }}
                                </div>
                                <v-text-field
                                    variant="plain"
                                    class="text-field"
                                    :placeholder="$t('email_address')"
                                    type="email"
                                    v-model="form.email"
                                    :error-messages="emailErrors"
                                    @blur="v$.form.email.$touch()"
                                    hide-details="auto"
                                    required
                                    outlined
                                ></v-text-field>
                            </div>
                            <div class="mb-4">
                                <div class="mb-1 fs-13 fw-500">
                                    {{ $t("password") }}
                                </div>
                                <v-text-field
                                    variant="plain"
                                    placeholder="* * * * * * * *"
                                    v-model="form.password"
                                    :error-messages="passwordErrors"
                                    @blur="v$.form.password.$touch()"
                                    type="password"
                                    class="input-group--focused text-field"
                                    hide-details="auto"
                                    required
                                    outlined
                                ></v-text-field>
                            </div>
                            <div class="mb-4">
                                <div class="mb-1 fs-13 fw-500">
                                    {{ $t("confirm_password") }}
                                </div>
                                <v-text-field
                                    variant="plain"
                                    placeholder="* * * * * * * *"
                                    v-model="form.confirmPassword"
                                    :error-messages="confirmPasswordErrors"
                                    @blur="v$.form.confirmPassword.$touch()"
                                    type="password"
                                    class="input-group--focused text-field"
                                    hide-details="auto"
                                    required
                                    outlined
                                ></v-text-field>
                            </div>
                        </v-card-text>

                        <v-divider />

                        <v-card-title>
                            <span class="text-h6 fw-600 opacity-80">{{
                                $t("shop_information")
                            }}</span>
                        </v-card-title>
                        <v-card-text>
                            <div class="mb-4">
                                <div class="mb-1 fs-13 fw-500">
                                    {{ $t("shop_name") }}
                                </div>
                                <v-text-field
                                    variant="plain"
                                    class="text-field"
                                    :placeholder="$t('shop_name')"
                                    type="text"
                                    v-model="form.shopName"
                                    :error-messages="shopNameErrors"
                                    @blur="v$.form.shopName.$touch()"
                                    hide-details="auto"
                                    required
                                    outlined
                                ></v-text-field>
                            </div>
                            <div class="mb-4">
                                <div class="mb-1 fs-13 fw-500">
                                    {{ $t("shop_phone") }}
                                </div>
                                <v-text-field
                                    variant="plain"
                                    class="text-field"
                                    :placeholder="$t('shop_phone')"
                                    type="number"
                                    v-model="form.shopPhone"
                                    :error-messages="shopPhoneErrors"
                                    @blur="v$.form.shopPhone.$touch()"
                                    hide-details="auto"
                                    required
                                    outlined
                                ></v-text-field>
                            </div>
                            <div class="mb-4">
                                <div class="mb-1 fs-13 fw-500">
                                    {{ $t("shop_address") }}
                                </div>
                                <v-text-field
                                    variant="plain"
                                    class="text-field"
                                    :placeholder="$t('shop_address')"
                                    type="text"
                                    v-model="form.shopAddress"
                                    :error-messages="shopAddressErrors"
                                    @blur="v$.form.shopAddress.$touch()"
                                    hide-details="auto"
                                    required
                                    outlined
                                ></v-text-field>
                            </div>
                            <div class="mb-4">
                                {{ $t("by_signing_up_you_agree_to_our") }}
                                <router-link
                                    :to="{
                                        name: 'CustomPage',
                                        params: {
                                            pageSlug: 'terms-and-conditions',
                                        },
                                    }"
                                    class="primary--text text-decoration-underline"
                                    >{{
                                        $t("terms_and_conditions")
                                    }}</router-link
                                >
                            </div>
                        </v-card-text>
                    </v-card>
                    <v-btn
                        x-large
                        class="px-12 mb-4 w-100 mt-5"
                        elevation="0"
                        type="submit"
                        color="primary"
                        @click="register"
                        :loading="loading"
                        :disabled="loading"
                        >{{ $t("register_shop") }}</v-btn
                    >
                </v-form>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { required, email, minLength } from "@vuelidate/validators";
import { useVuelidate } from "@vuelidate/core";
import { mapActions, mapGetters, mapMutations } from "vuex";
import { VueTelInput } from "vue-tel-input";
export default {
    data: () => ({
        loading: false,
        mobileInputProps: {
            inputOptions: {
                type: "tel",
                placeholder: "phone number",
            },
            dropdownOptions: {
                showDialCodeInSelection: false,
                showFlags: true,
                showDialCodeInList: true,
            },
            autoDefaultCountry: false,
            validCharactersOnly: true,
            mode: "international",
        },
        v$: useVuelidate(),
        form: {
            name: "",
            phone: "",
            email: "",
            password: "",
            confirmPassword: "",
            invalidPhone: true,
            showInvalidPhone: false,
            shopName: "",
            shopPhone: "",
            shopAddress: "",
        },
    }),
    components: {
        VueTelInput,
    },
    validations: {
        form: {
            name: { required },
            email: { required, email },
            phone: { required },
            password: { required, minLength: minLength(6) },
            confirmPassword: {
                required,
            },
            shopName: { required },
            shopPhone: { required },
            shopAddress: { required },
        },
    },
    computed: {
        ...mapGetters("app", ["generalSettings", "availableCountries"]),
        nameErrors() {
            const errors = [];
            if (!this.v$.form.name.$dirty) return errors;
            !this.v$.form.name.required &&
                errors.push(this.$i18n.t("this_field_is_required"));
            return errors;
        },
        emailErrors() {
            const errors = [];
            if (!this.v$.form.email.$dirty) return errors;
            !this.v$.form.email.required &&
                errors.push(this.$i18n.t("this_field_is_required"));
            !this.v$.form.email.email &&
                errors.push(
                    this.$i18n.t("this_field_is_required_a_valid_email")
                );
            return errors;
        },
        passwordErrors() {
            const errors = [];
            if (!this.v$.form.password.$dirty) return errors;
            !this.v$.form.password.required &&
                errors.push(this.$i18n.t("this_field_is_required"));
            !this.v$.form.password.minLength &&
                errors.push(
                    this.$i18n.t("password_must_be_minimum_6_characters")
                );
            return errors;
        },
        confirmPasswordErrors() {
            const errors = [];
            if (!this.v$.form.confirmPassword.$dirty) return errors;
            !this.v$.form.confirmPassword.required &&
                errors.push(this.$i18n.t("this_field_is_required"));
                !this.v$.form.password.minLength &&
                errors.push(
                    this.$i18n.t("password_and_confirm_password_should_match")
                );
            return errors;
        },
        shopNameErrors() {
            const errors = [];
            if (!this.v$.form.shopName.$dirty) return errors;
            !this.v$.form.shopName.required &&
                errors.push(this.$i18n.t("this_field_is_required"));
            return errors;
        },
        shopPhoneErrors() {
            const errors = [];
            if (!this.v$.form.shopPhone.$dirty) return errors;
            !this.v$.form.shopPhone.required &&
                errors.push(this.$i18n.t("this_field_is_required"));
            return errors;
        },
        shopAddressErrors() {
            const errors = [];
            if (!this.v$.form.shopAddress.$dirty) return errors;
            !this.v$.form.shopAddress.required &&
                errors.push(this.$i18n.t("this_field_is_required"));
            return errors;
        },
    },
    methods: {
        ...mapActions("auth", ["login"]),
        ...mapMutations("cart", ["removeTempUserId"]),
        ...mapMutations("auth", ["updateChatWindow", "showLoginDialog"]),
        phoneValidate(phone) {
            this.form.invalidPhone = phone.valid ? false : true;
            if (phone.valid) this.form.showInvalidPhone = false;
        },
        async register() {
            if (this.form.invalidPhone) {
                this.form.showInvalidPhone = true;
                return;
            }
            // Prevents form submitting if it has error
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) return;

            this.form.phone = this.form.phone.replace(/\s/g, "");

            this.loading = true;
            const res = await this.call_api("post", "shop/register", this.form);
            if (res.data.success) {
                this.snack({
                    color: "green",
                    message: res.data.message,
                });
                this.$router.push({ name: "ShopConfirmation" });
                // this.resetForm()
            } else {
                this.snack({
                    message: res.data.message,
                    color: "red",
                });
            }
            this.loading = false;
        },
        resetForm() {
            this.form.name = "";
            this.form.phone = "";
            this.form.email = "";
            this.form.password = "";
            this.form.confirmPassword = "";
            this.form.invalidPhone = true;
            this.form.showInvalidPhone = false;
            this.form.shopName = "";
            this.form.shopPhone = "";
            this.form.shopAddress = "";
        },
    },
};
</script>
