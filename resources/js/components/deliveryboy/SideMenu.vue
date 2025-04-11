<template>
    <v-card class="border-end shadow-none rounded-0">
        <div class="pa-5 text-center border-bottom">
            <v-avatar size="160" class="mb-5">
                <img
                    :src="currentUser.avatar"
                    @error="imageFallback($event)"
                    class="border border-4 user-dashboard-profile-image"
                />
            </v-avatar>
            <h4 class="fs-22 fw-500 mb-0 lh-1">{{ currentUser.name }}</h4>
            <div class="text-truncate opacity-60">{{ currentUser.email }}</div>
        </div>
        
        <v-list nav class="px-0 user-side-nav">
            <DeliveryBoyMenu/>
        </v-list>
    </v-card>
</template>

<script>
import { mapGetters } from "vuex";
import DeliveryBoyMenu from '../deliveryboy/DeliveryBoyMenu.vue';
export default {
    components: { DeliveryBoyMenu },
    data: () => ({
        
    }),
    computed: {
        ...mapGetters("auth",["currentUser"]),
    },
};
</script>
