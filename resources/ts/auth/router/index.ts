import type { RouteRecordRaw } from "vue-router";

export const authRoutes: Array<RouteRecordRaw> = [
    {
        path: "/auth",
        name: 'auth',
        redirect: { name: "auth-login" },
        children: [
            {
                path: "register",
                name: "auth-register",
                component: () => import("../pages/Register.vue"),
            },
            {
                path: "login",
                name: "auth-login",
                component: () => import("../pages/Login.vue"),
            },
        ],
    },
];
