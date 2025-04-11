let Home = () => import("../pages/Home.vue");
let ProductDetails = () => import("../pages/ProductDetails.vue");
let ProductListing = () => import("../pages/ProductListing.vue");
let TrackOrder = () => import("../pages/TrackOrder.vue");
let AllCategories = () => import("../pages/AllCategories.vue");
let AllBrands = () => import("../pages/AllBrands.vue");
let AllOffers = () => import("../pages/AllOffers.vue");
let OfferDetails = () => import("../pages/OfferDetails.vue");
let AllBlogs = () => import("../pages/AllBlogs.vue");
let BlogDetails = () => import("../pages/BlogDetails.vue");

export default [
    {
        path: "/",
        component: Home,
        name: "Home",
        meta: { requiresAuth: false },
    },
    {
        path: "/all-categories",
        component: AllCategories,
        name: "AllCategories",
        meta: { requiresAuth: false },
    },
    {
        path: "/all-brands",
        component: AllBrands,
        name: "AllBrands",
        meta: { requiresAuth: false },
    },
    {
        path: "/all-offers",
        component: AllOffers,
        name: "AllOffers",
        meta: { requiresAuth: false },
    },
    {
        path: "/offer/:offerSlug?",
        component: OfferDetails,
        name: "OfferDetails",
        meta: { requiresAuth: false },
    },
    {
        path: "/search",
        component: ProductListing,
        name: "Shop",
        meta: { requiresAuth: false },
    },
    {
        path: "/category/:categorySlug?",
        component: ProductListing,
        name: "Category",
        meta: { requiresAuth: false },
    },
    {
        path: "/brand/:brandId?",
        component: ProductListing,
        name: "Brand",
        meta: { requiresAuth: false },
    },
    {
        path: "/search/:keyword?",
        component: ProductListing,
        name: "Search",
        meta: { requiresAuth: false },
    },
    {
        path: "/product/:slug",
        component: ProductDetails,
        name: "ProductDetails",
        meta: { requiresAuth: false },
    },
    {
        path: "/track-order",
        component: TrackOrder,
        name: "TrackOrder",
        meta: { requiresAuth: true },
    },
    {
        path: "/all-blogs",
        component: AllBlogs,
        name: "AllBlogs",
        meta: { requiresAuth: false },
    },
    {
        path: "/all-blogs/category/:categorySlug?",
        component: AllBlogs,
        name: "AllBlogsFilter",
        meta: { requiresAuth: false },
    },
    {
        path: "/all-blogs/search/:searchKeyword?",
        component: AllBlogs,
        name: "SearchBlogs",
        meta: { requiresAuth: false },
    },
    {
        path: "/blog-details/:slug",
        component: BlogDetails,
        name: "BlogDetails",
        meta: { requiresAuth: false },
    },
];
