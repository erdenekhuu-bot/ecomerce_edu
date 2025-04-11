import Mixin from "../../utils/mixin";
const loadState = () => ({
    affiliateBalance: 0,
    withdrawRequest: [],
    withdrawRequestCurrentPage: 1,
    withdrawRequestLastPage: 1,
    referralCode: "",
    totalClick:0,
    totalItem:0,
    totalDelevered:0,
    totalCancel:0,
    affiliatedUser: false,
    userReferralCode:'',
    affiliateOption:false,
});

export default {
    namespaced: true,
    state: loadState(),
    getters: {
        isAffiliatedUser(state){
            return state.affiliatedUser;
        },
        getAffiliateOption(state){
            return state.affiliateOption;
        },
        getUserReferralCode(state){
            return state.userReferralCode;
        },
        getTotalClick(state){
            return state.totalClick;
        },
        getTotalItem(state){
            return state.totalItem;
        },
        getTotalDelevered(state){
            return state.totalDelevered;
        },
        getTotalCancel(state){
            return state.totalCancel;
        },
        getAffiliateBalance(state){
            return state.affiliateBalance;
        },
        getWithdrawRequest(state) {
            return state.withdrawRequest;
        },
        getReferralCode(state) {
            return state.referralCode;
        },
        getWithdrawRequestCurrentPage(state) {
            return state.withdrawRequestCurrentPage;

        },
        getWithdrawRequestLastPage(state) {
            return state.withdrawRequestLastPage;
        },
    },
    mutations: {
        setWithdrawRequest(state, data) {
            state.withdrawRequest = data;
        },
        setAffiliatedUser(state, data) {
            state.affiliatedUser = data;
        },
        setUserReferralCode(state, data) {
            state.userReferralCode = data;
        },
    },
    actions: {
        async fetchWithdrawRequest({ state, commit }, page) {
            const res = await Mixin.methods.call_api(
                "get",
                `user/affiliate/withdraw-request?page=${page}`
            );
            if (res.status == 200) {
                commit("setWithdrawRequest", res.data.data);
                state.withdrawRequestCurrentPage = res.data.meta.current_page;
                state.withdrawRequestLastPage = res.data.meta.last_page;
            } else {
                this.snack({
                    message: this.$i18n.t("something_went_wrong"),
                    color: "red",
                });
            }
        },
        async fetchAffiliateBalance({ state}) {
            const res = await Mixin.methods.call_api(
                "get",
                `user/affiliate/balance`
            );
            if (res.status == 200) {
                state.affiliateBalance = res.data.affiliate_balance;
            } else {
                this.snack({
                    message: this.$i18n.t("something_went_wrong"),
                    color: "red",
                });
            }
        },
        async fetchAffiliateReferralCode({ state}) {
            const res = await Mixin.methods.call_api(
                "get",
                `user/affiliate/referral-code`
            );
            if (res.status == 200) {
                state.referralCode = res.data.referral_code;
            } else {
                this.snack({
                    message: this.$i18n.t("something_went_wrong"),
                    color: "red",
                });
            }
        },
        async fetchAffiliateStats({ state}) {
            const res = await Mixin.methods.call_api(
                "get",
                `user/affiliate/stats`
            );
            if (res.status == 200) {
                state.totalClick = res.data.data.click;
                state.totalItem = res.data.data.item;
                state.totalDelevered = res.data.data.delivered;
                state.totalCancel = res.data.data.cancel;
            } else {
                this.snack({
                    message: this.$i18n.t("something_went_wrong"),
                    color: "red",
                });
            }
        },
        async fetchAffiliatedUser({ state }) {
            const res = await Mixin.methods.call_api(
                "get",
                `user/affiliate/user-check`
            );
            if (res.status == 200) {
                state.affiliatedUser = res.data.affiliated_user;
                state.userReferralCode = res.data.user_referral_code;
                state.affiliateOption = res.data.affiliate_option;
            } 
        },
    },
};
