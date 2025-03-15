import type { RouteRecordRaw } from "vue-router";

export const websiteRoutes: Array<RouteRecordRaw> = [
    { path: "/", component: () => import("../pages/Home.vue") },
    { path: "/sobre", component: () => import("../pages/About.vue") },
    { path: "/perdidos", component: () => import("../pages/Lost.vue") },
    { path: "/achados", component: () => import("../pages/Findings.vue") },
    { path: "/contacto", component: () => import("../pages/Contact.vue") },
    {
        path: "/cadastrar-item",
        component: () => import("../pages/RegisterItem.vue"),
    },
    { path: "/:pathMatch(.*)*", redirect: "/" },
];
