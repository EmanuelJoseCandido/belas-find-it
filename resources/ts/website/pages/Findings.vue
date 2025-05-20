<template>
  <WebsiteLayout>
    <div class="container py-8 px-20">
      <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2">Itens Achados</h1>
        <p class="text-muted-foreground">
          Confira os itens encontrados em nosso município. Se você perdeu algo,
          verifique nesta lista e entre em contato para recuperar seu pertence.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Sidebar com filtros -->
        <div class="md:col-span-1">
          <ItemFilter @filter="handleFilter" />
        </div>

        <!-- Lista de itens -->
        <div class="md:col-span-3">
          <!-- Status da busca -->
          <div class="mb-4 flex items-center justify-between">
            <p class="text-sm text-muted-foreground">
              <span v-if="isLoading">Buscando itens...</span>
              <span v-else-if="items.length === 0">Nenhum item encontrado</span>
              <span v-else
                >Mostrando {{ items.length }}
                {{ items.length === 1 ? "item" : "itens" }}</span
              >
            </p>

            <!-- Ordenação -->
            <div class="flex items-center gap-2">
              <span class="text-sm">Ordenar por:</span>
              <Select v-model="sortOption" @update:model-value="fetchItems">
                <SelectTrigger class="w-40">
                  <SelectValue placeholder="Selecione" />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="recent">Mais recentes</SelectItem>
                  <SelectItem value="oldest">Mais antigos</SelectItem>
                </SelectContent>
              </Select>
            </div>
          </div>

          <!-- Loading -->
          <div v-if="isLoading" class="py-12 flex justify-center">
            <div
              class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"
            ></div>
          </div>

          <!-- Empty state -->
          <div v-else-if="items.length === 0" class="py-12 text-center">
            <FileSearchIcon
              class="mx-auto h-12 w-12 text-muted-foreground mb-4"
            />
            <h3 class="text-lg font-medium mb-1">Nenhum item encontrado</h3>
            <p class="text-muted-foreground mb-4">
              Tente ajustar seus filtros ou procurar novamente mais tarde.
            </p>
            <Button @click="resetFilters">Limpar Filtros</Button>
          </div>

          <!-- Items grid -->
          <div
            v-else
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
          >
            <ItemCard v-for="item in items" :key="item.id" :item="item" />
          </div>

          <!-- Pagination -->
          <div
            v-if="items.length > 0 && totalPages > 1"
            class="mt-8 flex justify-center"
          >
            <div class="flex gap-1">
              <Button
                variant="outline"
                size="icon"
                :disabled="currentPage === 1"
                @click="changePage(currentPage - 1)"
              >
                <ChevronLeft class="h-4 w-4" />
              </Button>

              <Button
                v-for="page in paginationItems"
                :key="page"
                :variant="page === currentPage ? 'default' : 'outline'"
                size="icon"
                @click="changePage(page)"
              >
                {{ page }}
              </Button>

              <Button
                variant="outline"
                size="icon"
                :disabled="currentPage === totalPages"
                @click="changePage(currentPage + 1)"
              >
                <ChevronRight class="h-4 w-4" />
              </Button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </WebsiteLayout>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import WebsiteLayout from "@/website/layouts/WebsiteLayout.vue";
import ItemCard from "@/website/components/ItemCard.vue";
import ItemFilter from "@/website/components/ItemFilter.vue";
import { Button } from "@/ui/components/button";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/ui/components/select";
import { FileSearchIcon, ChevronLeft, ChevronRight } from "lucide-vue-next";
import { itemService } from "@/services/itemService";
import type { IItem } from "@/admin/types/IItem";

// Estado
const route = useRoute();
const router = useRouter();
const items = ref<IItem[]>([]);
const isLoading = ref(true);
const currentPage = ref(1);
const totalPages = ref(1);
const itemsPerPage = 12;
const sortOption = ref("recent");

// Filtros
const filters = ref({
  keyword: "",
  category: "",
  location: "",
  date: "",
});

// Controle de paginação
const paginationItems = computed(() => {
  const items = [];
  const maxVisiblePages = 5;

  if (totalPages.value <= maxVisiblePages) {
    for (let i = 1; i <= totalPages.value; i++) {
      items.push(i);
    }
  } else {
    // Lógica para mostrar páginas com limite
    let startPage = Math.max(1, currentPage.value - 2);
    let endPage = Math.min(totalPages.value, startPage + maxVisiblePages - 1);

    if (endPage - startPage < maxVisiblePages - 1) {
      startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }

    for (let i = startPage; i <= endPage; i++) {
      items.push(i);
    }
  }

  return items;
});

// Buscar itens
const fetchItems = async () => {
  isLoading.value = true;

  try {
    // Create params object from filters
    const params = {
      search: filters.value.keyword, // Map keyword to search
      category_id: filters.value.category,
      location: filters.value.location,
      date: filters.value.date,
      // Keep status as "perdido" for lost items
    };

    // Use the itemService with proper params
    const { data } = await itemService.getFound(params);

    // No need for client-side filtering now that backend does it
    let filteredItems = data.data;

    // Ordering is still done on client-side
    if (sortOption.value === "recent") {
      filteredItems.sort(
        (a, b) =>
          new Date(b.created_at).getTime() - new Date(a.created_at).getTime()
      );
    } else {
      filteredItems.sort(
        (a, b) =>
          new Date(a.created_at).getTime() - new Date(b.created_at).getTime()
      );
    }

    // Calculate pagination
    totalPages.value = Math.ceil(filteredItems.length / itemsPerPage);

    // Apply pagination
    const startIndex = (currentPage.value - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    items.value = filteredItems.slice(startIndex, endIndex);
  } catch (error) {
    console.error("Erro ao buscar itens:", error);
  } finally {
    isLoading.value = false;
  }
};

// Mudar página
const changePage = (page: number) => {
  currentPage.value = page;
  fetchItems();
  window.scrollTo({ top: 0, behavior: "smooth" });
};

// Lidar com filtros
const handleFilter = (newFilters: any) => {
  filters.value = newFilters;
  currentPage.value = 1;
  fetchItems();
};

// Resetar filtros
const resetFilters = () => {
  filters.value = {
    keyword: "",
    category: "",
    location: "",
    date: "",
  };
  currentPage.value = 1;
  fetchItems();
};

// Inicializar
onMounted(() => {
  // Verificar se há filtros na URL
  const queryKeyword = route.query.keyword as string;
  const queryCategory = route.query.category as string;
  const queryLocation = route.query.location as string;
  const queryDate = route.query.date as string;

  if (queryKeyword || queryCategory || queryLocation || queryDate) {
    filters.value = {
      keyword: queryKeyword || "",
      category: queryCategory || "",
      location: queryLocation || "",
      date: queryDate || "",
    };
  }

  fetchItems();
});
</script>
