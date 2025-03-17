// resources/ts/services/authService.ts
import api from "./api";

export const authService = {
    login: (credentials) => api.post("/auth/login", credentials),
    register: (userData) => api.post("/auth/register", userData),
    forgotPassword: (email) => api.post("/auth/forgot-password", { email }),
    resetPassword: (data) => api.post("/auth/reset-password", data),
    logout: () => api.post("/auth/logout"),
    me: () => api.get("/auth/me"),
};

// resources/ts/services/itemService.ts


// resources/ts/services/categoryService.ts
