import Mixin from "./../../utils/mixin";
const loadState = () => ({
    addressesLoaded: false,
    addresses: [],
});
export default {
    namespaced: true,
    state: loadState(),
    getters: {
        addressesLoaded(state) {
            return state.addressesLoaded;
        },
        getAddresses(state) {
            return state.addresses;
        },
        getDefaultShippingAddress(state) {
            return state.addresses.find((address) => address.default_shipping);
        },
        getDefaultBillingAddress(state) {
            return state.addresses.find((address) => address.default_billing);
        },
    },
    mutations: {
        setAddresses(state, data) {
            state.addressesLoaded = true;
            state.addresses = data;
        },
        addNewAddress(state, data) {
            state.addresses.unshift(data);
        },
    },
    actions: {
        async fetchAddresses({ commit, getters }) {
            if (!getters.addressesLoaded) {
                const res = await Mixin.methods.call_api(
                    "get",
                    `user/addresses`
                );
                if (res.data.success) {
                    commit("setAddresses", res.data.data);
                }
            }
        },
        addAddress({ commit }, data) {
            commit("addNewAddress", data);
        },
    },
};
