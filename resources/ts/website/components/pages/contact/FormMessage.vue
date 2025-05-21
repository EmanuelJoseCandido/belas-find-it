<template>
  <Card>
    <CardHeader>
      <CardTitle>{{ getCardTitle }}</CardTitle>
      <CardDescription>
        {{ getCardDescription }}
      </CardDescription>
    </CardHeader>
    <CardContent>
      <Form
        @submit="handleSubmit"
        :validation-schema="contactSchema"
        :initial-values="initialValues"
        v-slot="{
          errors,
          isSubmitting: formSubmitting,
          resetForm: resetFormContext,
        }"
      >
        <!-- Nome e Email -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <Field name="name" v-slot="{ field, errorMessage }">
            <div class="space-y-2">
              <Label :for="field.name">Nome Completo</Label>
              <Input
                :id="field.name"
                v-bind="field"
                placeholder="Seu nome"
                :disabled="!!user?.isAuthenticated"
                :class="{ 'border-red-500': errorMessage }"
              />
              <p v-if="errorMessage" class="mt-1 text-sm text-red-600">
                {{ errorMessage }}
              </p>
            </div>
          </Field>

          <Field name="email" v-slot="{ field, errorMessage }">
            <div class="space-y-2">
              <Label :for="field.name">E-mail</Label>
              <Input
                :id="field.name"
                type="email"
                v-bind="field"
                placeholder="seu@email.com"
                :disabled="!!user?.isAuthenticated"
                :class="{ 'border-red-500': errorMessage }"
              />
              <p v-if="errorMessage" class="mt-1 text-sm text-red-600">
                {{ errorMessage }}
              </p>
            </div>
          </Field>
        </div>

        <!-- Telefone  -->
        <div class="space-y-2 mt-4">
          <Field name="phone" v-slot="{ field, errorMessage }">
            <div class="space-y-2">
              <Label :for="field.name">Telefone</Label>
              <Input
                :id="field.name"
                v-bind="field"
                placeholder="9xx xxx xxx"
                :disabled="!!user?.isAuthenticated"
                :class="{ 'border-red-500': errorMessage }"
              />
              <p v-if="errorMessage" class="mt-1 text-sm text-red-600">
                {{ errorMessage }}
              </p>
            </div>
          </Field>
        </div>

        <!-- Assunto (oculto, mas com o valor atualizado) -->
        <Field name="subject" v-slot="{ field }">
          <input type="hidden" v-bind="field" :value="getSubject" />
        </Field>

        <!-- Mensagem -->
        <div class="space-y-2 mt-4">
          <Field name="message" v-slot="{ field, errorMessage }">
            <div class="space-y-2">
              <Label :for="field.name">Mensagem</Label>
              <Textarea
                :id="field.name"
                v-bind="field"
                :placeholder="getMessagePlaceholder"
                rows="6"
                :class="{ 'border-red-500': errorMessage }"
              />
              <p v-if="errorMessage" class="mt-1 text-sm text-red-600">
                {{ errorMessage }}
              </p>
            </div>
          </Field>
        </div>

        <!-- Botão de Envio -->
        <Button
          type="submit"
          class="w-full mt-6"
          :disabled="
            isSubmitting || formSubmitting || Object.keys(errors).length > 0
          "
        >
          <Loader2 v-if="isSubmitting" class="mr-2 h-4 w-4 animate-spin" />
          <span v-else>{{ getButtonText }}</span>
        </Button>
      </Form>
    </CardContent>
  </Card>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import { z } from "zod";
import { Field, Form, useForm } from "vee-validate";
import { toFormValidator } from "@vee-validate/zod";
import {
  Card,
  CardHeader,
  CardTitle,
  CardDescription,
  CardContent,
} from "@/ui/components/card";
import { Label } from "@/ui/components/label";
import { Input } from "@/ui/components/input";
import { Textarea } from "@/ui/components/textarea";
import { Button } from "@/ui/components/button";

import { Loader2 } from "lucide-vue-next";
import { useAuthStore } from "@/auth/stores/authStore";
import { contactService } from "@/services/contactService";
import { toast } from "@/ui/components/toast";
import type { IItem } from "@/admin/types/IItem";
import { h } from "vue";

const authStore = useAuthStore();
const user = computed(() => authStore.user);
const { resetForm } = useForm();

const props = defineProps<{
  item: IItem;
}>();

// Melhorias de textos com base no status do item
const getCardTitle = computed(() => {
  return props.item.status === "perdido"
    ? "Você encontrou este item?"
    : "Este item é seu?";
});

const getCardDescription = computed(() => {
  return props.item.status === "perdido"
    ? "Ajude a devolver este item para o seu dono. Entre em contacto utilizando o formulário abaixo."
    : "Se este item for seu, entre em contacto com quem o encontrou para recuperá-lo.";
});

const getSubject = computed(() => {
  return props.item.status === "perdido"
    ? `Item Perdido: ${props.item.title}`
    : `Item Encontrado: ${props.item.title}`;
});

const getMessagePlaceholder = computed(() => {
  return props.item.status === "perdido"
    ? "Descreva detalhes específicos do item para confirmar que você realmente o encontrou..."
    : "Descreva detalhes específicos do item para confirmar que é realmente o proprietário...";
});

const getButtonText = computed(() => {
  return props.item.status === "perdido"
    ? "Informar que encontrei este item"
    : "Informar que este item é meu";
});

// Definindo o esquema de validação com Zod
const contactSchema = toFormValidator(
  z.object({
    name: z.string().min(3, "O nome deve ter pelo menos 3 caracteres"),
    email: z.string().email("Digite um e-mail válido"),
    phone: z
      .string()
      .regex(/^[0-9+\-() ]*$/, "Formato de telefone inválido")
      .optional()
      .or(z.literal("")),
    subject: z.string().min(1, "Selecione um assunto"),
    message: z.string().min(10, "A mensagem deve ter pelo menos 10 caracteres"),
  })
);

// Valores iniciais do formulário - garantindo que sejam reativos
const initialValues = computed(() => ({
  name: user.value?.name || "",
  email: user.value?.email || "",
  phone: user.value?.phone || "",
  subject: getSubject.value,
  message: "",
}));

const isSubmitting = ref(false);

const handleSubmit = async (
  values: any,
  { resetForm: resetFormContext }: any
) => {
  isSubmitting.value = true;

  try {
    await contactService.submit({
      name: values.name || user.value?.name,
      email: values.email || user.value?.email,
      phone: values.phone || user.value?.phone,
      subject: getSubject.value,
      message: values.message,
      item_id: props.item.id,
    });

    toast({
      title: props.item.status === "perdido" ? "Obrigado por encontrar este item!" : "Solicitação de recuperação enviada!",
      description: h(
        "p",
        { class: "text-sm" },
        props.item.status === "perdido"
          ? "O proprietário será notificado e entrará em contacto em breve."
          : "O responsável pelo item encontrado será notificado e entrará em contacto para confirmar detalhes."
      ),
    });

    resetFormContext();
  } catch (error) {
    console.error(error);

    toast({
      title: "Erro ao enviar mensagem",
      description: "Ocorreu um erro ao processar sua solicitação. Por favor, tente novamente mais tarde.",
      variant: "destructive"
    });
  } finally {
    isSubmitting.value = false;
  }
};

// Garantir que os dados do usuário estejam disponíveis
onMounted(() => {
  console.log("Usuário logado:", user.value);
});
</script>
