<template>
  <WebsiteLayout>
    <div class="container py-8 px-20">
      <!-- Navegação de volta -->
      <div class="mb-6">
        <Button
          variant="outline"
          class="flex gap-2 items-center"
          @click="goBack"
        >
          <ArrowLeft class="h-4 w-4" />
          <span>Voltar</span>
        </Button>
      </div>

      <!-- Carregando -->
      <div v-if="isLoading" class="py-12 flex justify-center">
        <div
          class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"
        ></div>
      </div>

      <!-- Erro -->
      <div v-else-if="error" class="py-12 text-center">
        <AlertTriangleIcon class="mx-auto h-12 w-12 text-destructive mb-4" />
        <h3 class="text-lg font-medium mb-1">Erro ao carregar item</h3>
        <p class="text-muted-foreground mb-4">{{ error }}</p>
        <Button @click="fetchItem">Tentar Novamente</Button>
      </div>

      <!-- Conteúdo do Item -->
      <div
        v-else-if="item"
        class="mx-auto grid grid-cols-1 md:grid-cols-3 gap-8"
      >
        <!-- Imagem e Informações -->
        <div class="md:col-span-2 space-y-6">
          <!-- Status Badge -->
          <Badge :variant="statusColor" class="text-base py-1 px-4">{{
            statusLabel
          }}</Badge>

          <!-- Título e Descrição -->
          <div>
            <h1 class="text-3xl font-bold mb-2">
              {{ item.title }}
            </h1>
            <p class="text-muted-foreground">
              {{ item.description }}
            </p>
          </div>

          <!-- Imagem principal -->
          <div
            class="bg-accent/20 rounded-lg overflow-hidden aspect-video max-h-[400px]"
          >
            <img
              :src="imageUrl"
              :alt="item.title"
              class="w-full h-full object-contain"
            />
          </div>

          <!-- Detalhes em cards -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <Card>
              <CardHeader class="pb-2">
                <CardTitle class="text-base">Categoria</CardTitle>
              </CardHeader>
              <CardContent>
                <p>{{ categoryName }}</p>
              </CardContent>
            </Card>

            <Card>
              <CardHeader class="pb-2">
                <CardTitle class="text-base">Local</CardTitle>
              </CardHeader>
              <CardContent>
                <p>{{ item.location || "Não informado" }}</p>
              </CardContent>
            </Card>

            <Card>
              <CardHeader class="pb-2">
                <CardTitle class="text-base">Data do Ocorrido</CardTitle>
              </CardHeader>
              <CardContent>
                <p>
                  {{ formatDate(item.found_date || item.created_at) }}
                </p>
              </CardContent>
            </Card>

            <Card>
              <CardHeader class="pb-2">
                <CardTitle class="text-base">Data do Registro</CardTitle>
              </CardHeader>
              <CardContent>
                <p>{{ formatDate(item.created_at) }}</p>
              </CardContent>
            </Card>
          </div>
        </div>

        <!-- Sidebar com formulário de contacto -->
        <div>
          <FormMessage :item="item" />

          <!-- Info sobre o usuário que cadastrou -->
          <Card class="mt-4">
            <CardHeader>
              <CardTitle>Reportado por</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="flex items-center gap-3">
                <Avatar>
                  <AvatarImage src="/api/placeholder/40/40" />
                  <AvatarFallback>{{
                    getInitials(item.user?.name || "Usuário")
                  }}</AvatarFallback>
                </Avatar>
                <div>
                  <p class="font-medium">
                    {{ item.user?.name || "Usuário do Sistema" }}
                  </p>
                  <p class="text-sm text-muted-foreground">
                    Membro desde
                    {{ formatDate(item.user?.created_at || item.created_at) }}
                  </p>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>

      <!-- Related Items Section -->
      <div v-if="item && !isLoading && !error" class="mt-16">
        <h2 class="text-2xl font-bold mb-6">Itens Relacionados</h2>
        <div
          class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"
        >
          <ItemCard v-for="item in items" :key="item.id" :item="item" />
        </div>
      </div>
    </div>
  </WebsiteLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import WebsiteLayout from "@/website/layouts/WebsiteLayout.vue";
import { Card, CardContent, CardHeader, CardTitle } from "@/ui/components/card";

import { Badge } from "@/ui/components/badge";
import { Button } from "@/ui/components/button";

import { Avatar, AvatarImage, AvatarFallback } from "@/ui/components/avatar";
import { ArrowLeft, AlertTriangle as AlertTriangleIcon } from "lucide-vue-next";

import { itemService } from "@/services/itemService";
import { categoryService } from "@/services/categoryService";
import type { ICategory } from "@/admin/types/ICategory";
import type { IItem } from "@/admin/types/IItem";

import ItemCard from "@/website/components/ItemCard.vue";
import FormMessage from "@/website/components/pages/contact/FormMessage.vue";

// Router
const route = useRoute();
const router = useRouter();

// Estado
const isLoading = ref(true);
const items = ref<IItem[]>([]);
const error = ref<string | null>(null);
const item = ref<IItem | null>(null);
const categories = ref<ICategory[]>([]);

// Computed
const imageUrl = computed(() => {
  return item.value?.image || "/api/placeholder/800/600";
});

const statusColor = computed(() => {
  if (!item.value) return "default";

  switch (item.value.status) {
    case "perdido":
      return "destructive";
    case "achado":
      return "secondary";
    case "resgatado":
      return "default";
    default:
      return "outline";
  }
});

const statusLabel = computed(() => {
  if (!item.value) return "";

  switch (item.value.status) {
    case "perdido":
      return "Perdido";
    case "achado":
      return "Achado";
    case "resgatado":
      return "Resgatado";
    default:
      return item.value.status;
  }
});

const categoryName = computed(() => {
  const category = categories.value.find(
    (c) => c.id === item.value?.category_id
  );
  return category ? category.name : "Não categorizado";
});

// Métodos
const goBack = () => {
  router.back();
};

const fetchItem = async () => {
  isLoading.value = true;
  error.value = null;

  try {
    const { data } = await itemService.get(Number(route.params.id));

    item.value = data.data;
  } catch (err) {
    console.error("Erro ao buscar item:", err);
    error.value = err instanceof Error ? err.message : "Erro desconhecido";
  } finally {
    isLoading.value = false;
  }
};

const formatDate = (dateString: string) => {
  if (!dateString) return "Data não informada";

  try {
    return new Date(dateString).toLocaleDateString("pt-BR", {
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
    });
  } catch (e) {
    return dateString;
  }
};

const getInitials = (name: string) => {
  return name
    .split(" ")
    .map((part) => part.charAt(0))
    .join("")
    .toUpperCase()
    .substring(0, 2);
};

const fetchCategories = async () => {
  try {
    const response = await categoryService.getAll();
    categories.value = response.data.data || response.data;
  } catch (err: any) {
    console.error("Error fetching categories:", err);
  } finally {
  }
};

const fetchItems = async () => {
  isLoading.value = true;
  const status = item.value?.status;

  try {
    if (status === "achado") {
      const { data } = await itemService.getFound();
      items.value = data.data;
    } else if (status === "perdido") {
      const { data } = await itemService.getLost();
      items.value = data.data;
    }
  } catch (error) {
    console.error("Erro ao buscar itens:", error);
  } finally {
    isLoading.value = false;
  }
};

// Inicializar
onMounted(async () => {
  await fetchItem();
  fetchItems();
  fetchCategories();
});
</script>
