<template>
    <div class="ps-lg-7 pt-4">
        <h1 class="text-h6 fw-700 mb-3">
            {{ $t("order_code") }}: {{ order.code }}
        </h1>

        <Summary :order-details="order" />
    </div>
</template>

<script>
import Summary from "./../../components/order/Summary.vue";
export default {
    data() {
        return {
            dialog: false,
            order: {},
        };
    },
    components: {
        Summary,
    },
    methods: {
        async getDetails(orderCode) {
            const res = await this.call_api("get", `user/quote/${orderCode}`);
            if (res.data.success) {
                this.order = res.data.data;
            } else {
                this.snack({
                    message: res.data.message,
                    color: "red",
                });
                return;
            }
        },
    },
    created() {
        this.getDetails(this.$route.params.code);
    },
};
</script>
