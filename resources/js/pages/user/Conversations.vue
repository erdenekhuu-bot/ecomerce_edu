<template>
    <div v-show="check" class="custom-window" @click="globalClose"></div>
    <div class="ps-lg-7 pt-4" style="font-family: OpenSans-Bold">
        <h1 class="fs-24 fw-700 opacity-80 mb-5 mt-3">
            {{ $t("my_querries") }}
        </h1>
        <div v-if="loading">
            <div class="text-center">
                <div>{{ $t("loading_please_wait") }}</div>
            </div>
        </div>
        <div v-else>
            <v-card v-if="getProductQuerries.length > 0">
                <div
                    v-for="(conversation, i) in getProductQuerries"
                    :key="i"
                    :class="[
                        'py-2',
                        { 'border-bottom': i + 1 != getProductQuerries.length },
                    ]"
                >
                    <ConversationBox :conversation="conversation" />
                </div>
            </v-card>
            <div v-else class="text-center">
                <div>{{ $t("sorry_nothing_found") }}</div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import ConversationBox from "../../components/conversation/ConversationBox.vue";
export default {
    data: () => ({
        loading: false,
    }),
    components: {
        ConversationBox,
    },
    computed: {
        ...mapGetters("app", ["getProductQuerries"]),
        check() {
            return this.$store.state.auth.modalWindow;
        },
    },
    methods: {
        globalClose() {
            this.$store.commit("auth/modalWindow", false);
        },
    },
};
</script>
