<template>
  <AuthLayout>
    <div class="mb-3">
      <h2 class="text-xl font-bold text-center">Login</h2>
      <p class="text-gray-500 text-center text-sm mt-1">
        Entre com suas credenciais
      </p>
    </div>

    <Form
      @submit="login"
      :validation-schema="loginSchema"
      v-slot="{ errors: formErrors, isSubmitting: formSubmitting }"
    >
      <div class="space-y-3">
        <div class="flex gap-4">
          <Button type="button" @click="authStore.logout()">Logout</Button>
          <Button type="button" @click="userService.getAll()">GetAll</Button>
        </div>

        <Field name="identifier" v-slot="{ field, errorMessage }">
          <div>
            <Label :for="field.name">Email ou Telefone</Label>
            <Input
              v-bind="field"
              :id="field.name"
              placeholder="Digite seu email ou telefone"
              :class="{ 'border-red-500': errorMessage }"
            />
            <p v-if="errorMessage" class="mt-1 text-xs text-red-600">
              {{ errorMessage }}
            </p>
          </div>
        </Field>

        <Field name="password" v-slot="{ field, errorMessage }">
          <div class="relative">
            <Label :for="field.name">Senha</Label>
            <div class="relative">
              <Input
                v-bind="field"
                :id="field.name"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Digite sua senha"
                :class="{ 'border-red-500': errorMessage }"
              />
              <button
                type="button"
                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                @click="togglePasswordVisibility"
              >
                <EyeIcon v-if="showPassword" class="h-5 w-5" />
                <EyeOffIcon v-else class="h-5 w-5" />
              </button>
            </div>
            <p v-if="errorMessage" class="mt-1 text-xs text-red-600">
              {{ errorMessage }}
            </p>
          </div>
        </Field>
      </div>

      <div class="flex items-center justify-between mt-6 mb-4">
        <div class="flex items-center">
          <CheckboxField id="remember" name="remember" v-model="rememberMe">
            <span class="text-xs text-gray-700">Lembrar</span>
          </CheckboxField>
        </div>
        <a
          href="#"
          class="text-xs font-medium text-primary hover:text-primary/80 hover:underline"
        >
          Esqueceu a senha?
        </a>
      </div>

      <Button
        type="submit"
        class="w-full"
        :disabled="isLoading || formSubmitting"
      >
        <span v-if="isLoading || formSubmitting">Carregando...</span>
        <span v-else>Entrar</span>
      </Button>
    </Form>

    <div class="text-center mt-4">
      <p class="text-xs text-gray-600">
        Não tem conta?
        <router-link
          :to="{ name: 'auth-register' }"
          class="text-primary font-medium hover:text-primary/80"
        >
          Registre-se agora
        </router-link>
      </p>
    </div>
  </AuthLayout>
</template>

<script setup lang="ts">
import { ref, computed } from "vue";
import { z } from "zod";
import { Field, Form } from "vee-validate";
import { toFormValidator } from "@vee-validate/zod";
import AuthLayout from "@/auth/layouts/AuthLayout.vue";
import { Label } from "@/ui/components/label";
import { Input } from "@/ui/components/input";
import CheckboxField from "@/auth/components/CheckboxField.vue";
import { Button } from "@/ui/components/button";
import { Eye as EyeIcon, EyeOff as EyeOffIcon } from "lucide-vue-next";
import { useAuthStore } from "@/auth/stores/authStore";
import { toast } from "@/ui/components/toast";
import { useRouter } from "vue-router";
import type { ILoginCredentials, TTypeLogin } from "@/auth/types/UserInterface";
import { userService } from "@/services/userService";

const authStore = useAuthStore();
const router = useRouter();

// Schema de validação
const loginSchema = toFormValidator(
  z.object({
    identifier: z
      .string({ required_error: "Campo obrigatório" })
      .min(1, "Campo obrigatório"),
    password: z
      .string({ required_error: "Campo obrigatório" })
      .min(1, "Campo obrigatório"),
  })
);

// Estado para lembrar login
const rememberMe = ref(false);

// Estado de carregamento
const isLoading = ref(false);

// Estado de visibilidade da senha
const showPassword = ref(false);

// Função para alternar a visibilidade da senha
const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value;
};

// Função para determinar o tipo de identificador (email ou telefone)
const identifierType = (identifier: string): TTypeLogin => {
  // Verifica se contém apenas números e caracteres permitidos em telefone (+, -, espaços, parênteses)
  const isPhone = /^[0-9+\-() ]*$/.test(identifier.trim());
  return isPhone ? "phone" : "email";
};

// Função para formatar as credenciais com base no tipo de identificador
const formatCredentials = (values: {
  identifier: string;
  password: string;
}): ILoginCredentials => {
  const credentials: ILoginCredentials = {
    type: identifierType(values.identifier),
    password: values.password,
    remember: false,
  };

  // Remove caracteres não numéricos se for telefone
  if (credentials.type === "phone") {
    credentials.phone = Number(values.identifier.replace(/[^0-9]/g, ""));
  } else {
    credentials.email = values.identifier.trim();
  }

  return credentials;
};

const login = async (values: any) => {
  try {
    isLoading.value = true;
    const credentials = formatCredentials(values);
    credentials.remember = rememberMe.value;

    // Validações adicionais que o Zod não cobre
    if (credentials.type === "email") {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(values.identifier.trim())) {
        toast({
          title: "Formato inválido",
          description: "Digite um email válido",
          variant: "destructive",
        });
        return;
      }
    } else if (credentials.type === "phone") {
      const phoneDigits = values.identifier.replace(/[^0-9]/g, "");
      if (phoneDigits.length < 9) {
        toast({
          title: "Formato inválido",
          description: "Digite um número de telefone válido",
          variant: "destructive",
        });
        return;
      }
    }

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
