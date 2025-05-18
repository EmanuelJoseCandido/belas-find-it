import api from "./api";

export const categoryService = {
  getAll: () => api.get("/categories"),
  get: (id: number) => api.get(`/categories/${id}`),
  create: (data: any) => api.post("/categories", data),
  update: (id: number, data: any) => api.put(`/categories/${id}`, data),
  delete: (id: number) => api.delete(`/categories/${id}`),
  forceDelete: (id: number) => api.delete(`/categories/force-delete/${id}`),
  restore: (id: number) => api.put(`/categories/restore/${id}`),
};
