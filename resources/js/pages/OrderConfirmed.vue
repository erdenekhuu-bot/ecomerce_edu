<template>
    <div v-show="check" class="custom-window" @click="globalClose"></div>
    <v-container class="pt-7">
        <v-row>
            <v-col
                xl="8"
                lg="10"
                cols="12"
                class="mx-auto"
                v-if="!is_empty_obj(order)"
            >
                <div class="text-center py-5">
                    <h1>{{ $t("thank_you_for_your_order") }}</h1>
                    <div>
                        Order Code :
                        <span class="secondary--text">{{ order.code }}</span>
                    </div>
                    <div class="font-italic" v-if="order.user.email">
                        {{
                            $t("a_copy_of_your_order_summary_has_been_sent_to")
                        }}
                        {{ order.user.email }}
                    </div>
                    <img
                        v-if="order.orders[0].payment_type === 'qpay'"
                        :src="
                            'data:image/png;base64,' +
                            order.orders[0].qpay_qrimage
                        "
                        alt="QR Code"
                    />
                </div>
                <Summary
                    :order-details="order"
                    :ordercode="code"
                    :forward="forward"
                />
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import Summary from "../components/order/Summary.vue";
export default {
    data: () => {
        return {
            order: {},
            code: "",
            forward: 0,
        };
    },
    components: {
        Summary,
    },
    computed: {
        check() {
            return this.$store.state.auth.modalWindow;
        },
    },
    methods: {
        async getDetails() {
            const res = await this.call_api(
                "get",
                `user/order/${this.$route.query.orderCode}`
            );
            if (res.data.success) {
                this.order = res.data.data;
                this.code = res.data.data.code;
                this.forward =
                    res.data.data.orders[0].payment_type == "qpay" ? 0 : 1;
            } else {
                this.snack({
                    message: res.data.message,
                    color: "red",
                });
                return;
            }
        },
        globalClose() {
            this.$store.commit("auth/modalWindow", false);
        },
    },
    created() {
        this.getDetails();
    },
};
</script>
