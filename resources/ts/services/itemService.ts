import api from "./api";

export const itemService = {
    getAllItems: (params: any) => api.get("/items", { params }),
    getLostItems: (params: any) =>
        api.get("/items", { params: { ...params, status: "perdido" } }),
    getFoundItems: (params: any) =>
        api.get("/items", { params: { ...params, status: "achado" } }),
    getItemById: (id: number) => api.get(`/items/${id}`),
    createItem: (itemData: any) => {
        const formData = new FormData();
        Object.entries(itemData).forEach(([key, value]) => {
            if (value !== null && value !== undefined) {
                formData.append(key, value);
            }
        });
        return api.post("/items", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
    },
    updateItem: (id: number, itemData: any) =>
        api.put(`/items/${id}`, itemData),
    deleteItem: (id: number) => api.delete(`/items/${id}`),
    contactOwner: (data: any) => api.post("/contact-item-owner", data),
};
