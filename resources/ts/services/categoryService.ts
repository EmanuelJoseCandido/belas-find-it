import api from "./api";

export const categoryService = {
    getAll: () => api.get("/categories"),
    create: (data: any) => api.post("/categories", data),
    update: (id: number, data: any) => api.put(`/categories/${id}`, data),
    delete: (id: number) => api.delete(`/categories/${id}`),
};
