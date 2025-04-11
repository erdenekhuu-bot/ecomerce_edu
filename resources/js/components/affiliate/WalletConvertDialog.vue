<template>
    <v-dialog
        v-model="isVisible"
        max-width="700px"
        @click:outside="closeDialog"
    >
        <div class="white pa-5 rounded">
            <v-form lazy-validation v-on:submit.prevent>
                <!-- show authorize net payment method's data -->

                <h3 class="opacity-80 mb-3 fs-18 mt-3">
                    {{ $t("affiliate_balance_convert_to_wallet") }}
                </h3>
                <v-text-field
                    variant="plain"
                    class="text-field"
                    :placeholder="$t('converted_amount')"
                    type="Number"
                    v-model="requestedAmount"
                    hide-details="auto"
                    required
                    outlined
                ></v-text-field>
                <p v-for="error of v$.requestedAmount.$errors" :key="error.$uid" class="text-red">
                  {{error.$message }}
              </p>

                <div class="text-right mt-4">
                    <v-btn text @click="closeDialog" elevation="0" class="mr-4">{{ $t("cancel") }}</v-btn>
                    <v-btn
                        elevation="0"
                        type="submit"
                        color="primary"
                        @click="withdrawalRequest"
                        :loading="loading"
                        :disabled="loading"
                        >{{ $t("confirm") }}</v-btn
                    >
                </div>
            </v-form>
        </div>
    </v-dialog>
</template>

<script>
import { useVuelidate } from '@vuelidate/core';
import { minValue, required } from "@vuelidate/validators";
import { mapActions, mapGetters } from "vuex";
export default {
    props: {
        from: { type: String, default: "/user/wallet" },
        show: { type: Boolean, required: true, default: false },
    },
    data: () => ({
        v$: useVuelidate(),
        loading: false,
        requestedAmount: 0,
    }),
    validations: {
        requestedAmount: { required, minValue: minValue(1) },
    },

    computed: {
        ...mapGetters("affiliate", ["getAffiliateBalance",]),
       
        isVisible: {
            get: function () {
                return this.show;
            },
            set: function (newValue) {},
        },
    },

    methods: {
        ...mapActions("affiliate", ["fetchWithdrawRequest","fetchAffiliateBalance"]),
        closeDialog() {
            this.isVisible = false;
            this.receipt = null;
            this.$emit("close");
        },
        async withdrawalRequest() {
            this.isVisible = false;
            let affiliateBalance = Math.round(parseFloat(this.getAffiliateBalance.replace(/[$,]/g, '')));
            // Prevents form submitting if it has error
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) return;

            if(affiliateBalance < this.requestedAmount){
                this.snack({
                message: this.$i18n.t("you can't request for convert more than your affiliate balance"),
                color: "red",
            });
            return;
            }

            this.loading = false;
            const amount = {
                amount: this.requestedAmount,
            };
            // write code to check in update version of the shop cms if the response is a success.
            const res = await this.call_api(
                "post",
                `user/affiliate/convert-request`,
                amount
            );
            if(res.data.success){
                this.fetchAffiliateBalance();
            this.requestedAmount = 0;
            this.snack({
                message: this.$i18n.t(res.data.message),
                color: "green",
            });
            this.loading = false;
            this.closeDialog();
            this.isVisible = false;
            }else{
                this.snack({
                        message: res.data.message,
                        color: "red",
                    });
            }
        },
        
    },
};
</script>
