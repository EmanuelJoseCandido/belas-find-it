<template>
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-primary text-white">
            <div class="p-4">
                <div class="flex items-center">
                    <KeyIcon class="w-6 h-6 mr-2" />
                    <h1 class="text-xl font-bold">EncontraJá Admin</h1>
                </div>
            </div>

            <nav class="mt-8">
                <ul class="space-y-2">
                    <li>
                        <RouterLink
                            to="/admin"
                            class="flex items-center px-4 py-2 hover:bg-primary-foreground/10"
                            :class="{
                                'bg-primary-foreground/10': isActive('/admin'),
                            }"
                        >
                            <DashboardIcon class="w-5 h-5 mr-3" />
                            Dashboard
                        </RouterLink>
                    </li>
                    <li>
                        <RouterLink
                            to="/admin/items"
                            class="flex items-center px-4 py-2 hover:bg-primary-foreground/10"
                            :class="{
                                'bg-primary-foreground/10':
                                    isActive('/admin/items'),
                            }"
                        >
                            <PackageIcon class="w-5 h-5 mr-3" />
                            Itens
                        </RouterLink>
                    </li>
                    <li>
                        <RouterLink
                            to="/admin/categories"
                            class="flex items-center px-4 py-2 hover:bg-primary-foreground/10"
                            :class="{
                                'bg-primary-foreground/10':
                                    isActive('/admin/categories'),
                            }"
                        >
                            <FolderIcon class="w-5 h-5 mr-3" />
                            Categorias
                        </RouterLink>
                    </li>
                    <li>
                        <RouterLink
                            to="/admin/users"
                            class="flex items-center px-4 py-2 hover:bg-primary-foreground/10"
                            :class="{
                                'bg-primary-foreground/10':
                                    isActive('/admin/users'),
                            }"
                        >
                            <UsersIcon class="w-5 h-5 mr-3" />
                            Usuários
                        </RouterLink>
                    </li>
                    <li>
                        <RouterLink
                            to="/admin/claims"
                            class="flex items-center px-4 py-2 hover:bg-primary-foreground/10"
                            :class="{
                                'bg-primary-foreground/10':
                                    isActive('/admin/claims'),
                            }"
                        >
                            <ClipboardIcon class="w-5 h-5 mr-3" />
                            Reclamações
                        </RouterLink>
                    </li>
                    <li>
                        <RouterLink
                            to="/admin/settings"
                            class="flex items-center px-4 py-2 hover:bg-primary-foreground/10"
                            :class="{
                                'bg-primary-foreground/10':
                                    isActive('/admin/settings'),
                            }"
                        >
                            <SettingsIcon class="w-5 h-5 mr-3" />
                            Configurações
                        </RouterLink>
                    </li>
                </ul>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
            <!-- Header -->
            <header
                class="bg-background border-b p-4 flex justify-between items-center"
            >
                <h2 class="text-xl font-semibold">{{ pageTitle }}</h2>

                <div class="flex items-center">
                    <Button variant="ghost" @click="logout">
                        <LogOutIcon class="w-5 h-5 mr-2" />
                        Sair
                    </Button>
                </div>
            </header>

            <!-- Content -->
            <main class="p-6">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import { Button } from "@/ui/components/button";
import { useAuthStore } from "@/auth/stores/authStore";
import {
    Key as KeyIcon,
    LayoutDashboard as DashboardIcon,
    Package as PackageIcon,
    Folder as FolderIcon,
    Users as UsersIcon,
    Clipboard as ClipboardIcon,
    Settings as SettingsIcon,
    LogOut as LogOutIcon,
} from "lucide-vue-next";

const route = useRoute();
const router = useRouter();
const authStore = useAuthStore();

// Define page title based on current route
const pageTitle = computed(() => {
    const path = route.path;
    if (path === "/admin") return "Dashboard";
    if (path.includes("/admin/items")) return "Gerenciar Itens";
    if (path.includes("/admin/categories")) return "Gerenciar Categorias";
    if (path.includes("/admin/users")) return "Gerenciar Usuários";
    if (path.includes("/admin/claims")) return "Gerenciar Reivindicações";
    if (path.includes("/admin/settings")) return "Configurações";
    return "";
});

const isActive = (path: string) => {
    return route.path.startsWith(path);
};

const logout = async () => {
    await authStore.logout();
    router.push("/auth/login");
};

// Check if user is admin, if not redirect to home
onMounted(async () => {
    await authStore.fetchUser();
    if (!authStore.isAdmin && !authStore.isModerator) {
        router.push("/");
    }
});
</script>
