<template>
  <WebsiteLayout>
    <div class="container py-8 px-20">
      <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Cadastrar Item</h1>
        <p class="text-muted-foreground">
          Preencha o formulário abaixo com os detalhes do item perdido ou
          encontrado. Quanto mais informações você fornecer, maiores as chances
          de conexão.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div class="md:col-span-2">
          <Form
            @submit="handleSubmit"
            :validation-schema="itemSchema"
            :initial-values="initialValues"
            v-slot="{
              errors: formErrors,
              isSubmitting: formSubmitting,
              resetForm: formResetFunction,
            }"
            class="space-y-6"
          >
            <!-- Tipo de Item -->
            <div class="p-6 bg-white rounded-lg shadow-sm border">
              <h2 class="text-xl font-semibold mb-4">Tipo de Item</h2>

              <div class="grid grid-cols-2 gap-4">
                <div
                  class="border rounded-lg p-4 cursor-pointer transition-colors"
                  :class="
                    status === 'perdido'
                      ? 'bg-primary/10 border-primary'
                      : 'hover:bg-muted'
                  "
                  @click="setItemType('perdido')"
                >
                  <div class="flex justify-center mb-2">
                    <HelpCircleIcon
                      class="h-8 w-8"
                      :class="
                        status === 'perdido'
                          ? 'text-primary'
                          : 'text-muted-foreground'
                      "
                    />
                  </div>
                  <h3 class="font-medium text-center">Item Perdido</h3>
                  <p class="text-center text-sm text-muted-foreground mt-1">
                    Perdi um item e estou procurando
                  </p>
                </div>

                <div
                  class="border rounded-lg p-4 cursor-pointer transition-colors"
                  :class="
                    status === 'achado'
                      ? 'bg-primary/10 border-primary'
                      : 'hover:bg-muted'
                  "
                  @click="setItemType('achado')"
                >
                  <div class="flex justify-center mb-2">
                    <SearchIcon
                      class="h-8 w-8"
                      :class="
                        status === 'achado'
                          ? 'text-primary'
                          : 'text-muted-foreground'
                      "
                    />
                  </div>
                  <h3 class="font-medium text-center">Item Encontrado</h3>
                  <p class="text-center text-sm text-muted-foreground mt-1">
                    Encontrei um item e quero devolvê-lo
                  </p>
                </div>
              </div>

              <!-- Campo oculto para o status -->
              <Field name="status" v-slot="{ field, errorMessage }">
                <input v-bind="field" type="hidden" />
                <p v-if="errorMessage" class="mt-1 text-sm text-red-600">
                  {{ errorMessage }}
                </p>
              </Field>
            </div>

            <!-- Informações Básicas -->
            <div class="p-6 bg-white rounded-lg shadow-sm border">
              <h2 class="text-xl font-semibold mb-4">Informações Básicas</h2>

              <div class="space-y-4">
                <Field name="title" v-slot="{ field, errorMessage }">
                  <div>
                    <Label for="title">Título</Label>
                    <Input
                      id="title"
                      v-bind="field"
                      placeholder="Ex: Carteira de couro marrom"
                      maxlength="100"
                      :class="{ 'border-red-500': errorMessage }"
                    />
                    <p class="text-xs text-muted-foreground mt-1">
                      Título breve e descritivo para o item
                    </p>
                    <p v-if="errorMessage" class="mt-1 text-sm text-red-600">
                      {{ errorMessage }}
                    </p>
                  </div>
                </Field>

                <Field name="description" v-slot="{ field, errorMessage }">
                  <div>
                    <Label for="description">Descrição</Label>
                    <Textarea
                      id="description"
                      v-bind="field"
                      placeholder="Descreva o item com detalhes: cor, tamanho, marca, características especiais, etc."
                      rows="4"
                      maxlength="500"
                      :class="{ 'border-red-500': errorMessage }"
                    />
                    <p class="text-xs text-muted-foreground mt-1">
                      {{ field.value?.length || 0 }}/500 caracteres
                    </p>
                    <p v-if="errorMessage" class="mt-1 text-sm text-red-600">
                      {{ errorMessage }}
                    </p>
                  </div>
                </Field>

                <Field
                  name="category"
                  v-slot="{ field, errorMessage, value, handleChange }"
                >
                  <div>
                    <Label for="category">Categoria</Label>
                    <Select :value="value" @update:modelValue="handleChange">
                      <SelectTrigger
                        id="category"
                        :class="{ 'border-red-500': errorMessage }"
                      >
                        <SelectValue placeholder="Selecione uma categoria" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem
                          v-for="category in categories"
                          :key="category.id"
                          :value="category.id.toString()"
                        >
                          {{ category.name }}
                        </SelectItem>
                      </SelectContent>
                    </Select>
                    <p v-if="errorMessage" class="mt-1 text-sm text-red-600">
                      {{ errorMessage }}
                    </p>
                  </div>
                </Field>
              </div>
            </div>

            <!-- Local e Data -->
            <div class="p-6 bg-white rounded-lg shadow-sm border">
              <h2 class="text-xl font-semibold mb-4">Local e Data</h2>

              <div class="space-y-4">
                <Field name="location" v-slot="{ field, errorMessage }">
                  <div>
                    <Label for="location">Local</Label>
                    <Input
                      id="location"
                      v-bind="field"
                      placeholder="Ex: Parque Central, próximo ao chafariz"
                      :class="{ 'border-red-500': errorMessage }"
                    />
                    <p class="text-xs text-muted-foreground mt-1">
                      Descreva o local com o máximo de detalhes possível
                    </p>
                    <p v-if="errorMessage" class="mt-1 text-sm text-red-600">
                      {{ errorMessage }}
                    </p>
                  </div>
                </Field>

                <Field name="date" v-slot="{ field, errorMessage }">
                  <div>
                    <Label for="date">Data</Label>
                    <Input
                      id="date"
                      v-bind="field"
                      type="date"
                      :max="today"
                      :class="{ 'border-red-500': errorMessage }"
                    />
                    <p class="text-xs text-muted-foreground mt-1">
                      Data em que o item foi
                      {{ status === "perdido" ? "perdido" : "encontrado" }}
                    </p>
                    <p v-if="errorMessage" class="mt-1 text-sm text-red-600">
                      {{ errorMessage }}
                    </p>
                  </div>
                </Field>
              </div>
            </div>

            <!-- Imagem -->
            <div class="p-6 bg-white rounded-lg shadow-sm border">
              <h2 class="text-xl font-semibold mb-4">Imagem</h2>

              <div class="space-y-4">
                <div
                  class="border-2 border-dashed rounded-lg p-6 text-center"
                  :class="{
                    'border-primary': isDragging,
                    'border-red-500': imageError,
                  }"
                >
                  <div
                    v-if="!previewImage"
                    class="cursor-pointer"
                    @click="openFilePicker"
                    @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="handleDrop"
                  >
                    <UploadIcon
                      class="h-12 w-12 mx-auto text-muted-foreground mb-2"
                    />
                    <p class="font-medium">
                      Arraste uma imagem ou clique para escolher
                    </p>
                    <p class="text-sm text-muted-foreground mt-1">
                      JPG ou PNG até 5MB
                    </p>
                  </div>

                  <div v-else class="relative">
                    <img
                      :src="previewImage"
                      alt="Preview"
                      class="max-h-64 mx-auto rounded"
                    />
                    <Button
                      variant="destructive"
                      size="icon"
                      class="absolute top-2 right-2 h-8 w-8 rounded-full"
                      @click.prevent="clearImage"
                    >
                      <XIcon class="h-4 w-4" />
                    </Button>
                  </div>
                </div>

                <!-- Campo oculto para manter o valor da imagem no formulário -->
                <Field name="image" v-slot="{ field, errorMessage }">
                  <input v-bind="field" type="hidden" />
                  <p
                    v-if="errorMessage || imageError"
                    class="mt-1 text-sm text-red-600"
                  >
                    {{ errorMessage || imageError }}
                  </p>
                </Field>

                <input
                  ref="fileInput"
                  id="imageFile"
                  type="file"
                  class="hidden"
                  accept="image/jpeg,image/png"
                  @change="handleFileChange"
                />

                <p class="text-xs text-muted-foreground">
                  Uma imagem clara do item ajuda na identificação. Não inclua
                  informações pessoais visíveis.
                </p>
              </div>
            </div>

            <!-- Botão de Envio -->
            <div class="flex justify-end">
              <Button
                type="submit"
                size="lg"
                :disabled="
                  isSubmitting ||
                  formSubmitting ||
                  Object.keys(formErrors).length > 0
                "
              >
                <Loader2
                  v-if="isSubmitting || formSubmitting"
                  class="mr-2 h-4 w-4 animate-spin"
                />
                Cadastrar Item
              </Button>
            </div>
          </Form>
        </div>

        <!-- Sidebar com dicas (sem alterações) -->
        <div>
          <div class="bg-white rounded-lg shadow-sm border p-6 sticky top-20">
            <h2 class="text-xl font-semibold mb-4">
              Dicas para um bom cadastro
            </h2>

            <div class="space-y-4">
              <div class="flex gap-3">
                <BadgeCheckIcon
                  class="h-5 w-5 text-primary flex-shrink-0 mt-0.5"
                />
                <div>
                  <p class="font-medium">Seja detalhista</p>
                  <p class="text-sm text-muted-foreground">
                    Quanto mais detalhes você fornecer sobre o item, maiores as
                    chances de identificação.
                  </p>
                </div>
              </div>

              <div class="flex gap-3">
                <CameraIcon class="h-5 w-5 text-primary flex-shrink-0 mt-0.5" />
                <div>
                  <p class="font-medium">Adicione uma foto clara</p>
                  <p class="text-sm text-muted-foreground">
                    Imagens nítidas do item aumentam significativamente as
                    chances de reconhecimento.
                  </p>
                </div>
              </div>

              <div class="flex gap-3">
                <MapPinIcon class="h-5 w-5 text-primary flex-shrink-0 mt-0.5" />
                <div>
                  <p class="font-medium">Descreva o local precisamente</p>
                  <p class="text-sm text-muted-foreground">
                    Indique pontos de referência próximos para facilitar a
                    localização.
                  </p>
                </div>
              </div>

              <div class="flex gap-3">
                <CalendarIcon
                  class="h-5 w-5 text-primary flex-shrink-0 mt-0.5"
                />
                <div>
                  <p class="font-medium">Informe a data correta</p>
                  <p class="text-sm text-muted-foreground">
                    A data correta ajuda a conectar itens perdidos e achados no
                    mesmo período.
                  </p>
                </div>
              </div>

              <div class="flex gap-3">
                <ShieldCheckIcon
                  class="h-5 w-5 text-primary flex-shrink-0 mt-0.5"
                />
                <div>
                  <p class="font-medium">Proteja suas informações</p>
                  <p class="text-sm text-muted-foreground">
                    Evite compartilhar documentos pessoais, senhas ou
                    informações confidenciais.
                  </p>
                </div>
              </div>
            </div>

            <Separator class="my-6" />

            <div>
              <h3 class="font-medium mb-2">Precisa de ajuda?</h3>
              <p class="text-sm text-muted-foreground mb-4">
                Se tiver dúvidas sobre como cadastrar um item, entre em contato
                conosco.
              </p>
              <RouterLink to="/contato">
                <Button variant="outline" class="w-full">Fale Conosco</Button>
              </RouterLink>
            </div>
          </div>
        </div>
      </div>

      <!-- Success Modal (sem alterações) -->
      <Dialog :open="showSuccessModal" @close="showSuccessModal = false">
        <DialogContent class="sm:max-w-md">
          <DialogHeader>
            <DialogTitle>Item Cadastrado com Sucesso!</DialogTitle>
            <DialogDescription>
              Seu item foi cadastrado e já está disponível em nossa plataforma.
            </DialogDescription>
          </DialogHeader>

          <div class="flex items-center justify-center py-6">
            <div class="bg-primary/10 rounded-full p-3">
              <CheckIcon class="h-8 w-8 text-primary" />
            </div>
          </div>

          <p class="text-center mb-4">
            Agradecemos por utilizar nossa plataforma. Esperamos que seu item
            {{
              status === "perdido"
                ? "seja encontrado"
                : "seja devolvido ao dono"
            }}
            em breve.
          </p>

          <DialogFooter class="sm:justify-center">
            <Button @click="showSuccessModal = false">Fechar</Button>
            <Button variant="outline" @click="goToItemPage"
              >Ver Item Cadastrado</Button
            >
          </DialogFooter>
        </DialogContent>
      </Dialog>
    </div>
  </WebsiteLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { RouterLink, useRouter } from "vue-router";
import { z } from "zod";
import { Field, Form, useForm } from "vee-validate";
import { toFormValidator } from "@vee-validate/zod";
import WebsiteLayout from "@/website/layouts/WebsiteLayout.vue";
import { Label } from "@/ui/components/label";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/ui/components/select";
import { Input } from "@/ui/components/input";
import { Button } from "@/ui/components/button";
import { Textarea } from "@/ui/components/textarea";
import { Separator } from "@/ui/components/separator";
import { toast } from "@/ui/components/toast";
import { useAuthStore } from "@/auth/stores/authStore";
import { itemService } from "@/services/itemService";

import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
  DialogDescription,
  DialogFooter,
} from "@/ui/components/dialog";

import {
  HelpCircle as HelpCircleIcon,
  Search as SearchIcon,
  Upload as UploadIcon,
  X as XIcon,
  Info as InfoIcon,
  BadgeCheck as BadgeCheckIcon,
  Camera as CameraIcon,
  MapPin as MapPinIcon,
  Calendar as CalendarIcon,
  ShieldCheck as ShieldCheckIcon,
  Check as CheckIcon,
  Loader2,
} from "lucide-vue-next";
import type { ICategory } from "@/admin/types/ICategory";
import type { TTemStatus } from "@/admin/types/IItem";
import { categoryService } from "@/services/categoryService";

// Router
const router = useRouter();
const authStore = useAuthStore();

// Estado
const status = ref<TTemStatus>("perdido");
const isAuthenticated = computed(
  () => authStore.user?.isAuthenticated || false
);
const isDragging = ref(false);
const previewImage = ref("");
const fileInput = ref<HTMLInputElement | null>(null);
const isSubmitting = ref(false);
const imageError = ref("");
const showSuccessModal = ref(false);
const createdItemId = ref<number | null>(null);
const uploadedFile = ref<File | null>(null);

// Categorias
const categories = ref<ICategory[]>([]);

// Computed
const today = computed(() => {
  const now = new Date();
  return now.toISOString().split("T")[0];
});

// Valores iniciais do formulário
const initialValues = computed(() => ({
  status: status.value,
  user_id: authStore.user?.id,
  title: "",
  description: "",
  category: "",
  location: "",
  date: "",
  image: null,
}));

// Schema de validação com Zod
const itemSchema = toFormValidator(
  z.object({
    user_id: z.number({
      required_error: "ID do usuário é obrigatório",
    }),
    status: z.enum(["perdido", "achado", "resgatado"] as const, {
      required_error: "Selecione o tipo de item",
    }),
    title: z
      .string({ required_error: "Campo obrigatório" })
      .min(5, "O título deve ter pelo menos 5 caracteres")
      .max(100, "O título deve ter no máximo 100 caracteres"),
    description: z
      .string({ required_error: "Campo obrigatório" })
      .min(20, "A descrição deve ter pelo menos 20 caracteres")
      .max(500, "A descrição deve ter no máximo 500 caracteres"),
    category: z
      .string({ required_error: "Campo obrigatório" })
      .min(1, "Selecione uma categoria"),
    location: z
      .string({ required_error: "Campo obrigatório" })
      .min(5, "Forneça detalhes sobre o local"),
    date: z
      .string({ required_error: "Campo obrigatório" })
      .refine((val) => !!val, "Selecione uma data"),
    image: z.any().optional(), // Validação manual da imagem
  })
);

// Funções
const { resetForm } = useForm();

// Atualizar tipo de item - enviar para o contexto do formulário
const setItemType = (type: TTemStatus): void => {
  status.value = type;
};

// Métodos para manipulação de imagem
const openFilePicker = (): void => {
  if (fileInput.value) {
    fileInput.value.click();
  }
};

const handleFileChange = (event: Event): void => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];
  imageError.value = "";

  if (file) {
    validateAndProcessFile(file);
  }
};

const fetchCategories = async () => {
  try {
    const response = await categoryService.getAll();
    categories.value = response.data.data || response.data;
  } catch (err: any) {
    console.error("Error fetching categories:", err);

    toast({
      title: "Erro",
      description: "Não foi possível carregar as categorias",
      variant: "destructive",
    });
  } finally {
  }
};

const validateAndProcessFile = (file: File): void => {
  if (file.size > 5 * 1024 * 1024) {
    imageError.value = "O arquivo é muito grande. O tamanho máximo é 5MB.";
    return;
  }

  if (!["image/jpeg", "image/png"].includes(file.type)) {
    imageError.value = "Apenas arquivos JPG e PNG são aceitos.";
    return;
  }

  uploadedFile.value = file;

  const reader = new FileReader();
  reader.onload = (e) => {
    previewImage.value = e.target?.result as string;
  };
  reader.readAsDataURL(file);
};

const handleDrop = (event: DragEvent): void => {
  isDragging.value = false;
  const files = event.dataTransfer?.files;

  if (files && files.length > 0) {
    const file = files[0];
    imageError.value = "";
    validateAndProcessFile(file);
  }
};

const clearImage = (): void => {
  uploadedFile.value = null;
  previewImage.value = "";
  imageError.value = "";

  if (fileInput.value) {
    fileInput.value.value = "";
  }
};

const handleSubmit = async (
  values: Record<string, any>,
  { resetForm: formResetFunction }: any
): Promise<void> => {
  try {
    isSubmitting.value = true;

    // Preparar FormData para envio
    const formData = new FormData();

    Object.entries(values).forEach(([key, value]) => {
      if (value !== null && value !== undefined) {
        formData.append(key, value as string);
      }
    });

    if (uploadedFile.value) {
      formData.append("image", uploadedFile.value);
    }

    const response = await itemService.create(formData);
    createdItemId.value = response.data.id;

    toast({
      title: "Item cadastrado com sucesso!",
      description:
        "Seu item foi cadastrado e já está disponível na plataforma.",
    });

    // Mostrar modal de sucesso
    showSuccessModal.value = true;

    // Limpar formulário
    formResetFunction();

    // Limpar outras variáveis de estado
    previewImage.value = "";
    uploadedFile.value = null;
    imageError.value = "";

    // Voltar para o tipo padrão
    status.value = "perdido";
  } catch (error) {
    console.error("Erro ao cadastrar item:", error);

    toast({
      title: "Erro ao cadastrar item",
      description:
        "Ocorreu um erro ao processar seu cadastro. Tente novamente mais tarde.",
      variant: "destructive",
    });
  } finally {
    isSubmitting.value = false;
  }
};

const goToItemPage = (): void => {
  showSuccessModal.value = false;
  if (createdItemId.value) {
    const routeName =
      status.value === "perdido" ? "details-lost" : "details-findings";

    router.push({
      name: routeName,
      params: {
        id: createdItemId.value.toString(),
      },
    });
  }
};

// Inicializar
onMounted(async () => {
  if (!isAuthenticated.value) {
    toast({
      title: "Acesso restrito",
      description: "É necessário fazer login para cadastrar itens.",
      variant: "destructive",
    });

    router.push({
      name: "auth-login",
      query: { redirect: router.currentRoute.value.fullPath },
    });
    return;
  }

  // Load authenticated user data
  if (authStore.user) {
    await fetchCategories();
  }
});
</script>
