<template>
    <div>
        <v-list-item
            v-for="(cart_item, i) in cartItems"
            :key="cart_item.cart_id"
            class="px-0 py-3"
            :class="[
                { 'text-reset': cart_item.outOfStock },
                { 'border-top': i != 0 },
            ]"
        >
            <div class="w-100">
                <div class="position-relative">
                    <v-chip
                        class="absolute-top-left white-text z-1"
                        v-if="cart_item.outOfStock"
                        color="red"
                        x-small
                        label
                        >{{ $t("out_of_stock") }}</v-chip
                    >
                    <div
                        :class="[
                            'd-flex align-center',
                            { 'opacity-50': cart_item.outOfStock },
                        ]"
                    >
                        <v-checkbox
                            true-icon="las la-check"
                            hide-details
                            class="mt-0 pt-0"
                            :model-value="cart_item.selected"
                            :disabled="cart_item.outOfStock"
                            @update:modelValue="
                                toggleCartItem({
                                    cart_id: cart_item.cart_id,
                                    status: $event,
                                })
                            "
                        />
                        <div class="flex-shrink-0 lh-0">
                            <img
                                :src="cart_item.thumbnail"
                                :alt="cart_item.name"
                                class="img-fluid size-70px"
                                @error="imageFallback($event)"
                            />
                        </div>
                        <div class="flex-grow-1 minw-0 ms-3">
                            <div class="text-truncate fs-12 opacity-80 mb-2">
                                {{ cart_item.name }}
                            </div>
                            <div
                                :class="[
                                    'd-flex align-center',
                                    { 'pointer-disbled': cart_item.outOfStock },
                                ]"
                            >
                                <v-btn
                                    color="success"
                                    size="x-small"
                                    class="rounded cart-btn-minus text-white"
                                    elevation="0"
                                    fab
                                    @click="
                                        updateQuantity({
                                            type: 'minus',
                                            cart_id: cart_item.cart_id,
                                        })
                                    "
                                >
                                    <i class="las la-minus" />
                                </v-btn>

                                <span class="mx-3 fs-12"
                                    >{{ $t("qty") }} : {{ cart_item.qty }}</span
                                >

                                <v-btn
                                    color="success"
                                    size="x-small"
                                    class="rounded cart-btn-plus"
                                    elevation="0"
                                    fab
                                    x-small
                                    @click="
                                        updateQuantity({
                                            type: 'plus',
                                            cart_id: cart_item.cart_id,
                                        })
                                    "
                                >
                                    <i class="las la-plus" />
                                </v-btn>
                            </div>
                        </div>
                        <div class="flex-shrink-0 w-80px text-end">
                            <del
                                v-if="
                                    cart_item.regular_price >
                                    cart_item.dicounted_price
                                "
                                class="opacity-50"
                            >
                                {{
                                    format_price(
                                        cart_item.regular_price * cart_item.qty
                                    )
                                }}
                            </del>
                            <div class="">
                                {{
                                    format_price(
                                        cart_item.dicounted_price *
                                            cart_item.qty
                                    )
                                }}
                            </div>
                        </div>
                        <div class="ms-4">
                            <button
                                class=""
                                @click="removeFromCart(cart_item.cart_id)"
                            >
                                <i class="las la-trash fs-20 opacity-50" />
                            </button>
                        </div>
                    </div>
                </div>
                <div v-if="cart_item.combinations.length > 0" class="ms-5">
                    <span
                        v-for="(combination, j) in cart_item.combinations"
                        :key="j"
                        class="px-4 py-1 fs-12"
                    >
                        <span class="opacity-70">{{
                            combination.attribute
                        }}</span>
                        :
                        <span class="fw-500">{{ combination.value }}</span>
                    </span>
                </div>
            </div>
        </v-list-item>
    </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
    props: {
        cartItems: { type: Array, required: true, default: [] },
    },
    methods: {
        ...mapActions("cart", [
            "updateQuantity",
            "toggleCartItem",
            "removeFromCart",
        ]),
    },
};
</script>
