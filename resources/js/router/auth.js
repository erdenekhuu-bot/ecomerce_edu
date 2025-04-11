let DeliveryBoyLogin = () => import("../pages/auth/DeliveryBoyLogin.vue");
let Login = () => import("../pages/auth/Login.vue");
let Registration = () => import("../pages/auth/Registration.vue");
let ForgotPassword = () => import("../pages/auth/ForgotPassword.vue");
let NewPassword = () => import("../pages/auth/NewPassword.vue");
let VerifyAccount = () => import("../pages/auth/VerifyAccount.vue");

export default [
    {
        path: "/delivery-boy/login",
        component: DeliveryBoyLogin,
        name: "DeliveryBoyLogin",
        meta: { requiresAuth: false },
    },
    {
        path: "/user/login",
        component: Login,
        name: "Login",
        meta: { requiresAuth: false },
    },
    {
        path: "/user/registration",
        component: Registration,
        name: "Registration",
        meta: { requiresAuth: false },
    },
    {
        path: "/user/forgot-password",
        component: ForgotPassword,
        name: "ForgotPassword",
        meta: { requiresAuth: false },
    },
    {
        path: "/user/new-password",
        component: NewPassword,
        name: "NewPassword",
        meta: { requiresAuth: false },
    },
    {
        path: "/user/verify-account",
        component: VerifyAccount,
        name: "VerifyAccount",
        meta: { requiresAuth: false },
    },
];
