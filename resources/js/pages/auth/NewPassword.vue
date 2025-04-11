<template>
    <div>
        <v-container>
            <v-row>
                <v-col cols="12" lg="6" md="8" sm="10" class="mx-auto">
                    <div
                        class="my-5 my-lg-16 rounded-lg pa-5 border overflow-hidden shadow-light"
                    >
                        <div v-if="resetWith == 'email'" class="text-info mb-3">
                            {{
                                $t(
                                    "a_verification_code_has_been_sent_to_your_email"
                                )
                            }}
                        </div>
                        <div v-else class="text-info mb-3">
                            {{
                                $t(
                                    "a_verification_code_has_been_sent_to_your_phone_number"
                                )
                            }}
                        </div>
                        <h1 class="text-uppercase lh-1 mb-4 d-flex">
                            <span class="display-1 fw-900">{{
                                $t("reset")
                            }}</span>
                            <span
                                class="d-block display-1 fw-900 grey--text text--darken-3 ml-2"
                                >{{ $t("password") }}</span
                            >
                        </h1>
                        <div
                            v-if="resetWith == 'email'"
                            class="fs-16 fw-500 mb-6"
                        >
                            {{
                                $t(
                                    "enter_your_email_address_code__new_password"
                                )
                            }}
                        </div>
                        <div v-else class="fs-16 fw-500 mb-6">
                            {{
                                $t("enter_your_phone_number_code__new_password")
                            }}
                        </div>
                        <v-form
                            ref="loginForm"
                            lazy-validation
                            @submit.prevent="resetPassword()"
                        >
                            <div v-if="resetWith == 'email'" class="mb-4">
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
                            <div v-if="resetWith == 'phone'" class="mb-4">
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
                                    hide-details="auto"
                                    :disabled="loading"
                                    required
                                ></v-otp-input>
                            </div>
                            <div class="mb-4">
                                <div class="mb-1 fs-13 fw-500">
                                    {{ $t("password") }}
                                </div>
                                <v-text-field
                                    variant="plain"
                                    v-model="form.password"
                                    placeholder="* * * * * * * *"
                                    type="password"
                                    class="input-group--focused text-field"
                                    hide-details="auto"
                                    required
                                    outlined
                                ></v-text-field>
                            </div>
                            <p
                                v-for="error of v$.form.password.$errors"
                                :key="error.$uid"
                                class="text-red"
                            >
                                {{ error.$message }}
                            </p>
                            <div class="mb-4">
                                <div class="mb-1 fs-13 fw-500">
                                    {{ $t("confirm_password") }}
                                </div>
                                <v-text-field
                                    variant="plain"
                                    v-model="form.confirmPassword"
                                    placeholder="* * * * * * * *"
                                    type="password"
                                    class="input-group--focused text-field"
                                    hide-details="auto"
                                    required
                                    outlined
                                ></v-text-field>
                                <p
                                    v-for="error of v$.form.confirmPassword
                                        .$errors"
                                    :key="error.$uid"
                                    class="text-red"
                                >
                                    {{ error.$message }}
                                </p>
                            </div>
                            <v-btn
                                x-large
                                class="px-12 mb-4"
                                elevation="0"
                                type="submit"
                                color="primary"
                                :loading="loading"
                                :disabled="loading"
                                @click="resetPassword"
                                >{{ $t("reset_password") }}</v-btn
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
import {
    required,
    email,
    minLength,
    sameAs,
    requiredIf,
} from "@vuelidate/validators";
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
        v$: useVuelidate(),
        form: {
            email: "",
            code: "",
            password: "",
            confirmPassword: "",
            invalidPhone: true,
            showInvalidPhone: false,
        },
        resetWith: "email",
        loading: false,
    }),
    components: {
        VueTelInput,
    },
    validations: {
        form: {
            email: {
                requiredIf: requiredIf(function () {
                    return this.resetWith == "email";
                }),
                email,
            },
            phone: {
                requiredIf: requiredIf(function () {
                    return this.resetWith == "phone";
                }),
            },
            code: {
                required,
            },
            password: { required, minLength: minLength(6) },
            confirmPassword: { required },
        },
    },
    computed: {
        ...mapGetters("app", ["availableCountries"]),
        ...mapGetters("auth", ["authSettings"]),
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

            if (this.resetWith == "phone" && this.form.invalidPhone) {
                this.form.showInvalidPhone = true;
                return;
            }
            this.form.code = this.form.code.replace(/\s/g, "");
            this.loading = true;
            const res = await this.call_api(
                "post",
                "auth/password/reset",
                this.form
            );
            if (res.data.success) {
                this.$router.push({ name: "Login" });
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
    created() {
        if (this.$route.params.email) {
            this.form.email = this.$route.params.email;
        }
        if (this.$route.params.phone) {
            this.form.phone = this.$route.params.phone;
            this.resetWith = "phone";
        } else if (
            this.authSettings.customer_login_with == "phone" ||
            (this.authSettings.customer_login_with == "email_phone" &&
                this.authSettings.customer_otp_with == "phone")
        ) {
            this.resetWith = "phone";
        }
    },
};
</script>
