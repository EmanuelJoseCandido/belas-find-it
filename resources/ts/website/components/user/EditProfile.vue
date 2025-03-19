<template>
    <h2 class="text-2xl font-bold mb-6">Editar Perfil</h2>

    <Card>
        <CardContent class="p-6">
            <form @submit.prevent="updateProfile" class="space-y-6">
                <!-- Foto de perfil -->
                <div
                    class="flex flex-col items-center sm:flex-row sm:items-start gap-6 pb-6 border-b"
                >
                    <div>
                        <Avatar class="h-24 w-24">
                            <AvatarFallback>{{ userInitials }}</AvatarFallback>
                        </Avatar>
                    </div>
                    <div class="space-y-2 text-center sm:text-left">
                        <h3 class="font-medium">{{ user.name }}</h3>
                        <p class="text-sm text-muted-foreground max-w-md">
                            Uma foto ajuda a personalizar sua conta e torna mais
                            fácil para outros usuários te reconhecerem.
                        </p>
                    </div>
                </div>

                <!-- Informações pessoais -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <Label for="name">Nome Completo</Label>
                        <Input id="name" v-model="input.name" required />
                    </div>

                    <div class="space-y-2">
                        <Label for="email">Email</Label>
                        <Input
                            id="email"
                            type="email"
                            v-model="input.email"
                            required
                            disabled
                        />
                        <p class="text-xs text-muted-foreground">
                            O email não pode ser alterado.
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label for="phone">Telefone</Label>
                        <Input id="phone" v-model="input.phone" required />
                    </div>

                    <div class="space-y-2">
                        <Label for="gender">Gênero</Label>
                        <Select v-model="input.gender">
                            <SelectTrigger>
                                <SelectValue placeholder="Selecione" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="masculino"
                                    >Masculino</SelectItem
                                >
                                <SelectItem value="feminino"
                                    >Feminino</SelectItem
                                >
                                <SelectItem value="outro">Outro</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </div>

                <!-- Alteração de senha -->
                <div class="pt-6 border-t">
                    <h3 class="font-medium mb-4">Alterar Senha</h3>

                    <div class="space-y-4">
                        <div class="space-y-2">
                            <Label for="current-password">Senha Atual</Label>
                            <Input
                                id="current-password"
                                type="password"
                                v-model="passwordForm.currentPassword"
                            />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <Label for="new-password">Nova Senha</Label>
                                <Input
                                    id="new-password"
                                    type="password"
                                    v-model="passwordForm.newPassword"
                                />
                            </div>

                            <div class="space-y-2">
                                <Label for="confirm-password"
                                    >Confirmar Senha</Label
                                >
                                <Input
                                    id="confirm-password"
                                    type="password"
                                    v-model="passwordForm.confirmPassword"
                                />
                            </div>
                        </div>

                        <p class="text-xs text-muted-foreground">
                            A senha deve ter pelo menos 8 caracteres e incluir
                            pelo menos uma letra maiúscula, uma letra minúscula
                            e um número.
                        </p>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <Button
                        variant="outline"
                        type="button"
                        @click="resetProfileForm"
                        >Cancelar</Button
                    >
                    <Button type="submit" :disabled="isUpdating">
                        <Loader2
                            v-if="isUpdating"
                            class="h-4 w-4 mr-2 animate-spin"
                        />
                        Salvar Alterações
                    </Button>
                </div>
            </form>
        </CardContent>
    </Card>
</template>

<script setup lang="ts">
import { IUser, IProfileUser } from "@/auth/types/UserInterface";
import { toast } from "@/ui/components/toast";
import { ref, computed, onMounted } from "vue";
import { Card, CardContent } from "@/ui/components/card";
import { Avatar, AvatarFallback } from "@/ui/components/avatar";
import { Button } from "@/ui/components/button";
import { Input } from "@/ui/components/input";
import { Label } from "@/ui/components/label";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/ui/components/select";

import { useAuthStore } from "@/auth/stores/authStore";

const  {} = useAuthStore();
const isUpdating = ref(false);
const input = ref<IProfileUser>({} as IProfileUser);

const passwordForm = ref({
    currentPassword: "",
    newPassword: "",
    confirmPassword: "",
});

const props = defineProps<IUser>();

const user = computed(() => {
    return props;
});

const updateProfile = async () => {
    try {
        isUpdating.value = true;

        // Validação de senha
        if (
            passwordForm.value.newPassword ||
            passwordForm.value.confirmPassword ||
            passwordForm.value.currentPassword
        ) {
            if (
                passwordForm.value.newPassword !==
                passwordForm.value.confirmPassword
            ) {
                toast({
                    title: "Erro",
                    description: "As senhas não coincidem",
                    variant: "destructive",
                });
                return;
            }

            if (!passwordForm.value.currentPassword) {
                toast({
                    title: "Erro",
                    description: "Por favor, informe sua senha atual",
                    variant: "destructive",
                });
                return;
            }

            if (passwordForm.value.newPassword.length < 8) {
                toast({
                    title: "Erro",
                    description:
                        "A nova senha deve ter pelo menos 8 caracteres",
                    variant: "destructive",
                });
                return;
            }
        }

        // Simulando uma requisição
        await new Promise((resolve) => setTimeout(resolve, 1500));

        // Atualiza o usuário no store
        if (user.value) {
            user.value.name = input.value.name;
            user.value.phone = input.value.phone;
        }

        toast({
            title: "Perfil atualizado",
            description: "Suas informações foram atualizadas com sucesso",
        });

        // Limpar formulário de senha
        passwordForm.value = {
            currentPassword: "",
            newPassword: "",
            confirmPassword: "",
        };
    } catch (error) {
        console.error("Erro ao atualizar perfil:", error);
        toast({
            title: "Erro",
            description: "Não foi possível atualizar o perfil",
            variant: "destructive",
        });
    } finally {
        isUpdating.value = false;
    }
};

const resetProfileForm = () => {
    // Restaura os valores originais
    if (user.value) {
        input.value = {
            name: user.value.name,
            email: user.value.email,
            phone: user.value.phone || "",
            gender: user.value.gender || "",
        };
    }

    passwordForm.value = {
        currentPassword: "",
        newPassword: "",
        confirmPassword: "",
    };
};

const userInitials = computed(() => {
    if (!user.value?.name) return "U";
    return user.value.name
        .split(" ")
        .map((part) => part.charAt(0))
        .join("")
        .toUpperCase()
        .substring(0, 2);
});

onMounted(() => {
    // Carrega dados do usuário para o formulário
    console.log("Usuário:", user.value);
    if (user.value) {
        input.value = {
            name: user.value.name,
            email: user.value.email,
            phone: user.value.phone || "",
            gender: user.value.gender || "",
        };
    }
});
</script>

<style scoped></style>
