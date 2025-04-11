<template>
    <div>
        <v-container>
            <v-row>
                <v-col xl="10" class="mx-auto">
                    <div
                        class="my-5 my-lg-16 rounded-lg pa-5 border overflow-hidden shadow-light"
                    >
                        <v-row no-gutters align="center">
                            <v-col
                                cols="12"
                                lg="6"
                                order="2"
                                order-lg="1"
                                class="lh-0"
                            >
                                <banner
                                    :loading="false"
                                    :banner="
                                        $store.getters['app/banners']
                                            .forgot_page
                                    "
                                    class="mt-5 mt-lg-0"
                                />
                            </v-col>
                            <v-col cols="12" order="1" order-lg="2" lg="6">
                                <div class="px-lg-7">
                                    <h1 class="text-uppercase lh-1 mb-4">
                                        <span
                                            class="display-1 primary--text fw-900"
                                            >{{ $t("forgot") }}</span
                                        >
                                        <span
                                            class="d-block display-1 fw-900 grey--text text--darken-3"
                                            >{{ $t("password") }}</span
                                        >
                                        <span
                                            class="fs-22 fw-900 display-3 primary--text"
                                            >?</span
                                        >
                                    </h1>
                                    <div
                                        class="fs-16 fw-500 mb-6"
                                        v-if="
                                            authSettings.customer_login_with ==
                                            'email'
                                        "
                                    >
                                        {{
                                            $t(
                                                "enter_your_email_address_to_recover_your_password"
                                            )
                                        }}
                                    </div>
                                    <div
                                        class="fs-16 fw-500 mb-6"
                                        v-else-if="
                                            authSettings.customer_login_with ==
                                            'phone'
                                        "
                                    >
                                        {{
                                            $t(
                                                "enter_your_phone_number_to_recover_your_password"
                                            )
                                        }}
                                    </div>
                                    <div
                                        class="fs-16 fw-500 mb-6"
                                        v-else="
                                            authSettings.customer_login_with ==
                                            'phone'
                                        "
                                    >
                                        {{
                                            $t(
                                                "enter_your_email_address_or_phone_number_to_recover_your_password"
                                            )
                                        }}
                                    </div>
                                    <v-form
                                        ref="loginForm"
                                        lazy-validation
                                        v-on:submit.prevent="resetPassword()"
                                    >
                                        <div
                                            class="mb-6"
                                            v-if="
                                                authSettings.customer_login_with ==
                                                    'email' ||
                                                (!showPhoneField &&
                                                    authSettings.customer_login_with ==
                                                        'email_phone')
                                            "
                                        >
                                            <div class="mb-1 fs-13 fw-500">
                                                {{ $t("email") }}
                                            </div>
                                            <v-text-field
                                                variant="plain"
                                                class="text-field"
                                                :placeholder="
                                                    $t('email_address')
                                                "
                                                type="email"
                                                v-model="form.email"
                                                hide-details="auto"
                                                required
                                                outlined
                                            ></v-text-field>
                                            <p
                                                v-for="error of v$.form.email
                                                    .$errors"
                                                :key="error.$uid"
                                                class="text-red"
                                            >
                                                {{ error.$message }}
                                            </p>
                                            <!-- <div class="text-end font-italic fs-12 opacity-70" v-if="authSettings.customer_login_with == 'email_phone'">
                                                <span @click="showPhoneField = !showPhoneField" class="text-primary" >{{ $t("use_phone_instead") }}</span>
                                            </div> -->

                                            <div
                                                v-if="
                                                    authSettings.customer_login_with ==
                                                    'email_phone'
                                                "
                                                class="text-end font-italic fs-12 opacity-70"
                                            >
                                                <span
                                                    class="c-pointer"
                                                    @click="
                                                        showPhoneField =
                                                            !showPhoneField
                                                    "
                                                    >{{
                                                        $t("use_phone_instead")
                                                    }}</span
                                                >
                                            </div>
                                        </div>
                                        <div
                                            class="mb-6"
                                            v-if="
                                                authSettings.customer_login_with ==
                                                    'phone' ||
                                                (showPhoneField &&
                                                    authSettings.customer_login_with ==
                                                        'email_phone')
                                            "
                                        >
                                            <div class="mb-1 fs-13 fw-500">
                                                {{ $t("phone_number") }}
                                            </div>
                                            <vue-tel-input
                                                v-model="form.phone"
                                                v-bind="mobileInputProps"
                                                :onlyCountries="
                                                    availableCountries
                                                "
                                                @validate="phoneValidate"
                                            >
                                                <template slot="arrow-icon"
                                                    ><span
                                                        class="vti__dropdown-arrow"
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
                                                    <div
                                                        class="v-messages__wrapper"
                                                    >
                                                        <div
                                                            class="v-messages__message"
                                                        >
                                                            {{
                                                                $t(
                                                                    "this_field_is_required"
                                                                )
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
                                                    <div
                                                        class="v-messages__wrapper"
                                                    >
                                                        <div
                                                            class="v-messages__message"
                                                        >
                                                            {{
                                                                $t(
                                                                    "phone_number_must_be_valid"
                                                                )
                                                            }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div
                                                class="text-end font-italic fs-12 opacity-70"
                                                v-if="
                                                    authSettings.customer_login_with ==
                                                    'email_phone'
                                                "
                                            >
                                                <span
                                                    @click="
                                                        showPhoneField =
                                                            !showPhoneField
                                                    "
                                                    class="primary--text"
                                                    >{{
                                                        $t("use_phone_instead")
                                                    }}</span
                                                >
                                            </div>
                                        </div>
                                        <v-btn
                                            x-large
                                            class="px-12 mb-4"
                                            elevation="0"
                                            type="submit"
                                            color="primary"
                                            @click="resetPassword"
                                            :loading="loading"
                                            :disabled="loading"
                                            >{{
                                                $t("send_password_reset_code")
                                            }}</v-btn
                                        >
                                    </v-form>
                                    <div>
                                        {{ $t("back_to") }}
                                        <router-link
                                            :to="{ name: 'Login' }"
                                            class="primary--text text-decoration-underline"
                                            >{{ $t("login") }}</router-link
                                        >
                                    </div>
                                </div>
                            </v-col>
                        </v-row>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { email, requiredIf } from "@vuelidate/validators";
import { VueTelInput } from "vue-tel-input";
import { mapGetters } from "vuex";
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
        showPhoneField: false,
        v$: useVuelidate(),
        form: {
            email: "",
            phone: "",
            invalidPhone: true,
            showInvalidPhone: false,
        },
        loading: false,
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
                            !this.showPhoneField)
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
                            this.showPhoneField)
                    );
                }),
            },
        },
    },
    computed: {
        ...mapGetters("app", ["availableCountries"]),
        ...mapGetters("auth", ["authSettings"]),
        emailErrors() {
            const errors = [];
            if (!this.v$.form.email.$dirty) return errors;
            !this.v$.form.email.requiredIf &&
                errors.push(this.$i18n.t("this_field_is_required"));
            !this.v$.form.email.email &&
                errors.push(
                    this.$i18n.t("this_field_is_required_a_valid_email")
                );
            return errors;
        },
    },
    methods: {
        phoneValidate(phone) {
            this.form.invalidPhone = phone.valid ? false : true;
            if (phone.valid) this.form.showInvalidPhone = false;
        },
        async resetPassword() {
            // Prevents form submitting if it has error
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) return;

            if (
                (this.authSettings.customer_login_with == "phone" ||
                    (this.authSettings.customer_login_with == "email_phone" &&
                        this.showPhoneField)) &&
                this.form.invalidPhone
            ) {
                this.form.showInvalidPhone = true;
                return;
            }
            this.form.phone = this.form.phone.replace(/\s/g, "");

            this.loading = true;
            const res = await this.call_api(
                "post",
                "auth/password/create",
                this.form
            );
            if (res.data.success) {
                if (res.data.email) {
                    this.$router.push({
                        name: "NewPassword",
                        params: { email: this.form.email },
                    });
                } else {
                    this.$router.push({
                        name: "NewPassword",
                        params: { phone: this.form.phone },
                    });
                }

                this.snack({
                    message: res.data.message,
                });
            } else {
                this.snack({
                    message: res.data.message,
                    color: "red",
                });
            }
            this.loading = false;
        },
    },
};
</script>
