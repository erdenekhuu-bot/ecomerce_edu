<template>
    <v-dialog
        v-model="isVisible"
        max-width="700px"
        @click:outside="closeDialog"
    >
        <div class="white pa-5 rounded">
            <v-form
                ref="paymentForm"
                lazy-validation
                v-on:submit.prevent="storePaymentSettings()"
                autocomplete="chrome-off"
            >
                <!-- show authorize net payment method's data -->
                <h3 class="opacity-80 mb-3 fs-18 mt-3">
                    {{ $t("payment_settings") }}
                </h3>
                <v-text-field
                    variant="plain"
                    class="text-field"
                    :placeholder="$t('paypal_email')"
                    type="email"
                    v-model="form.paypalEmail"
                    hide-details="auto"
                    required
                    outlined
                ></v-text-field>
                <p
                    v-for="error of v$.form.paypalEmail.$errors"
                    :key="error.$uid"
                    class="text-red"
                >
                    {{ error.$message }}
                </p>

                <v-text-field
                    variant="plain"
                    class="mt-4 text-field"
                    :placeholder="$t('bank_informations')"
                    type="text"
                    v-model="form.bankInformations"
                    hide-details="auto"
                    required
                    outlined
                ></v-text-field>
                <p
                    v-for="error of v$.form.bankInformations.$errors"
                    :key="error.$uid"
                    class="text-red"
                >
                    {{ error.$message }}
                </p>

                <div class="text-right mt-4">
                    <v-btn
                        elevation="0"
                        class="mr-4"
                        text
                        @click="closeDialog"
                        >{{ $t("cancel") }}</v-btn
                    >
                    <v-btn
                        elevation="0"
                        type="submit"
                        color="primary"
                        @click="storePaymentSettings"
                        :loading="loading"
                        :disabled="loading"
                        >{{ $t("update") }}</v-btn
                    >
                </div>
            </v-form>
        </div>
    </v-dialog>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { required } from "@vuelidate/validators";
export default {
    props: {
        show: { type: Boolean, required: true, default: false },
    },
    data: () => ({
        v$: useVuelidate(),
        loading: false,
        form: {
            paypalEmail: "",
            bankInformations: "",
        },
    }),
    validations: {
        form: {
            paypalEmail: { required },
            bankInformations: { required },
        },
    },
    computed: {
        isVisible: {
            get: function () {
                return this.show;
            },
            set: function (newValue) {},
        },
    },

    methods: {
        closeDialog() {
            this.isVisible = false;
            this.$emit("close");
        },
        async storePaymentSettings() {
            // Prevents form submitting if it has error
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) return;

            this.loading = false;
            // write code to check in update version of the shop cms if the response is a success.
            const res = await this.call_api(
                "post",
                `user/affiliate/payment-settings`,
                this.form
            );
            this.snack({
                message: this.$i18n.t(res.data.message),
                color: "green",
            });
            this.$refs.paymentForm.reset();
            this.loading = false;
            this.closeDialog();
        },
    },
};
</script>
