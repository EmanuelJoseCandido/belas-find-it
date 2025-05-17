import { createRouter, createWebHistory } from "vue-router";
import { adminRoutes } from "./admin/router";
import { websiteRoutes } from "./website/router";
import { authRoutes } from "./auth/router";
import { useAuthStore } from "@/auth/stores/authStore";
import { computed } from "vue";

const routes = [...authRoutes, ...adminRoutes, ...websiteRoutes];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore();

  const isAuthenticated = computed(
    () => !!authStore.user && authStore.user.isAuthenticated === true
  );

  console.log("");
  
  if (!authStore.user?.id && authStore.token) {
    try {
      await authStore.getMe();
    } catch (error) {
      console.error("Erro ao buscar dados do usuário:", error);
    }
  }

  if (to.meta.requiresAuth && !isAuthenticated.value) {
    return next({
      name: "auth-login",
      query: { redirect: to.fullPath },
    });
  }

  if (to.meta.requiresAdmin && !authStore.isAdmin) {
    return next({ name: "home" });
  }

  if (isAuthenticated.value && to.path.startsWith("/auth")) {
    if (authStore.isAdmin) {
      return next({ name: "admin-dashboard" });
    }

    return next({ name: "home" });
  }

  if (isAuthenticated.value && to.path === "/" && authStore.isAdmin) {
    return next({ name: "admin-dashboard" });
  }

  return next();
});

export default router;
