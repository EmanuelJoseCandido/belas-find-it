import { createRouter, createWebHistory } from "vue-router";
import { adminRoutes } from "./admin/router";
import { websiteRoutes } from "./website/router";
import { authRoutes } from "./auth/router";
import { useAuthStore } from "@/auth/stores/authStore";

const routes = [...authRoutes, ...adminRoutes, ...websiteRoutes];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Navigation guards
router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();

    console.log("authStore", authStore);

    console.log("to", to);
    // Try to fetch user data if we don't have it but have a token
    if (!authStore.user && authStore.token) {
        await authStore.fetchUser();
    }

    // Check for protected routes
    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next({ name: "auth-login", query: { redirect: to.fullPath } });
    }
    // Check for admin routes
    else if (to.meta.requiresAdmin && !authStore.isAdmin) {
        next({ name: "home" });
    }
    // Already logged in but trying to access auth pages
    else if (authStore.isAuthenticated && to.path.startsWith("/auth")) {
        next({ name: "home" });
    } else {
        next();
    }
});

export default router;
