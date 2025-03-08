import { createRouter, createWebHistory } from "vue-router";
import { adminRoutes } from "./admin/router";
import { websiteRoutes } from "./website/router";
import { authRoutes } from "./auth/router";

const routes = [...authRoutes, ...adminRoutes, ...websiteRoutes];

export default createRouter({
    history: createWebHistory(),
    routes,
});
