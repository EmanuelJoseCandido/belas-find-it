// resources/ts/admin/router/index.ts
import type { RouteRecordRaw } from "vue-router";
import AdminLayout from "../layouts/AdminLayout.vue";

export const adminRoutes: Array<RouteRecordRaw> = [
    {
        path: "/admin",
        component: AdminLayout,
        meta: { requiresAuth: true, requiresAdmin: true },
        children: [
            {
                path: "",
                name: "admin-dashboard",
                component: () => import("../pages/Dashboard.vue"),
            },/*
            {
                path: "items",
                name: "admin-items",
                component: () => import("../pages/Items/ItemsList.vue"),
            },
            {
                path: "items/create",
                name: "admin-items-create",
                component: () => import("../pages/Items/ItemForm.vue"),
            },
            {
                path: "items/:id/edit",
                name: "admin-items-edit",
                component: () => import("../pages/Items/ItemForm.vue"),
            },
            {
                path: "categories",
                name: "admin-categories",
                component: () =>
                    import("../pages/Categories/CategoriesList.vue"),
            },
            {
                path: "users",
                name: "admin-users",
                component: () => import("../pages/Users/UsersList.vue"),
            },
            {
                path: "claims",
                name: "admin-claims",
                component: () => import("../pages/Claims/ClaimsList.vue"),
            },
            {
                path: "settings",
                name: "admin-settings",
                component: () => import("../pages/Settings.vue"),
            }, */
        ],
    },
];
