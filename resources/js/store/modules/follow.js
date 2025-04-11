import Mixin from './../../utils/mixin'
import { i18n } from './../../plugins/i18n'
const loadState = () => ({
    followedShops: [],
});
export default {
    namespaced: true,
    state: loadState(),
    getters: {
        isThisFollowed:(state) => (shop_id) => {
            return state.followedShops.includes(shop_id)
        },
        getFollowedShops(state){
            return state.followedShops
        },
    },
    mutations: {
        setFollowedShops(state, data){
            state.followedShops = data;
        },
        addNewFollowedShop(state, shop_id){
            if(!state.followedShops.includes(shop_id)){
                state.followedShops.push(shop_id);
            }
        },
        removeFromFollowedShop(state, shop_id){
            if(state.followedShops.includes(shop_id)){
                state.followedShops = state.followedShops.filter(val => val !== shop_id)
            }
        },
        resetFollowedShop(state){
            state.followedShops = []
        }
    },
    actions: {
        async addNewFollowedShop({commit},shop_id){
            if(this.getters['auth/isAuthenticated']){
                commit('addNewFollowedShop', shop_id)
                const res = await Mixin.methods.call_api("post", `user/follow`,{shop_id:shop_id});
                if(!res.data.success){
                    commit('removeFromFollowedShop', shop_id)
                    Mixin.methods.snack({
                        message: i18n.t("something_went_wrong"),
                        color: "red"
                    });
                }
            }else{
                commit('auth/showLoginDialog',true,{root:true})
            }
        },
        async removeFromFollowedShop({commit},shop_id){
            if(this.getters['auth/isAuthenticated']){
                commit('removeFromFollowedShop', shop_id)
                const res = await Mixin.methods.call_api("delete", `user/follow/${shop_id}`);
                if(!res.data.success){
                    commit('addNewFollowedShop', shop_id)
                    Mixin.methods.snack({
                        message: i18n.t("something_went_wrong"),
                        color: "red"
                    });
                }
            }else{
                commit('auth/showLoginDialog',true,{root:true})
            }
        },
        resetFollowedShop({commit}){
            commit('resetFollowedShop')
        }
    }
}