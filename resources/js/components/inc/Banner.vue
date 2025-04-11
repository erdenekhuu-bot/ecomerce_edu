<template>
    <div>
        <template v-if="loading">
            <v-skeleton-loader type="image" height="145"></v-skeleton-loader>
        </template>
        <template v-else>
            <dynamic-link
                :to="banner.link"
                append-class="text-reset d-block lh-0"
            >
                <img :src="banner.img" class="img-fluid w-100" :alt="appName" />
            </dynamic-link>
        </template>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
export default {
    props: {
        loading: { type: Boolean, required: true, default: true },
        banner: {
            type: Object,
            required: true,
        },
    },
    computed: {
        ...mapGetters("app", ["appName"]),
    },
};
</script>
