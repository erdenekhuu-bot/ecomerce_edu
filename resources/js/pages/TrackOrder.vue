<template>
    <div v-show="check" class="custom-window" @click="globalClose"></div>
    <div class="pb-6">
        <v-container>
            <v-row>
                <v-col cols="12" lg="6" class="mx-auto">
                    <h1 class="mb-7 mt-4">{{ $t("track_your_order") }}</h1>
                    <v-form lazy-validation v-on:submit.prevent="trackOrder()">
                        <div class="mb-1 fs-13 fw-500">
                            {{ $t("order_code") }}
                        </div>
                        <v-text-field
                            :placeholder="$t('order_code')"
                            type="text"
                            class="mb-3 text-field"
                            v-model="form.orderCode"
                            :error-messages="orderCodeErrors"
                            hide-details="auto"
                            required
                            variant="plain"
                        ></v-text-field>

                        <p
                            v-for="error of v$.form.orderCode.$errors"
                            :key="error.$uid"
                            class="text-red"
                        >
                            {{ error.$message }}
                        </p>

                        <v-btn
                            class="px-16 mb-4"
                            elevation="0"
                            type="submit"
                            color="primary"
                            @click="trackOrder"
                            :loading="loading"
                            :disabled="loading"
                            >{{ $t("track") }}</v-btn
                        >
                    </v-form>
                </v-col>
                <v-col
                    cols="12"
                    xl="8"
                    lg="10"
                    class="mx-auto"
                    v-if="!is_empty_obj(order)"
                >
                    <Summary :order-details="order" />
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { email, required } from "@vuelidate/validators";
import Summary from "../components/order/Summary.vue";
export default {
    head: {
        title: "Order Tracking Page",
    },
    data: () => ({
        loading: false,
        v$: useVuelidate(),
        form: {
            orderCode: "",
        },
        order: {},
    }),
    components: {
        Summary,
    },
    validations: {
        form: {
            orderCode: {
                required,
            },
        },
    },
    computed: {
        orderCodeErrors() {
            const errors = [];
            if (!this.v$.form.orderCode.$dirty) return errors;
            !this.v$.form.orderCode.required &&
                errors.push(this.$i18n.t("this_field_is_required"));
            return errors;
        },
        check() {
            return this.$store.state.auth.modalWindow;
        },
    },
    methods: {
        async trackOrder() {
            const isFormCorrect = await this.v$.$validate();
            // you can show some extra alert to the user or just leave the each field to show it's `$errors`.
            if (!isFormCorrect) return;

            this.loading = true;

            const res = await this.call_api(
                "get",
                `user/order/$+{this.form.orderCode}`
            );
            if (res.data.success) {
                this.order = res.data.data;
            } else {
                this.snack({
                    message: res.data.message,
                    color: "red",
                });
            }
            this.loading = false;
        },
        globalClose() {
            this.$store.commit("auth/modalWindow", false);
        },
    },
};
</script>
