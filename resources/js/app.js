import VueNumberInput from "@chenfengyuan/vue-number-input";
import { createApp } from "vue";
import VueSocialSharing from "vue-social-sharing";
import VueTelInput from "vue-tel-input";
import { VueHeadMixin, createHead } from "@unhead/vue";
import "vue-tel-input/vue-tel-input.css";
import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import "../sass/app.scss";
import "./bootstrap";

import { i18n } from "./plugins/i18n";
import init from "./plugins/init";
import plugins from "./plugins/plugins";
import router from "./router/router.js";
import store from "./store/store";
import Mixin from "./utils/mixin";

import TheShop from "./components/TheShop.vue";
import Banner from "./components/inc/Banner.vue";
import DynamicLink from "./components/inc/DynamicLink.vue";
import ProductBox from "./components/product/ProductBox.vue";
import HelperClass from "./utils/helpers";

const shopSetting = window.shopSetting;

const app = createApp(TheShop);

// color override
const customDarkTheme = {
    colors: {
        primary: shopSetting.primaryColor,
    },
};

let shopSelectedLanguage = localStorage.getItem("shopSelectedLanguage");
const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: "customDarkTheme",
        themes: {
            customDarkTheme,
        },
    },
    locale: {
        locale: shopSelectedLanguage ?? "en",
        fallback: shopSelectedLanguage ?? "en",
    },
});
const globalOptions = {
    mode: "auto",
};

app.component("dynamic-link", DynamicLink);
app.component("banner", Banner);
app.component("product-box", ProductBox);
app.component("vue-number-input", VueNumberInput);

init(store, router);
const head = createHead();
app.mixin(VueHeadMixin);
app.use(head);
app.use(vuetify);
app.use(VueSocialSharing);
app.use(VueTelInput, globalOptions);
app.use(store);
app.use(router);
app.use(i18n);
app.mixin(Mixin);
app.use(init);
app.use(plugins);
app.provide(HelperClass);
app.mount("#app");
