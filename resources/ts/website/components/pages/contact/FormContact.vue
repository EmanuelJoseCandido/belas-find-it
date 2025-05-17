<template>
  <div class="lg:col-span-2">
    <Card>
      <CardHeader>
        <CardTitle>Envie sua Mensagem</CardTitle>
        <CardDescription>
          Preencha o formulário abaixo para entrar em contacto connosco.
          Responderemos o mais breve possível.
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

          <!-- Telefone e Assunto -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
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

            <Field
              name="subject"
              v-slot="{ field, errorMessage, value, handleChange }"
            >
              <div class="space-y-2">
                <Label :for="field.name">Assunto</Label>
                <Select
                  :id="field.name"
                  :value="value"
                  @update:modelValue="handleChange"
                >
                  <SelectTrigger :class="{ 'border-red-500': errorMessage }">
                    <SelectValue placeholder="Selecione o assunto" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem
                      v-for="option in subjectOptions"
                      :key="option.value"
                      :value="option.value"
                    >
                      {{ option.label }}
                    </SelectItem>
                  </SelectContent>
                </Select>
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

          <!-- Item ID -->
          <div class="space-y-2 mt-4">
            <Field name="item_id" v-slot="{ field, errorMessage }">
              <div class="space-y-2">
                <Label :for="field.name">ID do Item (opcional)</Label>
                <Input
                  :id="field.name"
                  v-bind="field"
                  placeholder="Se sua mensagem é sobre um item específico, informe o ID"
                />
                <p class="text-xs text-muted-foreground mt-1">
                  Caso sua mensagem seja referente a um item específico, informe
                  o ID para agilizar o atendimento.
                </p>
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
  </div>
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
import { Checkbox } from "@/ui/components/checkbox";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/ui/components/select";
import { Loader2 } from "lucide-vue-next";
import { useAuthStore } from "@/auth/stores/authStore";
import { contactService } from "@/services/contactService";
import { toast } from "@/ui/components/toast";
import { h } from "vue";

const { user } = useAuthStore();
const { resetForm } = useForm();

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
    item_id: z.string().optional().or(z.literal("")),
  })
);

// Opções para o select de assunto
const subjectOptions = [
  { value: "duvida", label: "Dúvida" },
  { value: "sugestao", label: "Sugestão" },
  { value: "reclamacao", label: "Reclamação" },
  { value: "elogio", label: "Elogio" },
  { value: "outro", label: "Outro" },
];

// Valores iniciais do formulário
const initialValues = computed(() => ({
  name: user?.name || "",
  email: user?.email || "",
  phone: user?.phone || "",
  subject: "",
  message: "",
  item_id: "",
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
      subject: values.subject,
      message: values.message,
      item_id: values.item_id || null,
    });

    toast({
      title: "Mensagem enviada com sucesso!",
      description: h(
        "p",
        { class: "text-sm" },
        "Em breve entraremos em contato através do e-mail informado."
      ),
    });

    // Usar o resetForm recebido como argumento
    resetFormContext();
  } catch (error) {
    // Código existente...
  } finally {
    isSubmitting.value = false;
  }
};
</script>
