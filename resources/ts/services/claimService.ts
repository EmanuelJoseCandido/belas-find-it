import api from "./api";

export const claimService = {
  getAll: () => api.get("/claims"),
  get: (id: number) => api.get(`/claims/${id}`),
  create: (data: any) => api.post("/claims", data),
  update: (id: number, data: any) => api.put(`/claims/${id}`, data),
  delete: (id: number) => api.delete(`/claims/${id}`),
  forceDelete: (id: number) => api.delete(`/claims/force-delete/${id}`),
  restore: (id: number) => api.put(`/claims/restore/${id}`),
};
