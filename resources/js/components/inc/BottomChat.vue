<template>
    <div v-if="generalSettings.support_chat">
        <!-- v-if="currentUser.user_type == 'customer'" -->
        <button
            :class="[
                'bottom-chat-button text-center fw-500 fs-12 px-6 py-4 btn-primary',
                chatWindowOpen ? 'd-none' : 'd-none d-lg-block',
            ]"
            style="color: white; z-index: 20"
            type="button"
            @click.stop="openChatWindow"
        >
            <span class="d-flex align-center white-text">
                <img :src="static_asset('/assets/img/chat.svg')" height="30" />
                <span class="fs-14 fw-700 ms-2">{{ $t("talk_with_us") }}</span>
                <i class="las la-angle-up ms-auto"></i>
            </span>
        </button>
        <div
            :class="[
                'has-transition white bottom-chat-window',
                chatWindowOpen ? 'd-block h-auto' : 'd-none h-60px',
            ]"
        >
            <div class="d-flex bg-grey-lighten-3 pa-3 align-center">
                <img
                    :src="generalSettings.chat.customer_chat_logo"
                    class="size-30px"
                />
                <span class="mx-3 fs-14 fw-700">{{
                    generalSettings.chat.customer_chat_name
                }}</span>
                <button
                    class="ms-auto"
                    type="button"
                    @click.stop="closeChatWindow"
                >
                    <i class="la la-close fs-20"></i>
                </button>
            </div>
            <div
                v-if="isAuthenticated"
                class="bg-white c-scrollbar chat-box d-flex flex-column pa-4"
            >
                <ul
                    ref="chatList"
                    class="list-unstyled mt-auto px-0 d-flex flex-column"
                >
                    <li v-for="(message, i) in messages" :key="i" class="mb-2">
                        <div class="fs-10 opacity-60 text-center">
                            {{ message.time }}
                        </div>
                        <div
                            v-if="message.user_id == currentUser.id"
                            class="d-flex text-end ps-8 own"
                        >
                            <div class="flex-grow-1 message fs-13">
                                <span>{{ message.message }}</span>
                            </div>
                        </div>
                        <div v-else class="d-flex pe-8">
                            <v-avatar size="36px" class="me-2">
                                <img
                                    alt="Avatar"
                                    :src="
                                        generalSettings.chat.customer_chat_logo
                                    "
                                />
                            </v-avatar>
                            <div class="flex-grow-1 message fs-13">
                                {{ message.message }}
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div
                v-else
                class="text-center px-5 py-7 chat-box bg-white c-scrollbar chat-box d-flex flex-column pa-4 align-center"
            >
                <img
                    :src="static_asset('/assets/img/chat-login.png')"
                    class="chat-static-avatar"
                />
                <div class="fw-500">
                    {{ $t("you_have_to") }}
                    <router-link :to="{ name: 'Login' }" class="primary-text">{{
                        $t("login")
                    }}</router-link>
                    {{ $t("or") }}
                    <router-link
                        :to="{ name: 'Registration' }"
                        class="primary-text"
                        >{{ $t("register") }}</router-link
                    >
                    {{ $t("as_a_customer_to_contact_us") }}
                </div>
            </div>
            <div class="bg-grey-lighten-3 pa-4">
                <v-form class="bg-white rounded-pill" @submit.prevent>
                    <v-row no-gutters align="center">
                        <v-col>
                            <v-text-field
                                v-model="chat.message"
                                variant="plain"
                                flat
                                hide-details
                                class="border-0 px-3 chat-input-field"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="auto">
                            <v-btn
                                size="small"
                                type="submit"
                                icon="$vuetify"
                                class="me-1 shadow-none"
                                :disabled="sending"
                                @click.native="sendMessage"
                            >
                                <i class="las la-paper-plane fs-20"></i>
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-form>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters, mapMutations } from "vuex";
export default {
    data: () => ({
        sending: false,
        messages: [],
        chat: {
            message: "",
        },
    }),
    computed: {
        ...mapGetters("auth", [
            "chatWindowOpen",
            "currentUser",
            "isAuthenticated",
        ]),
        ...mapGetters("app", ["generalSettings"]),
    },
    watch: {
        chatWindowOpen(newValue) {
            if (newValue && this.isAuthenticated) {
                this.getOldChats();
                this.getNewMessages();
            }
        },
    },
    methods: {
        ...mapMutations("auth", ["updateChatWindow"]),
        openChatWindow() {
            this.updateChatWindow(true);
            if (this.isAuthenticated) {
                this.getOldChats();
                this.getNewMessages();
            }
        },
        closeChatWindow() {
            this.updateChatWindow(false);
        },
        async sendMessage() {
            this.sending = true;
            if (this.isAuthenticated && this.chat.message) {
                const res = await this.call_api(
                    "post",
                    "user/chats/send",
                    this.chat
                );
                if (res.data.success) {
                    this.chat.message = "";
                    this.messages.push(res.data.data);

                    this.chatScrollToBottom();
                } else {
                    this.snack({ message: res.data.message });
                }
                this.sending = false;
            }
        },
        async getOldChats() {
            const res = await this.call_api("get", "user/chats");
            if (res.data.success) {
                this.messages = res.data.data.data;
                this.chatScrollToBottom();
            }
        },
        chatScrollToBottom() {
            setTimeout(() => {
                const el = this.$refs.chatList.lastElementChild;
                if (el) {
                    el.scrollIntoView({ behavior: "smooth" });
                }
            }, 100);
        },
        getNewMessages() {
            setInterval(async () => {
                const res = await this.call_api(
                    "get",
                    "user/chats/new-messages"
                );
                if (res.data.success && res.data.data.data.length > 0) {
                    this.messages = [...this.messages, ...res.data.data.data];
                    this.chatScrollToBottom();
                }
            }, 5000);
        },
    },
    created() {
        if (this.isAuthenticated && this.chatWindowOpen) {
            this.getOldChats();
            this.getNewMessages();
        }
    },
};
</script>
