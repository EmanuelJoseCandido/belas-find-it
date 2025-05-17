import { api } from "@/plugins/axios";

export const contactService = {
  submit: (data: any) => api.post("/contacts", data),
  getAll: (params: any = {}) => api.get("/contacts", { params }),
  get: (id: number) => api.get(`/contacts/${id}`),
  update: (id: number, data: any) => api.put(`/contacts/${id}`, data),
  delete: (id: number) => api.delete(`/contacts/${id}`),
  restore: (id: number) => api.put(`/contacts/${id}/restore`),
  forceDelete: (id: number) => api.delete(`/contacts/${id}/force-delete`),
  updateStatus: (id: number, status: string) =>
    api.patch(`/contacts/${id}/status`, { status }),
};
