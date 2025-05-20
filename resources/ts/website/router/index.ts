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
    path: "/perdidos/:id",
    name: "details-lost",
    component: () => import("../pages/ItemDetails.vue"),
  },
  {
    path: "/achados",
    name: "findings",
    component: () => import("../pages/Findings.vue"),
  },
  {
    path: "/achados/:id",
    name: "details-findings",
    component: () => import("../pages/ItemDetails.vue"),
  },
  {
    path: "/contacto",
    name: "contact",
    component: () => import("../pages/Contact.vue"),
  },
  {
    path: "/cadastrar-item",
    name: "register-item",
    meta: { requiresAuth: true },
    component: () => import("../pages/RegisterItem.vue"),
  },
  {
    path: "/perfil",
    name: "user-profile",
    component: () => import("../pages/UserProfile.vue"),
    meta: { requiresAuth: true },
  },
  { path: "/:pathMatch(.*)*", redirect: "/" },
];
