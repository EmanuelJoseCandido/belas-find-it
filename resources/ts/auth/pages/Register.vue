<template>
  <AuthLayout>
    <div class="mb-3">
      <h2 class="text-xl font-bold text-center">Registro</h2>
      <p class="text-gray-500 text-center text-sm mt-1">
        Crie sua conta para começar
      </p>
    </div>

    <Form
      @submit="onSubmit"
      :validation-schema="registerSchema"
      v-slot="{
        errors,
        isSubmitting: formSubmitting,
        resetForm: resetFormContext,
      }"
    >
      <div class="grid grid-cols-2 gap-3">
        <Field name="name" v-slot="{ field, errorMessage }">
          <div class="col-span-2">
            <Label :for="field.name">Nome completo</Label>
            <Input
              :id="field.name"
              v-bind="field"
              placeholder="Digite seu nome"
              :class="{ 'border-red-500': errorMessage }"
            />
            <p v-if="errorMessage" class="mt-1 text-xs text-red-600">
              {{ errorMessage }}
            </p>
          </div>
        </Field>

        <Field name="email" v-slot="{ field, errorMessage }">
          <div class="col-span-2">
            <Label :for="field.name">Email</Label>
            <Input
              :id="field.name"
              type="email"
              v-bind="field"
              placeholder="seu@email.com"
              :class="{ 'border-red-500': errorMessage }"
            />
            <p v-if="errorMessage" class="mt-1 text-xs text-red-600">
              {{ errorMessage }}
            </p>
          </div>
        </Field>

        <Field name="phone" v-slot="{ field, errorMessage }">
          <div class="col-span-2 md:col-span-1">
            <Label :for="field.name">Telefone</Label>
            <Input
              :id="field.name"
              v-bind="field"
              placeholder="9xx xxx xxx"
              :class="{ 'border-red-500': errorMessage }"
            />
            <p v-if="errorMessage" class="mt-1 text-xs text-red-600">
              {{ errorMessage }}
            </p>
          </div>
        </Field>

        <Field
          name="gender"
          v-slot="{ field, errorMessage, value, handleChange }"
        >
          <div class="col-span-2 md:col-span-1">
            <Label :for="field.name">Gênero</Label>
            <Select
              :id="field.name"
              :value="value"
              @update:modelValue="handleChange"
            >
              <SelectTrigger :class="{ 'border-red-500': errorMessage }">
                <SelectValue placeholder="Selecione" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem
                  v-for="option in genderOptions"
                  :key="option.value"
                  :value="option.value"
                >
                  {{ option.label }}
                </SelectItem>
              </SelectContent>
            </Select>
            <p v-if="errorMessage" class="mt-1 text-xs text-red-600">
              {{ errorMessage }}
            </p>
          </div>
        </Field>

        <Field name="password" v-slot="{ field, errorMessage }">
          <div class="col-span-1">
            <Label :for="field.name">Senha</Label>
            <Input
              :id="field.name"
              type="password"
              v-bind="field"
              placeholder="Senha"
              :class="{ 'border-red-500': errorMessage }"
            />
            <p v-if="errorMessage" class="mt-1 text-xs text-red-600">
              {{ errorMessage }}
            </p>
          </div>
        </Field>

        <Field name="password_confirmation" v-slot="{ field, errorMessage }">
          <div class="col-span-1">
            <Label :for="field.name">Confirme a senha</Label>
            <Input
              :id="field.name"
              type="password"
              v-bind="field"
              placeholder="Confirme"
              :class="{ 'border-red-500': errorMessage }"
            />
            <p v-if="errorMessage" class="mt-1 text-xs text-red-600">
              {{ errorMessage }}
            </p>
          </div>
        </Field>
      </div>

      <Button
        type="submit"
        class="w-full mt-6"
        :disabled="
          isLoading || formSubmitting || Object.keys(errors).length > 0
        "
      >
        <span v-if="isLoading">Carregando...</span>
        <span v-else>Criar conta</span>
      </Button>
    </Form>

    <p class="text-xs text-center mt-3">
      Já tem uma conta?
      <router-link
        :to="{ name: 'auth-login' }"
        class="text-primary hover:underline font-medium"
      >
        Faça login
      </router-link>
    </p>
  </AuthLayout>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { useRouter } from "vue-router";
import { z } from "zod";
import { Field, Form } from "vee-validate";
import { toFormValidator } from "@vee-validate/zod";
import type { TGenders } from "@/auth/types/UserInterface";
import AuthLayout from "@/auth/layouts/AuthLayout.vue";
import { useAuthStore } from "@/auth/stores/authStore";
import { Label } from "@/ui/components/label";
import { Input } from "@/ui/components/input";
import { Button } from "@/ui/components/button";
import { Checkbox } from "@/ui/components/checkbox";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/ui/components/select";
import { toast } from "@/ui/components/toast";
import { h } from "vue";

const router = useRouter();
const authStore = useAuthStore();

const registerSchema = toFormValidator(
  z
    .object({
      name: z
        .string({ required_error: "Campo obrigatório" })
        .min(3, "O nome deve ter pelo menos 3 caracteres"),
      email: z
        .string({ required_error: "Campo obrigatório" })
        .email("Digite um e-mail válido"),
      phone: z
        .string({ required_error: "Campo obrigatório" })
        .regex(/^[0-9+\-() ]*$/, "Formato de telefone inválido")
        .min(9, "O telefone deve ter pelo menos 9 dígitos"),
      gender: z
        .enum(["masculino", "feminino", "outro"], {
          required_error: "Campo obrigatório",
        })
        .superRefine((val, ctx) => {
          if (!val) {
            ctx.addIssue({
              code: z.ZodIssueCode.custom,
              message: "Selecione um gênero",
            });
          }
        }),
      password: z
        .string({ required_error: "Campo obrigatório" })
        .min(8, "A senha deve ter pelo menos 8 caracteres")
        .regex(
          /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)/,
          "A senha deve conter pelo menos uma letra maiúscula, uma minúscula e um número"
        ),
      password_confirmation: z.string({ required_error: "Campo obrigatório" }),
    })
    .refine((data) => data.password === data.password_confirmation, {
      message: "As senhas não coincidem",
      path: ["password_confirmation"],
    })
);

const genderOptions = [
  { value: "masculino", label: "Masculino" },
  { value: "feminino", label: "Feminino" },
  { value: "outro", label: "Outro" },
];

const isLoading = ref(false);

const onSubmit = async (values: any, { resetForm }: any) => {
  try {
    isLoading.value = true;

    await authStore.register({
      name: values.name,
      email: values.email,
      phone: values.phone,
      gender: values.gender as TGenders,
      password: values.password,
      password_confirmation: values.password_confirmation,
      isAuthenticated: false,
    });

    toast({
      title: "Conta criada com sucesso!",
      description: h(
        "p",
        { class: "text-sm" },
        "Você será redirecionado para seu perfil."
      ),
    });

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
