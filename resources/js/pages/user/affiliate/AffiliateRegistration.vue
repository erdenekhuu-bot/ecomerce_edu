<template>
  <v-container class="py-6">
    <v-row>
      <v-col
        cols="12"
        lg="10"
        md="12"
        sm="12"
        class="mx-auto"
      >
        <h1 class="text-h3 fw-700 mb-8">
          {{ $t("affiliate_informations") }}
        </h1>
        <v-form
          lazy-validation
          v-on:submit.prevent="submitRequest()"
        >
          <v-card
            class="border"
            elevation="0"
          >
            <v-card-title>
              <span class="text-h6 fw-600 opacity-80">{{
                                $t("verification_info")
                            }}</span>
            </v-card-title>
            <v-card-text>
              <div class="mb-4">
                <div class="mb-1 fs-13 fw-500">
                  {{ $t("your_name") }}
                </div>
                <v-text-field
                variant="plain"
                class="text-field"
                  :placeholder="$t('full_name')"
                  type="text"
                  v-model="form.name"
                  hide-details="auto"
                  required
                  outlined
                ></v-text-field>
                <p v-for="error of v$.form.name.$errors" :key="error.$uid" class="text-red">
                  {{error.$message }}
              </p>
              </div>
              <div class="mb-4">
                <div class="fs-13 fw-500">
                  {{ $t("your_email") }}
                </div>
                <v-text-field
                variant="plain"
                class="text-field"
                  :placeholder="$t('email_address')"
                  type="email"
                  v-model="form.email"
                  hide-details="auto"
                  required
                  outlined
                ></v-text-field>
                <p v-for="error of v$.form.email.$errors" :key="error.$uid" class="text-red">
                  {{error.$message }}
              </p>

              </div>
              <div v-if="!isAuthenticated">
                <div class="mb-4">
                  <div class="mb-1 fs-13 fw-500">
                    {{ $t("password") }}
                  </div>
                  <v-text-field
                  variant="plain"
                    placeholder="* * * * * * * *"
                    v-model="form.password"
                    type="password"
                    class="input-group--focused text-field"
                    hide-details="auto"
                    required
                    outlined
                  ></v-text-field>
                  <p v-for="error of v$.form.password.$errors" :key="error.$uid" class="text-red">
                  {{error.$message }}
              </p>
                </div>
                <div class="mb-4">
                  <div class="mb-1 fs-13 fw-500">
                    {{ $t("confirm_password") }}
                  </div>
                  <v-text-field
                  variant="plain"
                    placeholder="* * * * * * * *"
                    v-model="form.confirmPassword"
                    type="password"
                    class="input-group--focused text-field"
                    hide-details="auto"
                    required
                    outlined
                  ></v-text-field>
                  <p v-for="error of v$.form.confirmPassword.$errors" :key="error.$uid" class="text-red">
                  {{error.$message }}
              </p>
                </div>
              </div>
              <div class="mb-4">
                <div class="mb-1 fs-13 fw-500">
                  {{ $t("phone_number") }}
                </div>
                <vue-tel-input
                  v-model="form.phone"
                  v-bind="mobileInputProps"
                  :onlyCountries="availableCountries"
                  @validate="phoneValidate"
                >
                  <template slot="arrow-icon"><span class="vti__dropdown-arrow">&nbsp;▼</span></template>
                </vue-tel-input>
                <div
                  class="v-text-field__details mt-2 pl-3"
                  v-if="v$.form.phone.$error"
                >
                  <div
                    class="v-messages theme--light error--text"
                    role="alert"
                  >
                    <div class="v-messages__wrapper text-red">
                      <div class="v-messages__message">
                        {{
                                                    $t("this_field_is_required")
                                                }}
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  class="v-text-field__details mt-2 pl-3"
                  v-if="
                                        !v$.form.phone.$error &&
                                        form.showInvalidPhone
                                    "
                >
                  <div
                    class="v-messages theme--light error--text"
                    role="alert"
                  >
                    <div class="v-messages__wrapper text-red">
                      <div class="v-messages__message">
                        {{
                                                    $t(
                                                        "phone_number_must_be_valid"
                                                    )
                                                }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="mb-4">
                <div class="mb-1 fs-13 fw-500">
                  {{ $t("full_address") }}
                </div>
                <v-text-field
                variant="plain"
                class="text-field"
                  :placeholder="$t('full_address')"
                  type="text"
                  v-model="form.address"
                  hide-details="auto"
                  required
                  outlined
                ></v-text-field>
                <p v-for="error of v$.form.address.$errors" :key="error.$uid" class="text-red">
                  {{error.$message }}
              </p>
              </div>

              <div class="mb-4">
                <div class="mb-1 fs-13 fw-500">
                  {{ $t("how_will_you_affiliate?") }}
                </div>
                <v-text-field
                variant="plain"
                class="text-field"
                  :placeholder="$t('description')"
                  type="text"
                  v-model="form.description"
                  hide-details="auto"
                  required
                  outlined
                ></v-text-field>
                <p v-for="error of v$.form.description.$errors" :key="error.$uid" class="text-red">
                  {{error.$message }}
              </p>
              </div>
            </v-card-text>
          </v-card>
          <v-btn
            x-large
            class="px-12 mb-4 w-100 mt-5"
            elevation="0"
            type="submit"
            color="primary"
            @click="submitRequest"
            :loading="loading"
            :disabled="loading"
          >{{ $t("save") }}</v-btn>
        </v-form>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { VueTelInput } from "vue-tel-input";

import { useVuelidate } from '@vuelidate/core';
import { email, required } from "@vuelidate/validators";
import { mapActions, mapGetters, mapMutations } from "vuex";
export default {
  data: () => ({
    v$: useVuelidate(),
    loading: false,
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
    form: {
      name: "",
      email: "",
      password: "",
      confirmPassword: "",
      phone: "",
      address: "",
      description: "",
      invalidPhone: true,
      showInvalidPhone: false,
    },
  }),
  components: {
    VueTelInput,
  },
  validations: {
    form: {
      name: { required },
      email: { required, email },
      phone: { required },
      // password: { required, minLength: minLength(6) },
      // confirmPassword: { required },
      address: { required },
      description: { required },
    },
  },
  computed: {
    ...mapGetters("auth", ["isAuthenticated"]),
    ...mapGetters("app", ["generalSettings", "availableCountries"]),

  },
  methods: {
    ...mapActions("auth", ["login"]),
    ...mapMutations("cart", ["removeTempUserId"]),
    ...mapMutations("auth", ["updateChatWindow", "showLoginDialog"]),
    phoneValidate(phone) {
      this.form.invalidPhone = phone.valid ? false : true;
      if (phone.valid) this.form.showInvalidPhone = false;
    },
    async submitRequest() {
      // Prevents form submitting if it has error
      const isFormCorrect = await this.v$.$validate();
            if (!isFormCorrect) return;

      if (this.form.invalidPhone) {
        this.form.showInvalidPhone = true;
        return;
      }

      this.form.phone = this.form.phone.replace(/\s/g, "");
      this.loading = true;
      const res = await this.call_api(
        "post",
        "user/affiliate/store",
        this.form
      );
      if (res.data.status == 400) {
        this.snack({
          message: res.data.message,
          color: "yellow",
        });
      }
      if (res.data.success) {
        this.snack({
          color: "green",
          message: res.data.message,
        });
        this.$router.push({ name: "AffiliateRegConfirmation" });
        // this.resetForm()
      } else {
        this.snack({
          message: res.data.message,
          color: "red",
        });
      }
      this.loading = false;
    },
    resetForm() {
      this.form.name = "";
      this.form.phone = "";
      this.form.email = "";
      this.form.invalidPhone = true;
      this.form.showInvalidPhone = false;
      this.form.address = "";
      this.form.description = "";
    },
  },
};
</script>
