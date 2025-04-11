<template>
    <v-dialog v-model="isVisible" width="700px" @click:outside="closeDialog">
        <div class="white pa-5 rounded">
            <v-form
                v-on:submit.prevent="rechargeWallet()"
                autocomplete="chrome-off"
            >
                <h3 class="opacity-80 mb-3 fs-18">
                    {{ $t("payment_options") }}
                </h3>
                <v-row class="mb-4">
                    <v-col
                        cols="6"
                        sm="4"
                        md="3"
                        v-for="(paymentMethod, i) in paymentMethods"
                        :key="i"
                        :class="[
                            (paymentMethod.status == 1 &&
                                paymentMethod.code == 'golomt') ||
                            paymentMethod.code == 'qpay'
                                ? ''
                                : 'd-none',
                        ]"
                    >
                        <label class="aiz-megabox d-block">
                            <input
                                type="radio"
                                name="wallet_payment_method"
                                :checked="
                                    selectedPaymentMethod &&
                                    paymentMethod.code ==
                                        selectedPaymentMethod.code
                                "
                                @change="paymentSelected($event, paymentMethod)"
                            />
                            <span
                                class="d-block pa-3 aiz-megabox-elem text-center"
                            >
                                <img
                                    :src="paymentMethod.img"
                                    class="img-fluid w-100"
                                />
                                <span class="fw-700 fs-13">{{
                                    paymentMethod.name
                                }}</span>
                            </span>
                        </label>
                    </v-col>
                    <v-col
                        cols="6"
                        sm="4"
                        md="3"
                        v-for="(
                            offlinePaymentMethod, i
                        ) in offlinePaymentMethods"
                        :key="offlinePaymentMethod.code"
                    >
                        <label class="aiz-megabox d-block">
                            <input
                                type="radio"
                                name="wallet_payment_method"
                                :checked="
                                    selectedPaymentMethod &&
                                    offlinePaymentMethod.code ==
                                        selectedPaymentMethod.code
                                "
                                @change="
                                    paymentSelected(
                                        $event,
                                        offlinePaymentMethod
                                    )
                                "
                            />
                            <span
                                class="d-block pa-3 aiz-megabox-elem text-center"
                            >
                                <img
                                    :src="offlinePaymentMethod.img"
                                    class="img-fluid w-100"
                                />
                                <span class="fw-700 fs-13">{{
                                    offlinePaymentMethod.name
                                }}</span>
                            </span>
                        </label>
                    </v-col>
                </v-row>

                <!-- show authorize net payment method's data -->
                <div
                    v-if="
                        selectedPaymentMethod &&
                        selectedPaymentMethod.code == 'authorizenet'
                    "
                    class="my-3"
                >
                    <h3 class="opacity-80 mb-3 fs-18 text-capitalize">
                        {{ $t("account_details") }}
                    </h3>
                    <div class="border px-2 py-2">
                        <!-- show authorize payment method's inputs -->

                        <v-text-field
                            class="my-2 text-field"
                            :placeholder="$t('please_enter_valid_card_number')"
                            type="text"
                            v-model="authorizeNet.card_number"
                            hide-details="auto"
                            required
                            variant="plain"
                        >
                        </v-text-field>

                        <v-text-field
                            class="my-2"
                            :placeholder="$t('please_enter_cvv')"
                            type="text"
                            v-model="authorizeNet.cvv"
                            hide-details="auto"
                            required
                            variant="plain"
                        >
                        </v-text-field>

                        <v-autocomplete
                            v-model="authorizeNet.expiration_month"
                            :items="months"
                            :label="$t('select_month')"
                            hide-details="auto"
                            class="mb-3"
                            variant="outlined"
                            allow-overflow
                            dense
                            required
                        ></v-autocomplete>
                        <v-autocomplete
                            v-model="authorizeNet.expiration_year"
                            :items="dateLoop"
                            :label="$t('select_year')"
                            hide-details="auto"
                            class="mb-3"
                            variant="outlined"
                            allow-overflow
                            dense
                            required
                        ></v-autocomplete>
                        <!-- show authorize payment method's inputs -->
                    </div>
                </div>

                <!-- show offline payment method's data -->
                <div
                    v-if="
                        selectedPaymentMethod &&
                        selectedPaymentMethod.code.includes('offline_payment')
                    "
                >
                    <h3 class="opacity-80 mb-3 fs-18 text-capitalize">
                        {{ $t("account_details") }}
                    </h3>
                    <div class="border px-2 py-2">
                        <div class="text-capitalize my-1">
                            <span class="font-weight-bold">{{
                                $t("payment_name")
                            }}</span>
                            : {{ selectedPaymentMethod.name }}
                        </div>
                        <div class="text-capitalize my-1">
                            <span class="font-weight-bold">{{
                                $t("payment_type")
                            }}</span>
                            : {{ selectedPaymentMethod.type_show }}
                        </div>
                        <div
                            class="text-capitalize d-flex my-1"
                            v-if="selectedPaymentMethod.description"
                        >
                            <span class="font-weight-bold me-1"
                                >{{ $t("description") }} :</span
                            >
                            <span
                                v-html="selectedPaymentMethod.description"
                            ></span>
                        </div>
                        <div
                            class="text-capitalize"
                            v-if="selectedPaymentMethod.bank_info.length > 0"
                        >
                            <span class="font-weight-bold"
                                >{{ $t("bank_info") }}:</span
                            >
                            <div
                                class="border px-2 py-2 mt-2"
                                v-for="(
                                    bankInfo, i
                                ) in selectedPaymentMethod.bank_info"
                                :key="bankInfo.bank_name"
                            >
                                <div>
                                    {{ $t("bank_name") }}:
                                    {{ bankInfo.bank_name }}
                                </div>
                                <div>
                                    {{ $t("account_name") }}:
                                    {{ bankInfo.account_name }}
                                </div>
                                <div>
                                    {{ $t("account_number") }}:
                                    {{ bankInfo.account_number }}
                                </div>
                                <div>
                                    {{ $t("routing_number") }}:
                                    {{ bankInfo.routing_number }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- show offline payment method's data -->

                <div class="text-right mt-4">
                    <v-btn class="mr-2" elevation="0" @click="closeDialog">{{
                        $t("cancel")
                    }}</v-btn>

                    <v-btn
                        elevation="0"
                        type="submit"
                        color="primary"
                        @click="rechargeWallet"
                        :loading="loading"
                        :disabled="loading"
                        >Худалдаж авах</v-btn
                    >
                </div>
            </v-form>
            <Payment ref="makePayment" />
        </div>
    </v-dialog>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import { mapGetters } from "vuex";
import Payment from "../payment/Payment.vue";

export default {
    props: {
        from: { type: String, default: "/user/wallet" },
        show: { type: Boolean, required: true, default: false },
        order_id: { type: String, required: true, default: "" },
    },
    data: () => ({
        loading: false,
        selectedPaymentMethod: null,
        rechargeAmount: 1,
        transactionId: null,
        v$: useVuelidate(),
        authorizeNet: {
            card_number: "",
            cvv: "",
            expiration_month: "",
            expiration_year: "",
        },
        months: [
            "Jan",
            "Feb",
            "Mar",
            "Apr",
            "May",
            "Jun",
            "Jul",
            "Aug",
            "Sep",
            "Oct",
            "Nov",
            "Dec",
        ],
        dateloop: [],
        receipt: null,
    }),
    validations: {
        rechargeAmount: { required },
        // transactionId: { required },
    },
    components: {
        Payment,
    },
    computed: {
        ...mapGetters("auth", ["currentUser"]),
        ...mapGetters("app", ["paymentMethods", "offlinePaymentMethods"]),
        isVisible: {
            get: function () {
                return this.show;
            },
            set: function (newValue) {},
        },
    },
    created() {
        let dateArray = [];
        let i = "";
        for (
            i = new Date().getFullYear();
            i <= new Date().getFullYear() + 15;
            i++
        ) {
            dateArray.push(i);
        }
        this.dateLoop = dateArray;
    },
    methods: {
        paymentSelected(event, paymentMethod) {
            this.selectedPaymentMethod = paymentMethod;
        },
        closeDialog() {
            this.isVisible = false;
            this.selectedPaymentMethod = null;
            this.rechargeAmount = 1;
            this.transactionId = null;
            this.receipt = null;
            this.$emit("close");
        },
        async rechargeWallet() {
            // Prevents form submitting if it has error
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) return;

            if (!this.selectedPaymentMethod) {
                this.snack({
                    message: this.$i18n.t("please_select_a_payment_method"),
                    color: "red",
                });
                return;
            }

            this.loading = true;

            let formData = new FormData();
            formData.append("payment_method", this.selectedPaymentMethod.code);

            // write code to check in update version of the shop cms if the response is a success.
            const res = await this.call_api(
                "post",
                `user/quote_order/${this.order_id}/store`,
                formData
            );

            if (res.data.success == false) {
                this.snack({
                    message: res.data.message,
                    color: "red",
                });
                this.loading = false;
            }

            setTimeout(() => {
                window.location.reload();
            }, 2 * 1000);
        },
    },
};
</script>
