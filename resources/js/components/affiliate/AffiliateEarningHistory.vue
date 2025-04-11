<template>
  <v-col>
    <div class="mt-4">
      <v-card class="mx-auto">
        <v-card-text>
          <h1 class="fs-21 fw-700 opacity-80 mb-5">
            {{ $t("affiliate_earning_history") }}
          </h1>

          <v-data-table
            :headers="headers"
            :items="earningtHistory"
            class="border px-4 pt-3"
            hide-default-footer
            item-class="c-pointer"
          >

          </v-data-table>

          <div class="text-start">
            <v-pagination
              v-model="currentPage"
              @update:modelValue="getEarningHistory"
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
</template>

<script>
export default {
  data: () => ({
    loading: true,
    currentPage: 1,
    totalPages: 1,
    earningtHistory: [],
  }),

  computed: {
    headers() {
      return [
        {
          title: this.$i18n.t("amount"),
          align: "start",
          sortable: false,
          value: "amount",
        },
        {
          title: this.$i18n.t("order_id"),
          sortable: false,
          align: "start",
          value: "order_id",
        },
        {
          title: this.$i18n.t("referrel_type"),
          sortable: false,
          align: "start",
          value: "referrel_type",
        },
        {
          title: this.$i18n.t("product"),
          sortable: false,
          align: "start",
          value: "product",
        },
        {
          title: this.$i18n.t("date"),
          sortable: false,
          align: "start",
          value: "date",
        },
      ];
    },
  },
  methods: {
    async getEarningHistory(page) {
      this.loading = true;
      const res = await this.call_api(
        "get",
        `user/affiliate/earning-history?page=${page}`
      );
      if (res.status == 200) {
        this.earningtHistory = res.data.data;
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
    this.getEarningHistory(page);
  },
};
</script>
