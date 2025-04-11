<template>
    <div>
        <v-container>
            <v-row>
                <v-col xl="10" class="mx-auto my-5 my-lg-16 ">
                    <delivery-boy-login-form />
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
import DeliveryBoyLoginForm from '../../components/auth/DeliveryBoyLogin.vue';
export default {
    components: { DeliveryBoyLoginForm },
};
</script>
