import Mixin from "./../../utils/mixin";
const loadState = () => ({
    comparedListProductIds:
        JSON.parse(localStorage.getItem("comparedProductList")) || [],
    comparedListProducts: [],
    comparedProductsList: [],
    specificationKeysList: [],
});

export default {
    namespaced: true,
    state: loadState(),
    getters: {
        comparedListProductIds(state) {
            return state.comparedListProductIds;
        },
        isThisComparedListed: (state) => (product_id) => {
            return state.comparedListProductIds.includes(product_id);
        },
        getTotalComparedList(state) {
            return state.comparedListProductIds.length;
        },
        getComparedListProducts(state) {
            return state.comparedListProducts;
        },
        getComparedProductsList(state) {
            return state.comparedProductsList;
        },
        getSpecificationKeysList(state) {
            return state.specificationKeysList;
        },
    },
    mutations: {
        addNewcomparedListProduct(state, product_id) {
            if (!state.comparedListProductIds.includes(product_id)) {
                if (state.comparedListProductIds.length >= 4) {
                    state.comparedListProductIds.shift();
                }
                state.comparedListProductIds.push(product_id);
                localStorage.removeItem("comparedProductList");
                localStorage.setItem(
                    "comparedProductList",
                    JSON.stringify(state.comparedListProductIds)
                );
            }
        },
        RemoveFromComparedList( state ,product_id) {
            let string_id =JSON.stringify(product_id);
            let productId = parseInt(string_id.replace(/[^0-9]*/g, ''));

            localStorage.removeItem("comparedProductList");
            state.comparedListProductIds = state.comparedListProductIds.filter((id) => id !== productId);
            localStorage.setItem(
                "comparedProductList",
                JSON.stringify(state.comparedListProductIds)
            );           
        },
        setComparedProductList(state, data) {
            state.comparedListProducts = data;
        },
        setComparedProductsList(state, data) {
            state.comparedProductsList = data;
        },
        setSpecificationKeysList(state, data) {
            state.specificationKeysList = data;
        },
        ResetComparedList(state){
            state.comparedListProductIds=[];
            state.comparedListProducts =[];
            localStorage.removeItem("comparedProductList");
        }
    },
    actions: {
        addNewComparedList({ commit }, product_id) {
            commit("addNewcomparedListProduct", product_id);
        },
        RemoveComparedListProduct({ commit }, product_id){
            commit("RemoveFromComparedList", product_id);
        },

        async fetchComparedListProducts({ commit }) {
            let product_ids = JSON.parse(localStorage.getItem("comparedProductList"));
            const res = await Mixin.methods.call_api("post", `compared-list`, {
                data: product_ids,
            });
            if (res.data.success) {
                
                commit("setComparedProductList", res.data.specifications);
                commit("setComparedProductsList", res.data.products);
                commit("setSpecificationKeysList", res.data.specifications_keys);
            }
        },
        ResetComparedList({commit}){
            commit("ResetComparedList");
        }
    },
};
