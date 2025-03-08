import type { RouteRecordRaw } from "vue-router";
import Login from "../pages/Login.vue";

export const authRoutes: Array<RouteRecordRaw> = [{ path: "/auth", component: Login }];
