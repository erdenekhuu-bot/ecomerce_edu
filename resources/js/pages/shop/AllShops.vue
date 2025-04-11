<template>
  <div>
    <banner
      :loading="false"
      :banner="$store.getters['app/banners'].all_shops_page"
      class="mb-5"
    />
    <v-container class="py-6">
      <v-row
        align="center"
        class="mb-3"
      >
        <v-col
          cols="12"
          sm
        >
          <h1 class="fs-21">{{ $t('all_shops') }}</h1>
        </v-col>
        <v-col
          cols="6"
          sm="auto"
        >
          <v-autocomplete
            v-model="selectedCategoryId"
            :items="allCategories"
            item-title="name"
            item-value="id"
            :menu-props="{ offsetY: true }"
            clear-icon="las la-times fs-14"
            append-inner-icon="las la-angle-down fs-14"
            :placeholder="$t('sort_by_category')"
            :label="$t('sort_by_category')"
            :hint="$t('sort_by_category')"
            :no-data-text="$t('no_data_available')"
            density="compact"
            flat
            solo
            variant="outlined"
            hide-details
            clearable
            @update:modelValue="categoryChange"
          >
          </v-autocomplete>
        </v-col>
        <v-col
          cols="6"
          sm="auto"
        >
          <v-autocomplete
            v-model="selectedBrandId"
            :items="allBrands"
            item-title="name"
            item-value="id"
            :menu-props="{ offsetY: true }"
            clear-icon="las la-times fs-14"
            append-inner-icon="las la-angle-down fs-14"
            :placeholder="$t('sort_by_brand')"
            :label="$t('sort_by_brand')"
            :hint="$t('sort_by_brand')"
            :no-data-text="$t('no_data_available')"
            density="compact"
            flat
            solo
            variant="outlined"
            hide-details
            clearable
            @update:modelValue="brandChange"
          >
          </v-autocomplete>
        </v-col>
      </v-row>
      <v-row class="row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4">
        <v-col
          v-for="(shop, i) in allShops"
          :key="i"
        >
          <shop-box
            :shop-details="shop"
            :is-loading="loading"
          />
        </v-col>
      </v-row>
      <div
        class="text-center"
        v-if="totalPages > 1"
      >
        <v-pagination
          v-model="queryParam.page"
          class="my-4"
          :length="totalPages"
          prev-icon="las la-angle-left"
          next-icon="las la-angle-right"
          :total-visible="7"
          elevation="0"
          @update:modelValue="pageSwitch"
        ></v-pagination>
      </div>
    </v-container>
  </div>
</template>

<script>
import ShopBox from "../../components/shop/ShopBox.vue";
export default {
  head: {
    title: 'All Shops',
  },
  data: () => ({
    loading: true,
    totalPages: 1,
    allCategories: [],
    allBrands: [],
    allShops: [{}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}, {}],
    queryParam: {
      page: 1,
      categoryId: null,
      brandId: null,
    },
    selectedCategoryId: "",
    selectedBrandId: "",
  }),
  components: {
    ShopBox,
  },
  computed: {},
  methods: {
    async getBrands() {
      const res = await this.call_api("get", "all-brands");
      if (res.data.success) {
        this.allBrands = res.data.data;
        this.selectedBrandId = this.allBrands.some(
          (brand) => brand.id == this.$route.query.brandId
        )
          ? this.allBrands.find(
              (brand) => brand.id == this.$route.query.brandId
            ).id
          : null;
    this.selectedBrandId= this.$i18n.t("select");
      }
    },
    async getCategories() {
      const res = await this.call_api("get", "categories/first-level");
      if (res.data.success) {
        this.allCategories = res.data.data;
        this.selectedCategoryId = this.allCategories.some(
          (category) => category.id == this.$route.query.categoryId
        )
          ? this.allCategories.find(
              (category) => category.id == this.$route.query.categoryId
            ).id
          : null;
          this.selectedCategoryId= this.$i18n.t("select");

      }
    },
    categoryChange(id) {
      this.queryParam.categoryId = id;
      this.queryParam.page = 1;
      this.$router
        .push({
          query: {
            ...this.$route.query,
            categoryId: this.queryParam.categoryId,
            page: this.queryParam.page,
          },
        })
        .catch(() => {});
      this.getShops({ categoryId: id });
    },
    brandChange(id) {
      this.queryParam.brandId = id;
      this.$router
        .push({
          query: {
            ...this.$route.query,
            brandId: this.queryParam.brandId,
            page: this.queryParam.page,
          },
        })
        .catch(() => {});
      this.getShops({ brandId: id });
    },
    pageSwitch(pageNumber) {
      this.$router
        .push({ query: { ...this.$route.query, page: this.queryParam.page } })
        .catch(() => {});
      this.getShops({ page: pageNumber });
    },
    async getShops(obj) {
      this.loading = true;
      let params = { ...this.queryParam, ...obj };

      let url = "all-shops?";
      url += `&page=${this.queryParam.page}`;
      url += params.categoryId ? `&category_id=${params.categoryId}` : "";
      url += params.brandId ? `&brand_id=${params.brandId}` : "";

      const res = await this.call_api("get", url);
      if (res.data.success) {
        this.allShops = res.data.data;
        this.totalPages = res.data.meta.last_page;
        this.queryParam.page = res.data.meta.current_page;
        this.loading = false;
      }
    },
  },
  created() {
    this.selectedCategoryId= this.$i18n.t("select"),
    this.selectedBrandId= this.$i18n.t("select"),
    this.getCategories();
    this.getBrands();

    this.queryParam.page = this.$route.query.page || this.queryParam.page;
    this.queryParam.brandId =
      this.$route.query.brandId || this.queryParam.brandId;
    this.queryParam.categoryId =
      this.$route.query.categoryId || this.queryParam.categoryId;

    this.getShops({
      page: this.queryParam.page,
      brandId: this.queryParam.brandId,
      categoryId: this.queryParam.categoryId,
    });
  },
};
</script>