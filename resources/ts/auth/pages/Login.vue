<template>
    <AuthLayout>
        <div class="mb-3">
            <h2 class="text-xl font-bold text-center">Login</h2>
            <p class="text-gray-500 text-center text-sm mt-1">
                Entre com suas credenciais
            </p>
        </div>

        <form @submit.prevent="login">
            <div class="space-y-3">
                <InputField
                    name="identifier"
                    label="Email ou Telefone"
                    v-model="form.identifier"
                    placeholder="Digite seu email ou telefone"
                    :error="errors.identifier"
                />
                <InputField
                    name="password"
                    label="Senha"
                    v-model="form.password"
                    type="password"
                    placeholder="Digite sua senha"
                    :error="errors.password"
                />
            </div>

            <div class="flex items-center justify-between mt-6 mb-4">
                <div class="flex items-center">
                    <input
                        id="remember"
                        type="checkbox"
                        class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                    />
                    <label
                        for="remember"
                        class="ml-2 block text-xs text-gray-700"
                        >Lembrar</label
                    >
                </div>
                <a
                    href="#"
                    class="text-xs font-medium text-primary-600 hover:text-primary-500 hover:underline"
                >
                    Esqueceu a senha?
                </a>
            </div>

            <Button type="submit" class="w-full" :disabled="isLoading">
                <span v-if="isLoading">Carregando...</span>
                <span v-else>Entrar</span>
            </Button>
        </form>

        <div class="text-center mt-4">
            <p class="text-xs text-gray-600">
                Não tem conta?
                <router-link
                    :to="{ name: 'auth-register' }"
                    class="text-primary-600 font-medium hover:text-primary-500"
                >
                    Registre-se agora
                </router-link>
            </p>
        </div>
    </AuthLayout>
</template>

<script setup lang="ts">
import { ref } from "vue";
import AuthLayout from "@/auth/layouts/AuthLayout.vue";
import InputField from "@/auth/components/InputField.vue";
import { Button } from "@/ui/components/button";

const form = ref({
    identifier: "",
    password: "",
});

const errors = ref({
    identifier: "",
    password: "",
});

const isLoading = ref(false);

const login = async () => {
    // Limpar erros anteriores
    errors.value = {
        identifier: "",
        password: "",
    };

    // Validação básica
    if (!form.value.identifier) {
        errors.value.identifier = "O email ou telefone é obrigatório";
    }

    if (!form.value.password) {
        errors.value.password = "A senha é obrigatória";
    }

    // Se não houver erros, continuar com o login
    if (!errors.value.identifier && !errors.value.password) {
        try {
            isLoading.value = true;
            // Simulando uma chamada de API
            await new Promise((resolve) => setTimeout(resolve, 1000));
            console.log("Fazendo login...", form.value);
            // Redirecionar após login bem-sucedido
        } catch (error) {
            console.error("Erro ao fazer login", error);
        } finally {
            isLoading.value = false;
        }
    }
};
</script>
