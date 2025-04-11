<template>
  <v-col>
    <div class="mt-4">
      <v-card class="mx-auto">
        <v-card-text>
          <h1 class="fs-21 fw-700 opacity-80 mb-5">
            {{ $t("affiliate_withdraw_request_history") }}
          </h1>

          <v-data-table
            :headers="headers"
            :items="getWithdrawRequest"
            class="border px-4 pt-3"
            hide-default-footer
            item-class="c-pointer"
          >
            <template v-slot:[`item.date`]="{ item }">
              <span class="d-block fw-600"> {{ item.date }}</span>
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
              >{{ $t("accepted") }}</v-btn>
              <v-btn
                v-else-if="item.status == 2"
                x-small
                color="error"
              >{{ $t("rejected") }}</v-btn>
              <v-btn
                v-else
                x-small
                color="info"
              >{{
                                $t("pending")
                            }}</v-btn>
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
</template>

<script>
import { mapActions, mapGetters } from "vuex";
export default {
  data: () => ({
    loading: true,
  }),

  computed: {
    ...mapGetters("affiliate", [
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
    ...mapActions("affiliate", ["fetchWithdrawRequest"]),
  },
  created() {
    let page = this.$route.query.page || this.currentPage;
    this.fetchWithdrawRequest(page);
  },
};
</script>
