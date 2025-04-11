<template>
    <v-dialog
        v-model="dialog"
        max-width="600"
        @keydown.esc="close"
    >
        <template v-slot:default="dialog">
            <v-card>
                <v-toolbar color="red" dark elevation="0" class="text-h6">{{ $t('payment_failed') }}</v-toolbar>
                <v-card-text class="text-center px-12 pt-12 pb-5">
                    <div class="mb-4 fs-16">{{ $t('your_order_has_been_placed_but_failed_to_complete_the_payment_please_try_again_or_contact_our_support') }}</div>

                    <v-btn color="primary" elevation="0" large class="px-7" @click.native="tryAgain">{{ $t('try_again') }}</v-btn>
                </v-card-text>
                <v-card-actions class="justify-end">
                    <v-btn text @click="dialog.value = false">{{ $t('close') }}</v-btn>
                </v-card-actions>
            </v-card>
        </template>
    </v-dialog>
</template>

<script>
export default {
    data() {
        return {
            dialog: false,
            orderCode: null,
            paymentMethod: null,
        };
    },
    methods: {
        open({orderCode,paymentMethod}) {
            this.dialog = true;
            this.orderCode = orderCode;
            this.paymentMethod = paymentMethod;
        },
        close() {
            this.dialog = false;
        },
        tryAgain() {
            this.$parent.$refs.makePayment.pay({
                requestedFrom: '/checkout',
                paymentAmount: 0,
                paymentMethod: this.paymentMethod,
                paymentType: 'cart_payment',
                userId: this.$parent.currentUser.id,
                oderCode: this.orderCode,
            })
        },
    },
};
</script>