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
                                        $t("affiliate_withdraw_request_history")
                                    }}
                </h1>

                <v-data-table
                  :headers="headers"
                  :items="getWithdrawRequest"
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

                  <template v-slot:[`item.status`]="{ item }">
                    <v-btn
                      v-if="item.status == 1"
                      x-small
                      color="success"
                      elevation="0"
                    >{{ $t("accepted") }}</v-btn>
                    <v-btn
                      v-else
                      x-small
                      color="info"
                      elevation="0"
                    >{{ $t("pending") }}</v-btn>
                  </template>
                </v-data-table>

                <div class="text-start">
                  <v-pagination
                    v-model="getWithdrawRequestCurrentPage"
                    @update:modelValue="this.fetchWithdrawRequest"
                    :length="getWithdrawRequestLastPage"
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
  }),
  components: {
    WithdrawDialog,
  },
  computed: {
    ...mapGetters("affiliate", [
      "getAffiliateBalance",
      "getWithdrawRequest",
      "getWithdrawRequestCurrentPage",
      "getWithdrawRequestLastPage",
    ]),
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
          title: this.$i18n.t("status"),
          sortable: false,
          align: "end",
          value: "status",
        },
      ];
    },
  },
  methods: {
    ...mapActions("affiliate", [
      "fetchWithdrawRequest",
      "fetchAffiliateBalance",
    ]),
    withdrawDialogClosed() {
      this.withdrawDialogShow = false;
    },
  },
  created() {
    let page = this.$route.query.page || this.currentPage;
    this.fetchWithdrawRequest(page);
    this.fetchAffiliateBalance();
  },
};
</script>
