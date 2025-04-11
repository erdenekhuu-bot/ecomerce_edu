import { createStore } from "vuex";
import addressModule from "./modules/address";
import affiliate from "./modules/affiliate";
import appModule from "./modules/app";
import authModule from "./modules/auth";
import cartModule from "./modules/cart";
import compareList from "./modules/compareList";
import deliveryboy from "./modules/deliveryboy";
import followModule from "./modules/follow";
import recentlyViewed from "./modules/recentlyViewed";
import snackBar from "./modules/snackbar";
import wishlistModule from "./modules/wishlist";

const store = createStore({
    modules: {
        app: appModule,
        auth: authModule,
        address: addressModule,
        wishlist: wishlistModule,
        follow: followModule,
        cart: cartModule,
        snackbar: snackBar,
        recentlyViewed: recentlyViewed,
        compareList: compareList,
        affiliate: affiliate,
        deliveryboy: deliveryboy,
    },
});

export default store;
