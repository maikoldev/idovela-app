let Home = () => import("../pages/Home");
let ProductDetails = () => import("../pages/ProductDetails");
let ServiceDetails = () => import("../pages/ServiceDetails");
let BrandDetails = () => import("../pages/BrandDetails");
let ProductListing = () => import("../pages/ProductListing");
let TrackOrder = () => import("../pages/TrackOrder");
let AllCategories = () => import("../pages/AllCategories");
let AllBrands = () => import("../pages/AllBrands");
let AllOffers = () => import("../pages/AllOffers");
let OfferDetails = () => import("../pages/OfferDetails");
let AllBlogs = () => import("../pages/AllBlogs");
let BlogDetails = () => import("../pages/BlogDetails");
let TempLanding = () => import("../pages/TempLanding");
let TestComponents = () => import("../pages/TestComponents");
let Home2 = () => import("../pages/Home2");
let Shop = () => import("../pages/Shop");
let About = () => import("../pages/About");
let Collection = () => import("../pages/Collection");

let ShopSpaces = () => import("../pages/shop/ShopSpaces");
let ShopServices = () => import("../pages/shop/ShopServices");
let ShopHomeAppliances = () => import("../pages/shop/ShopHomeAppliances");
let ShopBusinessPortal = () => import("../pages/shop/ShopBusinessPortal");

export default [
    {
        path: "/",
        component: TempLanding,
        name: "TempLanding",
        meta: { requiresAuth: false, hasHeader: false, hasFooter: false }
    },
    {
        path: "/test-components",
        component: TestComponents,
        name: "TestComponents",
        meta: { requiresAuth: false }
    },
    {
        path: "/about",
        component: About,
        name: "About",
        meta: { requiresAuth: false }
    },
    {
        path: "/about/brand/:slug?",
        component: About,
        name: "AboutBrand",
        meta: { requiresAuth: false }
    },
    {
        path: "/brands",
        component: AllBrands,
        name: "Brands",
        meta: { requiresAuth: false }
    },
    {
        path: "/shop",
        component: Shop,
        name: "Shop",
        redirect: "/shop/spaces",
        meta: { requiresAuth: false },
        children: [
            {
                path: "spaces",
                component: ShopSpaces,
                name: "ShopSpaces",
                meta: { requiresAuth: false, hasBottomBar: false }
            },
            {
                path: "services",
                component: ShopServices,
                name: "ShopServices",
                meta: { requiresAuth: false, hasBottomBar: false }
            },
            {
                path: "home-appliances",
                component: ShopHomeAppliances,
                name: "ShopHomeAppliances",
                meta: { requiresAuth: false, hasBottomBar: false }
            },
            {
                path: "business-portal",
                component: ShopBusinessPortal,
                name: "ShopBusinessPortal",
                meta: { requiresAuth: false, hasBottomBar: false }
            }
        ]
    },
    {
        path: "/home",
        component: Home,
        name: "Home",
        meta: { requiresAuth: false }
    },
    {
        path: "/home2",
        component: Home2,
        name: "Home2",
        meta: { requiresAuth: false }
    },
    {
        path: "/all-categories",
        component: AllCategories,
        name: "AllCategories",
        meta: { requiresAuth: false }
    },
    {
        path: "/all-brands",
        component: AllBrands,
        name: "AllBrands",
        meta: { requiresAuth: false }
    },
    {
        path: "/all-offers",
        component: AllOffers,
        name: "AllOffers",
        meta: { requiresAuth: false }
    },
    {
        path: "/offer/:offerSlug?",
        component: OfferDetails,
        name: "OfferDetails",
        meta: { requiresAuth: false }
    },
    // {
    //     path: "/shop",
    //     component: ProductListing,
    //     name: "Shop",
    //     meta: { requiresAuth: false }
    // },
    {
        path: "/category/:categorySlug?",
        component: ProductListing,
        name: "Category",
        meta: { requiresAuth: false }
    },
    {
        path: "/brand/:brandId?",
        component: ProductListing,
        name: "Brand",
        meta: { requiresAuth: false }
    },
    {
        path: "/category2/:categorySlug?",
        component: ProductListing,
        name: "Category2",
        meta: { requiresAuth: false }
    },
    {
        path: "/search/:keyword?",
        component: ProductListing,
        name: "Search",
        meta: { requiresAuth: false, shareble: true }
    },
    {
        path: "/product/:slug",
        component: ProductDetails,
        name: "ProductDetails",
        meta: { requiresAuth: false, shareble: true }
    },
    {
        path: "/service/:slug",
        component: ServiceDetails,
        name: "ServiceDetails",
        meta: { requiresAuth: false }
    },
    {
        path: "/brand/:slug?",
        component: BrandDetails,
        name: "BrandDetails",
        meta: { requiresAuth: false }
    },
    {
        path: "/track-order",
        component: TrackOrder,
        name: "TrackOrder",
        meta: { requiresAuth: true }
    },
    {
        path: "/all-blogs",
        component: AllBlogs,
        name: "AllBlogs",
        meta: { requiresAuth: false }
    },
    {
        path: "/all-blogs/category/:categorySlug?",
        component: AllBlogs,
        name: "AllBlogsFilter",
        meta: { requiresAuth: false }
    },
    {
        path: "/all-blogs/search/:searchKeyword?",
        component: AllBlogs,
        name: "SearchBlogs",
        meta: { requiresAuth: false }
    },
    {
        path: "/blog-details/:slug",
        component: BlogDetails,
        name: "BlogDetails",
        meta: { requiresAuth: false }
    },
    {
        path: "/collection/:slug",
        component: Collection,
        name: "Collection",
        meta: { requiresAuth: false }
    },
    {
        path: "/plan/:slug",
        component: Collection,
        name: "PlanDetails",
        meta: { requiresAuth: false }
    }
];
