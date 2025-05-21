<template>
  <Card>
    {{ user }}

    <CardHeader>
      <CardTitle>Entre em Contacto</CardTitle>
      <CardDescription>
        {{
          item.status === "perdido"
            ? "Você encontrou este item? Entre em contacto com o dono."
            : "Este item é seu? Entre em contacto para recuperá-lo."
        }}
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
                :disabled="user?.isAuthenticated"
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
                :disabled="user?.isAuthenticated"
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
                :disabled="user?.isAuthenticated"
                :class="{ 'border-red-500': errorMessage }"
              />
              <p v-if="errorMessage" class="mt-1 text-sm text-red-600">
                {{ errorMessage }}
              </p>
            </div>
          </Field>
        </div>

        <!-- Mensagem -->
        <div class="space-y-2 mt-4">
          <Field name="message" v-slot="{ field, errorMessage }">
            <div class="space-y-2">
              <Label :for="field.name">Mensagem</Label>
              <Textarea
                :id="field.name"
                v-bind="field"
                placeholder="Digite sua mensagem..."
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
          <span v-else>Enviar Mensagem</span>
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

const { user } = useAuthStore();
const { resetForm } = useForm();

const props = defineProps<{
  item: IItem;
}>();

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

// Valores iniciais do formulário
const initialValues = computed(() => ({
  name: user?.name || "",
  email: user?.email || "",
  phone: user?.phone || "",
  subject: props.item.status,
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
      name: values.name,
      email: values.email,
      phone: values.phone,
      subject: props.item.status,
      message: values.message,
      item_id: props.item.id,
    });

    toast({
      title: "Mensagem enviada com sucesso!",
      description: h(
        "p",
        { class: "text-sm" },
        "Em breve entraremos em contacto através do e-mail informado."
      ),
    });

    resetFormContext();
  } catch (error) {
    console.error(error);
  } finally {
    isSubmitting.value = false;
  }
};
</script>
