import Mixin from "./../../utils/mixin";
const loadState = () => ({
    cartLoaded: false,
    cartPrice: 0,
    earn_point: 0,
    cartProducts: [],
    cartShops: [],
    tempUserId: localStorage.getItem("shopTempUserId") || null,
    isDigital: false,
    pickupPoints: [],
    storepay: false,
    pocket: false,
});
export default {
    namespaced: true,
    state: loadState(),
    getters: {
        isThisInCart: (state) => (variation_id) => {
            return state.cartProducts.some(
                (cartProduct) => cartProduct.variation_id === variation_id
            );
        },
        findCartItemByVariationId: (state) => (variation_id) => {
            return state.cartProducts.find(
                (cartProduct) => cartProduct.variation_id === variation_id
            );
        },
        findCartItemByCartId: (state) => (cart_id) => {
            return state.cartProducts.find(
                (cartProduct) => cartProduct.cart_id === cart_id
            );
        },
        getTempUserId(state) {
            return state.tempUserId;
        },
        getAllCouponCodes(state) {
            return state.cartShops
                .map((shop) => shop.couponCode)
                .filter((code) => code);
        },
        getCouponCode: (state) => (shop_id) => {
            if (shop_id) {
                return state.cartShops.length > 0
                    ? state.cartShops.find((shop) => shop.id == shop_id)
                          .couponCode
                    : null;
            } else {
                return state.cartShops.length > 0
                    ? state.cartShops[0].couponCode
                    : null;
            }
        },
        getCouponDetails(state) {
            return state.couponDetails;
        },
        getTotalCouponDiscount(state) {
            let total = 0;
            state.cartShops.forEach((shop) => {
                total += shop.couponDiscount;
            });
            return total;
        },
        getCartCount(state) {
            return state.cartProducts.length;
        },
        getCartPrice(state) {
            let total = 0;
            state.cartProducts.forEach((item) => {
                if (item.selected) total += item.dicounted_price * item.qty;
            });
            return (state.cartPrice = total);
        },
        getCartClubPoints(state) {
            let points = 0;
            state.cartProducts.forEach((item) => {
                if (item.selected && item.earn_point !== null)
                    points += item.earn_point * item.qty;
            });
            return (state.earn_point = points);
        },
        getCartTax(state) {
            let total = 0;
            state.cartProducts.forEach((item) => {
                if (item.selected) total += item.tax * item.qty;
            });
            return total;
        },
        getCartProducts(state) {
            return state.cartProducts;
        },
        getCartShops(state) {
            return state.cartShops;
        },
        getShopProductsById: (state) => (shop_id) => {
            return state.cartProducts.filter((item) => item.shop_id == shop_id);
        },
        getShopCartTotalPrice: (state) => (shop_id) => {
            let total = 0;
            state.cartProducts.forEach((item) => {
                if (item.shop_id == shop_id && item.selected)
                    total += item.dicounted_price * item.qty;
            });
            return total;
        },
        getShopCartPrice: (state) => (shop_id) => {
            let total = 0;
            let shop = state.cartShops.find((shop) => shop.id == shop_id);
            state.cartProducts.forEach((item) => {
                if (item.shop_id == shop_id && item.selected)
                    total += item.dicounted_price * item.qty;
            });
            return total - shop.couponDiscount;
        },
        getShopMinOrder:
            (state) =>
            (shop_id = null) => {
                return shop_id
                    ? state.cartShops.find((shop) => shop.id == shop_id)
                          .min_order
                    : state.cartShops[0].min_order;
            },
        getStandardTime(state) {
            let selectedProducts = state.cartProducts.filter(
                (item) => item.selected
            );
            if (selectedProducts.length > 0) {
                let min = Math.ceil(
                    Math.min(
                        ...selectedProducts.map(
                            (item) => item.standard_delivery_time
                        )
                    ) / 24
                );
                let max = Math.ceil(
                    Math.max(
                        ...selectedProducts.map(
                            (item) => item.standard_delivery_time
                        )
                    ) / 24
                );
                return min !== max ? min + "-" + max : min;
            } else {
                return 0;
            }
        },
        getExpressTime(state) {
            let selectedProducts = state.cartProducts.filter(
                (item) => item.selected
            );
            if (selectedProducts.length > 0) {
                let min = Math.ceil(
                    Math.min(
                        ...selectedProducts.map(
                            (item) => item.express_delivery_time
                        )
                    ) / 24
                );
                let max = Math.ceil(
                    Math.max(
                        ...selectedProducts.map(
                            (item) => item.express_delivery_time
                        )
                    ) / 24
                );
                return min !== max ? min + "-" + max : min;
            } else {
                return 0;
            }
        },
        getSelectedCartIds(state) {
            return state.cartProducts
                .filter((item) => item.selected)
                .map((item) => item.cart_id);
        },
        getSelectedCartIdsByShopId: (state) => (shop_id) => {
            return state.cartProducts
                .filter((item) => item.shop_id == shop_id && item.selected)
                .map((item) => item.cart_id);
        },
        checkShopMinOrder: (state, getters) => {
            let result = { success: true, message: null };
            state.cartShops.forEach((shop) => {
                if (shop.selected && shop.min_order > 0) {
                    let total = 0;
                    state.cartProducts.forEach((item) => {
                        if (item.shop_id == shop.id && item.selected)
                            total += item.dicounted_price * item.qty;
                    });
                    let shopCartPrice = total - shop.couponDiscount;

                    if (shopCartPrice < shop.min_order) {
                        result = {
                            success: false,
                            message: `You need to reach ${Mixin.methods.format_price(
                                parseFloat(shop.min_order)
                            )}  to place order ${shop.name}`,
                            // message: `${i18n.t(
                            //     "you_need_to_reach"
                            // )} ${Mixin.methods.format_price(
                            //     parseFloat(shop.min_order)
                            // )} ${i18n.t("to_place_order_from")} ${shop.name}`,
                        };
                    }
                }
            });
            return result;
        },
        getIsDigital(state) {
            return state.isDigital;
        },
        getPickupPoints(state) {
            return state.pickupPoints;
        },
    },
    mutations: {
        setStorepay(state, data) {
            state.storepay = data;
        },
        setPocket(state, data) {
            state.pocket = data;
        },
        setIsDigital(state, data) {
            let status = data.map((cartProduct) => {
                return cartProduct.is_digital == 1 ? true : false;
            });
            if (status.includes(true)) {
                state.isDigital = true;
            }
        },

        setTempUserId(state, temp_user_id) {
            state.tempUserId = temp_user_id;
            localStorage.setItem("shopTempUserId", temp_user_id);
        },
        removeTempUserId(state) {
            state.tempUserId = null;
            localStorage.removeItem("shopTempUserId");
        },
        setCartProducts(state, data) {
            state.cartLoaded = true;
            state.cartProducts = data.map((cartProduct) => {
                cartProduct.selected = cartProduct.stock ? true : false;
                cartProduct.outOfStock = cartProduct.stock ? false : true;
                cartProduct.max_qty =
                    cartProduct.max_qty > 0 ? cartProduct.max_qty : Infinity;
                return cartProduct;
            });
        },
        setCartShops(state, data) {
            state.cartShops = data.map((cartShop) => {
                cartShop.selected = true;
                cartShop.couponCode = null;
                cartShop.couponDetails = {};
                cartShop.couponDiscount = 0;
                return cartShop;
            });
        },
        updateCartShops(state, data = {}) {
            //for adding shop
            if (
                !Mixin.methods.is_empty_obj(data) &&
                !state.cartShops.some(
                    (productShop) => productShop.id == data.id
                )
            ) {
                data.selected = true;
                data.couponCode = null;
                data.couponDetails = {};
                data.couponDiscount = 0;
                state.cartShops.push(data);
            } else {
                let shopIds = state.cartProducts.map(
                    (cartProduct) => cartProduct.shop_id
                );
                state.cartShops = state.cartShops.filter((shop) =>
                    shopIds.includes(shop.id)
                );
            }
        },

        addToCart(state, product) {
            let isAlreadyAdded = state.cartProducts.some(
                (cartProduct) =>
                    cartProduct.variation_id === product.variation_id
            );
            if (isAlreadyAdded) {
                state.cartProducts.map((cartProduct) => {
                    if (cartProduct.variation_id === product.variation_id)
                        return (cartProduct.qty =
                            cartProduct.qty + product.qty);
                });
            } else {
                product.selected = true;
                product.max_qty =
                    product.max_qty > 0 ? product.max_qty : Infinity;
                state.cartProducts.push(product);
            }
        },

        updateQuantity(state, { type, cart_id }) {
            let item = state.cartProducts.find(
                (cartProduct) => cartProduct.cart_id === cart_id
            );
            if (type == "plus") {
                state.cartProducts.map((cartProduct) => {
                    if (cartProduct.cart_id === cart_id)
                        return (cartProduct.qty = cartProduct.qty + 1);
                });
            } else if (type == "minus" && item.qty > item.min_qty) {
                state.cartProducts.map((cartProduct) => {
                    if (cartProduct.cart_id === cart_id)
                        return (cartProduct.qty = cartProduct.qty - 1);
                });
            } else {
                let index = state.cartProducts
                    .map((cartProduct) => cartProduct.cart_id)
                    .indexOf(item.cart_id);
                if (index > -1) {
                    state.cartProducts.splice(index, 1);
                }
            }
        },
        removeFromCart(state, id) {
            let index = state.cartProducts
                .map((cartProduct) => cartProduct.cart_id)
                .indexOf(id);
            if (index > -1) {
                state.cartProducts.splice(index, 1);
            }
        },
        removeMultipleFromCart(state, cart_ids) {
            state.cartProducts = state.cartProducts.filter(
                (cartProduct) => !cart_ids.includes(cartProduct.cart_id)
            );
        },
        resetCart(state) {
            state.cartLoaded = false;
            state.cartProducts = [];
        },
        toggleCartItem(state, { cart_id, status }) {
            let cartItem;
            state.cartProducts.map((item) => {
                if (item.cart_id == cart_id) {
                    cartItem = item;
                    return (item.selected = status);
                }
            });

            if (status) {
                state.cartShops.map((shop) => {
                    if (shop.id == cartItem.shop_id)
                        return (shop.selected = status);
                });
            } else if (
                !state.cartProducts.some(
                    (cartProduct) =>
                        cartProduct.shop_id == cartItem.shop_id &&
                        cartProduct.selected
                )
            ) {
                state.cartShops.map((shop) => {
                    if (shop.id == cartItem.shop_id)
                        return (shop.selected = status);
                });
            }
        },
        toggleCartShop(state, { shop_id, status }) {
            state.cartShops.map((shop) => {
                if (shop.id == shop_id) return (shop.selected = status);
            });
            state.cartProducts.map((cartItem) => {
                if (cartItem.shop_id == shop_id && !cartItem.outOfStock)
                    return (cartItem.selected = status);
            });
        },
        saveCoupon(state, { shopId, couponCode, couponDetails }) {
            if (state.cartShops.length > 0) {
                if (shopId) {
                    state.cartShops = state.cartShops.map((shop) => {
                        if (shop.id == shopId) {
                            shop.couponCode = couponCode;
                            shop.couponDetails = couponDetails;
                        }
                        return shop;
                    });
                } else {
                    state.cartShops[0].couponCode = couponCode;
                    state.cartShops[0].couponDetails = couponDetails;
                }
            }
        },
        setShopCouponDiscount(state, { shop_id, amount }) {
            state.cartShops = state.cartShops.map((shop) => {
                if (shop.id == shop_id) {
                    shop.couponDiscount = amount;
                }
                return shop;
            });
        },
        resetCoupon(state, shop_id) {
            if (shop_id) {
                state.cartShops = state.cartShops.map((shop) => {
                    if (shop.id == shop_id) {
                        shop.couponCode = null;
                        shop.couponDetails = {};
                    }
                    return shop;
                });
            } else {
                state.cartShops = state.cartShops.map((shop) => {
                    shop.couponCode = null;
                    shop.couponDetails = {};
                    return shop;
                });
            }
        },
        setPickupPoints(state, data) {
            state.pickupPoints = data;
        },
    },
    actions: {
        async fetchCartProducts({ commit, getters }) {
            if (!getters.cartLoaded || getters.getTempUserId) {
                const res = await Mixin.methods.call_api("post", `carts`, {
                    temp_user_id: getters.getTempUserId,
                });
                if (res.data.success) {
                    commit("setCartProducts", res.data.cart_items.data);
                    commit("setIsDigital", res.data.cart_items.data);
                    commit("setCartShops", res.data.shops.data);
                }
            }
        },
        async addToCart({ commit, getters, dispatch }, { variation_id, qty }) {
            let temp_user_id = getters.getTempUserId;
            if (!this.getters["auth/isAuthenticated"] && !temp_user_id) {
                temp_user_id =
                    Math.floor(Math.random() * 10000) + new Date().getTime();
                commit("setTempUserId", temp_user_id);
            }
            const res = await Mixin.methods.call_api("post", `carts/add`, {
                variation_id: variation_id,
                qty: qty,
                temp_user_id: temp_user_id,
            });

            if (res.data.success) {
                commit("addToCart", res.data.data);
                commit("updateCartShops", res.data.shop);

                dispatch("proccessCoupon");
            }
        },
        async updateQuantity({ commit, getters, dispatch }, { type, cart_id }) {
            let cartItem = getters.findCartItemByCartId(cart_id);
            if (type == "plus" && cartItem.qty + 1 > cartItem.max_qty) {
                Mixin.methods.snack({
                    message: `"You can purchase maximum quantity" ${cartItem.max_qty}.`,
                    // message: `${i18n.t("you_can_purchase_maximum_quantity")} ${
                    //     cartItem.max_qty
                    // }.`,
                    color: "red",
                });
                return;
            }
            const res = await Mixin.methods.call_api(
                "post",
                `carts/change-quantity`,
                {
                    type: type,
                    cart_id: cart_id,
                    temp_user_id: getters.getTempUserId,
                }
            );
            if (res.data.success) {
                commit("updateQuantity", { type, cart_id });
                commit("updateCartShops");
                dispatch("proccessCoupon");
            } else {
                Mixin.methods.snack({
                    message: res.data.message,
                    color: "red",
                });
            }
        },
        async removeFromCart({ commit, getters, dispatch }, cart_id) {
            const res = await Mixin.methods.call_api("post", `carts/destroy`, {
                cart_id: cart_id,
                temp_user_id: getters.getTempUserId,
            });
            if (res.data.success) {
                commit("removeFromCart", cart_id);
                commit("updateCartShops");
                dispatch("proccessCoupon");
            }
        },
        toggleCartItem({ commit, dispatch }, { cart_id, status }) {
            commit("toggleCartItem", { cart_id, status });
            dispatch("proccessCoupon");
        },
        toggleCartShop({ commit, dispatch }, { shop_id, status }) {
            commit("toggleCartShop", { shop_id, status });
            dispatch("proccessCoupon");
        },
        resetCart({ commit, dispatch }) {
            commit("resetCart");
            commit("updateCartShops");
            dispatch("proccessCoupon");
        },
        removeMultipleFromCart({ commit, dispatch }, cart_ids) {
            commit("removeMultipleFromCart", cart_ids);
            commit("updateCartShops");
            dispatch("proccessCoupon");
        },
        saveCoupon(
            { commit, dispatch },
            { shopId, couponCode, couponDetails }
        ) {
            commit("saveCoupon", { shopId, couponCode, couponDetails });
            dispatch("proccessCoupon");
        },
        resetCoupon({ commit, dispatch }, shop_id) {
            commit("resetCoupon", shop_id);
            dispatch("proccessCoupon");
        },
        proccessCoupon({ commit, getters }) {
            getters.getCartShops.forEach((shop) => {
                if (shop.couponCode) {
                    let total = getters.getShopCartTotalPrice(shop.id);
                    let couponDiscount = 0;

                    if (shop.couponDetails.coupon_type == "product_base") {
                        let applicableProductIds =
                            shop.couponDetails.conditions.map((item) =>
                                parseFloat(item.product_id)
                            );
                        let willGetDiscount = false;

                        getters.getShopProductsById(shop.id).forEach((item) => {
                            if (
                                item.selected &&
                                applicableProductIds.includes(item.product_id)
                            ) {
                                willGetDiscount = true;
                                if (
                                    shop.couponDetails.discount_type ==
                                    "percent"
                                ) {
                                    couponDiscount +=
                                        ((item.dicounted_price *
                                            parseFloat(
                                                shop.couponDetails.discount
                                            )) /
                                            100) *
                                        item.qty;
                                } else if (
                                    shop.couponDetails.discount_type == "amount"
                                ) {
                                    couponDiscount +=
                                        item.qty *
                                        parseFloat(shop.couponDetails.discount);
                                }
                            }
                        });

                        if (!willGetDiscount) {
                            Mixin.methods.snack({
                                message:
                                    "applied coupon code is no applicable for your selected cart products",
                                color: "red",
                            });
                        }
                    } else if (shop.couponDetails.coupon_type == "cart_base") {
                        if (
                            total >=
                            parseFloat(shop.couponDetails.conditions.min_buy)
                        ) {
                            if (shop.couponDetails.discount_type == "percent") {
                                couponDiscount +=
                                    (total *
                                        parseFloat(
                                            shop.couponDetails.discount
                                        )) /
                                    100;
                                if (
                                    couponDiscount >
                                    parseFloat(
                                        shop.couponDetails.conditions
                                            .max_discount
                                    )
                                ) {
                                    couponDiscount = parseFloat(
                                        shop.couponDetails.conditions
                                            .max_discount
                                    );
                                }
                            } else if (
                                shop.couponDetails.discount_type == "amount"
                            ) {
                                couponDiscount += shop.couponDetails.discount;
                            }
                        } else {
                            Mixin.methods.snack({
                                message: `Minimum order total of ${Mixin.methods.format_price(
                                    parseFloat(
                                        shop.couponDetails.conditions.min_buy
                                    )
                                )} is required to use applied coupon code`,
                                color: "red",
                            });
                        }
                    }

                    commit("setShopCouponDiscount", {
                        amount: couponDiscount,
                        shop_id: shop.id,
                    });
                }
            });
        },

        async fetchPickupPoints({ commit }) {
            const res = await Mixin.methods.call_api(
                "get",
                `user/pickup-points`
            );
            if (res.data.success) {
                commit("setPickupPoints", res.data.pickup_points);
            }
        },
    },
};
