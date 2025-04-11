import Mixin from "./../../utils/mixin";

//state management dump all window objects
const shopSetting = window.shopSetting;
const loadState = () => ({
    appName: shopSetting.appName,
    appMetaTitle: shopSetting.appMetaTitle,
    appMetaDescription: shopSetting.appMetaDescription,

    appLogo: shopSetting.appLogo,
    appUrl: shopSetting.appUrl,
    demoMode: shopSetting.demoMode,
    appLanguage: shopSetting.appLanguage,
    paymentMethods: shopSetting.paymentMethods,
    offlinePaymentMethods: shopSetting.offlinePaymentMethods,
    userLanguage:
        localStorage.getItem("shopSelectedLanguage") || shopSetting.appLanguage,
    isRTL: localStorage.getItem("isRTL") || false,
    availableCountries:
        shopSetting.availableCountries.length > 0
            ? shopSetting.availableCountries
            : ["US"],
    cookieStatus: localStorage.getItem("cookieStatus") || null,
    allLanguages: shopSetting.allLanguages,
    allCurrencies: shopSetting.allCurrencies,
    generalSettings: shopSetting.general_settings,
    addons: shopSetting.addons,
    banners: shopSetting.banners,
    apiPath: "/api/v1/",

    routerLoading: true,
    refundSettings: shopSetting.refundSettings,
    productQuerries: [],
    unseenProductQuerries: 0,
    shopRegistrationMessageTitle:
        shopSetting.shop_registration_message.shop_registration_message_title,
    shopRegistrationMessageContent:
        shopSetting.shop_registration_message.shop_registration_message_content,
    cookieTitle: shopSetting.cookie_message.cookie_title,
    cookieDescription: shopSetting.cookie_message.cookie_description,
    checkWindow: true,
});
export default {
    namespaced: true,
    state: loadState(),
    getters: {
        appName(state) {
            return state.appName;
        },
        appMetaTitle(state) {
            return state.appMetaTitle;
        },
        appMetaDescription(state) {
            return state.appMetaDescription;
        },
        appLogo(state) {
            return state.appLogo;
        },
        appUrl(state) {
            return state.appUrl.slice(-1) == "/"
                ? state.appUrl.slice(0, state.appUrl.length - 1)
                : state.appUrl;
        },
        demoMode(state) {
            return state.demoMode;
        },
        apiPath(state) {
            return state.apiPath;
        },
        appLanguage(state) {
            return state.appLanguage;
        },
        userLanguage(state) {
            return state.userLanguage;
        },
        userLanguageObj(state) {
            return state.allLanguages.find(
                (language) => language.code === state.userLanguage
            );
        },
        paymentMethods(state) {
            return state.paymentMethods;
        },
        itemPayment: (state) => (parameter) => {
            return state.paymentMethods.filter(parameter);
        },
        offlinePaymentMethods(state) {
            return state.offlinePaymentMethods;
        },
        availableCountries(state) {
            return state.availableCountries;
        },
        allLanguages(state) {
            return state.allLanguages;
        },
        allCurrencies(state) {
            return state.allCurrencies;
        },
        generalSettings(state) {
            return state.generalSettings;
        },
        banners(state) {
            return state.banners;
        },
        addons(state) {
            return state.addons;
        },
        routerLoading(state) {
            return state.routerLoading;
        },
        refundSettings(state) {
            return state.refundSettings;
        },
        getProductQuerries(state) {
            return state.productQuerries;
        },
        getUnseenProductQuerries(state) {
            return state.unseenProductQuerries;
        },
        shopRegistrationMessageTitle(state) {
            return state.shopRegistrationMessageTitle;
        },
        shopRegistrationMessageContent(state) {
            return state.shopRegistrationMessageContent;
        },
        getCookieStatus(state) {
            return state.cookieStatus;
        },

        getCookieTitle(state) {
            return state.cookieTitle;
        },
        getCookieDescription(state) {
            return state.cookieDescription;
        },
    },
    mutations: {
        getCheckWindow(state, status) {
            state.checkWindow = status;
        },
        setProductQuerries(state, data) {
            state.productQuerries = data;
            let unseenData = data.filter((data) => {
                return data.sender_viewed == 0;
            });
            state.unseenProductQuerries = unseenData.length;
        },
        setLanguage(state, lang) {
            if (state.userLanguage !== lang) {
                state.userLanguage = lang;

                localStorage.removeItem("shopSelectedLanguage");
                localStorage.setItem("shopSelectedLanguage", lang);
            }
        },
        removeLanguage(state) {
            state.userLanguage = state.appLanguage;
            localStorage.removeItem("shopSelectedLanguage");
        },
        setRouterLoading(state, status) {
            state.routerLoading = status;
        },
        setCookie(state, status) {
            state.cookieStatus = status;
            localStorage.setItem("cookieStatus", status);
        },
        setRTL(state, status) {
            state.cookieStatus = status;
            localStorage.setItem("isRTL", status);
        },
    },
    actions: {
        setLanguage({ commit }, lang) {
            commit("setLanguage", lang);
        },
        removeLanguage({ commit }) {
            commit("removeLanguage");
        },
        async fetchProductQuerries({ commit }) {
            if (this.getters["auth/isAuthenticated"]) {
                const res = await Mixin.methods.call_api(
                    "get",
                    `user/querries`
                );
                if (res.data.success) {
                    commit("setProductQuerries", res.data.data);
                }
            }
        },
        setCookie({ commit }, cookieStatus) {
            commit("setCookie", cookieStatus);
        },
        setRTL({ commit }, isRTL) {
            commit("setRTL", isRTL);
        },
    },
};
