<template>
    <v-dialog
        v-model="isVisible"
        max-width="600px"
        @click:outside="closeDialog"
    >
        <div class="white pa-5 rounded">
            <v-form
                v-on:submit.prevent="addNewAddress()"
                autocomplete="chrome-off"
            >
                <div class="mb-3">
                    <div class="mb-1 fs-13 fw-500">{{ $t("address") }}</div>
                    <v-textarea
                        :label="$t('address')"
                        v-model="form.address"
                        hide-details="auto"
                        rows="3"
                        required
                        variant="outlined"
                        no-resize
                    ></v-textarea>
                    <p
                        v-for="error of v$.form.address.$errors"
                        :key="error.$uid"
                        class="text-red"
                    >
                        {{ error.$message }}
                    </p>
                </div>
                <div class="mb-3">
                    <div class="mb-1 fs-13 fw-500">{{ $t("country") }}</div>
                    <v-autocomplete
                        v-model="form.country"
                        :items="countries"
                        :label="$t('select_country')"
                        hide-details="auto"
                        variant="outlined"
                        item-title="name"
                        item-value="id"
                        dense
                        autocomplete="off"
                        :custom-filter="countryChanged"
                        @update:modelValue="countryChanged"
                    ></v-autocomplete>
                    <p
                        v-for="error of v$.form.country.$errors"
                        :key="error.$uid"
                        class="text-red"
                    >
                        {{ error.$message }}
                    </p>
                </div>
                <div class="mb-3">
                    <div class="mb-1 fs-13 fw-500">{{ $t("state") }}</div>
                    <v-autocomplete
                        v-model="form.state"
                        :items="filteredStates"
                        hide-details="auto"
                        :label="statePlaceholer"
                        variant="outlined"
                        item-title="name"
                        item-value="id"
                        dense
                        @update:modelValue="stateChanged"
                        :readonly="waiting"
                        :loading="waiting"
                        :disabled="disabling"
                    ></v-autocomplete>
                    <p
                        v-for="error of v$.form.state.$errors"
                        :key="error.$uid"
                        class="text-red"
                    >
                        {{ error.$message }}
                    </p>
                </div>
                <div class="mb-3">
                    <div class="mb-1 fs-13 fw-500">
                        {{ $t("phone_number") }}
                    </div>
                    <v-text-field
                        :placeholder="$t('phone_number')"
                        type="number"
                        v-model="form.phone"
                        hide-details="auto"
                        required
                        variant="outlined"
                    ></v-text-field>
                    <p
                        v-for="error of v$.form.phone.$errors"
                        :key="error.$uid"
                        class="text-red"
                    >
                        {{ error.$message }}
                    </p>
                </div>
                <div class="text-right mt-4">
                    <v-btn
                        text
                        @click="closeDialog"
                        elevation="0"
                        class="mr-2"
                        >{{ $t("cancel") }}</v-btn
                    >
                    <v-btn
                        v-if="!is_empty_obj(oldAddress)"
                        elevation="0"
                        type="submit"
                        color="primary"
                        @click="updateAddress"
                        :loading="adding"
                        :disabled="adding"
                        >{{ $t("update") }}</v-btn
                    >
                    <v-btn
                        v-else
                        elevation="0"
                        type="submit"
                        color="primary"
                        @click="addNewAddress"
                        :loading="adding"
                        :disabled="adding"
                        >{{ $t("add_new") }}</v-btn
                    >
                </div>
            </v-form>
        </div>
    </v-dialog>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import { mapActions, mapMutations } from "vuex";
export default {
    props: {
        show: { type: Boolean, required: true, default: false },
        oldAddress: { type: Object, default: () => {} },
    },
    data: () => ({
        adding: false,
        countriesLoaded: false,
        waiting: false,
        disabling: true,
        countries: [],
        filteredStates: [],
        filteredCities: [],
        v$: useVuelidate(),
        form: {
            id: null,
            address: "",
            postal_code: "",
            country: "",
            state: "",
            city: "",
            phone: "",
        },
    }),
    validations: {
        form: {
            address: { required },
            country: { required },
            state: { required },
            phone: { required },
        },
    },
    watch: {
        oldAddress(newVal, oldVal) {
            if (newVal && !this.is_empty_obj(newVal)) {
                this.processOldAddress(newVal);
            } else {
                this.resetData();
            }
        },
    },
    computed: {
        statePlaceholer() {
            return this.$i18n.t("select_a_state");
        },
        cityPlaceholer() {
            return this.$i18n.t("select_a_city");
        },
        isVisible: {
            get: function () {
                return this.show;
            },
            set: function (newValue) {},
        },
    },
    created() {
        this.fetchCountries();
    },
    methods: {
        ...mapActions("address", ["addAddress"]),
        ...mapMutations("address", ["setAddresses"]),

        async fetchCountries() {
            if (!this.countriesLoaded) {
                const res = await this.call_api("get", "all-countries");
                if (res.data.success) {
                    this.countriesLoaded = true;
                    this.countries = res.data.data;
                }
            }
        },
        async countryChanged() {
            if (!this.form.country) {
                return;
            }
            this.waiting = true;
            const res = await this.call_api(
                "get",
                `states/${this.form.country}`
            );
            if (res.data.success) {
                this.filteredStates = res.data.data;
                this.form.state = "";
                this.form.city = "";
                this.filteredCities = [];
            } else {
                this.snack({
                    message: this.$i18n.t("something_went_wrong"),
                    color: "red",
                });
            }
            setTimeout(() => {
                this.waiting = false;
                this.disabling = false;
            }, 2000);
        },
        async stateChanged() {
            const res = await this.call_api("get", `cities/${this.form.state}`);
            if (res.data.success) {
                this.filteredCities = res.data.data;
                this.form.city = "";
            } else {
                this.snack({
                    message: this.$i18n.t("something_went_wrong"),
                    color: "red",
                });
            }
        },
        async addNewAddress() {
            // Prevents form submitting if it has error
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) return;

            this.adding = true;
            const res = await this.call_api(
                "post",
                "user/address/create",
                this.form
            );
            if (res.data.success) {
                this.addAddress(res.data.data);
                this.snack({ message: res.data.message });
                this.resetData();
                this.closeDialog();
            } else {
                this.snack({
                    message: this.$i18n.t("something_went_wrong"),
                    color: "red",
                });
            }
            this.adding = false;
        },
        async updateAddress() {
            // Prevents form submitting if it has error
            const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) return;

            this.adding = true;
            const res = await this.call_api(
                "post",
                `user/address/update`,
                this.form
            );
            if (res.data.success) {
                this.setAddresses(res.data.data);
                this.snack({ message: res.data.message });
                this.closeDialog();
            } else {
                this.snack({
                    message: this.$i18n.t("something_went_wrong"),
                    color: "red",
                });
            }
            this.adding = false;
        },
        resetData() {
            this.form.id = null;
            this.form.address = "";
            this.form.postal_code = "";
            this.form.country = "";
            this.form.state = "";
            this.form.city = "";
            this.form.phone = "";

            this.v$.form.$reset();
        },
        async processOldAddress(oldVal) {
            let oldAddress = { ...oldVal };

            this.form.id = oldAddress.id;
            this.form.address = oldAddress.address;
            this.form.postal_code = oldAddress.postal_code;
            this.form.phone = oldAddress.phone;

            //find selected country and filter states
            let selectedCountry = this.countries.find(
                (country) => country.name === oldAddress.country
            );
            this.form.country = selectedCountry.id;
            await this.countryChanged(selectedCountry.id);

            //find selected state and filter cities
            let selectedState = this.filteredStates.find(
                (state) => state.name === oldAddress.state
            );
            this.form.state = selectedState.id;
            await this.stateChanged(selectedState.id);

            //find selected city
            let selectedCity = this.filteredCities.find(
                (city) => city.name === oldAddress.city
            );
            this.form.city = selectedCity.id;
        },
        closeDialog() {
            this.isVisible = false;
            this.$emit("close");
        },
    },
};
</script>
