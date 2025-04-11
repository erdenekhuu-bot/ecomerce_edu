<template>
    <v-container class="pt-6 pb-14">
        <v-row>
            <v-col cols="12" lg="6" md="8" sm="10" class="mx-auto text-center">
                <img src="/public/assets/img/shop-confirmation.jpg" alt="shop confirmation" class="h-md-300px mw-100">
               <h1>{{ $t(shopRegistrationMessageTitle) }}</h1>
                <div class="fs-16">{{ $t(shopRegistrationMessageContent) }}</div>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    data: () => ({
        // banner: helpers.asset("/assets/img/shop-confirmation.jpg"),
    }),
    computed: {
        ...mapGetters("app", [
            "shopRegistrationMessageTitle",
            "shopRegistrationMessageContent",
        ]),
    },
}
</script>