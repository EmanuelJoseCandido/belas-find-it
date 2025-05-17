<template>
  <div class="lg:col-span-2">
    <Card>
      <CardHeader>
        <CardTitle>Envie sua Mensagem</CardTitle>
        <CardDescription>
          Preencha o formulário abaixo para entrar em contato conosco.
          Responderemos o mais breve possível.
        </CardDescription>
      </CardHeader>
      <CardContent>
        <form @submit.prevent="handleSubmit" class="space-y-6">
          <!-- Nome e Email -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <InputField
              name="name"
              label="Nome Completo"
              v-model="form.name"
              placeholder="Seu nome"
              :disabled="user?.isAuthenticated"
              required
            />
            <InputField
              name="email"
              label="E-mail"
              type="email"
              v-model="form.email"
              placeholder="seu@email.com"
              :disabled="user?.isAuthenticated"
              required
            />
          </div>

          <!-- Telefone e Assunto -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <InputField
              name="phone"
              label="Telefone"
              v-model="form.phone"
              placeholder="9xx xxx xxx"
              :disabled="user?.isAuthenticated"
            />
            <SelectField
              name="subject"
              label="Assunto"
              v-model="form.subject"
              :options="subjectOptions"
              placeholder="Selecione o assunto"
              required
            />
          </div>

          <!-- Mensagem -->
          <div class="space-y-2">
            <Label for="message">Mensagem</Label>
            <Textarea
              id="message"
              v-model="form.message"
              placeholder="Digite sua mensagem..."
              rows="6"
              required
            />
          </div>

          <!-- Item ID -->
          <InputField
            name="itemId"
            label="ID do Item (opcional)"
            v-model="form.itemId"
            placeholder="Se sua mensagem é sobre um item específico, informe o ID"
            hint="Caso sua mensagem seja referente a um item específico, informe o ID para agilizar o atendimento."
          />

          <!-- Termos -->
          <div class="my-3">
            <CheckboxField
              id="terms"
              name="terms"
              v-model="form.acceptTerms"
              required
            >
              <span class="text-xs text-gray-700">
                Concordo com o
                <a href="#" class="text-primary hover:underline"
                  >Tratamento de Dados Pessoais</a
                >
                conforme a
                <a href="#" class="text-primary hover:underline"
                  >Política de Privacidade</a
                >.
              </span>
            </CheckboxField>
          </div>

          <!-- Botão de Envio -->
          <Button
            type="submit"
            class="w-full"
            :disabled="isSubmitting || !form.acceptTerms"
          >
            <Loader2 v-if="isSubmitting" class="mr-2 h-4 w-4 animate-spin" />
            <span v-else>Enviar Mensagem</span>
          </Button>
        </form>
      </CardContent>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import {
  Card,
  CardHeader,
  CardTitle,
  CardDescription,
  CardContent,
} from "@/ui/components/card";
import { Label } from "@/ui/components/label";
import { Textarea } from "@/ui/components/textarea";
import { Button } from "@/ui/components/button";
import InputField from "@/auth/components/InputField.vue";
import SelectField from "@/auth/components/SelectField.vue";
import CheckboxField from "@/auth/components/CheckboxField.vue";
import { Loader2 } from "lucide-vue-next";
import { useAuthStore } from "@/auth/stores/authStore";
import { contactService } from "@/services/contactService";
import { toast } from "@/ui/components/toast";
import { h } from "vue";

const { user } = useAuthStore();

// Opções para o select de assunto
const subjectOptions = [
  { value: "duvida", label: "Dúvida" },
  { value: "sugestao", label: "Sugestão" },
  { value: "reclamacao", label: "Reclamação" },
  { value: "elogio", label: "Elogio" },
  { value: "outro", label: "Outro" },
];

// Estado do formulário
const form = ref({
  name: "",
  email: "",
  phone: "",
  subject: "",
  message: "",
  itemId: "",
  acceptTerms: false,
});

const setUserAuth = () => {
  if (user) {
    form.value.name = user.name;
    form.value.email = user.email;
    form.value.phone = user.phone || "";
  }
};

onMounted(() => {
  setUserAuth();
});

const isSubmitting = ref(false);

const resetForm = () => {
  form.value = {
    name: "",
    email: "",
    phone: "",
    subject: "",
    message: "",
    itemId: "",
    acceptTerms: false,
  };

  if (user && user.isAuthenticated) {
    setUserAuth();
  }
};

const handleSubmit = async () => {
  isSubmitting.value = true;

  try {
    await contactService.submit({
      name: form.value.name,
      email: form.value.email,
      phone: form.value.phone,
      subject: form.value.subject,
      message: form.value.message,
      item_id: form.value.itemId || null,
    });

    toast({
      title: "Mensagem enviada com sucesso!",
      description: h(
        "p",
        { class: "text-sm" },
        "Em breve entraremos em contato através do e-mail informado."
      ),
    });

    resetForm();
  } catch (error) {
    console.error("Erro ao enviar mensagem:", error);

    toast({
      title: "Erro ao enviar mensagem",
      description: h(
        "p",
        { class: "text-sm" },
        "Houve um problema ao enviar sua mensagem. Tente novamente mais tarde ou entre em contacto por telefone."
      ),
      variant: "destructive",
    });
  } finally {
    isSubmitting.value = false;
  }
};
</script>
