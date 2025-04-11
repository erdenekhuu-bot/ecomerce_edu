<template>
    <div
        class="rounded-lg pa-10 border border-gray-200 overflow-hidden white shadow-light"
    >
        <v-row no-gutters align="center">
            <v-col cols="12" lg="6" order="2" order-lg="1">
                <banner
                    :loading="false"
                    :banner="$store.getters['app/banners'].login_page"
                    class="mt-5 mt-lg-0"
                />
            </v-col>
            <v-col cols="12" order="1" order-lg="2" lg="6">
                <div class="px-lg-7">
                    <h1 class="text-uppercase lh-1 mb-6">
                        <span class="d-block display-1 fs-34 fw-900">{{
                            $store.getters["app/appName"]
                        }}</span>
                        <span class="opacity-50 fs-22 fw-400">{{
                            $t("account")
                        }}</span>
                    </h1>
                    <v-form ref="loginForm" @submit.prevent="login()">
                        <div
                            v-if="
                                authSettings.customer_login_with == 'email' ||
                                (!showPhoneField &&
                                    authSettings.customer_login_with ==
                                        'email_phone')
                            "
                            class="mb-3"
                        >
                            <div class="mb-1 fs-13 fw-500">
                                {{ $t("email") }}
                            </div>
                            <v-text-field
                                variant="plain"
                                class="text-field"
                                v-model="form.email"
                                placeholder="Имэйл хаяг эсвэл Утасны дугаар"
                                type="text"
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

                            <div
                                v-if="
                                    authSettings.customer_login_with ==
                                    'email_phone'
                                "
                                class="text-end font-italic fs-12 opacity-70"
                            >
                                <span
                                    class="c-pointer"
                                    @click="showPhoneField = !showPhoneField"
                                    >{{ $t("use_phone_instead") }}</span
                                >
                            </div>
                        </div>
                        <div
                            v-if="
                                authSettings.customer_login_with == 'phone' ||
                                (showPhoneField &&
                                    authSettings.customer_login_with ==
                                        'email_phone')
                            "
                            class="mb-3"
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
                                    class="v-messages theme-light error-text"
                                    role="alert"
                                >
                                    <div class="v-messages__wrapper">
                                        <div class="v-messages__message">
                                            {{ $t("this_field_is_required") }}
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
                                    class="v-messages theme-light error-text"
                                    role="alert"
                                >
                                    <div class="v-messages__wrapper">
                                        <div class="v-messages__message">
                                            {{
                                                $t("phone_number_must_be_valid")
                                            }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div
                                v-if="
                                    authSettings.customer_login_with ==
                                    'email_phone'
                                "
                                class="text-end font-italic fs-12 opacity-70"
                            >
                                <span
                                    class="c-pointer"
                                    @click="showPhoneField = !showPhoneField"
                                    >{{ $t("use_phone_instead") }}</span
                                >
                            </div>
                        </div>
                        <div>
                            <div class="mb-1 fs-13 fw-500">
                                {{ $t("password") }}
                            </div>
                            <v-text-field
                                v-model="form.password"
                                placeholder="* * * * * * * *"
                                :type="passwordShow ? 'text' : 'password'"
                                :append-icon="
                                    passwordShow
                                        ? 'las la-eye'
                                        : 'las la-eye-slash'
                                "
                                class="input-group-focused text-field"
                                variant="plain"
                                hide-details="auto"
                                required
                                outlined
                                @click:append="passwordShow = !passwordShow"
                            ></v-text-field>
                            <p
                                v-for="error of v$.form.password.$errors"
                                :key="error.$uid"
                                class="text-red"
                            >
                                {{ error.$message }}
                            </p>
                        </div>
                        <v-row class="mt-1">
                            <v-col>
                                <v-btn
                                    style="color: #fff !important"
                                    size="x-large"
                                    class="px-16 mb-4"
                                    elevation="0"
                                    type="submit"
                                    color="primary"
                                    :loading="loading"
                                    :disabled="loading"
                                    @click="login"
                                    >{{ $t("login") }}</v-btn
                                ></v-col
                            >
                            <v-col>
                                <div class="text-end fs-12 opacity-70">
                                    <router-link
                                        :to="{ name: 'ForgotPassword' }"
                                        style="color: black"
                                        >{{
                                            $t("forgot_password") + " ?"
                                        }}</router-link
                                    >
                                </div></v-col
                            >
                        </v-row>
                    </v-form>

                    <!-- <div
                        v-if="
                            generalSettings.social_login.facebook == 1 ||
                            generalSettings.social_login.twitter == 1 ||
                            generalSettings.social_login.google == 1
                        "
                    >
                        <div class="d-flex align-center mb-3">
                            <span class="me-2 fs-13 fw-500 opacity-60 w-110px"
                                >{{ $t("or") }}, {{ $t("login_with") }}</span
                            >
                            <v-divider></v-divider>
                        </div>
                        <SocialLogin class="mb-5" />
                    </div> -->

                    <!-- <div>
                        {{ $t("dont_have_an_account") }},
                        <router-link
                            :to="{ name: 'Registration' }"
                            class="text-primary text-decoration-underline"
                            >{{ $t("signup") }}</router-link
                        >
                    </div> -->
                </div>
            </v-col>
        </v-row>
    </div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { email, required, requiredIf } from "@vuelidate/validators";
import { VueTelInput } from "vue-tel-input";

import { mapActions, mapGetters, mapMutations } from "vuex";
import SocialLogin from "../auth/SocialLogin.vue";
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
            phone: "",
            email: "",
            password: "",
            invalidPhone: true,
            showInvalidPhone: false,
        },
        passwordShow: false,
        loading: false,
    }),
    components: {
        SocialLogin,
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
            password: {
                required,
            },
        },
    },
    computed: {
        ...mapGetters("app", [
            "generalSettings",
            "availableCountries",
            "demoMode",
            "banners",
        ]),
        ...mapGetters("cart", ["getTempUserId"]),
        ...mapGetters("auth", ["authSettings", "currentUser"]),
    },
    methods: {
        ...mapActions("auth", {
            actionLogin: "login",
        }),
        ...mapActions("app", ["fetchProductQuerries"]),
        ...mapActions("wishlist", ["fetchWislistProducts"]),
        ...mapActions("cart", ["fetchCartProducts"]),
        ...mapMutations("cart", ["removeTempUserId"]),
        ...mapMutations("auth", ["updateChatWindow", "showLoginDialog"]),
        phoneValidate(phone) {
            this.form.invalidPhone = phone.valid ? false : true;
            if (phone.valid) this.form.showInvalidPhone = false;
        },
        async login() {
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
            if (this.getTempUserId) {
                this.form.temp_user_id = this.getTempUserId;
            }
            this.form.phone = this.form.phone.replace(/\s/g, "");
            this.form.form_type = "customer";
            this.loading = true;
            const res = await this.call_api("post", "auth/login", this.form);
            if (res.data.success) {
                if (
                    res.data.verified == true ||
                    this.authSettings.customer_otp_with == "disabled"
                ) {
                    if (this.getTempUserId) {
                        this.removeTempUserId();
                    }
                    this.actionLogin(res.data);
                    this.showLoginDialog(false);
                    this.updateChatWindow(false);
                    this.fetchWislistProducts();
                    this.fetchProductQuerries();
                    this.fetchCartProducts();
                    this.$router.push(
                        //  { name: "DeliveryBoyDashboard" }
                        this.$route.query.redirect || { name: "DashBoard" }
                    );
                } else {
                    if (
                        this.authSettings.customer_login_with == "email" ||
                        (this.authSettings.customer_login_with ==
                            "email_phone" &&
                            this.authSettings.customer_otp_with == "email")
                    ) {
                        this.$router.push({
                            name: "VerifyAccount",
                            params: { email: this.form.email },
                        });
                    } else {
                        this.$router.push({
                            name: "VerifyAccount",
                            params: { phone: this.form.phone },
                        });
                    }
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
    created() {
        if (this.demoMode) {
            this.form.email = "customer@example.com";
            this.form.password = "123456";
        }
    },
};
</script>
