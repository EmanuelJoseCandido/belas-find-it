// resources/ts/plugins/axios.ts
import axios, { AxiosResponse, AxiosError } from "axios";
import router from "@/router";
import cookie from "@/services/cookies/cookie";
const axiosInstance = window.axios;

const api = axios.create({
  baseURL: "/api",
  withCredentials: true,
  headers: {
    "X-Requested-With": "XMLHttpRequest",
    "Content-Type": "application/json",
  },
});

api.interceptors.request.use(
  (config) => {
    const token = cookie.getToken();
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

api.interceptors.response.use(
  (response: AxiosResponse) => response,
  async (error: AxiosError) => {
    const originalRequest = error.config;

    if (error?.response?.status === 401 || error?.response?.status === 419) {
      if (router.currentRoute.value.name !== "auth-login") {
        const currentPath = router.currentRoute.value.fullPath;

        router.push({
          name: "auth-login",
          query: { redirect: currentPath },
        });
      }
    }

    return Promise.reject(error.response || error);
  }
);

export { axiosInstance as axios, api };

export function setupAxios(app: any) {
  app.config.globalProperties.$api = api;
}
