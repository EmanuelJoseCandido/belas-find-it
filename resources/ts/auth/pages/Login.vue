<template>
  <AuthLayout>
    <div class="mb-3">
      <h2 class="text-xl font-bold text-center">Login</h2>
      <p class="text-gray-500 text-center text-sm mt-1">
        Entre com suas credenciais
      </p>
    </div>

    {{ authStore }} -
    <Button type="submit" class="w-full" @click="authStore.logout()">
      Logout
    </Button>
    <form @submit.prevent="login">
      <div class="space-y-3">
        <InputField
          name="identifier"
          label="Email ou Telefone"
          v-model="form.identifier"
          placeholder="Digite seu email ou telefone"
          :error="errors.identifier"
        />
        <div class="relative">
          <InputField
            name="password"
            label="Senha"
            v-model="form.password"
            :type="showPassword ? 'text' : 'password'"
            placeholder="Digite sua senha"
            :error="errors.password"
          />
          <button
            type="button"
            class="absolute right-3 top-[34px] text-gray-500 hover:text-gray-700 focus:outline-none"
            @click="togglePasswordVisibility"
          >
            <EyeIcon v-if="showPassword" class="h-5 w-5" />
            <EyeOffIcon v-else class="h-5 w-5" />
          </button>
        </div>
      </div>
      <div class="flex items-center justify-between mt-6 mb-4">
        <div class="flex items-center">
          <CheckboxField id="remember" name="remember" v-model="form.remember">
            <span class="text-xs text-gray-700">Lembrar</span>
          </CheckboxField>
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
import { ref, computed } from "vue";
import AuthLayout from "@/auth/layouts/AuthLayout.vue";
import InputField from "@/auth/components/InputField.vue";
import CheckboxField from "@/auth/components/CheckboxField.vue";
import { Button } from "@/ui/components/button";
import { Eye as EyeIcon, EyeOff as EyeOffIcon } from "lucide-vue-next";
import { useAuthStore } from "@/auth/stores/authStore";
import { toast } from "@/ui/components/toast";
import { useRouter } from "vue-router";
import { h } from "vue";
import type { ILoginCredentials, TTypeLogin } from "@/auth/types/UserInterface";

const authStore = useAuthStore();
const router = useRouter();

// Estado do formulário
const form = ref({
  identifier: "",
  password: "",
  remember: false,
});

// Estado de erros
const errors = ref({
  identifier: "",
  password: "",
});

// Estado de carregamento
const isLoading = ref(false);

// Estado de visibilidade da senha
const showPassword = ref(false);

// Função para alternar a visibilidade da senha
const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
};

// Função para determinar o tipo de identificador (email ou telefone)
const identifierType = computed((): TTypeLogin => {
  const value = form.value.identifier.trim();
  // Verifica se contém apenas números e caracteres permitidos em telefone (+, -, espaços, parênteses)
  const isPhone = /^[0-9+\-() ]*$/.test(value);
  return isPhone ? "phone" : "email";
});

// Função para formatar as credenciais com base no tipo de identificador
const formatCredentials = (): ILoginCredentials => {
  const credentials: ILoginCredentials = {
    type: identifierType.value,
    password: form.value.password,
  };

  // Remove caracteres não numéricos se for telefone
  if (identifierType.value === "phone") {
    credentials.phone = Number(form.value.identifier.replace(/[^0-9]/g, ""));
  } else {
    credentials.email = form.value.identifier.trim();
  }

  return credentials;
};

// Função para validar o formulário
const validateForm = (): boolean => {
  let isValid = true;
  errors.value = {
    identifier: "",
    password: "",
  };

  // Validar identificador
  if (!form.value.identifier.trim()) {
    errors.value.identifier = "O email ou telefone é obrigatório";
    isValid = false;
  } else if (identifierType.value === "email") {
    // Validar formato de email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(form.value.identifier.trim())) {
      errors.value.identifier = "Digite um email válido";
      isValid = false;
    }
  } else if (identifierType.value === "phone") {
    // Validar formato de telefone (apenas números após remover caracteres especiais)
    const phoneDigits = form.value.identifier.replace(/[^0-9]/g, "");
    if (phoneDigits.length < 10) {
      // Assumindo telefone brasileiro com DDD
      errors.value.identifier = "Digite um número de telefone válido";
      isValid = false;
    }
  }

  // Validar senha
  if (!form.value.password) {
    errors.value.password = "A senha é obrigatória";
    isValid = false;
  } else if (form.value.password.length < 6) {
    errors.value.password = "A senha deve ter pelo menos 6 caracteres";
    isValid = false;
  }

  return isValid;
};

const login = async () => {
  if (!validateForm()) {
    return;
  }

  try {
    isLoading.value = true;
    const credentials = formatCredentials();

    // Chamar a função de login do store
    await authStore.login(credentials);

    toast({
      title: "Login realizado com sucesso!",
      description: "Você será redirecionado para o painel.",
    });

    // Redirecionamento baseado no tipo de usuário
    if (authStore.isAdmin || authStore.isModerator) {
      router.push({ name: "admin-dashboard" });
    } else {
      router.push({ name: "user-profile" });
    }
  } catch (error) {
    console.error("Erro ao fazer login", error);

    // Notificação de erro
    toast({
      title: "Falha no login",
      description:
        "Credenciais inválidas. Verifique seu email/telefone e senha.",
      variant: "destructive",
    });
  } finally {
    isLoading.value = false;
  }
};
</script>
