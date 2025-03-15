<template>
    <footer class="border-t bg-background px-20">
        <div class="container py-8">
            <div class="grid gap-8 md:grid-cols-3">
                <div>
                    <div class="flex items-center">
                        <KeyIcon class="w-6 h-6 mr-2 text-primary" />
                        <span class="font-bold text-xl">EncontraJá</span>
                    </div>
                    <p class="mt-2 text-muted-foreground">
                        Conectando pessoas e objetos perdidos em nosso
                        município.
                    </p>
                </div>

                <div class="space-y-2">
                    <h3 class="font-semibold">Links Rápidos</h3>
                    <div class="grid grid-cols-2 gap-2">
                        <RouterLink
                            to="/"
                            class="text-sm text-muted-foreground hover:text-primary"
                            >Início</RouterLink
                        >
                        <RouterLink
                            to="/perdidos"
                            class="text-sm text-muted-foreground hover:text-primary"
                            >Perdidos</RouterLink
                        >
                        <RouterLink
                            to="/achados"
                            class="text-sm text-muted-foreground hover:text-primary"
                            >Achados</RouterLink
                        >
                        <RouterLink
                            to="/cadastrar"
                            class="text-sm text-muted-foreground hover:text-primary"
                            >Cadastrar Item</RouterLink
                        >
                        <RouterLink
                            to="/sobre"
                            class="text-sm text-muted-foreground hover:text-primary"
                            >Sobre Nós</RouterLink
                        >
                        <RouterLink
                            to="/contato"
                            class="text-sm text-muted-foreground hover:text-primary"
                            >Contato</RouterLink
                        >
                    </div>
                </div>

                <div class="space-y-2">
                    <h3 class="font-semibold">Contato</h3>
                    <p class="text-sm text-muted-foreground">
                        contato@encontraja.co.ao
                    </p>
                    <p class="text-sm text-muted-foreground">+244 939 722 301</p>
                    <div class="flex items-center gap-4 mt-4">
                        <Button
                            variant="outline"
                            size="icon"
                            class="rounded-full"
                        >
                            <FacebookIcon class="h-4 w-4" />
                        </Button>
                        <Button
                            variant="outline"
                            size="icon"
                            class="rounded-full"
                        >
                            <InstagramIcon class="h-4 w-4" />
                        </Button>
                        <Button
                            variant="outline"
                            size="icon"
                            class="rounded-full"
                        >
                            <TwitterIcon class="h-4 w-4" />
                        </Button>
                    </div>
                </div>
            </div>

            <div
                class="border-t mt-8 pt-4 text-center text-sm text-muted-foreground"
            >
                © {{ currentYear }} EncontraJá. Todos os direitos
                reservados.
            </div>
        </div>
    </footer>
</template>

<script setup lang="ts">
import { RouterLink } from "vue-router";
import { Button } from "@/ui/components/button";
import {
    KeyIcon,
    FacebookIcon,
    InstagramIcon,
    TwitterIcon,
} from "lucide-vue-next";
import { computed } from "vue";

const currentYear = computed(() => new Date().getFullYear());
</script>
