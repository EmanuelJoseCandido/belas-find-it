<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl font-bold">
        {{ isEditing ? "Editar" : "Novo" }} Item
      </h2>
      <Button variant="outline" @click="goBack">
        <ArrowLeftIcon class="h-4 w-4 mr-2" />
        Voltar
      </Button>
    </div>

    <div v-if="loading" class="flex justify-center py-12">
      <Loader2Icon class="h-12 w-12 animate-spin text-primary" />
    </div>

    <div v-else-if="error" class="bg-destructive/10 p-6 rounded-lg text-center">
      <AlertTriangleIcon class="h-12 w-12 mx-auto text-destructive mb-4" />
      <p class="text-lg font-medium text-destructive">{{ error }}</p>
      <Button @click="goBack" class="mt-4">Voltar</Button>
    </div>

    <div v-else>
      <Form
        @submit="saveItem"
        :validation-schema="itemSchema"
        :initial-values="formValues"
        v-slot="{ errors: formErrors, isSubmitting: formSubmitting }"
        class="space-y-8"
      >
        <!-- Tipo de Item -->
        <Card>
          <CardHeader>
            <CardTitle>Tipo de Item</CardTitle>
            <CardDescription
              >Especifique se o item foi perdido ou encontrado</CardDescription
            >
          </CardHeader>
          <CardContent>
            <Field
              name="status"
              v-slot="{ field, errorMessage, value, handleChange }"
            >
              <div class="grid grid-cols-2 gap-4">
                <div
                  class="border rounded-lg p-4 cursor-pointer transition-colors"
                  :class="
                    value === 'perdido'
                      ? 'bg-primary/10 border-primary'
                      : 'hover:bg-muted'
                  "
                  @click="() => handleChange('perdido')"
                >
                  <div class="flex justify-center mb-2">
                    <HelpCircleIcon
                      class="h-8 w-8"
                      :class="
                        value === 'perdido'
                          ? 'text-primary'
                          : 'text-muted-foreground'
                      "
                    />
                  </div>
                  <h3 class="font-medium text-center">Item Perdido</h3>
                  <p class="text-center text-sm text-muted-foreground mt-1">
                    Um item que foi perdido e está sendo procurado
                  </p>
                </div>

                <div
                  class="border rounded-lg p-4 cursor-pointer transition-colors"
                  :class="
                    value === 'achado'
                      ? 'bg-primary/10 border-primary'
                      : 'hover:bg-muted'
                  "
                  @click="() => handleChange('achado')"
                >
                  <div class="flex justify-center mb-2">
                    <SearchIcon
                      class="h-8 w-8"
                      :class="
                        value === 'achado'
                          ? 'text-primary'
                          : 'text-muted-foreground'
                      "
                    />
                  </div>
                  <h3 class="font-medium text-center">Item Encontrado</h3>
                  <p class="text-center text-sm text-muted-foreground mt-1">
                    Um item que foi encontrado e está procurando seu dono
                  </p>
                </div>
              </div>
              <p v-if="errorMessage" class="mt-2 text-sm text-red-600">
                {{ errorMessage }}
              </p>
            </Field>
          </CardContent>
        </Card>

        <!-- Informações Básicas -->
        <Card>
          <CardHeader>
            <CardTitle>Informações Básicas</CardTitle>
            <CardDescription>Detalhes principais do item</CardDescription>
          </CardHeader>
          <CardContent>
            <div class="grid gap-6">
              <Field name="title" v-slot="{ field, errorMessage }">
                <div>
                  <Label for="title">Título</Label>
                  <Input
                    id="title"
                    v-bind="field"
                    placeholder="Título do item"
                    :class="{ 'border-red-500': errorMessage }"
                  />
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
                    placeholder="Descreva o item com detalhes"
                    rows="4"
                    :class="{ 'border-red-500': errorMessage }"
                  />
                  <p v-if="errorMessage" class="mt-1 text-sm text-red-600">
                    {{ errorMessage }}
                  </p>
                </div>
              </Field>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <Field
                  name="category_id"
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

                <Field
                  name="user_id"
                  v-slot="{ field, errorMessage, value, handleChange }"
                >
                  <div>
                    <Label for="user">Usuário</Label>
                    <Select :value="value" @update:modelValue="handleChange">
                      <SelectTrigger
                        id="user"
                        :class="{ 'border-red-500': errorMessage }"
                      >
                        <SelectValue placeholder="Selecione um usuário" />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem
                          v-for="user in users"
                          :key="user.id"
                          :value="user.id.toString()"
                        >
                          {{ user.name }}
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
          </CardContent>
        </Card>

        <!-- Localização e Data -->
        <Card>
          <CardHeader>
            <CardTitle>Localização e Data</CardTitle>
            <CardDescription
              >Onde e quando o item foi perdido ou encontrado</CardDescription
            >
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <Field name="location" v-slot="{ field, errorMessage }">
                <div>
                  <Label for="location">Localização</Label>
                  <Input
                    id="location"
                    v-bind="field"
                    placeholder="Onde o item foi perdido/encontrado"
                    :class="{ 'border-red-500': errorMessage }"
                  />
                  <p v-if="errorMessage" class="mt-1 text-sm text-red-600">
                    {{ errorMessage }}
                  </p>
                </div>
              </Field>

              <Field name="found_date" v-slot="{ field, errorMessage }">
                <div>
                  <Label for="found_date">Data</Label>
                  <Input
                    id="found_date"
                    type="date"
                    v-bind="field"
                    :max="today"
                    :class="{ 'border-red-500': errorMessage }"
                  />
                  <p v-if="errorMessage" class="mt-1 text-sm text-red-600">
                    {{ errorMessage }}
                  </p>
                </div>
              </Field>
            </div>
          </CardContent>
        </Card>

        <!-- Imagem -->
        <Card>
          <CardHeader>
            <CardTitle>Imagem</CardTitle>
            <CardDescription
              >Adicione uma imagem do item (opcional)</CardDescription
            >
          </CardHeader>
          <CardContent>
            <div
              class="border-2 border-dashed rounded-lg p-6 text-center"
              :class="{
                'border-primary bg-primary/5': isDragging,
                'border-red-500': imageError,
              }"
              @dragover.prevent="isDragging = true"
              @dragleave.prevent="isDragging = false"
              @drop.prevent="handleDrop"
            >
              <div
                v-if="!previewImage && !currentImage"
                class="cursor-pointer"
                @click="openFilePicker"
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

              <div v-else-if="previewImage || currentImage" class="relative">
                <img
                  :src="previewImage || getImageUrl(currentImage)"
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

            <p v-if="imageError" class="mt-2 text-sm text-red-600">
              {{ imageError }}
            </p>

            <input
              ref="fileInput"
              id="image"
              name="image"
              type="file"
              class="hidden"
              accept="image/jpeg,image/png"
              @change="handleFileChange"
            />
          </CardContent>
        </Card>

        <!-- Submit Button -->
        <div class="flex justify-end gap-3">
          <Button
            variant="outline"
            type="button"
            @click="goBack"
            :disabled="isSubmitting || formSubmitting"
          >
            Cancelar
          </Button>
          <Button
            type="submit"
            :disabled="
              isSubmitting ||
              formSubmitting ||
              Object.keys(formErrors).length > 0
            "
          >
            <Loader2Icon
              v-if="isSubmitting || formSubmitting"
              class="mr-2 h-4 w-4 animate-spin"
            />
            {{ isEditing ? "Salvar Alterações" : "Criar Item" }}
          </Button>
        </div>
      </Form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useRouter, useRoute } from "vue-router";
import { z } from "zod";
import { Field, Form } from "vee-validate";
import { toFormValidator } from "@vee-validate/zod";
import { toast } from "@/ui/components/toast";
import { Button } from "@/ui/components/button";
import { Input } from "@/ui/components/input";
import { Label } from "@/ui/components/label";
import { Textarea } from "@/ui/components/textarea";
import {
  Card,
  CardHeader,
  CardTitle,
  CardDescription,
  CardContent,
} from "@/ui/components/card";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/ui/components/select";
import {
  ArrowLeft as ArrowLeftIcon,
  HelpCircle as HelpCircleIcon,
  Search as SearchIcon,
  Upload as UploadIcon,
  X as XIcon,
  AlertTriangle as AlertTriangleIcon,
  Loader2 as Loader2Icon,
} from "lucide-vue-next";

import { itemService } from "@/services/itemService";
import { categoryService } from "@/services/categoryService";
import { userService } from "@/services/userService";

import { IItem } from "@/admin/types/IItem";
import { ICategory } from "@/admin/types/ICategory";
import { IUser } from "@/admin/types/IUser";

// Router
const router = useRouter();
const route = useRoute();

// Estado
const loading = ref<boolean>(true);
const error = ref<string | null>(null);
const isSubmitting = ref<boolean>(false);
const item = ref<IItem | null>(null);
const categories = ref<ICategory[]>([]);
const users = ref<IUser[]>([]);
const isEditing = computed(() => !!route.params.id);
const isDragging = ref<boolean>(false);
const previewImage = ref<string>("");
const currentImage = ref<string>("");
const fileInput = ref<HTMLInputElement | null>(null);
const imageError = ref<string>("");
const imageFile = ref<File | null>(null);

// Schema validação
const itemSchema = toFormValidator(
  z.object({
    title: z
      .string({ required_error: "O título é obrigatório" })
      .min(3, "O título deve ter pelo menos 3 caracteres")
      .max(100, "O título deve ter no máximo 100 caracteres"),
    description: z
      .string({ required_error: "A descrição é obrigatória" })
      .min(10, "A descrição deve ter pelo menos 10 caracteres")
      .max(500, "A descrição deve ter no máximo 500 caracteres"),
    category_id: z
      .string({ required_error: "A categoria é obrigatória" })
      .min(1, "Selecione uma categoria"),
    location: z.string().optional().nullable(),
    status: z.enum(["perdido", "achado", "resgatado"] as const, {
      required_error: "O status é obrigatório",
    }),
    user_id: z
      .string({ required_error: "O usuário é obrigatório" })
      .min(1, "Selecione um usuário"),
    found_date: z.string().optional().nullable(),
  })
);

// Computed values
const formValues = computed(() => {
  if (isEditing.value && item.value) {
    return {
      title: item.value.title,
      description: item.value.description || "",
      category_id: item.value.category_id.toString(),
      location: item.value.location || "",
      status: item.value.status,
      user_id: item.value.user_id.toString(),
      found_date: item.value.found_date || "",
    };
  }

  return {
    title: "",
    description: "",
    category_id: "",
    location: "",
    status: "perdido",
    user_id: "",
    found_date: "",
  };
});

const today = computed(() => {
  const now = new Date();
  return now.toISOString().split("T")[0];
});

// Métodos
const fetchItem = async (id: string) => {
  try {
    loading.value = true;
    error.value = null;

    const response = await itemService.get(Number(id));
    item.value = response.data.data || response.data;

    if (item.value.image) {
      currentImage.value = item.value.image;
    }
  } catch (err: any) {
    console.error("Error fetching item:", err);
    error.value =
      "Não foi possível carregar o item. Ele pode ter sido excluído ou você não tem permissão para acessá-lo.";
  } finally {
    loading.value = false;
  }
};

const fetchCategories = async () => {
  try {
    const response = await categoryService.getAll();
    categories.value = response.data.data || response.data;
  } catch (err: any) {
    console.error("Error fetching categories:", err);
    toast({
      title: "Atenção",
      description: "Não foi possível carregar as categorias",
      variant: "default",
    });
  }
};

const fetchUsers = async () => {
  try {
    const response = await userService.getAll();
    users.value = response.data.data || response.data;
  } catch (err: any) {
    console.error("Error fetching users:", err);
    toast({
      title: "Atenção",
      description: "Não foi possível carregar os usuários",
      variant: "default",
    });
  }
};

const goBack = () => {
  router.push({ name: "admin-items" });
};

// Image handling
const openFilePicker = () => {
  if (fileInput.value) {
    fileInput.value.click();
  }
};

const handleFileChange = (event: Event) => {
  const target = event.target as HTMLInputElement;
  const file = target.files?.[0];
  imageError.value = "";

  if (file) {
    validateAndProcessFile(file);
  }
};

const validateAndProcessFile = (file: File) => {
  if (file.size > 5 * 1024 * 1024) {
    imageError.value = "O arquivo é muito grande. O tamanho máximo é 5MB.";
    return;
  }

  if (!["image/jpeg", "image/png"].includes(file.type)) {
    imageError.value = "Apenas arquivos JPG e PNG são aceitos.";
    return;
  }

  imageFile.value = file;

  const reader = new FileReader();
  reader.onload = (e) => {
    previewImage.value = e.target?.result as string;
  };
  reader.readAsDataURL(file);
};

const handleDrop = (event: DragEvent) => {
  isDragging.value = false;
  const files = event.dataTransfer?.files;

  if (files && files.length > 0) {
    const file = files[0];
    validateAndProcessFile(file);
  }
};

const clearImage = () => {
  imageFile.value = null;
  previewImage.value = "";
  currentImage.value = "";
  imageError.value = "";

  if (fileInput.value) {
    fileInput.value.value = "";
  }
};

const getImageUrl = (imagePath?: string) => {
  if (!imagePath) return "";

  // Verificar se a URL já é completa
  if (imagePath.startsWith("http")) {
    return imagePath;
  }

  // Caso seja um caminho relativo, construir a URL completa
  // Assumindo uma estrutura de URL baseada na configuração da API
  return `/storage/${imagePath}`;
};

const saveItem = async (values: Record<string, any>) => {
  try {
    isSubmitting.value = true;

    // Preparar FormData para envio
    const formData = new FormData();

    // Adicionar todos os valores do formulário
    Object.entries(values).forEach(([key, value]) => {
      if (value !== null && value !== undefined && value !== "") {
        formData.append(key, value as string);
      }
    });

    // Adicionar arquivo de imagem se houver
    if (imageFile.value) {
      formData.append("image", imageFile.value);
    }

    console.log("formData: ", formData);

    let response;
    if (isEditing.value && item.value) {
      // Update existing item
      response = await itemService.update(item.value.id, formData);
      toast({
        title: "Sucesso",
        description: "Item atualizado com sucesso",
      });
    } else {
      // Create new item
      response = await itemService.create(formData);
      toast({
        title: "Sucesso",
        description: "Item criado com sucesso",
      });
    }

    // Navegar de volta para a lista de itens
    router.push({ name: "admin-items" });
  } catch (err: any) {
    console.error("Error saving item:", err);
    toast({
      title: "Erro",
      description: err.response?.data?.message || "Erro ao salvar item",
      variant: "destructive",
    });
  } finally {
    isSubmitting.value = false;
  }
};

// Initialize
onMounted(async () => {
  // Carregar categorias e usuários em paralelo
  Promise.all([fetchCategories(), fetchUsers()]);

  // Se estiver editando, carregar o item
  if (isEditing.value) {
    await fetchItem(route.params.id as string);
  } else {
    loading.value = false;
  }
});
</script>
