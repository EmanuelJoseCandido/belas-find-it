import api from "./api";

export const authService = {
    login: (credentials: any) => api.post("/auth/login", credentials),
    register: (userData: any) => api.post("/auth/register", userData),
    updateProfile: (userData: any) => api.post("/auth/updateProfile", userData),
    updatePassword: (data: any) => api.post("/auth/update-password", data),
    forgotPassword: (email: string) =>
        api.post("/auth/forgot-password", { email }),
    resetPassword: (data: any) => api.post("/auth/reset-password", data),
    logout: () => api.post("/auth/logout"),
    me: () => api.get("/auth/me"),
};
