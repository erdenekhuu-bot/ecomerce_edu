<template>
    <div class="">
        <v-progress-linear
            class="mb-1"
            v-model="progressPercent"
            color="primary"
            background-color="blue-grey lighten-3"
            height="8"
            rounded
            query
        ></v-progress-linear>
        <div class="fs-12 green-text" v-if="progressPercent == 100">
            {{ $t("congratulations_you_reached_the_minimum_order_amount") }}
        </div>
        <div class="fs-12" v-else>
            {{ $t("you_need_to_reach") }}
            <span class="fw-600">{{ format_price(minOrder) }}</span>
            {{ $t("to_place_order_from_this_shop") }}
        </div>
    </div>
</template>

<script>
export default {
    props: {
        shopId: { type: Number, required: true, default: null },
        minOrder: { type: Number, required: true, default: 0 },
        cartPrice: { type: Number, required: true, default: 0 },
    },
    data: () => ({
        progressPercent: 0,
    }),
    watch: {
        cartPrice: {
            immediate: true,
            handler(newVal, oldVal) {
                this.progressPercent =
                    newVal >= this.minOrder
                        ? 100
                        : (newVal * 100) / this.minOrder;
            },
        },
    },
};
</script>
