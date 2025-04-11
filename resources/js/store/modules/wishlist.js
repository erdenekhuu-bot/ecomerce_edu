import Mixin from './../../utils/mixin'
import { i18n } from './../../plugins/i18n'
const loadState = () => ({
    wislistLoaded: false,
    wislistProductIds: [],
    wislistProducts: [],
});
export default {
    namespaced: true,
    state: loadState(),
    getters: {
        wislistLoaded(state){
            return state.wislistLoaded
        },
        isThisWishlisted:(state) => (product_id) => {
            return state.wislistProductIds.includes(product_id)
        },
        getTotalWishlisted(state){
            return state.wislistProductIds.length
        },
        getWislistProducts(state){
            return state.wislistProducts
        },
    },
    mutations: {
        setWislistProducts(state, data){
            state.wislistLoaded = true;
            state.wislistProducts = data;
            state.wislistProductIds = data.map(item => item.id);
        },
        addNewWishlistId(state, product_id){
            if(!state.wislistProductIds.includes(product_id)){
                state.wislistProductIds.push(product_id);
            }
        },
        addNewWishlist(state, product){
            if(!state.wislistProducts.find(item => item.id === product.id)){
                state.wislistProducts.push(product);
            }
        },
        removeFromWishlistID(state, product_id){
            if(state.wislistProductIds.includes(product_id)){
                state.wislistProductIds = state.wislistProductIds.filter(val => val !== product_id);
            }
        },
        removeFromWishlist(state, product_id){
            if(state.wislistProducts.find(item => item.id === product_id)){
                state.wislistProducts = state.wislistProducts.filter(val => val.id !== product_id);
            }
        },
        resetWishlist(state){
            state.wislistLoaded = false
            state.wislistProductIds = []
            state.wislistProducts = []
        }
    },
    actions: {
        async fetchWislistProducts({commit,getters}){
            if(this.getters['auth/isAuthenticated'] && !getters.wislistLoaded){
                const res = await Mixin.methods.call_api("get", `user/wishlists`);
                if(res.data.success){
                    commit('setWislistProducts',res.data.data)
                }
            }
        },
        async addNewWishlist({commit},product_id){
            if(this.getters['auth/isAuthenticated']){
                commit('addNewWishlistId', product_id)
                const res = await Mixin.methods.call_api("post", `user/wishlists`,{product_id:product_id});
                if(res.data.success){
                    commit('addNewWishlist', res.data.product)
                }else{
                    commit('removeFromWishlistID', product_id)
                    Mixin.methods.snack({
                        message: i18n.t("something_went_wrong"),
                        color: "red"
                    });
                }
            }else{
                commit('auth/showLoginDialog',true,{root:true})
            }
        },
        async removeFromWishlist({commit},product_id){
            if(this.getters['auth/isAuthenticated']){
                commit('removeFromWishlistID', product_id)
                const res = await Mixin.methods.call_api("delete", `user/wishlists/${product_id}`);
                if(res.data.success){
                    commit('removeFromWishlist', product_id)
                }else{
                    commit('addNewWishlistId', product_id)
                    Mixin.methods.snack({
                        message: i18n.t("something_went_wrong"),
                        color: "red"
                    });
                }
            }else{
                commit('auth/showLoginDialog',true,{root:true})
            }
        },
        resetWishlist({commit}){
            commit('resetWishlist')
        }
    }
}