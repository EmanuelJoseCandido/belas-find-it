import type { RouteRecordRaw } from "vue-router";

export const websiteRoutes: Array<RouteRecordRaw> = [
    { path: "/", name: "home", component: () => import("../pages/Home.vue") },
    {
        path: "/sobre",
        name: "about",
        component: () => import("../pages/About.vue"),
    },
    {
        path: "/perdidos",
        name: "lost",
        component: () => import("../pages/Lost.vue"),
    },
    {
        path: "/achados",
        name: "findings",
        component: () => import("../pages/Findings.vue"),
    },
    {
        path: "/contacto",
        name: "contact",
        component: () => import("../pages/Contact.vue"),
    },
    {
        path: "/cadastrar-item",
        name: "register-item",
        component: () => import("../pages/RegisterItem.vue"),
    },
    { path: "/:pathMatch(.*)*", redirect: "/" },
];
