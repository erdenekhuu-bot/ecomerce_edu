<template>
    <div v-show="check" class="custom-window" @click="globalClose"></div>
    <div class="ps-lg-7 pt-4" style="font-family: OpenSans-Bold">
        <h1 class="fs-21 fw-700 opacity-80 mb-5">{{ $t("manage_profile") }}</h1>
        <v-row>
            <v-col xl="8">
                <v-card elevation="0" class="mb-6">
                    <v-card-title class="pa-0 fs-16 fw-700">{{
                        $t("profile_information")
                    }}</v-card-title>
                    <v-form
                        class="py-4"
                        v-on:submit.prevent="updateBasic()"
                        enctype="multipart/form-data"
                    >
                        <input
                            type="email"
                            name="hidden"
                            style="width: 0; height: 0; border: 0; padding: 0"
                        />
                        <input
                            type="password"
                            name="hidden"
                            style="width: 0; height: 0; border: 0; padding: 0"
                        />

                        <h4>{{ $t("full_name") }}</h4>
                        <v-text-field
                            :placeholder="$t('full_name')"
                            v-model="form.name"
                            variant="outlined"
                            hide-details="auto"
                            class="mb-3"
                        ></v-text-field>

                        <!-- <p v-for="error of v$.form.name.$errors" :key="error.$uid" class="text-red">
                            {{error.$message }}
                        </p> -->

                        <h4>{{ $t("email") }}</h4>
                        <v-text-field
                            :placeholder="$t('email_address')"
                            v-model="form.email"
                            variant="outlined"
                            hide-details="auto"
                            class="mb-3"
                            disabled
                        ></v-text-field>
                        <!-- <p v-for="error of v$.form.email.$errors" :key="error.$uid" class="text-red">
                            {{error.$message }}
                        </p> -->

                        <h4>{{ $t("phone_number") }}</h4>
                        <vue-tel-input
                            v-model="form.phone"
                            v-bind="mobileInputProps"
                            :onlyCountries="availableCountries"
                            class="mb-3 opacity-80"
                            @validate="phoneValidate"
                            disabled
                        ></vue-tel-input>
                        <!-- <p v-for="error of v$.form.phone.$errors" :key="error.$uid" class="text-red">
                            {{error.$message }}
                        </p> -->

                        <!-- <label>{{ $t("old_password") }}</label>
                        <v-text-field placeholder="******" type="password" v-model="form.oldPassword" :error-messages="oldPasswordErrors" variant="outlined" hide-details="auto" class="mb-3"></v-text-field> -->

                        <h4>{{ $t("new_password") }}</h4>
                        <v-text-field
                            placeholder="******"
                            type="password"
                            v-model="form.password"
                            @blur="v$.form.password.$touch()"
                            variant="outlined"
                            hide-details="auto"
                            class="mb-3"
                        ></v-text-field>
                        <!-- <p v-for="error of v$.form.password.$errors" :key="error.$uid" class="text-red">
                            {{error.$message }}
                        </p> -->

                        <h4>{{ $t("confirm_password") }}</h4>
                        <v-text-field
                            placeholder="******"
                            type="password"
                            v-model="form.confirmPassword"
                            @blur="v$.form.confirmPassword.$touch()"
                            variant="outlined"
                            hide-details="auto"
                            class="mb-3"
                        ></v-text-field>
                        <!-- <p v-for="error of v$.form.confirmPassword.$errors" :key="error.$uid" class="text-red">
                            {{error.$message }}
                        </p>
                         -->
                        <h4>{{ $t("profile_image") }}</h4>
                        <div
                            class="d-flex flex-column align-center justify-center border rounded pa-6 mb-3"
                        >
                            <div class="avatar-upload">
                                <v-avatar size="160" class="">
                                    <img
                                        :src="form.previewAvatar"
                                        @error="imageFallback($event)"
                                        class="user-dashboard-profile-image"
                                    />
                                </v-avatar>
                                <label>
                                    <input
                                        type="file"
                                        id="avatar-input"
                                        accept="image/png, image/jpg, image/jpeg"
                                        @change="previewThumbnail"
                                    />
                                    <i class="las la-image"></i>
                                </label>
                            </div>
                            <label
                                for="avatar-input"
                                class="border border-gray-300 fs-14 fw-500 grey lighten-4 mt-3 opacity-50 px-4 py-1 rounded c-pointer"
                                >{{ $t("select_image") }}</label
                            >
                        </div>
                        <v-btn
                            type="submit"
                            :loading="infoUpdateLoading"
                            :disabled="infoUpdateLoading"
                            elevation="0"
                            class="px-10 btn-primary"
                            @click="updateBasic"
                            >{{ $t("update") }}</v-btn
                        >
                    </v-form>
                </v-card>
                <v-card elevation="0">
                    <address-dialog
                        :show="addDialogShow"
                        @close="addressDialogClosed"
                        :old-address="addressSelectedForEdit"
                    />
                    <v-card-title class="pa-0 fs-16 fw-700 mb-4">
                        <span class="">{{ $t("addresses") }}</span>
                        <v-btn
                            elevation="0"
                            class="ms-auto float-right btn-primary"
                            @click.stop="addDialogShow = true"
                            >{{ $t("add_new") }}</v-btn
                        >
                    </v-card-title>
                    <v-row class="mb-4 row-cols-1 row-cols-sm-2 gutters-10">
                        <v-col>
                            <div class="fs-13 fw-600 opacity-80 mb-2">
                                {{ $t("default_shipping_address") }}
                            </div>
                            <div
                                class="border rounded pa-4 fs-13"
                                v-if="getDefaultShippingAddress"
                            >
                                <div>
                                    {{ getDefaultShippingAddress.address }}
                                </div>
                                <div>
                                    {{ getDefaultShippingAddress.postal_code }},
                                    {{ getDefaultShippingAddress.city }},
                                    {{ getDefaultShippingAddress.state }}
                                </div>
                                <div>
                                    {{ getDefaultShippingAddress.country }}
                                </div>
                                <div>{{ getDefaultShippingAddress.phone }}</div>
                            </div>
                        </v-col>
                        <v-col>
                            <div class="fs-13 fw-600 opacity-80 mb-2">
                                {{ $t("default_billing_address") }}
                            </div>
                            <div
                                class="border rounded pa-4 fs-13"
                                v-if="getDefaultBillingAddress"
                            >
                                <div>
                                    {{ getDefaultBillingAddress.address }}
                                </div>
                                <div>
                                    {{ getDefaultBillingAddress.postal_code }},
                                    {{ getDefaultBillingAddress.city }},
                                    {{ getDefaultBillingAddress.state }}
                                </div>
                                <div>
                                    {{ getDefaultBillingAddress.country }}
                                </div>
                                <div>{{ getDefaultBillingAddress.phone }}</div>
                            </div>
                        </v-col>
                    </v-row>
                </v-card>
                <v-card elevation="0">
                    <v-card-title class="pa-0 fs-16 fw-700 mb-4">{{
                        $t("all_addresses")
                    }}</v-card-title>
                    <div>
                        <v-row
                            class="row-cols-1 row-cols-sm-2 gutters-10"
                            v-if="addressesLoaded && getAddresses.length > 0"
                        >
                            <v-col
                                v-for="(address, i) in getAddresses"
                                :key="i"
                            >
                                <div
                                    class="border rounded py-4 ps-4 pe-9 fs-13 position-relative"
                                >
                                    <div>{{ address.address }}</div>
                                    <div>
                                        {{ address.postal_code }},
                                        {{ address.city }}, {{ address.state }}
                                    </div>
                                    <div>{{ address.country }}</div>
                                    <div>{{ address.phone }}</div>
                                    <v-menu offset-y left>
                                        <template v-slot:activator="{ props }">
                                            <v-btn
                                                v-bind="props"
                                                class="absolute-top-right"
                                                color="transparent"
                                                elevation="0"
                                                fab
                                                small
                                            >
                                                <i
                                                    class="las la-ellipsis-v fs-18"
                                                ></i>
                                            </v-btn>
                                        </template>
                                        <v-list class="">
                                            <v-list-item
                                                class="c-pointer"
                                                @click="editAddress(address)"
                                            >
                                                <v-list-item-title>{{
                                                    $t("edit")
                                                }}</v-list-item-title>
                                            </v-list-item>
                                            <v-list-item
                                                class="c-pointer"
                                                @click="
                                                    markDefaultShipping(
                                                        address.id
                                                    )
                                                "
                                            >
                                                <v-list-item-title>{{
                                                    $t(
                                                        "mark_as_default_shipping_address"
                                                    )
                                                }}</v-list-item-title>
                                            </v-list-item>
                                            <v-list-item
                                                class="c-pointer"
                                                @click="
                                                    markDefaultBilling(
                                                        address.id
                                                    )
                                                "
                                            >
                                                <v-list-item-title>{{
                                                    $t(
                                                        "mark_as_default_billing_address"
                                                    )
                                                }}</v-list-item-title>
                                            </v-list-item>
                                            <v-list-item
                                                class="c-pointer"
                                                @click="
                                                    deleteAddress(address.id)
                                                "
                                            >
                                                <v-list-item-title>{{
                                                    $t("delete")
                                                }}</v-list-item-title>
                                            </v-list-item>
                                        </v-list>
                                    </v-menu>
                                </div>
                            </v-col>
                        </v-row>
                        <div class="text-center" v-else>
                            <div>{{ $t("no_saved_address") }}</div>
                        </div>
                    </div>
                </v-card>
            </v-col>
            <v-col cols="auto"> </v-col>
        </v-row>
    </div>
</template>

<script>
import { useVuelidate } from "@vuelidate/core";
import { minLength, required } from "@vuelidate/validators";
import { VueTelInput } from "vue-tel-input";
import { mapActions, mapGetters, mapMutations } from "vuex";
import AddressDialog from "../../components/address/AddressDialog.vue";
export default {
    data: () => ({
        mobileInputProps: {
            inputOptions: {
                type: "tel",
                placeholder: "phone number",
            },
            dropdownOptions: {
                showDialCodeInSelection: false,
                showFlags: true,
                showDialCodeInList: true,
            },
            autoDefaultCountry: false,
            validCharactersOnly: true,
            mode: "international",
        },
        v$: useVuelidate(),
        form: {
            name: "",
            email: "",
            phone: "",
            invalidPhone: false,
            avatar: "",
            previewAvatar: "",
            oldPassword: "",
            password: "",
            confirmPassword: "",
        },
        passwordShow: false,
        addDialogShow: false,
        infoUpdateLoading: false,
        addressSelectedForEdit: {},
    }),
    components: {
        VueTelInput,
        AddressDialog,
    },
    validations: {
        form: {
            name: { required },
            // email: {
            //     email,
            //     required
            // },
            // oldPassword: { required},
            password: { minLength: minLength(6) },
            confirmPassword: { minLength: minLength(6) },

            // confirmPassword: { sameAsPassword: sameAs('password') }
        },
    },
    computed: {
        ...mapGetters("auth", ["currentUser"]),
        ...mapGetters("app", ["availableCountries"]),
        ...mapGetters("address", [
            "getAddresses",
            "addressesLoaded",
            "getDefaultShippingAddress",
            "getDefaultBillingAddress",
        ]),
        check() {
            return this.$store.state.auth.modalWindow;
        },
    },
    created() {
        this.form.name = this.currentUser.name;
        this.form.email = this.currentUser.email;
        this.form.phone = this.currentUser.phone;
        this.form.previewAvatar = this.currentUser.avatar;

        this.fetchAddresses();
    },
    methods: {
        ...mapMutations("auth", ["setUser"]),
        ...mapMutations("address", ["setAddresses"]),
        ...mapActions("address", ["fetchAddresses"]),
        globalClose() {
            this.$store.commit("auth/modalWindow", false);
        },
        previewThumbnail(event) {
            this.form.avatar = event.target.files[0];
            if (event.target.files && event.target.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.form.previewAvatar = e.target.result;
                };
                reader.readAsDataURL(event.target.files[0]);
            }
        },
        phoneValidate(phone) {
            this.form.invalidPhone = phone.valid ? false : true;
        },
        async updateBasic() {
            // if(this.form.email == ""){
            //     this.snack({
            //         message: "Email is required.",
            //         color: "red"
            //     });
            //     return;
            // }
            // if(this.form.email != "" && !this.v$.form.email.email){
            //     this.snack({
            //         message: "Email must be valid.",
            //         color: "red"
            //     });
            //     return;
            // }
            // if(this.form.phone != ""  && this.form.invalidPhone){
            //     this.snack({
            //         message: "Phone number must be valid.",
            //         color: "red"
            //     });
            //     return;
            // }

            // Prevents form submitting if it has error

            //    const isFormCorrect = await this.v$.$validate();
            //     if (!isFormCorrect) return;

            // this.form.phone = this.form.phone.replace(/\s/g, '')
            this.infoUpdateLoading = true;

            let formData = new FormData();
            for (var key in this.form) {
                formData.append(key, this.form[key]);
            }

            const res = await this.call_api(
                "post",
                "user/info/update",
                formData,
                true
            );

            if (res.data.success) {
                this.setUser(res.data.user);
                this.snack({ message: res.data.message });
            } else {
                this.snack({ message: res.data.message, color: "red" });
            }
            this.infoUpdateLoading = false;
        },
        async deleteAddress(id) {
            const res = await this.call_api("get", `user/address/delete/${id}`);
            if (res.data.success) {
                this.setAddresses(res.data.data);
                this.snack({ message: res.data.message });
            }
        },
        editAddress(address) {
            this.addressSelectedForEdit = address;
            this.addDialogShow = true;
        },
        async markDefaultShipping(id) {
            const res = await this.call_api(
                "get",
                `user/address/default-shipping/${id}`
            );
            if (res.data.success) {
                this.setAddresses(res.data.data);
                this.snack({ message: res.data.message });
            } else {
                this.snack({
                    message: this.$i18n.t("something_went_wrong"),
                    color: "red",
                });
            }
        },
        async markDefaultBilling(id) {
            const res = await this.call_api(
                "get",
                `user/address/default-billing/${id}`
            );
            if (res.data.success) {
                this.setAddresses(res.data.data);
                this.snack({ message: res.data.message });
            } else {
                this.snack({
                    message: this.$i18n.t("something_went_wrong"),
                    color: "red",
                });
            }
        },
        addressDialogClosed() {
            this.addressSelectedForEdit = {};
            this.addDialogShow = false;
        },
    },
};
</script>
<style scoped>
.custom-text-field input::placeholder {
    font-weight: bold;
}

.custom-text-field input {
    font-weight: normal;
}
</style>
