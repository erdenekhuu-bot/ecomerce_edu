import Mixin from './../../utils/mixin'
const loadState = () => ({
    recentlyViewedLoaded: false,
    recentlyViewedids: JSON.parse(localStorage.getItem('shopRecentlyViewed')) || [],
    recentlyViewedProducts: [],
});
export default {
    namespaced: true,
    state: loadState(),
    getters: {
        recentlyViewedLoaded(state){
            return state.recentlyViewedLoaded
        },
        recentlyViewedids(state){
            return state.recentlyViewedids
        },
        getRecentlyViewedProducts(state){
            return state.recentlyViewedProducts
        },
    },
    mutations: {
        setRecentlyViewedProducts(state, data){
            state.recentlyViewedLoaded = true;
            state.recentlyViewedProducts = data;
        },
        addNewRecentlyViewedProduct(state, product_id){
            if(!state.recentlyViewedids.includes(product_id)){
                if(state.recentlyViewedids.length >= 10 ){
                    state.recentlyViewedids.shift()
                }
                state.recentlyViewedids.push(product_id)

                localStorage.removeItem('shopRecentlyViewed');
                localStorage.setItem('shopRecentlyViewed', JSON.stringify(state.recentlyViewedids));
            }
        },
    },
    actions: {
        async fetchRecentlyViewedProducts({commit,getters}){
            if(!getters.recentlyViewedLoaded){
                const res = await Mixin.methods.call_api("post", `product/get-by-ids`,{ product_ids: getters.recentlyViewedids});
                if(res.data.success && res.data.data.length > 0){
                    commit('setRecentlyViewedProducts',res.data.data)
                }
            }
        },
        addNewRecentlyViewedProduct({commit},product_id){
            commit('addNewRecentlyViewedProduct',product_id)
        }
    }
}