<template>
    <header
        class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60 px-20"
    >
        <div class="container flex h-16 items-center">
            <RouterLink :to="{ name: 'home' }" class="flex items-center">
                <KeyIcon class="w-6 h-6 mr-2 text-primary" />
                <span class="font-bold text-xl">EncontraJá</span>
            </RouterLink>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex ml-auto gap-6">
                <RouterLink
                    :to="{ name: 'home' }"
                    class="text-sm font-medium transition-colors hover:text-primary"
                    >Início
                </RouterLink>
                <RouterLink
                    :to="{ name: 'lost' }"
                    class="text-sm font-medium transition-colors hover:text-primary"
                    >Perdidos</RouterLink
                >
                <RouterLink
                    :to="{ name: 'findings' }"
                    class="text-sm font-medium transition-colors hover:text-primary"
                >
                    Achados
                </RouterLink>
                <RouterLink
                    :to="{ name: 'register-item' }"
                    class="text-sm font-medium transition-colors hover:text-primary"
                >
                    Cadastrar Item
                </RouterLink>
                <RouterLink
                    :to="{ name: 'about' }"
                    class="text-sm font-medium transition-colors hover:text-primary"
                >
                    Sobre
                </RouterLink>
                <RouterLink
                    :to="{ name: 'contact' }"
                    class="text-sm font-medium transition-colors hover:text-primary"
                >
                    Contacto
                </RouterLink>
            </nav>

            <!-- Authentication Buttons -->
            <div class="ml-4 hidden md:flex items-center gap-4">
                <RouterLink :to="{ name: 'auth-login' }">
                    <Button variant="outline" size="sm" class="flex">
                        <UserIcon class="mr-2 h-4 w-4" />
                        Entrar
                    </Button>
                </RouterLink>
                <RouterLink :to="{ name: 'auth-register' }">
                    <Button size="sm" class="flex">Cadastrar-se</Button>
                </RouterLink>
            </div>

            <!-- Mobile Menu Button -->
            <div class="ml-auto md:hidden flex items-center">
                <Button
                    variant="ghost"
                    size="sm"
                    @click="isOpen = !isOpen"
                    class="text-base"
                    aria-label="Menu"
                >
                    <Menu v-if="!isOpen" class="h-6 w-6" />
                    <X v-else class="h-6 w-6" />
                </Button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div v-show="isOpen" class="md:hidden">
            <div class="container py-3 space-y-1">
                <RouterLink
                    v-for="item in navItems"
                    :key="item.path"
                    :to="item.path"
                    class="block py-2 px-3 rounded-md hover:bg-accent"
                    @click="isOpen = false"
                >
                    {{ item.label }}
                </RouterLink>

                <div class="pt-2 mt-2 border-t border-muted">
                    <RouterLink
                        :to="{ name: 'auth-login' }"
                        class="block py-2 px-3 rounded-md hover:bg-accent"
                        @click="isOpen = false"
                    >
                        Entrar
                    </RouterLink>
                    <RouterLink
                        :to="{ name: 'auth-register' }"
                        class="block py-2 px-3 rounded-md hover:bg-accent"
                        @click="isOpen = false"
                    >
                        Cadastrar-se
                    </RouterLink>
                </div>
            </div>
        </div>
    </header>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { RouterLink } from "vue-router";
import { Button } from "@/ui/components/button";
import { KeyIcon, UserIcon, Menu, X } from "lucide-vue-next";

const isOpen = ref(false);

const navItems = [
    { label: "Início", path: "/" },
    { label: "Perdidos", path: "/perdidos" },
    { label: "Achados", path: "/achados" },
    { label: "Cadastrar Item", path: "/cadastrar" },
    { label: "Sobre", path: "/sobre" },
    { label: "Contato", path: "/contato" },
];
</script>
