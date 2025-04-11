import { i18n } from "./../../plugins/i18n";
import Mixin from "./../../utils/mixin";
const shopSetting = window.shopSetting;
const loadState = () => ({
    chatWindowOpen: false,
    cartDrawerOpen: false,
    modalWindow: false,
    mobileSideMenuOpen: false,
    showLoginDialog: false,
    showAddToCartDialog: false,
    showConversationDialog: false,
    cartDialogProductSlug: null,
    accessToken: localStorage.getItem("shopAccessToken") || null,
    currentUser: {},
    sociaLoginStatus: null,
    authSettings: shopSetting.authSettings,
    hideTransform: true,
    // checkLogin: false,
    checkLogin: localStorage.getItem("shopAccessToken") ? true : false,
});
export default {
    namespaced: true,
    state: loadState(),
    getters: {
        triggerCheckLogin(state) {
            return state.checkLogin;
        },
        chatWindowOpen(state) {
            return state.chatWindowOpen;
        },
        cartDrawerOpen(state) {
            return state.cartDrawerOpen;
        },
        mobileSideMenuOpen(state) {
            return state.mobileSideMenuOpen;
        },
        showLoginDialog(state) {
            return state.showLoginDialog;
        },
        showAddToCartDialog(state) {
            return state.showAddToCartDialog;
        },
        showConversationDialog(state) {
            return state.showConversationDialog;
        },
        cartDialogProductSlug(state) {
            return state.cartDialogProductSlug;
        },
        isAuthenticated(state) {
            return state.accessToken !== null;
        },
        accessToken(state) {
            return state.accessToken;
        },
        currentUser(state) {
            return state.currentUser;
        },
        sociaLoginStatus(state) {
            return state.sociaLoginStatus;
        },
        authSettings(state) {
            return state.authSettings;
        },
    },
    mutations: {
        setCheckLogin(state, status) {
            state.checkLogin = status;
        },
        updateChatWindow(state, status) {
            state.chatWindowOpen = status;
        },
        updateCartDrawer(state, status) {
            state.cartDrawerOpen = status;
        },
        modalWindow(state, status) {
            state.modalWindow = status;
        },
        updateMobileSideMenu(state, status) {
            state.mobileSideMenuOpen = status;
        },
        showLoginDialog(state, status) {
            state.showLoginDialog = status;
        },
        showAddToCartDialog(state, { status, slug }) {
            state.showAddToCartDialog = status;
            state.cartDialogProductSlug = slug;
            if (!status) {
                state.hideTransform = true;
            } else {
                state.hideTransform = false;
            }
        },
        showConversationDialog(state, { status }) {
            state.showConversationDialog = status;
        },
        login(state, data) {
            state.accessToken = data.access_token;
            state.currentUser = data.user;

            localStorage.setItem("shopAccessToken", data.access_token);
        },
        logout(state) {
            localStorage.removeItem("shopAccessToken");
            const newState = loadState();
            Object.keys(newState).forEach((key) => {
                state[key] = newState[key];
            });
            state.checkLogin = false;
        },
        setUser(state, data) {
            state.currentUser = data;
        },
        setSociaLoginStatus(state, status) {
            state.sociaLoginStatus = status;
        },
        setAccessToken(state, token) {
            state.accessToken = token;
            localStorage.setItem("shopAccessToken", token);
        },
        deductFromWallet(state, amount) {
            if (state.currentUser.balance >= amount) {
                state.currentUser.balance -= amount;
            }
        },
    },
    actions: {
        async getUser({ commit, getters }) {
            if (getters.accessToken) {
                try {
                    const res = await Mixin.methods.call_api(
                        "get",
                        `user/info`
                    );
                    if (res.data.success) {
                        commit("setUser", res.data.user);
                        commit(
                            "follow/setFollowedShops",
                            res.data.followed_shops,
                            { root: true }
                        );
                    } else {
                        commit("logout");
                    }
                } catch (e) {
                    commit("logout");
                }
            }
        },
        rechargeWallet({ dispatch }, status) {
            if (status && status == "success") {
                Mixin.methods.snack({
                    message: "wallet_successfully_recharged",
                });
                dispatch("getUser");
            } else if (status && status == "failed") {
                Mixin.methods.snack({
                    message: "wallet_recharge_failed",
                    color: "red",
                });
            }
        },
        deductFromWallet({ commit }, amount) {
            commit("deductFromWallet", amount);
        },
        checkSocialLoginStatus({ dispatch, getters }) {
            if (getters.sociaLoginStatus == "success") {
                Mixin.methods.snack({ message: "login_successfull" });
                dispatch("getUser");
            } else if (getters.sociaLoginStatus == "failed") {
                Mixin.methods.snack({
                    message: "something_went_wrong",
                    color: "red",
                });
            }
        },
        login({ commit }, data) {
            commit("login", data);
        },
        logout({ commit }) {
            commit("logout");
        },
        showConversationDialog({ commit }, status) {
            if (this.getters["auth/isAuthenticated"]) {
                commit("showConversationDialog", status);
            } else {
                commit("auth/showLoginDialog", true, { root: true });
            }
        },
    },
};
