<template>
  <h2 class="text-2xl font-bold mb-6">Editar Perfil</h2>

  <Card>
    <CardContent class="p-6">
      <Form
        @submit="updateProfile"
        :validation-schema="profileSchema"
        :initial-values="initialValues"
        v-slot="{ errors: formErrors, isSubmitting: formSubmitting }"
        class="space-y-6"
      >
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
              Uma foto ajuda a personalizar sua conta e torna mais fácil para
              outros usuários te reconhecerem.
            </p>
          </div>
        </div>

        <!-- Informações pessoais -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <Field name="name" v-slot="{ field, errorMessage }">
            <div class="space-y-2">
              <Label for="name">Nome Completo</Label>
              <Input
                id="name"
                v-bind="field"
                :class="{ 'border-red-500': errorMessage }"
                required
              />
              <p v-if="errorMessage" class="text-xs text-red-600">
                {{ errorMessage }}
              </p>
            </div>
          </Field>

          <Field name="email" v-slot="{ field, errorMessage }">
            <div class="space-y-2">
              <Label for="email">Email</Label>
              <Input
                id="email"
                type="email"
                v-bind="field"
                disabled
                :class="{ 'border-red-500': errorMessage }"
                required
              />
              <p class="text-xs text-muted-foreground">
                O email não pode ser alterado.
              </p>
              <p v-if="errorMessage" class="text-xs text-red-600">
                {{ errorMessage }}
              </p>
            </div>
          </Field>

          <Field name="phone" v-slot="{ field, errorMessage }">
            <div class="space-y-2">
              <Label for="phone">Telefone</Label>
              <Input
                id="phone"
                v-bind="field"
                :class="{ 'border-red-500': errorMessage }"
                required
              />
              <p v-if="errorMessage" class="text-xs text-red-600">
                {{ errorMessage }}
              </p>
            </div>
          </Field>

          <Field
            name="gender"
            v-slot="{ field, errorMessage, value, handleChange }"
          >
            <div class="space-y-2">
              <Label for="gender">Gênero</Label>
              <Select :value="value" @update:modelValue="handleChange">
                <SelectTrigger :class="{ 'border-red-500': errorMessage }">
                  <SelectValue placeholder="Selecione" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="masculino">Masculino</SelectItem>
                  <SelectItem value="feminino">Feminino</SelectItem>
                  <SelectItem value="outro">Outro</SelectItem>
                </SelectContent>
              </Select>
              <p v-if="errorMessage" class="text-xs text-red-600">
                {{ errorMessage }}
              </p>
            </div>
          </Field>
        </div>

        <!-- Alteração de senha -->
        <div class="pt-6 border-t">
          <h3 class="font-medium mb-4">Alterar Senha</h3>

          <div class="space-y-4">
            <Field name="currentPassword" v-slot="{ field, errorMessage }">
              <div class="space-y-2">
                <Label for="currentPassword">Senha Atual</Label>
                <Input
                  id="currentPassword"
                  type="password"
                  v-bind="field"
                  :class="{ 'border-red-500': errorMessage }"
                />
                <p v-if="errorMessage" class="text-xs text-red-600">
                  {{ errorMessage }}
                </p>
              </div>
            </Field>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <Field name="newPassword" v-slot="{ field, errorMessage }">
                <div class="space-y-2">
                  <Label for="newPassword">Nova Senha</Label>
                  <Input
                    id="newPassword"
                    type="password"
                    v-bind="field"
                    :class="{ 'border-red-500': errorMessage }"
                  />
                  <p v-if="errorMessage" class="text-xs text-red-600">
                    {{ errorMessage }}
                  </p>
                </div>
              </Field>

              <Field
                name="passwordConfirmation"
                v-slot="{ field, errorMessage }"
              >
                <div class="space-y-2">
                  <Label for="passwordConfirmation">Confirmar Senha</Label>
                  <Input
                    id="passwordConfirmation"
                    type="password"
                    v-bind="field"
                    :class="{ 'border-red-500': errorMessage }"
                  />
                  <p v-if="errorMessage" class="text-xs text-red-600">
                    {{ errorMessage }}
                  </p>
                </div>
              </Field>
            </div>

            <p class="text-xs text-muted-foreground">
              A senha deve ter pelo menos 8 caracteres e incluir pelo menos uma
              letra maiúscula, uma letra minúscula e um número.
            </p>
          </div>
        </div>

        <div class="flex justify-end gap-3 pt-4">
          <Button variant="outline" type="button" @click="resetForm"
            >Cancelar</Button
          >
          <Button
            type="submit"
            :disabled="
              isUpdating || formSubmitting || Object.keys(formErrors).length > 0
            "
          >
            <Loader2
              v-if="isUpdating || formSubmitting"
              class="h-4 w-4 mr-2 animate-spin"
            />
            Salvar Alterações
          </Button>
        </div>
      </Form>
    </CardContent>
  </Card>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { z } from "zod";
import { Field, Form, useForm } from "vee-validate";
import { toFormValidator } from "@vee-validate/zod";
import { toast } from "@/ui/components/toast";
import { Card, CardContent } from "@/ui/components/card";
import { Avatar, AvatarFallback } from "@/ui/components/avatar";
import { Button } from "@/ui/components/button";
import { Input } from "@/ui/components/input";
import { Label } from "@/ui/components/label";
import { Loader2 } from "lucide-vue-next";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/ui/components/select";

import { useAuthStore } from "@/auth/stores/authStore";
import {
  IUser,
  IFormValues,
  TGenders,
  IUpdateProfileUser,
} from "@/auth/types/UserInterface";

const authStore = useAuthStore();
const isUpdating = ref(false);
const { resetForm } = useForm();

const props = defineProps<IUser>();

const user = computed(() => {
  return props;
});

// Schema de validação com Zod
const profileSchema = toFormValidator(
  z
    .object({
      name: z
        .string({ required_error: "O nome é obrigatório" })
        .min(3, "O nome deve ter pelo menos 3 caracteres")
        .max(100, "O nome deve ter no máximo 100 caracteres"),
      email: z
        .string({ required_error: "O email é obrigatório" })
        .email("Email inválido"),
      phone: z
        .string({ required_error: "O telefone é obrigatório" })
        .min(9, "O telefone deve ter pelo menos 9 dígitos"),
      gender: z.enum(["masculino", "feminino", "outro"] as const, {
        required_error: "O gênero é obrigatório",
      }),
      currentPassword: z.string().optional(),
      newPassword: z.string().optional(),
      passwordConfirmation: z.string().optional(),
    })
    .refine(
      (data) => {
        // Se nenhum dos campos de senha está preenchido, não valida
        if (
          !data.currentPassword &&
          !data.newPassword &&
          !data.passwordConfirmation
        ) {
          return true;
        }

        // Se algum campo de senha está preenchido, todos precisam estar
        if (data.newPassword || data.passwordConfirmation) {
          if (!data.currentPassword) return false;
        }

        return true;
      },
      {
        message: "É necessário informar a senha atual para alterar a senha",
        path: ["currentPassword"],
      }
    )
    .refine(
      (data) => {
        // Se está tentando alterar a senha
        if (data.currentPassword && data.newPassword) {
          // Verifica se a nova senha tem pelo menos 8 caracteres
          return data.newPassword.length >= 8;
        }
        return true;
      },
      {
        message: "A nova senha deve ter pelo menos 8 caracteres",
        path: ["newPassword"],
      }
    )
    .refine(
      (data) => {
        // Se está tentando alterar a senha
        if (data.currentPassword && data.newPassword) {
          // Verifica se a nova senha tem pelo menos uma letra maiúscula, uma minúscula e um número
          const hasUpperCase = /[A-Z]/.test(data.newPassword);
          const hasLowerCase = /[a-z]/.test(data.newPassword);
          const hasNumber = /[0-9]/.test(data.newPassword);
          return hasUpperCase && hasLowerCase && hasNumber;
        }
        return true;
      },
      {
        message:
          "A senha deve conter pelo menos uma letra maiúscula, uma minúscula e um número",
        path: ["newPassword"],
      }
    )
    .refine(
      (data) => {
        // Se está tentando alterar a senha
        if (
          data.currentPassword &&
          data.newPassword &&
          data.passwordConfirmation
        ) {
          // Verifica se as senhas coincidem
          return data.newPassword === data.passwordConfirmation;
        }
        return true;
      },
      {
        message: "As senhas não coincidem",
        path: ["passwordConfirmation"],
      }
    )
);

// Valores iniciais do formulário
const initialValues = computed<IFormValues>(() => ({
  name: user.value?.name || "",
  email: user.value?.email || "",
  phone: user.value?.phone || "",
  gender: (user.value?.gender as TGenders) || "masculino",
  currentPassword: "",
  newPassword: "",
  passwordConfirmation: "",
}));

const updateProfile = async (values: IFormValues) => {
  try {
    isUpdating.value = true;

    // Preparação dos dados para envio à API, seguindo a interface IUpdateProfileUser
    const userData: IUpdateProfileUser = {
      name: values.name,
      email: values.email,
      phone: values.phone,
      gender: values.gender,
    };

    // Se os campos de senha foram preenchidos, processo separadamente pela função específica
    if (values.currentPassword && values.newPassword) {
      try {
        // Atualizar a senha usando o método específico do authStore
        await authStore.updatePassword({
          currentPassword: values.currentPassword,
          password: values.newPassword,
          password_confirmation: values.passwordConfirmation || ""
        });

        toast({
          title: "Senha atualizada",
          description: "Sua senha foi atualizada com sucesso",
        });
      } catch (passwordError: any) {
        // Se houver erro apenas na atualização da senha, mostramos mensagem mas continuamos o processo
        const passwordErrorMessage = passwordError.response?.data?.message || "Erro ao atualizar a senha";
        toast({
          title: "Erro ao atualizar senha",
          description: passwordErrorMessage,
          variant: "destructive",
        });

        // Se falhar a atualização da senha, ainda queremos tentar atualizar o perfil
        console.error("Erro ao atualizar senha:", passwordError);
      }
    }

    // Atualiza o perfil usando o método do authStore
    await authStore.updateProfile(userData);

    toast({
      title: "Perfil atualizado",
      description: "Suas informações foram atualizadas com sucesso",
    });

    // Reseta campos de senha
    resetForm({
      values: {
        ...values,
        currentPassword: "",
        newPassword: "",
        passwordConfirmation: "",
      },
    });
  } catch (error: any) {
    console.error("Erro ao atualizar perfil:", error);
    const errorMessage =
      error.response?.data?.message || "Não foi possível atualizar o perfil";

    toast({
      title: "Erro",
      description: errorMessage,
      variant: "destructive",
    });
  } finally {
    isUpdating.value = false;
  }
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
  // Inicialização já é feita através do computed initialValues
});
</script>
