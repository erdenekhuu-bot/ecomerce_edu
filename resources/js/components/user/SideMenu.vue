<template>
    <v-card class="shadow-none rounded-0" style="font-family: OpenSans-Bold">
        <div class="pa-5 text-center">
            <v-avatar size="126" class="mb-5">
                <img
                    :src="currentUser.avatar"
                    @error="imageFallback($event)"
                    class="user-dashboard-profile-image"
                />
            </v-avatar>
            <h4 class="fs-22 fw-500 mb-0 lh-1">{{ currentUser.name }}</h4>
            <div class="text-truncate opacity-60">{{ currentUser.email }}</div>
        </div>
        <v-list nav class="px-0 user-side-nav" style="font-size: 14px">
            <UserMenu />
        </v-list>
    </v-card>
</template>

<script>
import { mapGetters } from "vuex";
import UserMenu from "./UserMenu.vue";
export default {
    components: { UserMenu },
    data: () => ({}),
    computed: {
        ...mapGetters("auth", ["currentUser"]),
    },
};
</script>
