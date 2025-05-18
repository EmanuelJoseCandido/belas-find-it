import api from "./api";

export const itemService = {
  getAll: (params: any) => api.get("/items", { params }),
  getLost: (params: any) =>
    api.get("/items", { params: { ...params, status: "perdido" } }),
  getFound: (params: any) =>
    api.get("/items", { params: { ...params, status: "achado" } }),
  get: (id: number) => api.get(`/items/${id}`),
  create: (itemData: any) => {
    const formData = new FormData();
    Object.entries(itemData).forEach(([key, value]) => {
      if (value !== null && value !== undefined) {
        formData.append(key, value as string | Blob);
      }
    });
    return api.post("/items", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    });
  },
  update: (id: number, itemData: any) => api.put(`/items/${id}`, itemData),
  delete: (id: number) => api.delete(`/items/${id}`),
  contactOwner: (data: any) => api.post("/contact-item-owner", data),
};
