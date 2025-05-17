<template>
  <AuthLayout>
    <div class="mb-3">
      <h2 class="text-xl font-bold text-center">Registro</h2>
      <p class="text-gray-500 text-center text-sm mt-1">
        Crie sua conta para começar
      </p>
    </div>

    <form @submit.prevent="onSubmit">
      <div class="grid grid-cols-2 gap-3">
        <InputField
          name="name"
          label="Nome completo"
          v-model="form.name"
          placeholder="Digite seu nome"
          class="col-span-2"
        />
        <InputField
          name="email"
          label="Email"
          v-model="form.email"
          type="email"
          placeholder="seu@email.com"
          class="col-span-2"
        />
        <InputField
          name="phone"
          label="Telefone"
          v-model="form.phone"
          type="tel"
          placeholder="9xx xxx xxx"
          class="col-span-2 md:col-span-1"
        />
        <SelectField
          name="gender"
          label="Gênero"
          v-model="form.gender"
          :options="genderOptions"
          placeholder="Selecione"
          class="col-span-2 md:col-span-1"
        />
        <InputField
          name="password"
          label="Senha"
          v-model="form.password"
          type="password"
          placeholder="Senha"
        />
        <InputField
          name="password_confirmation"
          label="Confirme a senha"
          v-model="form.password_confirmation"
          type="password"
          placeholder="Confirme"
        />
      </div>

      <div class="my-3">
        <CheckboxField id="terms" name="terms" v-model="form.terms">
          <span class="text-xs text-gray-700">
            Eu concordo com os
            <a href="#" class="text-primary-600 hover:text-primary-500"
              >Termos</a
            >
            e
            <a href="#" class="text-primary-600 hover:text-primary-500"
              >Política de Privacidade</a
            >
          </span>
        </CheckboxField>
      </div>

      <Button type="submit" class="w-full" :disabled="isLoading">
        <span v-if="isLoading">Carregando...</span>
        <span v-else>Criar conta</span>
      </Button>
    </form>

    <p class="text-xs text-center mt-3">
      Já tem uma conta?
      <router-link
        :to="{ name: 'auth-login' }"
        class="text-primary-600 font-medium hover:text-primary-500"
      >
        Faça login
      </router-link>
    </p>
  </AuthLayout>
</template>

<script setup lang="ts">
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import type { IRegisterUser, TGenders } from "@/auth/types/UserInterface";
import AuthLayout from "@/auth/layouts/AuthLayout.vue";
import { useAuthStore } from "@/auth/stores/authStore";
import InputField from "@/auth/components/InputField.vue";
import SelectField from "@/auth/components/SelectField.vue";
import { Button } from "@/ui/components/button";
import CheckboxField from "@/auth/components/CheckboxField.vue";
import { toast } from "@/ui/components/toast";
import { h } from "vue";

const router = useRouter();
const authStore = useAuthStore();

const genderOptions = [
  { value: "masculino", label: "Masculino" },
  { value: "feminino", label: "Feminino" },
  { value: "outro", label: "Outro" },
];

const form = reactive<IRegisterUser>({
  name: "",
  email: "",
  phone: "",
  gender: "" as TGenders,
  password: "",
  password_confirmation: "",
  terms: false,
  isAuthenticated: false,
});

const isLoading = ref(false);

const onSubmit = async () => {
  try {
    isLoading.value = true;

    await authStore.register(form);

    toast({
      title: "Conta criada com sucesso!",
      description: h(
        "p",
        { class: "text-sm" },
        "Você será redirecionado para seu perfil."
      ),
    });

    // Redirecionamento baseado no tipo de usuário
    if (authStore.isAdmin || authStore.isModerator) {
      router.push({ name: "admin-dashboard" });
    } else {
      router.push({ name: "user-profile" });
    }
  } catch (error) {
    console.error("Erro ao registrar", error);
    toast({
      title: "Erro ao criar conta",
      description: h(
        "p",
        { class: "text-sm" },
        "Ocorreu um erro ao processar seu registro. Tente novamente."
      ),
      variant: "destructive",
    });
  } finally {
    isLoading.value = false;
  }
};
</script>
