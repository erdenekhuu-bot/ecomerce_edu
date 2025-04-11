<template>
  <div>
    <div class="ps-lg-7 pt-4">
      <h1 class="fs-21 fw-700 opacity-80 mb-5">{{ $t("affiliate") }}</h1>
      <v-row>
        <v-col
          cols="12"
          sm="6"
        >
          <v-sheet
            color="grey darken-3"
            rounded="rounded"
            elevation="0"
            height="130"
            class="d-flex justify-center align-center white--text flex-column"
          >
            <div class="fs-14 mb-3 fw-700 primary--text">
              {{ $t("affiliate_balance") }}
            </div>
            <div class="fw-500 text-h4">{{ getAffiliateBalance }}</div>
          </v-sheet>
        </v-col>

        <v-col
          cols="12"
          sm="6"
        >
          <withdraw-dialog
            :show="withdrawDialogShow"
            @close="withdrawDialogClosed"
          />
          <v-btn
            class="border-dashed border-gray-300 h-100 py-6"
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
      </v-row>

      <!-- Affiliate Earning History -->
      <v-row>
        <v-col>
          <div class="mt-4">
            <v-card class="mx-auto">
              <v-card-text>
                <h1 class="fs-21 fw-700 opacity-80 mb-5">
                  {{
                                        $t("affiliate_payment_history")
                                    }}
                </h1>

                <v-data-table
                  :headers="headers"
                  :items="paymentHistory"
                  class="border px-4 pt-3"
                  hide-default-footer
                  item-class="c-pointer"
                >
                  <template v-slot:[`item.date`]="{ item }">
                    <span class="d-block fw-600">
                      {{ item.date }}</span>
                  </template>
                  <template v-slot:[`item.amount`]="{ item }">
                    <span class="d-block fw-600">
                      {{ item.amount }}</span>
                  </template>

                  <template v-slot:[`item.payment_method`]="{
                                            item,
                                        }">
                    <span class="d-block fw-600">
                      {{ item.payment_method }}</span>
                  </template>
                </v-data-table>

                <div class="text-start">
                  <v-pagination
                    v-model="currentPage"
                    @update:modelValue="getPaymentHistory"
                    :length="totalPages"
                    prev-icon="las la-angle-left"
                    next-icon="las la-angle-right"
                    :total-visible="7"
                    elevation="0"
                    class="my-4"
                  ></v-pagination>
                </div>
              </v-card-text>
            </v-card>
          </div>
        </v-col>
      </v-row>
      <!-- End of Affiliate Earning History -->
    </div>
  </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import WithdrawDialog from "../../../components/affiliate/WithdrawDialog.vue";
export default {
  data: () => ({
    withdrawDialogShow: false,
    loading: true,
    currentPage: 1,
    totalPages: 1,
    paymentHistory: [],
  }),
  components: {
    WithdrawDialog,
  },
  computed: {
    ...mapGetters("affiliate", ["getAffiliateBalance"]),
    headers() {
      return [
        {
          title: this.$i18n.t("date"),
          align: "start",
          sortable: false,
          value: "date",
        },
        {
          title: this.$i18n.t("amount"),
          align: "start",
          sortable: false,
          value: "amount",
        },
        {
          title: this.$i18n.t("payment_method"),
          sortable: false,
          align: "end",
          value: "payment_method",
        },
      ];
    },
  },
  methods: {
    ...mapActions("affiliate", ["fetchAffiliateBalance"]),
    withdrawDialogClosed() {
      this.withdrawDialogShow = false;
    },
    async getPaymentHistory(page) {
      this.loading = true;
      const res = await this.call_api(
        "get",
        `user/affiliate/payment-history?page=${page}`
      );
      if (res.status == 200) {
        this.paymentHistory = res.data.data;
        this.totalPages = res.data.meta.last_page;
        this.currentPage = res.data.meta.current_page;
      } else {
        this.snack({
          message: this.$i18n.t("something_went_wrong"),
          color: "red",
        });
      }
      this.loading = false;
    },
  },
  created() {
    let page = this.$route.query.page || this.currentPage;
    this.getPaymentHistory(page);
    this.fetchAffiliateBalance();
  },
};
</script>
