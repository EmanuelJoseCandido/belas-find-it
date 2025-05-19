<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl font-bold">Gerenciar Itens</h2>
      <Button @click="navigateToCreate">
        <PlusIcon class="h-4 w-4 mr-2" />
        Novo Item
      </Button>
    </div>

    <!-- Filters -->
    <Card class="mb-6">
      <CardContent class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <!-- Search -->
          <div>
            <Label for="search">Buscar</Label>
            <div class="relative">
              <SearchIcon
                class="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground"
              />
              <Input
                id="search"
                type="search"
                placeholder="Buscar por título..."
                class="pl-8"
                v-model="filters.search"
                @input="debounceSearch"
              />
            </div>
          </div>

          <!-- Status Filter -->
          <div>
            <Label for="status">Status</Label>
            <Select v-model="filters.status" @update:modelValue="applyFilters">
              <SelectTrigger id="status">
                <SelectValue placeholder="Todos os status" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">Todos os status</SelectItem>
                <SelectItem value="perdido">Perdido</SelectItem>
                <SelectItem value="achado">Achado</SelectItem>
                <SelectItem value="resgatado">Resgatado</SelectItem>
              </SelectContent>
            </Select>
          </div>

          <!-- Category Filter -->
          <div>
            <Label for="category">Categoria</Label>
            <Select
              v-model="filters.category"
              @update:modelValue="applyFilters"
            >
              <SelectTrigger id="category">
                <SelectValue placeholder="Todas as categorias" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">Todas as categorias</SelectItem>
                <SelectItem
                  v-for="category in categories"
                  :key="category.id"
                  :value="category.id.toString()"
                >
                  {{ category.name }}
                </SelectItem>
              </SelectContent>
            </Select>
          </div>

          <!-- Date Range -->
          <div>
            <Label for="date">Data</Label>
            <Select v-model="filters.date" @update:modelValue="applyFilters">
              <SelectTrigger id="date">
                <SelectValue placeholder="Todas as datas" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="all">Todas as datas</SelectItem>
                <SelectItem value="today">Hoje</SelectItem>
                <SelectItem value="week">Última semana</SelectItem>
                <SelectItem value="month">Último mês</SelectItem>
              </SelectContent>
            </Select>
          </div>
        </div>
      </CardContent>
    </Card>

    <!-- Data Table -->
    <DataTable
      :items="filteredItems"
      :columns="columns"
      :loading="loading"
      :error="error"
      :empty-state-title="'Nenhum item encontrado'"
      :empty-state-description="'Crie um novo item ou ajuste os filtros para ver os itens.'"
      :empty-state-icon="PackageIcon"
      :empty-state-button-text="'Novo Item'"
      @create="navigateToCreate"
    >
      <!-- Slot para coluna de imagem -->
      <template #image="{ item }">
        <div
          class="w-10 h-10 rounded overflow-hidden bg-muted flex items-center justify-center"
        >
          <img
            v-if="item.image"
            :src="getImageUrl(item.image)"
            :alt="item.title"
            class="w-full h-full object-cover"
          />
          <PackageIcon v-else class="h-5 w-5 text-muted-foreground" />
        </div>
      </template>

      <!-- Slot para coluna de título -->
      <template #title="{ item }">
        <div>
          <p class="font-medium truncate max-w-[200px]">{{ item.title }}</p>
          <p class="text-xs text-muted-foreground truncate max-w-[200px]">
            {{ item.location || "Sem localização" }}
          </p>
        </div>
      </template>

      <!-- Slot para coluna de status -->
      <template #status="{ item }">
        <Badge :variant="getStatusVariant(item.status)">
          {{ getStatusLabel(item.status) }}
        </Badge>
      </template>

      <!-- Slot para coluna de categoria -->
      <template #category="{ item }">
        {{ getCategoryName(item.category_id) }}
      </template>

      <!-- Slot para coluna de usuário -->
      <template #user="{ item }">
        <div class="flex items-center space-x-2">
          <Avatar class="h-6 w-6">
            <AvatarFallback>{{
              getUserInitials(item.user?.name)
            }}</AvatarFallback>
          </Avatar>
          <span class="truncate max-w-[150px]">{{
            item.user?.name || "Usuário não encontrado"
          }}</span>
        </div>
      </template>

      <!-- Slot para coluna de reivindicações -->
      <template #claims_count="{ item }">
        <Badge variant="outline" v-if="item.claims && item.claims.length > 0">
          {{ item.claims.length }}
        </Badge>
        <span v-else class="text-muted-foreground">0</span>
      </template>

      <template #created_at="{ item }">
        {{ timeAgo(item.created_at) }}
      </template>

      <!-- Slot para coluna de ações -->
      <template #actions="{ item }">
        <ActionButtons
          :item="item"
          @edit="navigateToEdit"
          @delete="confirmDelete"
          @restore="restoreItem"
          @forceDelete="confirmForceDelete"
          @view="viewItem"
        />
      </template>
    </DataTable>

    <!-- Delete Confirmation Dialog -->
    <ConfirmDialog
      v-model="showDeleteModal"
      :title="'Confirmar Exclusão'"
      :description="getDeleteDescription()"
      :confirm-text="forceDelete ? 'Excluir Permanentemente' : 'Excluir'"
      :loading="isSubmitting"
      @confirm="deleteItem"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, watch } from "vue";
import { useRouter } from "vue-router";
import { toast } from "@/ui/components/toast";
import { Button } from "@/ui/components/button";
import { Input } from "@/ui/components/input";
import { Label } from "@/ui/components/label";
import { Badge } from "@/ui/components/badge";
import { Avatar, AvatarFallback } from "@/ui/components/avatar";
import { Card, CardContent } from "@/ui/components/card";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/ui/components/select";
import {
  Plus as PlusIcon,
  Package as PackageIcon,
  Search as SearchIcon,
} from "lucide-vue-next";

import { itemService } from "@/services/itemService";
import { categoryService } from "@/services/categoryService";
import { useDateFormat } from "@/composables/useDateFormat";

// Componentes reutilizáveis
import DataTable from "@/admin/components/common/DataTable.vue";
import ConfirmDialog from "@/admin/components/common/ConfirmDialog.vue";
import ActionButtons from "@/admin/components/common/ActionButtons.vue";

import { IItem } from "@/admin/types/IItem";
import { ICategory } from "@/admin/types/ICategory";

// Router
const router = useRouter();
const { timeAgo } = useDateFormat();

// Definição das colunas
const columns = [
  { key: "image", label: "", class: "w-[60px]" },
  { key: "title", label: "Título" },
  { key: "status", label: "Status" },
  { key: "category", label: "Categoria" },
  { key: "user", label: "Usuário" },
  { key: "created_at", label: "Data", format: "date" },
  { key: "claims_count", label: "Reclamações", class: "text-center" },
  { key: "actions", label: "Ações", class: "text-right" },
];

// Estado
const items = ref<IItem[]>([]);
const categories = ref<ICategory[]>([]);
const loading = ref<boolean>(true);
const error = ref<string | null>(null);
const isSubmitting = ref<boolean>(false);
const showDeleteModal = ref<boolean>(false);
const itemToDelete = ref<IItem | null>(null);
const forceDelete = ref<boolean>(false);

// Filtros
const filters = ref({
  search: "",
  status: "all",
  category: "all",
  date: "all",
});

// Computed values
const filteredItems = computed(() => {
  let filtered = [...items.value];

  // Filtro de busca
  if (filters.value.search) {
    const search = filters.value.search.toLowerCase();
    filtered = filtered.filter(
      (item) =>
        item.title.toLowerCase().includes(search) ||
        (item.description && item.description.toLowerCase().includes(search)) ||
        (item.location && item.location.toLowerCase().includes(search))
    );
  }

  // Filtro de status
  if (filters.value.status !== "all") {
    filtered = filtered.filter((item) => item.status === filters.value.status);
  }

  // Filtro de categoria
  if (filters.value.category !== "all") {
    filtered = filtered.filter(
      (item) => item?.category_id?.toString() === filters.value.category
    );
  }

  // Filtro de data
  if (filters.value.date !== "all") {
    const now = new Date();
    const today = new Date(now.getFullYear(), now.getMonth(), now.getDate());

    if (filters.value.date === "today") {
      filtered = filtered.filter((item) => {
        const itemDate = new Date(item.created_at);
        return itemDate >= today;
      });
    } else if (filters.value.date === "week") {
      const weekAgo = new Date(today);
      weekAgo.setDate(weekAgo.getDate() - 7);

      filtered = filtered.filter((item) => {
        const itemDate = new Date(item.created_at);
        return itemDate >= weekAgo;
      });
    } else if (filters.value.date === "month") {
      const monthAgo = new Date(today);
      monthAgo.setMonth(monthAgo.getMonth() - 1);

      filtered = filtered.filter((item) => {
        const itemDate = new Date(item.created_at);
        return itemDate >= monthAgo;
      });
    }
  }

  return filtered;
});

// Métodos
const fetchItems = async () => {
  try {
    loading.value = true;
    error.value = null;
    const response = await itemService.getAll();
    items.value = response.data.data || response.data;
  } catch (err: any) {
    console.error("Error fetching items:", err);
    error.value = err.message || "Erro ao carregar itens";
    toast({
      title: "Erro",
      description: "Não foi possível carregar os itens",
      variant: "destructive",
    });
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
      description: "Não foi possível carregar as categorias para o filtro",
      variant: "default",
    });
  }
};

// Debounce para busca
let searchTimeout: number | null = null;
const debounceSearch = () => {
  if (searchTimeout) {
    clearTimeout(searchTimeout);
  }
  searchTimeout = window.setTimeout(() => {
    applyFilters();
  }, 300);
};

const applyFilters = () => {
  // O computed filteredItems já está aplicando os filtros automaticamente
  // Esta função está aqui para possíveis implementações futuras, como filtros do servidor
};

// Getters
const getStatusVariant = (status: string) => {
  switch (status) {
    case "perdido":
      return "destructive";
    case "achado":
      return "secondary";
    case "resgatado":
      return "default";
    default:
      return "outline";
  }
};

const getStatusLabel = (status: string) => {
  switch (status) {
    case "perdido":
      return "Perdido";
    case "achado":
      return "Achado";
    case "resgatado":
      return "Resgatado";
    default:
      return status;
  }
};

const getCategoryName = (categoryId: number) => {
  const category = categories.value.find((c) => c.id === categoryId);
  return category ? category.name : "Sem categoria";
};

const getUserInitials = (name?: string) => {
  if (!name) return "UN";

  return name
    .split(" ")
    .map((part) => part.charAt(0))
    .join("")
    .toUpperCase()
    .substring(0, 2);
};

const getImageUrl = (imagePath: string) => {
  // Verificar se a URL já é completa
  console.log("imagePath: ", imagePath);
  if (imagePath.startsWith("http")) {
    return imagePath;
  }

  // Caso seja um caminho relativo, construir a URL completa
  // Assumindo uma estrutura de URL baseada na configuração da API
  return `/storage/${imagePath}`;
};

// Navigation
const navigateToCreate = () => {
  router.push({ name: "admin-items-create" });
};

const navigateToEdit = (item: IItem) => {
  router.push({ name: "admin-items-edit", params: { id: item.id.toString() } });
};

const viewItem = (item: IItem) => {
  // Abrir o item no site principal, em uma nova aba
  window.open(`/${item.status}s/${item.id}`, "_blank");
};

// Delete
const confirmDelete = (item: IItem) => {
  itemToDelete.value = item;
  forceDelete.value = false;
  showDeleteModal.value = true;
};

const confirmForceDelete = (item: IItem) => {
  itemToDelete.value = item;
  forceDelete.value = true;
  showDeleteModal.value = true;
};

const getDeleteDescription = () => {
  if (!itemToDelete.value) return "";

  return `Tem certeza que deseja excluir o item "${
    itemToDelete.value.title
  }"? ${
    forceDelete.value
      ? "Esta ação é permanente e não pode ser desfeita."
      : "O item pode ser restaurado posteriormente."
  }`;
};

const deleteItem = async () => {
  if (!itemToDelete.value) return;

  try {
    isSubmitting.value = true;

    if (forceDelete.value) {
      // Permanently delete
      await itemService.forceDelete(itemToDelete.value.id);
      toast({
        title: "Sucesso",
        description: "Item excluído permanentemente",
      });
    } else {
      // Soft delete
      await itemService.delete(itemToDelete.value.id);
      toast({
        title: "Sucesso",
        description: "Item excluído com sucesso",
      });
    }

    // Refresh the items list
    await fetchItems();

    // Close the confirmation modal
    showDeleteModal.value = false;
    itemToDelete.value = null;
  } catch (err: any) {
    console.error("Error deleting item:", err);
    toast({
      title: "Erro",
      description: err.response?.data?.message || "Erro ao excluir item",
      variant: "destructive",
    });
  } finally {
    isSubmitting.value = false;
  }
};

const restoreItem = async (item: IItem) => {
  try {
    isSubmitting.value = true;

    await itemService.restore(item.id);
    toast({
      title: "Sucesso",
      description: "Item restaurado com sucesso",
    });

    // Refresh the items list
    await fetchItems();
  } catch (err: any) {
    console.error("Error restoring item:", err);
    toast({
      title: "Erro",
      description: err.response?.data?.message || "Erro ao restaurar item",
      variant: "destructive",
    });
  } finally {
    isSubmitting.value = false;
  }
};

// Initialize
onMounted(() => {
  fetchItems();
  fetchCategories();
});
</script>
