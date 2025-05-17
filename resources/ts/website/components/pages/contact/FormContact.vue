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
            <div class="space-y-2">
              <Label for="name">Nome Completo</Label>
              <Input
                id="name"
                v-model="form.name"
                placeholder="Seu nome"
                required
                :disabled="user?.isAuthenticated"
              />
            </div>
            <div class="space-y-2">
              <Label for="email">E-mail</Label>
              <Input
                id="email"
                v-model="form.email"
                type="email"
                placeholder="seu@email.com"
                required
                :disabled="user?.isAuthenticated"
              />
            </div>
          </div>

          <!-- Telefone e Assunto -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="space-y-2">
              <Label for="phone">Telefone</Label>
              <Input
                id="phone"
                v-model="form.phone"
                placeholder="9xx xxx xxx"
                :disabled="user?.isAuthenticated"
              />
            </div>
            <div class="space-y-2">
              <Label for="subject">Assunto</Label>
              <Select v-model="form.subject" required>
                <SelectTrigger id="subject">
                  <SelectValue placeholder="Selecione o assunto" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="duvida">Dúvida</SelectItem>
                  <SelectItem value="sugestao">Sugestão</SelectItem>
                  <SelectItem value="reclamacao">Reclamação</SelectItem>
                  <SelectItem value="elogio">Elogio</SelectItem>
                  <SelectItem value="outro">Outro</SelectItem>
                </SelectContent>
              </Select>
            </div>
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
          <div class="space-y-2">
            <Label for="itemId">ID do Item (opcional)</Label>
            <Input
              id="itemId"
              v-model="form.itemId"
              placeholder="Se sua mensagem é sobre um item específico, informe o ID"
            />
            <p class="text-xs text-muted-foreground">
              Caso sua mensagem seja referente a um item específico, informe o
              ID para agilizar o atendimento.
            </p>
          </div>

          <!-- Termos -->
          <div class="flex items-start gap-2">
            <Checkbox id="terms" v-model:checked="form.acceptTerms" required />
            <div>
              <Label for="terms" class="text-sm">
                Concordo com o
                <a href="#" class="text-primary hover:underline"
                  >Tratamento de Dados Pessoais</a
                >
                conforme a
                <a href="#" class="text-primary hover:underline"
                  >Política de Privacidade</a
                >.
              </Label>
            </div>
          </div>

          <!-- Alertas -->
          <Alert v-if="formSuccess" variant="default" class="mb-4">
            <CheckCircle2 class="h-4 w-4 mr-2" />
            <AlertTitle>Mensagem enviada!</AlertTitle>
            <AlertDescription>
              Sua mensagem foi enviada com sucesso. Em breve entraremos em
              contato através do e-mail informado.
            </AlertDescription>
          </Alert>

          <Alert v-if="formError" variant="destructive" class="mb-4">
            <AlertTriangleIcon class="h-4 w-4 mr-2" />
            <AlertTitle>Erro ao enviar</AlertTitle>
            <AlertDescription>
              Houve um problema ao enviar sua mensagem. Tente novamente mais
              tarde ou entre em contato por telefone.
            </AlertDescription>
          </Alert>

          <!-- Botão de Envio -->
          <div class="flex justify-end">
            <Button
              type="submit"
              size="lg"
              :disabled="isSubmitting || !form.acceptTerms"
            >
              <Loader2 v-if="isSubmitting" class="mr-2 h-4 w-4 animate-spin" />
              Enviar Mensagem
            </Button>
          </div>
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

import {
  Select,
  SelectTrigger,
  SelectValue,
  SelectContent,
  SelectItem,
} from "@/ui/components/select";
import { Alert, AlertTitle, AlertDescription } from "@/ui/components/alert";

import { Label } from "@/ui/components/label";
import { Input } from "@/ui/components/input";
import { Button } from "@/ui/components/button";
import { Textarea } from "@/ui/components/textarea";
import { Checkbox } from "@/ui/components/checkbox";

import {
  CheckCircle2,
  AlertTriangle as AlertTriangleIcon,
  Loader2,
} from "lucide-vue-next";
import { useAuthStore } from "@/auth/stores/authStore";
import { contactService } from "@/services/contactService";

const { user } = useAuthStore();

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
    form.value.phone = user.phone;
  }
};

onMounted(() => {
  setUserAuth();
});

// Estado dos alertas e submissão
const isSubmitting = ref(false);
const formSuccess = ref(false);
const formError = ref(false);

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
};

const handleSubmit = async () => {
  isSubmitting.value = true;
  formSuccess.value = false;
  formError.value = false;

  try {
    await contactService.submit(form.value);
    formSuccess.value = true;
    resetForm();
  } catch (error) {
    console.error("Erro ao enviar mensagem:", error);
    formError.value = true;
  } finally {
    isSubmitting.value = false;
  }
};
</script>

<style scoped></style>
