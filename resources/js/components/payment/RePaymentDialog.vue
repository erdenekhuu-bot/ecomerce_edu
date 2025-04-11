<template>
    <v-dialog
      v-model="isVisible"
      width="700px"
      @click:outside="closeDialog"
    >
      <div class="white pa-5 rounded">
        <v-form
          v-on:submit.prevent="rePayment()"
          autocomplete="chrome-off"
        >
          <h3 class="opacity-80 mb-3 fs-18">{{ $t('payment_options') }}</h3>
          <v-row class="mb-4">
            <v-col
              cols="6"
              sm="4"
              md="3"
              v-for="(paymentMethod, i) in paymentMethods"
              :key="i"
              :class="[paymentMethod.status == 1 && paymentMethod.code != 'cash_on_delivery' ? '' : 'd-none']"
            >
              <label class="aiz-megabox d-block">
                <input
                  type="radio"
                  name="wallet_payment_method"
                  :checked="selectedPaymentMethod && paymentMethod.code == selectedPaymentMethod.code"
                  @change="paymentSelected($event,paymentMethod)"
                >
                <span class="d-block pa-3 aiz-megabox-elem text-center">
                  <img
                    :src="paymentMethod.img"
                    class="img-fluid w-100"
                  >
                  <span class="fw-700 fs-13">{{ paymentMethod.name }}</span>
                </span>
              </label>
            </v-col>
            <v-col
              cols="6"
              sm="4"
              md="3"
              v-for="(offlinePaymentMethod, i) in offlinePaymentMethods"
              :key="offlinePaymentMethod.code"
            >
              <label class="aiz-megabox d-block">
                <input
                  type="radio"
                  name="wallet_payment_method"
                  :checked="selectedPaymentMethod && offlinePaymentMethod.code == selectedPaymentMethod.code"
                  @change="paymentSelected($event,offlinePaymentMethod)"
                >
                <span class="d-block pa-3 aiz-megabox-elem text-center">
                  <img
                    :src="offlinePaymentMethod.img"
                    class="img-fluid w-100"
                  >
                  <span class="fw-700 fs-13">{{ offlinePaymentMethod.name }}</span>
                </span>
              </label>
            </v-col>
          </v-row>
  
          <!-- show authorize net payment method's data -->
          <div
            v-if="selectedPaymentMethod && selectedPaymentMethod.code == 'authorizenet'"
            class="my-3"
          >
            <h3 class="opacity-80 mb-3 fs-18 text-capitalize">{{ $t('account_details') }}</h3>
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
                class="my-2 text-field"
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
          <div v-if="selectedPaymentMethod && selectedPaymentMethod.code.includes('offline_payment')">
            <h3 class="opacity-80 mb-3 fs-18 text-capitalize">{{ $t('account_details') }}</h3>
            <div class="border px-2 py-2">
              <div class="text-capitalize my-1"><span class="font-weight-bold">{{ $t('payment_name') }}</span> : {{ selectedPaymentMethod.name }}</div>
              <div class="text-capitalize my-1"><span class="font-weight-bold">{{ $t('payment_type') }}</span> : {{ selectedPaymentMethod.type_show }}</div>
              <div
                class="text-capitalize d-flex my-1"
                v-if="selectedPaymentMethod.description"
              >
                <span class="font-weight-bold me-1">{{ $t('description') }} :</span>
                <span v-html="selectedPaymentMethod.description"></span>
              </div>
              <div
                class="text-capitalize"
                v-if="selectedPaymentMethod.bank_info.length>0"
              >
                <span class="font-weight-bold">{{ $t('bank_info') }}:</span>
                <div
                  class="border px-2 py-2 mt-2"
                  v-for="(bankInfo, i) in selectedPaymentMethod.bank_info"
                  :key="bankInfo.bank_name"
                >
                  <div>{{ $t('bank_name') }}: {{ bankInfo.bank_name }}</div>
                  <div>{{ $t('account_name') }}: {{ bankInfo.account_name }}</div>
                  <div>{{ $t('account_number') }}: {{ bankInfo.account_number }}</div>
                  <div>{{ $t('routing_number') }}: {{ bankInfo.routing_number }}</div>
                </div>
              </div>
            </div>
          </div>
          <!-- show offline payment method's data -->
  
          <h3 class="opacity-80 mb-3 fs-18 mt-3 text-center">{{ $t('total_order_amount') }} {{  combinedOrder.grand_total}}</h3>
  



        <div v-if="selectedPaymentMethod && selectedPaymentMethod.code.includes('offline_payment')">

          <v-text-field
            class="my-2 text-field"
            :placeholder="$t('transaction_id')"
            type="text"
            v-model="transactionId"
            hide-details="auto"
            required
            variant="plain"
          >
          </v-text-field>


          <v-file-input
            accept="image/*"
            :label="$t('add_receipt')"
            :placeholder="$t('add_receipt')"
            flat
            variant="plain"
            class="my-2 text-field"
            prepend-icon=""
            clearable
            v-model="receipt"
          ></v-file-input>
        </div>

          <div class="text-right mt-4">
            <v-btn
            class="mr-2"
            elevation="0"
              @click="closeDialog"
            >{{ $t('cancel') }}</v-btn>
  
            <v-btn
              elevation="0"
              type="submit"
              class="btn-primary"
              @click="rePayment"
              :loading="loading"
              :disabled="loading"
            >{{ $t('pay_now') }}</v-btn>
          </div>
        </v-form>
        <Payment ref="makePayment" />
      </div>
    </v-dialog>
  </template>
  
  <script>
  
  import { useVuelidate } from '@vuelidate/core';
  import { mapGetters } from "vuex";
  import Payment from "../payment/Payment.vue";
  
  export default {
    props: {
      from: { type: String, default: "/user/purchase-history" },
      show: { type: Boolean, required: true, default: false },
      combinedOrder: { type: Object, default: {} },
    },
    data: () => ({
      loading: false,
      selectedPaymentMethod: null,
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
        this.transactionId = null;
        this.receipt = null;
        this.$emit("close");
      },
      async rePayment() {

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
  
  
        if (
          this.selectedPaymentMethod &&
          this.selectedPaymentMethod.code.includes("offline_payment") &&
          this.transactionId === null
        ) {
          this.snack({
            message: this.$i18n.t("please_input_transaction_id"),
            color: "red",
          });
          return;
        }
  
        if (
          this.selectedPaymentMethod &&
          this.selectedPaymentMethod.code.includes("offline_payment") &&
          !isFormCorrect
        ) {
          return;
        }
  
        this.loading = true;
  
        // call api based on offline / online payment
        if (this.selectedPaymentMethod.code.includes("offline_payment")) {
          let formData = new FormData();
          formData.append("redirect_to", this.from);
          formData.append("amount", this.combinedOrder.grand_total);
          formData.append("payment_method", this.selectedPaymentMethod.code);
          formData.append("payment_type", "repayment");
          formData.append("user_id", this.currentUser.id);
          formData.append("order_code", this.combinedOrder.code);
          formData.append("transactionId", this.transactionId);
          formData.append("receipt", this.receipt);
  
          // write code to check in update version of the shop cms if the response is a success.
          await this.call_api(
            "post",
            `payment/${this.selectedPaymentMethod.code}/pay`,
            formData,
            true
          );
          this.snack({
            message: this.$i18n.t("please_wait_for_approval"),
            color: "green",
          });
  
          setTimeout(() => {
            window.location.reload();
          }, 2 * 1000);
        } else {
          this.$refs.makePayment.pay({
            requestedFrom: this.from,
            paymentAmount: this.combinedOrder.grand_total,
            paymentMethod: this.selectedPaymentMethod.code,
            paymentType: "repayment",
            userId: this.currentUser.id,
            oderCode: this.combinedOrder.code,
            transactionId: null,
            receipt: null,
            card_number: this.authorizeNet.card_number,
            cvv: this.authorizeNet.cvv,
            expiration_month:
              this.months.indexOf(this.authorizeNet.expiration_month) + 1,
            expiration_year: this.authorizeNet.expiration_year,
          });
        }
      },
    },
  };
  </script>