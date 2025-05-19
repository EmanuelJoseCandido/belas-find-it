import api from "./api";

export const userService = {
  getAll: () => api.get("/users"),
  get: (id: number) => api.get(`/users/${id}`),
  create: (data: any) => api.post("/users", data),
  update: (id: number, data: any) => api.put(`/users/${id}`, data),
  delete: (id: number) => api.delete(`/users/${id}`),
  forceDelete: (id: number) => api.delete(`/users/force-delete/${id}`),
  restore: (id: number) => api.put(`/users/restore/${id}`),
  block: (id: number) => api.put(`/users/block/${id}`),
  unblock: (id: number) => api.put(`/users/unblock/${id}`),
};
