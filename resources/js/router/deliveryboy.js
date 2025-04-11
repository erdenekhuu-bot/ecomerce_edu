let DeliveryBoyDashboard = () =>
    import("../pages/deliveryboy/DeliveryBoyDashboard.vue");
let AssignedDeliveries = () =>
    import("../pages/deliveryboy/AssignedDelivery.vue");
let CancelDeliveries = () => import("../pages/deliveryboy/CancelDelivery.vue");
let CompletedDeliveries = () =>
    import("../pages/deliveryboy/CompletedDelivery.vue");
let PendingDeliveries = () =>
    import("../pages/deliveryboy/PendingDelivery.vue");
let PickedUpDeliveries = () =>
    import("../pages/deliveryboy/PickedUpDelivery.vue");
let OnTheWayDeliveries = () =>
    import("../pages/deliveryboy/OnTheWayDelivery.vue");
let CollectionsList = () => import("../pages/deliveryboy/CollectionsList.vue");
let EarningsList = () => import("../pages/deliveryboy/EarningsList.vue");

let Profile = () => import("../pages/user/Profile.vue");
let OrderDetails = () => import("../pages/user/OrderDetails.vue");

let DeliveryBoyLayout = () =>
    import("../components/deliveryboy/DeliveryBoyLayout.vue");
let Error404 = () => import("../pages/errors/404.vue");

export default [
    {
        path: "/delivery-boy/",
        component: DeliveryBoyLayout,
        redirect: "/delivery-boy/dashboard",
        children: [
            {
                path: "dashboard",
                component: DeliveryBoyDashboard,
                name: "DeliveryBoyDashboard",
                meta: { requiresAuth: true },
            },
            {
                path: "assigned-deliveries",
                component: AssignedDeliveries,
                name: "AssignedDeliveries",
                meta: { requiresAuth: true },
            },
            {
                path: "cancelled-deliveries",
                component: CancelDeliveries,
                name: "CancelDeliveries",
                meta: { requiresAuth: true },
            },
            {
                path: "completed-deliveries",
                component: CompletedDeliveries,
                name: "CompletedDeliveries",
                meta: { requiresAuth: true },
            },
            {
                path: "pending-deliveries",
                component: PendingDeliveries,
                name: "PendingDeliveries",
                meta: { requiresAuth: true },
            },
            {
                path: "picked-up-deliveries",
                component: PickedUpDeliveries,
                name: "PickedUpDeliveries",
                meta: { requiresAuth: true },
            },
            {
                path: "on-the-way-deliveries",
                component: OnTheWayDeliveries,
                name: "OnTheWayDeliveries",
                meta: { requiresAuth: true },
            },
            {
                path: "earnings-list",
                component: EarningsList,
                name: "EarningsList",
                meta: { requiresAuth: true },
            },
            {
                path: "collections-list",
                component: CollectionsList,
                name: "CollectionsList",
                meta: { requiresAuth: true },
            },
            {
                path: "purchase-history/:code",
                component: OrderDetails,
                name: "DeliveryboyOrderDetails",
                meta: { requiresAuth: true },
            },
            {
                path: "profile",
                component: Profile,
                name: "delivery-boy-profile",
                meta: { requiresAuth: true },
            },
        ],
    },
];
