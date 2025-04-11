import { createRouter, createWebHistory } from "vue-router";
import { loadLanguageAsync } from "../plugins/i18n";
import store from "./../store/store";
import Mixin from "./../utils/mixin";
import AuthRoutes from "./auth";
import CheckoutRoutes from "./checkout";
import DeliveryBoyRoutes from "./deliveryboy";
import HomeRoutes from "./Home";
import PageRoutes from "./page";

import ShopRoutes from "./shop";
import UserRoutes from "./user";

let routes = [
    ...HomeRoutes,
    ...CheckoutRoutes,
    ...AuthRoutes,
    ...UserRoutes,
    ...DeliveryBoyRoutes,
];

if (Mixin.methods.is_addon_activated("multi_vendor")) {
    routes = [...routes, ...ShopRoutes];
}

//404 routes in PageRoutes
routes = [...routes, ...PageRoutes];

const router = createRouter({
    // mode: "history",
    history: createWebHistory(),
    base: "/",
    scrollBehavior(to, from, savedPosition) {
        return { top: 0 }
        // return element.scrollTo(x=0, y=0);
        // return { x: 0, y: 0 };
    },
    routes: routes,
});

router.beforeEach((to, from, next) => {
    if (from.name == "ConversationsDetails") {
        clearInterval(window.intervalCall);
    }
    store.commit("app/setRouterLoading", true);
    if (to.query.social_login == "failed") {
        store.commit("auth/setSociaLoginStatus", "failed");
    } else if (to.query.access_token) {
        store.commit("auth/setAccessToken", to.query.access_token);
        store.commit("auth/setSociaLoginStatus", "success");
    }

    const locale = store.getters["app/userLanguage"];
    const allLocales = store.getters["app/allLanguages"];
    if (!allLocales.find((lang) => lang.code == locale)) {
        store.dispatch("app/removeLanguage");
    }

    const requiresAuth = to.matched.some((record) => record.meta.requiresAuth);
    const isAuthenticated = store.getters["auth/isAuthenticated"];

    loadLanguageAsync(locale).then(function () {
        if (requiresAuth && !isAuthenticated) {
            router
                .push({
                    name: "Login",
                    query: { redirect: to.fullPath },
                })
                .catch((e) => {
                    if (from.name == "Login") {
                        store.commit("auth/updateCartDrawer", false);
                        store.commit("auth/updateMobileSideMenu", false);
                    }
                });
        } else if (
            (to.name == "Login" ||
                to.name == "Registration" ||
                to.name == "ForgotPassword") &&
            isAuthenticated
        ) {
            // router.push({ name: "DeliveryBoyDashboard" });
            router.push({ name: "DashBoard" });
        } else {
            next();
        }
    });
});
router.afterEach((to, from) => {
    store.commit("app/setRouterLoading", false);
});
export default router;
