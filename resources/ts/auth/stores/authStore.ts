import { defineStore } from "pinia";
import { api } from "@/plugins/axios";
import { ref, computed } from "vue";
import { authService } from "@/services/authService";
import cookie from "@/services/cookies/cookie";
import type {
  IUser,
  IRegisterUser,
  ILoginCredentials,
  IAuthResponse,
  IProfileUser,
} from "@/auth/types/UserInterface";

export const useAuthStore = defineStore("auth", () => {
  const user = ref<IUser | null>(null);
  const token = ref<string | null>(localStorage.getItem("token") || null);
  const loading = ref<boolean>(false);
  const error = ref<string | null>(null);

  const isUser = computed<boolean>(() => user.value?.role === "user");
  const isAdmin = computed<boolean>(() => user.value?.role === "admin");
  const isModerator = computed<boolean>(() => user.value?.role === "moderator");

  async function getSanctumCookie() {
    try {
      await authService.getSanctumCookie();
      return true;
    } catch (e: any) {
      error.value = e.response?.data?.message || "Erro ao pegar o csrf-cookie";
      return false;
    }
  }

  async function register(user: IRegisterUser): Promise<IUser> {
    loading.value = true;
    error.value = null;
    try {
      const { data } = (await authService.register(user)) as IAuthResponse;
      setData(data.data);

      return data.data;
    } catch (e: any) {
      error.value = e.response?.data?.message || "Erro ao fazer login";
      throw e;
    } finally {
      loading.value = false;
    }
  }

  async function updateProfile(profileData: IProfileUser): Promise<IUser> {
    loading.value = true;
    error.value = null;
    try {
      const response = (await authService.updateProfile(
        profileData
      )) as IAuthResponse;
      user.value = { ...user.value, ...response.data.data };
      return response.data.data;
    } catch (e: any) {
      error.value = e.response?.data?.message || "Erro ao atualizar o perfil";
      throw e;
    } finally {
      loading.value = false;
    }
  }

  async function updatePassword(passwordData: {
    currentPassword: string;
    password: string;
    password_confirmation: string;
  }): Promise<void> {
    loading.value = true;
    error.value = null;
    try {
      await authService.updatePassword(passwordData);
    } catch (e: any) {
      error.value = e.response?.data?.message || "Erro ao atualizar a senha";
      throw e;
    } finally {
      loading.value = false;
    }
  }

  async function login(credentials: ILoginCredentials): Promise<IUser> {
    loading.value = true;
    error.value = null;
    try {
      const csrfSuccess = await getSanctumCookie();

      if (!csrfSuccess) {
        throw new Error("Falha ao obter CSRF token");
      }
      const { data } = (await authService.login(credentials)) as IAuthResponse;

      setData(data.data);
      return data.data;
    } catch (e: any) {
      error.value = e.response?.data?.message || "Erro ao fazer login";
      throw e;
    } finally {
      loading.value = false;
    }
  }

  async function logout() {
    try {
      await api.post("auth/logout");
    } catch (error) {
    } finally {
      restoreMe();
    }
  }

  async function getMe() {
    try {
      const token = cookie.getToken();

      if (!token) {
        restoreMe();
        return false;
      }

      api.defaults.headers.common["Authorization"] = `Bearer ${token}`;
      const { data } = await api.get("auth/me");

      setData(data.data);
      return true;
    } catch (error) {
      console.error("Erro ao verificar autenticação:", error);
      restoreMe();
      return false;
    }
  }

  const restoreMe = () => {
    user.value = {} as IUser;
    user.value.isAuthenticated = false;
    delete api.defaults.headers.common["Authorization"];
    cookie.deleteToken();
  };

  const setData = (data: IUser) => {
    if (data.token) {
      user.value = data;
      user.value.isAuthenticated = true;
      cookie.setToken(data.token);
      api.defaults.headers.common["Authorization"] = `Bearer ${data.token}`;
    } else {
      restoreMe();
    }
  };

  return {
    user,
    token,
    loading,
    error,

    isUser,
    isAdmin,
    isModerator,

    login,
    logout,
    getMe,
    updateProfile,
    updatePassword,
    register,
  };
});
