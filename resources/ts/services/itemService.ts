import api from "./api";

export const itemService = {
  getCountsByStatus: () => api.get("/items/getCountsByStatus"),
  getAll: (params?: any) => api.get("/items", { params }),
  getLost: (params: any = {}) =>
    api.get("/items", {
      params: {
        ...params,
        status: "perdido",
        ...(params.keyword && !params.search && { search: params.keyword }),
      },
    }),
  getFound: (params: any = {}) =>
    api.get("/items", {
      params: {
        ...params,
        status: "achado",
        ...(params.keyword && !params.search && { search: params.keyword }),
      },
    }),
  get: (id: number) => api.get(`/items/${id}`),
  create: (itemData: FormData) =>
    api.post("/items", itemData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    }),
  update: (id: number, itemData: any) => api.put(`/items/${id}`, itemData),
  delete: (id: number) => api.delete(`/items/${id}`),
  forceDelete: (id: number) => api.delete(`/items/force-delete/${id}`),
  restore: (id: number) => api.put(`/items/restore/${id}`),
  contactOwner: (data: any) => api.post("/contact-item-owner", data),
};
