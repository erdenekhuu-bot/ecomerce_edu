import Mixin from "../../utils/mixin";
const loadState = () => ({
    dashboardData: {}
});

export default {
    namespaced: true,
    state: loadState(),
    getters: {
        getDashboardData(state) {
            return state.dashboardData;
        },
    },
    mutations: {
        setdashboardData(state, data) {
            state.dashboardData = data;
        },
    },
    actions: {

        async fetchDashboardData({ commit, state}) {
            const res = await Mixin.methods.call_api(
                "get",
                `delivery-boy/dashboard`
            );
            if (res.status == 200) {
                commit("setdashboardData", res.data.data);
                // state.dashboardData =  res.data.data;
            } else {
                this.snack({
                    message: this.$i18n.t("something_went_wrong"),
                    color: "red",
                });
            }
        },  
    },
};
