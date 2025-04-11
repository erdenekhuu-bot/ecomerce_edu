<template>
    <v-container class=" pt-7">
        <div class="text-center mb-8">
            <v-row>
                <v-col cols="12" md="8" class="mx-auto">
                    <h1 class="fw-300 text-uppercase">{{ $t('the_page_you_are_looking_was_not') }} <br> {{ $t('in_this_planet') }}</h1>
                    <img :src="banner" alt="404" class="h-md-220px mw-100">
                    <div></div>
                    <router-link :to="{ name: 'Home' }" class="primary--text fs-16 text-uppercase">
                        <i class="las la-angle-double-left"></i>
                        {{ $t('back_to_home') }}
                    </router-link>
                </v-col>
            </v-row>
        </div>
        <h3 class="mb-4 fs-21 fw-700">{{ $t('newly_added_products') }}</h3>
        <v-row class="row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 row-cols-xl-6 guttes-10 mb-4">
            <v-col v-for="(product, i) in products" :key="i">
                <product-box :product-details="product" :is-loading="loading" />
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import helpers from "../../utils/helpers";

export default {
    data: () => ({
        loading: true,
        banner: helpers.asset("/assets/img/404.svg"),
        products:[{},{},{},{},{},{},{},{},{},{},{},{}]
    }),
    async created(){
        const res = await this.call_api("get", `product/latest/12`);
        if(res.data.success){
            this.products = res.data.data
        }else{
            this.snack({
                message: this.$i18n.t("something_went_wrong"),
                color: "red"
            });
        }
        this.loading = false
    }
};
</script>
