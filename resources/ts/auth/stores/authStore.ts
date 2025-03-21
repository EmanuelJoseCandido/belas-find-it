import { defineStore } from "pinia";
import { ref, computed } from "vue";
import { authService } from "@/services/authService";
import type {
    IUser,
    IRegisterUser,
    ILoginCredentials,
    IAuthResponse,
} from "@/auth/types/UserInterface";

export const useAuthStore = defineStore("auth", () => {
    const user = ref<IUser | null>(null);
    const token = ref<string | null>(localStorage.getItem("token") || null);
    const isAuthenticated = ref<boolean>(false);
    const loading = ref<boolean>(false);
    const error = ref<string | null>(null);

    const isUser = computed<boolean>(() => user.value?.role === "user");
    const isAdmin = computed<boolean>(() => user.value?.role === "admin");
    const isModerator = computed<boolean>(
        () => user.value?.role === "moderator"
    );

    async function register(user: IRegisterUser): Promise<IUser> {
        loading.value = true;
        error.value = null;
        try {
            const response = (await authService.register(
                user
            )) as IAuthResponse;
            token.value = response.data.data.token;
            isAuthenticated.value = true;
            localStorage.setItem("token", response.data.data.token);
            return response.data.data;
        } catch (e: any) {
            error.value = e.response?.data?.message || "Erro ao fazer login";
            throw e;
        } finally {
            loading.value = false;
        }
    }

    async function update(user: IRegisterUser): Promise<IUser> {
        loading.value = true;
        error.value = null;
        try {
            const response = (await authService.update(user)) as IAuthResponse;

            return response.data.data;
        } catch (e: any) {
            error.value =
                e.response?.data?.message || "Erro ao actualizar o perfil";
            throw e;
        } finally {
            loading.value = false;
        }
    }

    async function login(credentials: ILoginCredentials): Promise<IUser> {
        loading.value = true;
        error.value = null;
        console.log("credentials", credentials);
        try {
            const { data } = (await authService.login(
                credentials
            )) as IAuthResponse;

            user.value = data.data;
            token.value = data.data.token;
            isAuthenticated.value = true;
            localStorage.setItem("token", data.data.token);
            return data.data;
        } catch (e: any) {
            error.value = e.response?.data?.message || "Erro ao fazer login";
            throw e;
        } finally {
            loading.value = false;
        }
    }

    async function logout(): Promise<void> {
        try {
            await authService.logout();
        } catch (e) {
            console.error("Erro ao fazer logout:", e);
        }

        user.value = null;
        token.value = null;
        isAuthenticated.value = false;
        localStorage.removeItem("token");
    }

    async function fetchUser(): Promise<void> {
        if (!token.value) return;
        loading.value = true;
        try {
            const { data } = (await authService.me()) as IAuthResponse;

            user.value = data.data;
            isAuthenticated.value = true;
        } catch (e) {
            user.value = null;
            token.value = null;
            isAuthenticated.value = false;
            localStorage.removeItem("token");
        } finally {
            loading.value = false;
        }
    }

    return {
        user,
        token,
        isAuthenticated,
        loading,
        error,

        isUser,
        isAdmin,
        isModerator,

        login,
        logout,
        update,
        register,
        fetchUser,
    };
});
