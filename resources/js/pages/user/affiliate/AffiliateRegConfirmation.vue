<template>
    <v-container class="pt-6 pb-14">
        <v-row>
            <v-col cols="12" lg="6" md="8" sm="10" class="mx-auto text-center">
                <img src="/public/assets/img/shop-confirmation.jpg" alt="confirmation" class="h-md-300px mw-100">
               <h1>{{ $t('registration_for_affiliate_is_successful') }}</h1>
                <div class="fs-16">{{ $t('please_wait_for_admin_approval') }}</div>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
const helpers = "../../utils/helpers";

export default {
    data: () => ({
        // banner: helpers.asset("/public/assets/img/shop-confirmation.jpg"),
    }),
}
</script>