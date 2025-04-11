let DashBoard = () => import("../pages/user/Dashboard.vue");
let DatabaseNotification = () =>
    import("../components/notification/DatabaseNotification.vue");
let PurchaseHistory = () => import("../pages/user/PurchaseHistory.vue");
let Downloads = () => import("../pages/user/Downloads.vue");
let Affiliate = () => import("../pages/user/affiliate/Affiliate.vue");
let AffiliatePaymentHistory = () =>
    import("../pages/user/affiliate/AffiliatePaymentHistory.vue");
let AffiliatePaymentWithdraw = () =>
    import("../pages/user/affiliate/AffiliatePaymentWithdraw.vue");
let AffiliateRegistration = () =>
    import("../pages/user/affiliate/AffiliateRegistration.vue");
let AffiliateRegConfirmation = () =>
    import("../pages/user/affiliate/AffiliateRegConfirmation.vue");
let OrderDetails = () => import("../pages/user/OrderDetails.vue");
let QuoteDetails = () => import("../pages/user/QuoteDetails.vue");
let RefundRequests = () =>
    import("../pages/user/refund_request/RefundRequestList.vue");
let RefundRequestCreate = () =>
    import("../pages/user/refund_request/RefundRequestCreate.vue");
let Wishlist = () => import("../pages/user/Wishlist.vue");
let Conversations = () => import("../pages/user/Conversations.vue");
let ConversationDetails = () => import("../pages/user/ConversationDetails.vue");
let FollowedShops = () => import("../pages/user/FollowedShops.vue");
let Wallet = () => import("../pages/user/Wallet.vue");
let EarningPoints = () => import("../pages/user/EarningPoints.vue");
let Coupon = () => import("../pages/user/Coupon.vue");
let Profile = () => import("../pages/user/Profile.vue");
let UserLayout = () => import("../components/user/UserLayout.vue");
let Error404 = () => import("../pages/errors/404.vue");
let Usertab = () => import("../pages/user/Usertab.vue");

export default [
    {
        path: "/user/affiliate-registration",
        component: AffiliateRegistration,
        name: "AffiliateRegistration",
        meta: { requiresAuth: false },
    },
    {
        path: "/user/affiliate-registration-confirmation",
        component: AffiliateRegConfirmation,
        name: "AffiliateRegConfirmation",
        meta: { requiresAuth: false },
    },
    {
        path: "/user/",
        component: UserLayout,
        redirect: "/user/dashboard",
        children: [
            {
                path: "dashboard",
                component: DashBoard,
                name: "DashBoard",
                meta: { requiresAuth: true },
            },
            {
                path: "notification",
                component: DatabaseNotification,
                name: "Notification",
                meta: { requiresAuth: true },
            },
            {
                path: "purchase-history",
                component: PurchaseHistory,
                name: "PurchaseHistory",
                meta: { requiresAuth: true },
            },
            {
                path: "offer-price",
                component: Usertab,
                name: "OfferPrice",
                meta: { requiresAuth: true },
            },
            {
                path: "downloads",
                component: Downloads,
                name: "Downloads",
                meta: { requiresAuth: true },
            },
            {
                path: "affiliate",
                component: Affiliate,
                name: "Affiliate",
                meta: { requiresAuth: true },
            },
            {
                path: "affiliate-payment-history",
                component: AffiliatePaymentHistory,
                name: "AffiliatePaymentHistory",
                meta: { requiresAuth: true },
            },
            {
                path: "affiliate-payment-withdraw",
                component: AffiliatePaymentWithdraw,
                name: "AffiliatePaymentWithdraw",
                meta: { requiresAuth: true },
            },

            {
                path: "purchase-history/:code",
                component: OrderDetails,
                name: "OrderDetails",
                meta: { requiresAuth: true },
            },
            {
                path: "quote-history/:code",
                component: QuoteDetails,
                name: "QuoteDetails",
                meta: { requiresAuth: true },
            },
            {
                path: "refund-requests",
                component: RefundRequests,
                name: "RefundRequests",
                meta: { requiresAuth: true },
            },
            {
                path: "create-refund-request/:orderId",
                component: RefundRequestCreate,
                name: "CreateRefundRequest",
                meta: { requiresAuth: true },
            },
            {
                path: "wishlist",
                component: Wishlist,
                name: "Wishlist",
                meta: { requiresAuth: true },
            },
            {
                path: "followed-shops",
                component: FollowedShops,
                name: "FollowedShops",
                meta: { requiresAuth: true },
            },

            {
                path: "product-query",
                component:
                    window.shopSetting.general_settings.conversation_system == 1
                        ? Conversations
                        : Error404,
                name: "Conversations",
                meta: { requiresAuth: true },
            },
            {
                path: "product-query/:slug",
                component:
                    window.shopSetting.general_settings.conversation_system == 1
                        ? ConversationDetails
                        : Error404,
                name: "ConversationsDetails",
                meta: { requiresAuth: true },
            },
            {
                path: "wallet",
                component:
                    window.shopSetting.general_settings.wallet_system == 1
                        ? Wallet
                        : Error404,
                name: "Wallet",
                meta: { requiresAuth: true },
            },

            {
                path: "earning-points",
                component:
                    window.shopSetting.general_settings.club_point == 1
                        ? EarningPoints
                        : Error404,
                name: "Earning",
                meta: { requiresAuth: true },
            },

            {
                path: "coupon",
                component: Coupon,
                name: "Coupon",
                meta: { requiresAuth: true },
            },
            {
                path: "profile",
                component: Profile,
                name: "Profile",
                meta: { requiresAuth: true },
            },
        ],
    },
];
