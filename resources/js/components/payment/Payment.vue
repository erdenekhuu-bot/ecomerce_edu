<template>
    <form :action="appUrl+'/payment/'+paymentMethod+'/pay'" ref="paymentForm" method="POST" enctype="multipart/form-data">
        <template v-if="triggered">
            <input type="hidden" name="redirect_to" :value="requestedFrom">
            <input type="hidden" name="amount" :value="paymentAmount">
            <input type="hidden" name="payment_method" :value="paymentMethod">
            <input type="hidden" name="payment_type" :value="paymentType">
            <input type="hidden" name="user_id" :value="userId">
            <input type="hidden" name="order_code" :value="oderCode">
            <input type="hidden" name="transactionId" :value="transactionId">
            <input type="hidden" name="receipt" :value="receipt">

            <!-- Authorize Net -->
            <input type="hidden" name="card_number" :value="card_number">
            <input type="hidden" name="cvv" :value="cvv">
            <input type="hidden" name="expiration_month" :value="expiration_month">
            <input type="hidden" name="expiration_year" :value="expiration_year">

        </template>
    </form>
</template>

<script>
import { mapGetters } from "vuex";
export default {
    data() {
        return {
            triggered: false,
            requestedFrom: '',
            paymentAmount: 0 ,
            paymentMethod: '',
            paymentType: '',
            userId: null,
            oderCode: null,
            transactionId: null,
            receipt: null,
            card_number: null,
            cvv: null,
            expiration_month: null,
            expiration_year: null
        }
    },
    computed:{
        ...mapGetters("app",["appUrl"]),
    },
    methods:{
        pay({
            requestedFrom,
            paymentAmount,
            paymentMethod,
            paymentType,
            userId,oderCode,
            transactionId,receipt,
            card_number,cvv,
            expiration_month,
            expiration_year
        }){
            this.triggered = true
            this.requestedFrom  = requestedFrom
            this.paymentAmount  = paymentAmount
            this.paymentMethod  = paymentMethod
            this.paymentType    = paymentType
            this.userId         = userId
            this.oderCode       = oderCode
            this.transactionId  = transactionId
            this.receipt        = receipt
            this.card_number    = card_number
            this.cvv            = cvv
            this.expiration_month = expiration_month
            this.expiration_year = expiration_year
             
            setTimeout(() => {
                this.$refs.paymentForm.submit()
            }, 100);
            
        }
    }
}
</script>