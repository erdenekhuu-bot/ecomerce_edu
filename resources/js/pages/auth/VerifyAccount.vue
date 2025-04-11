<template>
    <div>
        <v-container>
            <v-row>
                <v-col cols="12" lg="6" md="8" sm="10" class="mx-auto">
                    <div
                        class="my-5 my-lg-16 rounded-lg pa-5 border overflow-hidden shadow-light"
                    >
                        <div
                            v-if="
                                authSettings.customer_login_with == 'email' ||
                                (authSettings.customer_login_with ==
                                    'email_phone' &&
                                    authSettings.customer_otp_with == 'email')
                            "
                            class="text-info mb-3"
                        >
                            {{
                                $t(
                                    "a_verification_code_has_been_sent_to_your_email"
                                )
                            }}
                        </div>
                        <div
                            v-else-if="
                                authSettings.customer_login_with == 'phone' ||
                                (authSettings.customer_login_with ==
                                    'email_phone' &&
                                    authSettings.customer_otp_with == 'phone')
                            "
                            class="text-info mb-3"
                        >
                            {{
                                $t(
                                    "a_verification_code_has_been_sent_to_your_phone_number"
                                )
                            }}
                        </div>
                        <h1 class="text-uppercase lh-1 mb-4 d-flex">
                            <span class="display-1 fw-00">{{
                                $t("verify")
                            }}</span>
                            <span
                                class="d-block display-1 fw-900 grey--text text--darken-3 ml-2"
                                >{{ $t("account") }}</span
                            >
                        </h1>
                        <div
                            v-if="
                                authSettings.customer_login_with == 'email' ||
                                (authSettings.customer_login_with ==
                                    'email_phone' &&
                                    authSettings.customer_otp_with == 'email')
                            "
                            class="fs-16 fw-500 mb-6"
                        >
                            {{
                                $t("enter_your_email_address_verification_code")
                            }}
                        </div>
                        <div
                            v-else-if="
                                authSettings.customer_login_with == 'phone' ||
                                (authSettings.customer_login_with ==
                                    'email_phone' &&
                                    authSettings.customer_otp_with == 'phone')
                            "
                            class="fs-16 fw-500 mb-6"
                        >
                            {{
                                $t("enter_your_phone_number_verification_code")
                            }}
                        </div>
                        <v-form
                            ref="loginForm"
                            lazy-validation
                            @submit.prevent="verifyAccount()"
                        >
                            <div
                                v-if="
                                    authSettings.customer_login_with ==
                                        'email' ||
                                    (authSettings.customer_login_with ==
                                        'email_phone' &&
                                        authSettings.customer_otp_with ==
                                            'email')
                                "
                                class="mb-4"
                            >
                                <div class="mb-1 fs-13 fw-500">
                                    {{ $t("email") }}
                                </div>
                                <v-text-field
                                    variant="plain"
                                    class="text-field"
                                    v-model="form.email"
                                    :placeholder="$t('email_address')"
                                    type="email"
                                    hide-details="auto"
                                    required
                                    outlined
                                ></v-text-field>
                                <p
                                    v-for="error of v$.form.email.$errors"
                                    :key="error.$uid"
                                    class="text-red"
                                >
                                    {{ error.$message }}
                                </p>
                            </div>
                            <div
                                v-if="
                                    authSettings.customer_login_with ==
                                        'phone' ||
                                    (authSettings.customer_login_with ==
                                        'email_phone' &&
                                        authSettings.customer_otp_with ==
                                            'phone')
                                "
                                class="mb-4"
                            >
                                <div class="mb-1 fs-13 fw-500">
                                    {{ $t("phone_number") }}
                                </div>
                                <vue-tel-input
                                    v-model="form.phone"
                                    v-bind="mobileInputProps"
                                    :only-countries="availableCountries"
                                    @validate="phoneValidate"
                                >
                                    <template #arrow-icon
                                        ><span class="vti__dropdown-arrow"
                                            >&nbsp;▼</span
                                        ></template
                                    >
                                </vue-tel-input>
                                <div
                                    v-if="v$.form.phone.$error"
                                    class="v-text-field__details mt-2 pl-3"
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
                                    v-if="
                                        !v$.form.phone.$error &&
                                        form.showInvalidPhone
                                    "
                                    class="v-text-field__details mt-2 pl-3"
                                >
                                    <div
                                        class="v-messages theme--light error--text"
                                        role="alert"
                                    >
                                        <div class="v-messages__wrapper">
                                            <div class="v-messages__message">
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
                                <div class="mb-1 fs-13 fw-500">
                                    {{ $t("code") }}
                                </div>
                                <v-otp-input
                                    v-model="form.code"
                                    length="6"
                                    type="number"
                                    :error-messages="codeErrors"
                                    hide-details="auto"
                                    :disabled="loading"
                                    required
                                ></v-otp-input>
                            </div>
                            <v-btn
                                size="x-large"
                                class="px-12 mb-4 btn-primary"
                                elevation="0"
                                type="submit"
                                :loading="loading"
                                :disabled="loading"
                                @click="verifyAccount"
                                >{{ $t("verify") }}</v-btn
                            >
                            <v-btn
                                size="x-large"
                                class="px-12 mb-4 ms-3"
                                elevation="0"
                                type="button"
                                color="grey-lighten-4"
                                :loading="resendLoading"
                                :disabled="resendLoading"
                                @click="resendCode"
                                >{{ $t("resend_code") }}</v-btn
                            >
                        </v-form>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { email, required, requiredIf } from "@vuelidate/validators";
import { VueTelInput } from "vue-tel-input";
import { mapActions, mapGetters, mapMutations } from "vuex";
export default {
    data: () => ({
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
            email: "",
            phone: "",
            code: "",
            invalidPhone: true,
            showInvalidPhone: false,
        },
        loading: false,
        resendLoading: false,
    }),
    components: {
        VueTelInput,
    },
    validations: {
        form: {
            email: {
                requiredIf: requiredIf(function () {
                    return (
                        this.authSettings.customer_login_with == "email" ||
                        (this.authSettings.customer_login_with ==
                            "email_phone" &&
                            this.authSettings.customer_otp_with == "email")
                    );
                }),
                email,
            },
            phone: {
                requiredIf: requiredIf(function () {
                    return (
                        this.authSettings.customer_login_with == "phone" ||
                        (this.authSettings.customer_login_with ==
                            "email_phone" &&
                            this.authSettings.customer_otp_with == "phone")
                    );
                }),
            },
            code: {
                required,
            },
        },
    },
    computed: {
        ...mapGetters("auth", ["authSettings"]),
        ...mapGetters("app", ["availableCountries"]),
    },
    methods: {
        ...mapActions("auth", {
            actionLogin: "login",
        }),
        ...mapMutations("auth", ["updateChatWindow", "showLoginDialog"]),
        ...mapActions("app", ["fetchProductQuerries"]),
        ...mapActions("wishlist", ["fetchWislistProducts"]),
        ...mapActions("cart", ["fetchCartProducts"]),
        phoneValidate(phone) {
            this.form.invalidPhone = phone.valid ? false : true;
            if (phone.valid) this.form.showInvalidPhone = false;
        },
        async verifyAccount() {
            // Prevents form submitting if it has error
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) return;

            if (
                (this.authSettings.customer_login_with == "phone" ||
                    (this.authSettings.customer_login_with == "email_phone" &&
                        this.authSettings.customer_otp_with == "phone")) &&
                this.form.invalidPhone
            ) {
                this.form.showInvalidPhone = true;
                return;
            }
            this.form.phone = this.form.phone.replace(/\s/g, "");

            this.loading = true;
            const res = await this.call_api("post", "auth/verify", this.form);
            if (res.data.success) {
                this.actionLogin(res.data);
                this.showLoginDialog(false);
                this.updateChatWindow(false);

                this.fetchWislistProducts();
                this.fetchProductQuerries();
                this.fetchCartProducts();

                this.$router.push(
                    this.$route.query.redirect || { name: "DashBoard" }
                );
            } else {
                this.snack({
                    message: res.data.message,
                    color: "red",
                });
            }
            this.loading = false;
        },
        async resendCode() {
            this.v$.form.email.$touch();
            if (this.v$.form.email.$anyError) {
                return;
            }
            if (
                (this.authSettings.customer_login_with == "phone" ||
                    (this.authSettings.customer_login_with == "email_phone" &&
                        this.authSettings.customer_otp_with == "phone")) &&
                this.form.invalidPhone
            ) {
                this.form.showInvalidPhone = true;
                return;
            }
            this.form.phone = this.form.phone.replace(/\s/g, "");

            this.resendLoading = true;
            const res = await this.call_api(
                "post",
                "auth/resend-code",
                this.form
            );

            if (res.data.success) {
                this.snack({
                    message: res.data.message,
                });
            } else {
                this.snack({
                    message: res.data.message,
                    color: "red",
                });
            }
            this.resendLoading = false;
        },
    },
    created() {
        if (this.$route.params.email) {
            this.form.email = this.$route.params.email;
        }
        if (this.$route.params.phone) {
            this.form.phone = this.$route.params.phone;
        }
    },
};
</script>
