<template>
    <div v-show="check" class="custom-window" @click="globalClose"></div>
    <v-container class="pt-7">
        <v-row>
            <v-col xl="8" lg="10" class="mx-auto">
                <h1 class="fs-24 fw-700 opacity-80 mb-4">
                    {{ $t("checkout") }}
                </h1>
                <div class="mb-4">
                    <div>
                        <div class="delivery-type">
                            <h3 class="opacity-80 mb-3 fs-20">
                                {{ $t("delivery_type") }}
                            </h3>
                            <v-row>
                                <v-col cols="12" sm="6">
                                    <div class="position-relative mb-3">
                                        <label class="aiz-megabox d-block">
                                            <input
                                                type="radio"
                                                name="delivery_type"
                                                v-model="selectedDeliveryType"
                                                value="home_delivery"
                                                @click="
                                                    ChooseDeleviryType(
                                                        'home_delivery'
                                                    )
                                                "
                                            />
                                            <span
                                                class="d-flex pa-3 aiz-megabox-elem fs-13"
                                            >
                                                <span
                                                    class="aiz-rounded-check flex-shrink-0 mt-1"
                                                ></span>
                                                <span
                                                    class="flex-grow-1 ps-3 lh-1-5"
                                                >
                                                    <span
                                                        class="d-block fw-600"
                                                        >{{
                                                            $t("home_delivery")
                                                        }}</span
                                                    >
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                </v-col>
                                <v-col
                                    cols="12"
                                    sm="6"
                                    v-if="generalSettings.pickup_point"
                                >
                                    <div class="position-relative mb-3">
                                        <label class="aiz-megabox d-block">
                                            <input
                                                type="radio"
                                                name="delivery_type"
                                                v-model="selectedDeliveryType"
                                                value="pickup"
                                                @click="
                                                    checkForPickUp('pickup')
                                                "
                                            />
                                            <span
                                                class="d-flex pa-3 aiz-megabox-elem fs-13"
                                            >
                                                <span
                                                    class="aiz-rounded-check flex-shrink-0 mt-1"
                                                ></span>
                                                <span
                                                    class="flex-grow-1 ps-3 lh-1-5"
                                                >
                                                    <span
                                                        class="d-block fw-600"
                                                        >{{
                                                            $t("pickup")
                                                        }}</span
                                                    >
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                </v-col>
                            </v-row>
                            <!-- Pick up point ist -->
                            <div
                                class="position-relative my-3"
                                v-if="selectedDeliveryType == 'pickup'"
                            >
                                <label
                                    class="aiz-megabox d-block"
                                    v-if="for_pickup"
                                >
                                    <!-- <v-select
                        label="Select"
                        :items="getPickupPoints"
                      ></v-select>
                       -->

                                    <v-autocomplete
                                        v-model="selectedPickupPoint"
                                        :items="getPickupPoints"
                                        :label="$t('select_pickup_point')"
                                        hide-details="auto"
                                        variant="outlined"
                                        item-title="name"
                                        item-value="id"
                                        dense
                                        autocomplete="off"
                                        class=""
                                    >
                                    </v-autocomplete>
                                </label>
                            </div>
                        </div>
                        <!-- ========== -->
                        <div v-if="selectedDeliveryType == 'home_delivery'">
                            <address-dialog
                                :show="addDialogShow"
                                @close="addressDialogClosed"
                                :old-address="addressSelectedForEdit"
                            />
                            <h3 class="opacity-80 mb-3 fs-20">
                                {{ $t("shipping_address") }}
                            </h3>
                            <div class="mb-4">
                                <div
                                    class="position-relative mb-3"
                                    v-for="address in getAddresses"
                                    :key="address.id"
                                >
                                    <label class="aiz-megabox d-block">
                                        <input
                                            type="radio"
                                            name="checkout_shipping"
                                            v-model="selectedShippingAddressId"
                                            :value="address.id"
                                            :checked="address.default_shipping"
                                            @change="
                                                shippingAddressSelected(
                                                    address.id
                                                )
                                            "
                                        />
                                        <span
                                            class="d-flex pa-3 aiz-megabox-elem fs-13 fw-600"
                                        >
                                            <span
                                                class="aiz-rounded-check flex-shrink-0 mt-1"
                                            ></span>
                                            <span
                                                class="flex-grow-1 ps-3 opacity-80 lh-1-5"
                                            >
                                                <span class="d-block"
                                                    >{{ address.address }},
                                                    {{
                                                        address.postal_code
                                                    }}</span
                                                >
                                                <span class="d-block"
                                                    >{{ address.city }},
                                                    {{ address.state }},
                                                    {{ address.country }}</span
                                                >
                                                <span>{{ address.phone }}</span>
                                            </span>
                                        </span>
                                    </label>
                                    <v-btn
                                        class="absolute-right-center me-3"
                                        color="primary"
                                        elevation="0"
                                        small
                                        @click="editAddress(address)"
                                    >
                                        {{ $t("change") }}
                                    </v-btn>
                                </div>
                                <v-btn
                                    class="border-dashed border-gray-300 primary--text fs-14"
                                    elevation="0"
                                    block
                                    x-large
                                    @click.stop="addDialogShow = true"
                                >
                                    <i class="las la-plus"></i>
                                    <span>{{ $t("add_new_address") }}</span>
                                </v-btn>
                            </div>
                            <!-- <h3 class="opacity-80 mb-3 fs-20">
                                {{ $t("billing_address") }}
                            </h3>
                            <div class="mb-4">
                                <div
                                    class="position-relative mb-3"
                                    v-for="address in getAddresses"
                                    :key="address.id"
                                >
                                    <label class="aiz-megabox d-block">
                                        <input
                                            type="radio"
                                            name="checkout_billing"
                                            v-model="selectedBillingAddressId"
                                            :value="address.id"
                                            :checked="address.default_billing"
                                        />
                                        <span
                                            class="d-flex pa-3 aiz-megabox-elem fs-13 fw-600"
                                        >
                                            <span
                                                class="aiz-rounded-check flex-shrink-0 mt-1"
                                            ></span>
                                            <span
                                                class="flex-grow-1 ps-3 opacity-80 lh-1-5"
                                            >
                                                <span class="d-block"
                                                    >{{ address.address }},
                                                    {{
                                                        address.postal_code
                                                    }}</span
                                                >
                                                <span class="d-block"
                                                    >{{ address.city }},
                                                    {{ address.state }},
                                                    {{ address.country }}</span
                                                >
                                                <span>{{ address.phone }}</span>
                                            </span>
                                        </span>
                                    </label>
                                    <v-btn
                                        class="absolute-right-center me-3"
                                        color="primary"
                                        elevation="0"
                                        small
                                        @click="editAddress(address)"
                                    >
                                        {{ $t("change") }}
                                    </v-btn>
                                </div>
                            </div> -->
                        </div>
                        <!-- ================== -->
                        <div
                            class="delivery-option"
                            v-if="selectedDeliveryType == 'home_delivery'"
                        >
                            <h3 class="opacity-80 mb-3 fs-20">
                                {{ $t("delivery_option") }}
                            </h3>
                            <v-row v-if="selectedDeliveryOption !== ''">
                                <v-col cols="12" sm="6">
                                    <div class="position-relative mb-3">
                                        <label class="aiz-megabox d-block">
                                            <input
                                                type="radio"
                                                name="delivery_option"
                                                v-model="selectedDeliveryOption"
                                                value="standard"
                                            />
                                            <span
                                                class="d-flex pa-3 aiz-megabox-elem fs-13"
                                            >
                                                <span
                                                    class="aiz-rounded-check flex-shrink-0 mt-1"
                                                ></span>
                                                <span
                                                    class="flex-grow-1 ps-3 lh-1-5"
                                                >
                                                    <span
                                                        class="d-block fw-600"
                                                        >{{
                                                            $t(
                                                                "standard_delivery"
                                                            )
                                                        }}</span
                                                    >
                                                    <span class="d-block">
                                                        {{
                                                            $t("delivery_cost")
                                                        }}:
                                                        <span class="fw-600">{{
                                                            format_price(
                                                                standardDeliveryCost
                                                            )
                                                        }}</span>
                                                        <span
                                                            v-if="
                                                                is_addon_activated(
                                                                    'multi_vendor'
                                                                )
                                                            "
                                                            >/{{
                                                                $t("shop")
                                                            }}</span
                                                        >
                                                    </span>
                                                    <span class="d-block"
                                                        >{{
                                                            $t(
                                                                "delivery_timing"
                                                            )
                                                        }}:
                                                        <span class="fw-600"
                                                            >{{
                                                                getStandardTime
                                                            }}
                                                            {{
                                                                $t("days")
                                                            }}</span
                                                        ></span
                                                    >
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                </v-col>
                                <v-col cols="12" sm="6">
                                    <div class="position-relative mb-3">
                                        <label class="aiz-megabox d-block">
                                            <input
                                                type="radio"
                                                name="delivery_option"
                                                v-model="selectedDeliveryOption"
                                                value="express"
                                            />
                                            <span
                                                class="d-flex pa-3 aiz-megabox-elem fs-13"
                                            >
                                                <span
                                                    class="aiz-rounded-check flex-shrink-0 mt-1"
                                                ></span>
                                                <span
                                                    class="flex-grow-1 ps-3 lh-1-5"
                                                >
                                                    <span
                                                        class="d-block fw-600"
                                                        >{{
                                                            $t(
                                                                "express_delivery"
                                                            )
                                                        }}</span
                                                    >
                                                    <span class="d-block">
                                                        {{
                                                            $t("delivery_cost")
                                                        }}:
                                                        <span class="fw-600">{{
                                                            format_price(
                                                                expressDeliveryCost
                                                            )
                                                        }}</span>
                                                        <span
                                                            v-if="
                                                                is_addon_activated(
                                                                    'multi_vendor'
                                                                )
                                                            "
                                                            >/{{
                                                                $t("shop")
                                                            }}</span
                                                        >
                                                    </span>
                                                    <span class="d-block"
                                                        >{{
                                                            $t(
                                                                "delivery_timing"
                                                            )
                                                        }}:
                                                        <span class="fw-600"
                                                            >{{
                                                                getExpressTime
                                                            }}
                                                            {{
                                                                $t("days")
                                                            }}</span
                                                        ></span
                                                    >
                                                </span>
                                            </span>
                                        </label>
                                    </div>
                                </v-col>
                            </v-row>
                            <div
                                class="border red white--text rounded pa-4"
                                v-else
                            >
                                {{
                                    $t(
                                        "sorry_delivery_is_not_available_in_this_shipping_address"
                                    )
                                }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <h3 class="opacity-80 mb-3 fs-20">
                        {{ $t("order_summary") }}
                    </h3>
                    <div class="mb-4">
                        <v-row>
                            <v-col cols="12" sm="8">
                                <div
                                    class="bg-soft-primary text-reset px-6 rounded-sm"
                                    v-if="generalSettings.club_point == 1"
                                >
                                    <v-row class="mb-2">
                                        <v-col
                                            cols="8"
                                            class="fw-500 opacity-80"
                                            >{{
                                                $t("total_club_points")
                                            }}</v-col
                                        >
                                        <v-col cols="4" class="fw-700">{{
                                            getCartClubPoints
                                        }}</v-col>
                                    </v-row>
                                </div>
                                <div
                                    class="border border-gray-200 rounded px-6 py-5 grey lighten-5"
                                >
                                    <v-row class="">
                                        <v-col
                                            cols="8"
                                            class="fw-500 opacity-80"
                                            >{{ $t("sub_total") }}</v-col
                                        >
                                        <v-col cols="4" class="fw-700">{{
                                            format_price(
                                                getCartPrice - getCartTax,
                                                false
                                            )
                                        }}</v-col>
                                    </v-row>
                                    <v-row class="mt-0">
                                        <v-col
                                            cols="8"
                                            class="fw-500 opacity-80"
                                            >{{ $t("shipping_charge") }}</v-col
                                        >
                                        <v-col cols="4" class="fw-700">
                                            {{
                                                selectedDeliveryType ==
                                                "home_delivery"
                                                    ? this
                                                          .selectedDeliveryOption ===
                                                      "standard"
                                                        ? format_price(
                                                              standardDeliveryCost *
                                                                  getCartShops.length
                                                          )
                                                        : format_price(
                                                              expressDeliveryCost *
                                                                  getCartShops.length
                                                          )
                                                    : 0
                                            }}
                                        </v-col>
                                    </v-row>
                                    <v-row class="mt-0">
                                        <v-col
                                            cols="8"
                                            class="fw-500 opacity-80"
                                            >{{ $t("tax") }}</v-col
                                        >
                                        <v-col cols="4" class="fw-700">{{
                                            format_price(getCartTax, false)
                                        }}</v-col>
                                    </v-row>
                                    <v-divider class="mt-3 mb-2"></v-divider>

                                    <coupon-form
                                        v-if="
                                            !is_addon_activated('multi_vendor')
                                        "
                                        for-checkout
                                    />

                                    <v-row class="mt-0">
                                        <v-col
                                            cols="8"
                                            class="fw-500 opacity-80"
                                            >{{ $t("discount") }}</v-col
                                        >
                                        <v-col cols="4" class="fw-700">{{
                                            format_price(getTotalCouponDiscount)
                                        }}</v-col>
                                    </v-row>
                                    <v-divider class="my-3"></v-divider>
                                    <v-row class="fs-16">
                                        <v-col
                                            cols="8"
                                            class="fw-500 opacity-80"
                                            >{{ $t("total_to_pay") }}</v-col
                                        >
                                        <v-col cols="4" class="fw-700">{{
                                            format_price(totalPrice, false)
                                        }}</v-col>
                                    </v-row>
                                </div>
                            </v-col>
                            <v-col cols="12" sm="4">
                                <banner
                                    :loading="false"
                                    :banner="
                                        $store.getters['app/banners']
                                            .checkout_page
                                    "
                                    class="checkout-banner"
                                />
                            </v-col>
                        </v-row>
                    </div>
                </div>
                <div class="mb-4">
                    <h3 class="opacity-80 mb-3 fs-20">
                        {{ $t("payment_options") }}
                    </h3>
                    <v-row class="mb-3">
                        <!-- online payment methods -->

                        <v-col
                            cols="6"
                            sm="4"
                            md="3"
                            v-for="(paymentMethod, i) in paymentMethods"
                            :key="i"
                            :class="[paymentMethod.status == 1 ? '' : 'd-none']"
                        >
                            <label
                                class="aiz-megabox d-block"
                                v-if="
                                    getIsDigital &&
                                    paymentMethod.code != 'cash_on_delivery'
                                "
                            >
                                <input
                                    type="radio"
                                    name="checkout_payment_method"
                                    :checked="
                                        selectedPaymentMethod &&
                                        paymentMethod.code ==
                                            selectedPaymentMethod.code
                                    "
                                    @change="
                                        paymentSelected($event, paymentMethod)
                                    "
                                />
                                <span
                                    class="d-block pa-3 aiz-megabox-elem text-center"
                                >
                                    <img
                                        :src="paymentMethod.img"
                                        class="img-fluid w-100"
                                    />
                                    <span class="fw-700 fs-14">{{
                                        paymentMethod.name
                                    }}</span>
                                </span>
                            </label>
                            <label
                                class="aiz-megabox d-block"
                                v-else-if="!getIsDigital"
                            >
                                <input
                                    type="radio"
                                    name="checkout_payment_method"
                                    :checked="
                                        selectedPaymentMethod &&
                                        paymentMethod.code ==
                                            selectedPaymentMethod.code
                                    "
                                    @change="
                                        paymentSelected($event, paymentMethod)
                                    "
                                />
                                <span
                                    class="d-block pa-3 aiz-megabox-elem text-center"
                                >
                                    <img
                                        :src="paymentMethod.img"
                                        class="img-fluid w-100"
                                    />
                                    <span class="fw-700 fs-14">{{
                                        paymentMethod.name
                                    }}</span>
                                </span>
                            </label>
                        </v-col>
                        <!-- online payment methods ends -->

                        <!-- offline payment methods -->
                        <v-col
                            cols="6"
                            sm="4"
                            md="3"
                            v-for="(
                                offlinePaymentMethod, i
                            ) in offlinePaymentMethods"
                            :key="offlinePaymentMethod.code"
                        >
                            <label class="aiz-megabox d-block">
                                <input
                                    type="radio"
                                    name="wallet_payment_method"
                                    :checked="
                                        selectedPaymentMethod &&
                                        offlinePaymentMethod.code ==
                                            selectedPaymentMethod.code
                                    "
                                    @change="
                                        paymentSelected(
                                            $event,
                                            offlinePaymentMethod
                                        )
                                    "
                                />
                                <span
                                    class="d-block pa-3 aiz-megabox-elem text-center"
                                >
                                    <img
                                        :src="offlinePaymentMethod.img"
                                        class="w-100 h-90px"
                                    />
                                    <span class="fw-700 fs-13">{{
                                        offlinePaymentMethod.name
                                    }}</span>
                                </span>
                            </label>
                        </v-col>
                        <!-- offline payment methods loop ends -->
                    </v-row>

                    <!-- show authorize net payment method's data -->
                    <div
                        v-if="
                            selectedPaymentMethod &&
                            selectedPaymentMethod.code == 'authorizenet'
                        "
                        class="my-3"
                    >
                        <h3 class="opacity-80 mb-3 fs-18 text-capitalize">
                            {{ $t("account_details") }}
                        </h3>
                        <div class="border px-2 py-2">
                            <!-- show authorize payment method's inputs -->
                            <v-text-field
                                variant="plain"
                                class="my-2 text-field"
                                :placeholder="
                                    $t('please_enter_valid_card_number')
                                "
                                type="text"
                                v-model="authorizeNet.card_number"
                                hide-details="auto"
                                required
                                outlined
                            >
                            </v-text-field>

                            <v-text-field
                                variant="plain"
                                class="my-2 text-field"
                                :placeholder="$t('please_enter_cvv')"
                                type="text"
                                v-model="authorizeNet.cvv"
                                hide-details="auto"
                                required
                                outlined
                            >
                            </v-text-field>

                            <v-autocomplete
                                variant="plain"
                                v-model="authorizeNet.expiration_month"
                                :items="months"
                                placeholder="$t('select_month')"
                                hide-details="auto"
                                class="mb-3 text-field"
                                outlined
                                allow-overflow
                                dense
                                required
                                :label="$t('select_month')"
                            ></v-autocomplete>
                            <v-autocomplete
                                variant="plain"
                                v-model="authorizeNet.expiration_year"
                                :items="dateLoop"
                                placeholder="$t('select_year')"
                                hide-details="auto"
                                class="mb-3 text-field"
                                outlined
                                allow-overflow
                                dense
                                required
                                :label="$t('select_year')"
                            ></v-autocomplete>
                            <!-- show authorize payment method's inputs -->
                        </div>
                    </div>

                    <!-- show offline payment method's data -->
                    <div
                        v-if="
                            selectedPaymentMethod &&
                            selectedPaymentMethod.code.includes(
                                'offline_payment'
                            )
                        "
                        class="my-3"
                    >
                        <h3 class="opacity-80 mb-3 fs-18 text-capitalize">
                            {{ $t("account_details") }}
                        </h3>
                        <div class="border px-2 py-2">
                            <div class="text-capitalize my-1">
                                <span class="font-weight-bold">{{
                                    $t("payment_name")
                                }}</span>
                                : {{ selectedPaymentMethod.name }}
                            </div>
                            <div class="text-capitalize my-1">
                                <span class="font-weight-bold">{{
                                    $t("payment_type")
                                }}</span>
                                : {{ selectedPaymentMethod.type_show }}
                            </div>
                            <div
                                class="text-capitalize d-flex my-1"
                                v-if="selectedPaymentMethod.description"
                            >
                                <span class="font-weight-bold me-1"
                                    >{{ $t("description") }} :</span
                                >
                                <span
                                    v-html="selectedPaymentMethod.description"
                                ></span>
                            </div>
                            <div
                                class="text-capitalize"
                                v-if="
                                    selectedPaymentMethod.bank_info.length > 0
                                "
                            >
                                <span class="font-weight-bold"
                                    >{{ $t("bank_info") }}:</span
                                >
                                <div
                                    class="border px-2 py-2 mt-2"
                                    v-for="(
                                        bankInfo, i
                                    ) in selectedPaymentMethod.bank_info"
                                    :key="bankInfo.bank_name"
                                >
                                    <div>
                                        {{ $t("bank_name") }}:
                                        {{ bankInfo.bank_name }}
                                    </div>
                                    <div>
                                        {{ $t("account_name") }}:
                                        {{ bankInfo.account_name }}
                                    </div>
                                    <div>
                                        {{ $t("account_number") }}:
                                        {{ bankInfo.account_number }}
                                    </div>
                                    <div>
                                        {{ $t("routing_number") }}:
                                        {{ bankInfo.routing_number }}
                                    </div>
                                </div>
                            </div>

                            <!-- show offline payment method's inputs -->
                            <div
                                v-if="
                                    selectedPaymentMethod &&
                                    selectedPaymentMethod.code.includes(
                                        'offline_payment'
                                    )
                                "
                            >
                                <v-text-field
                                    class="my-2 text-field"
                                    :placeholder="$t('transaction_id')"
                                    type="text"
                                    v-model="transactionId"
                                    hide-details="auto"
                                    required
                                    variant="plain"
                                >
                                </v-text-field>
                                <v-file-input
                                    accept="image/*"
                                    :label="$t('add_receipt')"
                                    :placeholder="$t('add_receipt')"
                                    flat
                                    variant="plain"
                                    class="text-field"
                                    prepend-icon=""
                                    clearable
                                    v-model="receipt"
                                ></v-file-input>
                            </div>
                            <!-- show offline payment method's inputs -->
                        </div>
                    </div>

                    <template v-if="generalSettings.wallet_system == 1">
                        <div class="mt-4 mb-3 fs-16 fw-700">
                            {{ $t("or") }},
                        </div>
                        <div
                            :class="[
                                'border rounded pa-4 d-flex',
                                {
                                    'bg-soft-primary border-primary':
                                        selectedPaymentMethod &&
                                        selectedPaymentMethod.code == 'wallet',
                                },
                            ]"
                        >
                            <recharge-dialog
                                :show="rechargeDialogShow"
                                from="/checkout"
                                @close="rechargeDialogClosed"
                            />
                            <v-row align="center">
                                <v-col cols="12" sm="4">
                                    <v-btn
                                        color="red"
                                        elevation="0"
                                        class="px-7 white--text"
                                        @click.stop="walletSelected()"
                                        >{{ $t("pay_with_wallet") }}</v-btn
                                    >
                                </v-col>
                                <v-col
                                    cols="12"
                                    sm="4"
                                    class="text-sm-center lh-1-5"
                                >
                                    <div>
                                        <span
                                            >{{
                                                $t("your_wallet_balance")
                                            }}
                                            :</span
                                        >
                                        <span class="fw-700 fs-15">{{
                                            format_price(currentUser.balance)
                                        }}</span>
                                    </div>
                                    <div
                                        v-if="
                                            selectedPaymentMethod &&
                                            selectedPaymentMethod.code ==
                                                'wallet'
                                        "
                                    >
                                        <span
                                            >{{
                                                $t("remaining_balance")
                                            }}
                                            :</span
                                        >
                                        <span class="fw-700 fs-15">{{
                                            format_price(
                                                currentUser.balance - totalPrice
                                            )
                                        }}</span>
                                    </div>
                                </v-col>
                                <v-col cols="12" sm="4" class="text-sm-end">
                                    <v-btn
                                        color="grey lighten-4"
                                        elevation="0"
                                        class="px-7"
                                        @click.stop="rechargeDialogShow = true"
                                        >{{ $t("recharge_wallet") }}</v-btn
                                    >
                                </v-col>
                            </v-row>
                        </div>
                    </template>
                </div>
                <div>
                    <!--  -->
                    <input
                        class="m-2"
                        type="checkbox"
                        id="checkbox"
                        v-model="checkbox"
                    />
                    <!--  -->
                    {{ $t("by_clicking_proceed_i_agree_to") }}
                    {{ $store.getters["app/appName"] }} системийн
                    <router-link
                        :to="{
                            name: 'CustomPage',
                            params: { pageSlug: 'terms-and-conditions' },
                        }"
                        class="primary--text fw-500"
                    >
                        {{ $t("terms_and_conditions") }} {{ " , " }}
                    </router-link>
                    <router-link
                        :to="{
                            name: 'CustomPage',
                            params: { pageSlug: 'return-policy' },
                        }"
                        class="primary--text fw-500"
                    >
                        {{ $t("return_policy") }}
                    </router-link>
                    мөн
                    <router-link
                        :to="{
                            name: 'CustomPage',
                            params: { pageSlug: 'privacy-policy' },
                        }"
                        class="primary--text fw-500"
                    >
                        {{ $t("privacy_policy") }}
                    </router-link>
                </div>
                <div class="my-8">
                    <v-btn
                        elevation="0"
                        color="primary"
                        class=""
                        x-large
                        @click="proceedCheckout"
                        :loading="checkoutLoading"
                        :disabled="checkoutLoading"
                    >
                        <span class="">{{ $t("proceed") }}</span>
                        <span class="border-start border-gray-400 ps-5 ms-5">{{
                            format_price(totalPrice)
                        }}</span>
                    </v-btn>
                </div>
                <Payment ref="makePayment" />
                <FailedDialog ref="failedPayment" />
                <v-overlay :value="checkoutLoading" z-index="99999">
                    <v-progress-circular
                        indeterminate
                        size="64"
                    ></v-progress-circular>
                </v-overlay>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import AddressDialog from "../components/address/AddressDialog.vue";
import CouponForm from "../components/cart/CouponForm.vue";
import RechargeDialog from "../components/wallet/RechargeDialog.vue";
import FailedDialog from "./../components/payment/FailedDialog.vue";
import Payment from "./../components/payment/Payment.vue";
export default {
    head: {
        title: "Checkout Page",
    },
    name: "AizShopCheckout",
    data() {
        return {
            for_pickup: true,
            selectedPickupPoint: null,
            checkbox: false,
            checkoutLoading: false,
            selectedShippingAddressId: null,
            selectedBillingAddressId: null,
            selectedPaymentMethod: null,
            selectedDeliveryOption: "",
            selectedDeliveryType: "",
            standardDeliveryCost: 0,
            expressDeliveryCost: 0,
            addDialogShow: false,
            addressSelectedForEdit: {},
            rechargeDialogShow: false,
            transactionId: null,
            receipt: null,
            authorizeNet: {
                card_number: "",
                cvv: "",
                expiration_month: "",
                expiration_year: "",
            },
            months: [
                "Jan",
                "Feb",
                "Mar",
                "Apr",
                "May",
                "Jun",
                "Jul",
                "Aug",
                "Sep",
                "Oct",
                "Nov",
                "Dec",
            ],
            dateloop: [],
        };
    },
    components: {
        AddressDialog,
        RechargeDialog,
        Payment,
        FailedDialog,
        CouponForm,
    },
    computed: {
        ...mapGetters("app", [
            "generalSettings",
            "appUrl",
            "paymentMethods",
            "offlinePaymentMethods",
        ]),
        ...mapGetters("address", [
            "getAddresses",
            "getDefaultShippingAddress",
            "getDefaultBillingAddress",
        ]),
        ...mapGetters("cart", [
            "getCartPrice",
            "getTotalCouponDiscount",
            "getCartClubPoints",
            "getCartTax",
            "getCartShops",
            "getStandardTime",
            "getExpressTime",
            "getAllCouponCodes",
            "getSelectedCartIds",
            "checkShopMinOrder",
            "getIsDigital",
            "getPickupPoints",
            "getCartProducts",
        ]),
        check() {
            return this.$store.state.auth.modalWindow;
        },
        ...mapGetters("auth", ["currentUser"]),
        totalPrice() {
            return this.selectedDeliveryType == "home_delivery"
                ? this.selectedDeliveryOption === "standard"
                    ? this.getCartPrice -
                      this.getTotalCouponDiscount +
                      this.standardDeliveryCost * this.getCartShops.length
                    : this.getCartPrice -
                      this.getTotalCouponDiscount +
                      this.expressDeliveryCost * this.getCartShops.length
                : this.getCartPrice - this.getTotalCouponDiscount;
        },
    },
    methods: {
        ...mapActions("cart", [
            "resetCoupon",
            "removeMultipleFromCart",
            "fetchCartProducts",
            "fetchPickupPoints",
        ]),
        ...mapActions("address", ["fetchAddresses"]),
        ...mapActions("auth", ["rechargeWallet", "deductFromWallet"]),

        // check for pick up availability
        globalClose() {
            this.$store.commit("auth/modalWindow", false);
        },
        async checkForPickUp(type) {
            this.getCartProducts.map((product) => {
                if (product.for_pickup == 0) {
                    this.selectedPickupPoint = null;
                    this.for_pickup = false;
                    this.snack({
                        message: `One or more items in the cart are not available for pickup`,
                        color: "red",
                    });
                    return;
                } else {
                    this.for_pickup = true;
                }
            });
            this.selectedDeliveryType = type;
        },

        ChooseDeleviryType(deliveryType) {
            this.selectedDeliveryType = deliveryType;
        },

        addressDialogClosed() {
            this.addressSelectedForEdit = {};
            this.addDialogShow = false;
        },

        editAddress(address) {
            this.addressSelectedForEdit = address;
            this.addDialogShow = true;
        },
        rechargeDialogClosed() {
            this.rechargeDialogShow = false;
        },
        paymentSelected(event, paymentMethod) {
            this.selectedPaymentMethod = paymentMethod;
        },
        walletSelected() {
            if (this.currentUser.balance >= this.totalPrice) {
                this.selectedPaymentMethod = {
                    code: "wallet",
                };
            } else {
                this.snack({
                    message: `You don't have enough wallet balance. Please recharge.`,
                    color: "red",
                });
            }
        },
        shippingAddressSelected(address_id) {
            this.getShippingCost(address_id);
        },
        async getShippingCost(address_id) {
            const res = await this.call_api(
                "get",
                `checkout/get-shipping-cost/${address_id}`
            );
            this.selectedDeliveryOption = res.data.success ? "standard" : "";
            this.standardDeliveryCost = parseFloat(
                res.data.standard_delivery_cost
            );
            this.expressDeliveryCost = parseFloat(
                res.data.express_delivery_cost
            );
        },
        async proceedCheckout() {
            if (!this.checkbox) {
                this.snack({
                    message: `You need to agree with our policies`,
                    color: "red",
                });
                return;
            }
            if (this.getSelectedCartIds.length == 0) {
                this.snack({
                    message: `Please select a cart product`,
                    color: "red",
                });
                return;
            }
            if (
                this.selectedDeliveryType == "home_delivery" &&
                this.getAddresses.length == 0
            ) {
                this.snack({
                    message: `Please add a delivery address`,
                    color: "red",
                });
                return;
            }

            if (
                this.selectedDeliveryType === "home_delivery" &&
                !this.selectedShippingAddressId
            ) {
                this.snack({
                    message: `Please select a delivery address`,
                    color: "red",
                });
                return;
            }
            // if (
            //     this.selectedDeliveryType === "home_delivery" &&
            //     !this.selectedBillingAddressId
            // ) {
            //     this.snack({
            //         message: `Please select a billing address`,
            //         color: "red",
            //     });
            //     return;
            // }

            if (this.selectedDeliveryType === "") {
                this.snack({
                    message: `Please select delivery type at first`,
                    color: "red",
                });
                return;
            }
            if (
                this.selectedDeliveryType === "pickup" &&
                this.for_pickup == false
            ) {
                this.snack({
                    message: `One or more items in the cart are not available for pickup`,
                    color: "red",
                });
                return;
            }
            if (
                this.selectedDeliveryType === "pickup" &&
                this.selectedPickupPoint == null
            ) {
                this.snack({
                    message: `Please select a pick up point`,
                    color: "red",
                });
                return;
            }
            if (
                this.selectedDeliveryType === "home_delivery" &&
                this.selectedDeliveryOption === ""
            ) {
                this.snack({
                    message: `Sorry, delivery is not available in this shipping address.`,
                    color: "red",
                });
                return;
            }

            if (!this.selectedPaymentMethod) {
                this.snack({
                    message: `Please select a payment method`,
                    color: "red",
                });
                return;
            }

            if (
                this.selectedPaymentMethod &&
                this.selectedPaymentMethod.code.includes("offline_payment") &&
                this.transactionId === null
            ) {
                this.snack({
                    message: this.$i18n.t("please_input_transaction_id"),
                    color: "red",
                });
                return;
            }

            if (!this.checkShopMinOrder.success) {
                this.snack({
                    message: this.checkShopMinOrder.message,
                    color: "red",
                });
                return;
            }

            let formData = new FormData();
            formData.append(
                "shipping_address_id",
                this.selectedShippingAddressId
            );
            formData.append(
                "billing_address_id",
                this.selectedBillingAddressId
            );
            formData.append("payment_type", this.selectedPaymentMethod.code);
            formData.append("delivery_type", this.selectedDeliveryOption);
            formData.append("type_of_delivery", this.selectedDeliveryType);
            formData.append("pickup_point_id", this.selectedPickupPoint);

            let cartIds = this.getSelectedCartIds;
            cartIds.forEach((item, index) => {
                formData.append("cart_item_ids[]", item);
            });

            let coupon_codes = this.getAllCouponCodes;
            coupon_codes.forEach((couponItem, couponItemIndex) => {
                formData.append("coupon_codes[]", couponItem);
            });

            formData.append("transactionId", this.transactionId);
            formData.append("receipt", this.receipt);

            if (this.getCartPrice > 0) {
                this.checkoutLoading = true;
                const res = await this.call_api(
                    "post",
                    "checkout/order/store",
                    formData
                );

                if (res.data.success) {
                    if (res.data.payment_method == "wallet") {
                        this.deductFromWallet(res.data.grand_total);
                    }
                    if (res.data.go_to_payment) {
                        this.$refs.makePayment.pay({
                            requestedFrom: "/checkout",
                            paymentAmount: 0,
                            paymentMethod: res.data.payment_method,
                            paymentType: "cart_payment",
                            userId: this.currentUser.id,
                            oderCode: res.data.order_code,

                            // Authorize Net
                            card_number: this.authorizeNet.card_number,
                            cvv: this.authorizeNet.cvv,
                            expiration_month:
                                this.authorizeNet.expiration_month,
                            expiration_year: this.authorizeNet.expiration_year,
                        });
                    } else {
                        this.$router
                            .push({
                                name: "OrderConfirmed",
                                query: { orderCode: res.data.order_code },
                            })
                            .catch(() => {});
                    }
                    setTimeout(() => {
                        this.resetCoupon();
                        this.removeMultipleFromCart(this.getSelectedCartIds);
                    }, 2000);
                } else {
                    this.snack({
                        message: res.data.message,
                        color: "red",
                    });
                }
                this.checkoutLoading = false;
            }
        },
    },
    async created() {
        await this.fetchPickupPoints();
        await this.fetchAddresses();
        this.selectedShippingAddressId = this.getDefaultShippingAddress.id;
        this.selectedBillingAddressId = this.getDefaultBillingAddress.id;
        this.getShippingCost(this.selectedShippingAddressId);

        let dateArray = [];
        let i = new Date().getFullYear();
        for (i; i <= new Date().getFullYear() + 15; i++) {
            dateArray.push(i);
        }
        this.dateLoop = dateArray;
    },
    mounted() {
        if (this.$route.query.cart_payment && this.$route.query.order_code) {
            if (this.$route.query.cart_payment == "success") {
                this.$router
                    .push({
                        name: "OrderConfirmed",
                        query: {
                            orderCode: this.$route.query.order_code,
                        },
                    })
                    .catch(() => {});
                this.snack({ message: "Payment successful!" });
            } else if (this.$route.query.cart_payment == "failed") {
                this.$refs.failedPayment.open({
                    orderCode: this.$route.query.order_code,
                    paymentMethod: this.$route.query.payment_method,
                });
            }
        }
        this.rechargeWallet(this.$route.query.wallet_payment);
        this.fetchCartProducts();
    },
};
</script>
<style>
@media (min-width: 600px) {
    .checkout-banner img {
        height: 281px;
        object-fit: cover;
    }
}
</style>
