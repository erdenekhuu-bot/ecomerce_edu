<template>
    <div>
        <div class="ps-lg-7 pt-4">
            <h1 class="fs-21 fw-700 opacity-80 mb-5">{{ $t("affiliate") }}</h1>
            <v-row>
                <v-col cols="12" sm="3">
                    <v-sheet
                        color="grey-darken-3"
                        rounded="rounded"
                        elevation="0"
                        height="130"
                        class="d-flex justify-center align-center white--text flex-column"
                    >
                        <div class="fs-14 mb-3 fw-700">
                            {{ $t("affiliate_balance") }}
                        </div>
                        <div class="fw-500 text-h4">
                            {{ getAffiliateBalance }}
                        </div>
                    </v-sheet>
                </v-col>
                <v-col cols="12" sm="3">
                    <config-payout-dialog
                        :show="ConfigPayoutDialogShow"
                        @close="ConfigPayoutDialogClosed"
                    />
                    <v-btn
                        class="border border-gray-300 h-100 py-6"
                        elevation="0"
                        block
                        x-large
                        @click.stop="ConfigPayoutDialogShow = true"
                    >
                        <span>
                            <div class="fs-14 mb-3 w-100">
                                {{ $t("configure_payout") }}
                            </div>
                            <i class="las la-cog la-3x opacity-70"></i>
                        </span>
                    </v-btn>
                </v-col>
                <v-col cols="12" sm="3">
                    <withdraw-dialog
                        :show="withdrawDialogShow"
                        @close="withdrawDialogClosed"
                    />
                    <v-btn
                        class="border-dashed bg-grey-lighten-4 border-gray-300 h-100 py-6"
                        elevation="0"
                        block
                        x-large
                        @click.stop="withdrawDialogShow = true"
                    >
                        <span>
                            <div class="fs-14 mb-3 w-100">
                                {{ $t("affiliate_withdraw_request") }}
                            </div>
                            <i class="las la-plus la-3x opacity-70"></i>
                        </span>
                    </v-btn>
                </v-col>
                <v-col cols="12" sm="3">
                    <wallet-convert-dialog
                        :show="WalletConvertDialogShow"
                        @close="WalletConvertDialogClosed"
                    />
                    <v-btn
                        class="border-dashed bg-grey-darken-1 border-gray-300 h-100 py-6"
                        elevation="0"
                        block
                        x-large
                        @click.stop="WalletConvertDialogShow = true"
                    >
                        <span>
                            <div class="fs-14 mb-3 w-100">
                                {{ $t("convert_to_wallet") }}
                            </div>
                            <i class="las la-plus la-3x opacity-70"></i>
                        </span>
                    </v-btn>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="12" sm="12">
                    <v-card class="mx-auto" prepend-icon="mdi-home">
                        <v-card-text>
                            <v-text-field
                                readonly
                                v-model="getReferralCode"
                                ref="textToCopy"
                                id="referralLink"
                            ></v-text-field>
                            <v-btn @click="copyText(getReferralCode)"
                                >copy</v-btn
                            >
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
            <!-- Affiliate Status Start -->
            <v-row>
                <v-col cols="12" sm="12">
                    <v-card class="mx-auto">
                        <v-card-text>
                            <h1 class="fs-21 fw-700 opacity-80 mb-5">
                                {{ $t("affiliate_status") }}
                            </h1>
                            <v-row>
                                <v-col cols="12" sm="3">
                                    <v-btn
                                        class="border border-gray-300 h-100 py-6"
                                        elevation="0"
                                        block
                                        x-large
                                    >
                                        <span>
                                            <h1>{{ getTotalClick }}</h1>
                                            <div class="fs-14 mb-3 w-100">
                                                {{ $t("no_of_click") }}
                                            </div>
                                        </span>
                                    </v-btn>
                                </v-col>
                                <v-col cols="12" sm="3">
                                    <v-btn
                                        class="border border-gray-300 h-100 py-6"
                                        elevation="0"
                                        block
                                        x-large
                                    >
                                        <span>
                                            <h1>{{ getTotalItem }}</h1>
                                            <div class="fs-14 mb-3 w-100">
                                                {{ $t("no_of_item") }}
                                            </div>
                                        </span>
                                    </v-btn>
                                </v-col>
                                <v-col cols="12" sm="3">
                                    <v-btn
                                        class="border border-gray-300 h-100 py-6"
                                        elevation="0"
                                        block
                                        x-large
                                    >
                                        <span>
                                            <h1>{{ getTotalDelevered }}</h1>
                                            <div class="fs-14 mb-3 w-100">
                                                {{ $t("no_of_delivered") }}
                                            </div>
                                        </span>
                                    </v-btn>
                                </v-col>
                                <v-col cols="12" sm="3">
                                    <v-btn
                                        class="border border-gray-300 h-100 py-6"
                                        elevation="0"
                                        block
                                        x-large
                                    >
                                        <span>
                                            <h1>{{ getTotalCancel }}</h1>
                                            <div class="fs-14 mb-3 w-100">
                                                {{ $t("no_of_cancel") }}
                                            </div>
                                        </span>
                                    </v-btn>
                                </v-col>
                            </v-row>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
            <!-- Affiliate Data tables Start -->
            <!-- <v-row>
                <affiliate-earning-history></affiliate-earning-history>
            </v-row>

            <v-row>
                <affiliate-payment-history></affiliate-payment-history>
            </v-row>

            <v-row>
                <affiliate-payment-withdraw></affiliate-payment-withdraw>
            </v-row> -->
            <!-- Affiliate Data tables Ends -->
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import AffiliateEarningHistory from "../../../components/affiliate/AffiliateEarningHistory.vue";
import AffiliatePaymentHistory from "../../../components/affiliate/AffiliatePaymentHistory.vue";
import AffiliatePaymentWithdraw from "../../../components/affiliate/AffiliatePaymentWithdraw.vue";
import ConfigPayoutDialog from "../../../components/affiliate/ConfigPayoutDialog.vue";
import WithdrawDialog from "../../../components/affiliate/WithdrawDialog.vue";
import WalletConvertDialog from "../../../components/affiliate/WalletConvertDialog.vue";
export default {
    data: () => ({
        withdrawDialogShow: false,
        WalletConvertDialogShow: false,
        ConfigPayoutDialogShow: false,
        loading: true,
        currentPage: 1,
        totalPages: 1,
        products: [],
        referrelCode: "",
    }),
    components: {
        WithdrawDialog,
        ConfigPayoutDialog,
        AffiliateEarningHistory,
        AffiliatePaymentHistory,
        AffiliatePaymentWithdraw,
        WalletConvertDialog,
    },
    computed: {
        ...mapGetters("affiliate", [
            "getAffiliateBalance",
            "getReferralCode",
            "getTotalClick",
            "getTotalItem",
            "getTotalDelevered",
            "getTotalCancel",
        ]),
    },
    methods: {
        ...mapActions("affiliate", [
            "fetchAffiliateBalance",
            "fetchAffiliateReferralCode",
            "fetchAffiliateStats",
        ]),
        copyText(referrelCode) {
            // navigator.clipboard.writeText(referrelCode);
            const textArea = document.createElement("textarea");
            textArea.value = referrelCode;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand("copy");
            document.body.removeChild(textArea);
        },
        withdrawDialogClosed() {
            this.withdrawDialogShow = false;
        },
        WalletConvertDialogClosed() {
            this.WalletConvertDialogShow = false;
        },
        ConfigPayoutDialogClosed() {
            this.ConfigPayoutDialogShow = false;
        },
    },
    created() {
        this.fetchAffiliateBalance();
        this.fetchAffiliateReferralCode();
        this.fetchAffiliateStats();
    },
};
</script>
