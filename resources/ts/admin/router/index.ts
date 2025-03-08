import type { RouteRecordRaw } from "vue-router";
import Dashboard from "../pages/Dashboard.vue";

export const adminRoutes:Array<RouteRecordRaw> = [{ path: "/admin", component: Dashboard }];
