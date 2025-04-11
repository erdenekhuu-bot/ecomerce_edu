<template>
    <div class="topbar">
        <div
            v-if="topBannerVisible && !loading && data.top_banner.img"
            class="position-relative"
        >
            <div v-show="check" class="demo-print hide-topbar">
                <dynamic-link
                    :to="data.top_banner.link"
                    append-class="text-reset d-block lh-0"
                >
                    <img
                        :src="data.top_banner.img"
                        class="img-fit h-50px w-100"
                    />
                </dynamic-link>
                <v-btn
                    elevation="0"
                    fab
                    outlined
                    size="x-small"
                    class="absolute-top-right rounded border-2 btn-xxs mt-2 me-2 transparent"
                    color="white"
                    v-on:click="closeTopBanner"
                >
                    <i class="las la-times ts-10 text-white" />
                </v-btn>
            </div>
        </div>

        <v-container class="fs-13 py-0 px-0 px-md-3">
            <v-row
                align="center"
                class="my-0 d-none d-md-flex"
                :style="{ fontSize: '12px' }"
            >
                <v-col cols="6" class="py-2">
                    <div class="d-flex align-center">
                        <!-- language switcher -->
                        <v-menu
                            v-if="
                                data.show_language_switcher == 'on' &&
                                allLanguages.length > 1
                            "
                            offset-y
                            :close-on-click="menuCloseOnClick"
                            :elevation="2"
                        >
                            <template v-slot:activator="{ props }">
                                <span
                                    class="d-flex align-center"
                                    v-bind="props"
                                >
                                    <span class="opacity-60">{{
                                        userLanguageObj.name
                                    }}</span>
                                    <i class="las la-angle-down ms-1 fs-12" />
                                </span>
                            </template>

                            <v-list class="fs-13">
                                <v-list-item
                                    v-for="(language, i) in allLanguages"
                                    :key="i"
                                    :value="language"
                                    color="primary"
                                    @click="switchLanguage(language.code)"
                                    class="language-list-item"
                                >
                                    <template v-slot:prepend>
                                        <img
                                            :src="
                                                static_asset(
                                                    `/assets/img/flags/${language.flag}.png`
                                                )
                                            "
                                            class="me-1 h-10px"
                                        />
                                    </template>

                                    <v-list-item-title
                                        class="language-list-item-title ml-1"
                                        v-text="language.name"
                                    ></v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu>

                        <!-- currency switcher -->
                        <!-- <v-menu offset-y :close-on-click="menuCloseOnClick">
                            <template #activator="{ on, attrs }">
                                <span
                                    v-bind="attrs"
                                    class="d-flex align-center py-1 ms-2"
                                    v-on="on"
                                >
                                    <span class="opacity-60"
                                        >{{ cselectedCurrency.name }} ({{
                                            cselectedCurrency.sysmbol
                                        }})</span
                                    >
                                    <i class="las la-angle-down ms-1 fs-12"></i>
                                </span>
                            </template>

                            <v-list>
                                <v-list-item
                                    v-for="(currency, i) in allCurrencies"
                                    :key="i"
                                    class="c-pointer"
                                >
                                    <v-list-item-title class="fs-13 opacity-60">
                                        {{ currency.name }} ({{
                                            currency.symbol
                                        }})
                                    </v-list-item-title>
                                </v-list-item>
                            </v-list>
                        </v-menu> -->

                        <v-divider
                            v-if="
                                data.show_language_switcher == 'on' &&
                                allLanguages.length > 1
                            "
                            vertical
                            class="mx-4"
                        />
                        <a
                            :href="data.mobile_app_links?.play_store"
                            target="_blank"
                            class="me-4 text-reset"
                            v-if="
                                data.mobile_app_links &&
                                data.mobile_app_links.show_play_store == 'on'
                            "
                        >
                            <i class="lab la-android" />
                            <span class="opacity-60">{{
                                $t("play_store")
                            }}</span>
                        </a>
                        <a
                            :href="data.mobile_app_links?.app_store"
                            target="_blank"
                            class="text-reset"
                            v-if="
                                data.mobile_app_links &&
                                data.mobile_app_links.show_app_store == 'on'
                            "
                        >
                            <i class="lab la-apple" />
                            <span class="opacity-60">{{
                                $t("app_store")
                            }}</span>
                        </a>
                        <template v-if="is_addon_activated('multi_vendor')">
                            <v-divider
                                vertical
                                class="mx-4"
                                v-if="
                                    data.mobile_app_links &&
                                    (data.mobile_app_links.show_play_store ==
                                        'on' ||
                                        data.mobile_app_links.show_app_store ==
                                            'on')
                                "
                            />
                        </template>
                    </div>
                </v-col>
                <v-col cols="6" class="py-2">
                    <div
                        class="d-flex align-center"
                        style="justify-content: space-between"
                    >
                        <router-link
                            :to="{ name: 'TrackOrder' }"
                            class="text-reset opacity-60"
                        >
                            {{ $t("track_order") }}
                        </router-link>

                        <!-- <router-link
                            :href="'tel:' + data.helpline"
                            class="text-reset opacity-60"
                        >
                            <span class="px-1">{{ $t("help_line") }}</span>
                            <span>{{ data.helpline }}</span>
                        </router-link> -->
                        <a
                            :href="'tel:' + data.helpline"
                            class="text-reset opacity-60"
                        >
                            <span class="px-1">{{ $t("help_line") }}</span>
                            <span>{{ data.helpline }}</span>
                        </a>

                        <router-link
                            :to="{ name: 'ComparedList' }"
                            class="text-reset opacity-60 me-3"
                            v-if="generalSettings.product_comparison == 1"
                        >
                            <span class=""
                                >{{ $t("compare_list") }} ({{
                                    getTotalComparedList
                                }})</span
                            >
                        </router-link>
                        <router-link
                            :to="{ name: 'Wishlist' }"
                            class="text-reset opacity-60"
                        >
                            <span class=""
                                >{{ $t("wishlist") }} ({{
                                    getTotalWishlisted
                                }})</span
                            >
                        </router-link>
                    </div>
                </v-col>
            </v-row>
        </v-container>
        <v-divider class="" />
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import helpers from "../../utils/helpers";
export default {
    props: {
        loading: { type: Boolean, required: true, default: true },
        data: {
            type: Object,
            default: {},
        },
    },
    data: () => ({
        topBannerVisible: false,
        topBanner: {
            image: helpers.asset("/assets/img/icons/topbar.jpg"),
            link: "",
        },

        currencies: [
            {
                name: "U.S. Dollar",
                sysmbol: "$",
                code: "USD",
            },
            {
                name: "Taka",
                sysmbol: "Tk",
                code: "BDT",
            },
        ],
        cselectedCurrency: {
            name: "U.S. Dollar",
            sysmbol: "$",
            code: "USD",
        },
        menuCloseOnClick: true,
    }),
    computed: {
        ...mapGetters("app", ["generalSettings"]),
        ...mapGetters("wishlist", ["getTotalWishlisted"]),
        ...mapGetters("compareList", ["getTotalComparedList"]),
        ...mapGetters("app", [
            "userLanguageObj",
            "allLanguages",
            "allCurrencies",
        ]),
        check() {
            return this.$store.state.app.checkWindow;
        },
    },
    methods: {
        ...mapActions("app", ["fetchProductQuerries"]),
        ...mapActions("wishlist", ["fetchWislistProducts"]),
        ...mapActions("app", ["setLanguage"]),

        switchLanguage(locale) {
            if (this.$i18n.locale !== locale) {
                this.setLanguage(locale);
                this.$i18n.locale = locale;
                window.location.reload();
            }
        },
        closeTopBanner() {
            this.topBannerVisible = false;
            this.$store.commit("app/getCheckWindow", false);
            this.setSession("shopTopBanner", "hidden");
        },
    },
    created() {
        if (this.checkSession("shopTopBanner") != "hidden") {
            this.topBannerVisible = true;
        }
        this.fetchWislistProducts();
        this.fetchProductQuerries();
        // setInterval(() => {
        //   this.fetchProductQuerries();
        // }, 8000);

        // set default language
        if (!localStorage.getItem("shopSelectedLanguage")) {
            localStorage.setItem(
                "shopSelectedLanguage",
                shopSetting.appLanguage
            );
            this.$i18n.locale = shopSetting.appLanguage;
        }
    },
};
</script>
<style scoped>
.topbar {
    position: relative;
    z-index: 1;
    background-color: #fff;
}
.px-1 {
    padding-left: 0.25rem;
    padding-right: 0.25rem;
}
.mx-custom {
    margin-left: 1rem;
    margin-right: 1rem;
}
</style>
