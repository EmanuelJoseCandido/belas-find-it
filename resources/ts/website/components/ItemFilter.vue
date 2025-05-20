<template>
  <div class="bg-card rounded-lg border p-4">
    <h3 class="font-medium mb-4">Filtros</h3>

    <div class="space-y-4">
      <!-- Busca por palavra-chave -->
      <div>
        <label for="keyword" class="text-sm font-medium block mb-1"
          >Buscar</label
        >
        <input
          id="keyword"
          type="text"
          placeholder="Digite sua busca"
          class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
          v-model="filterData.keyword"
          @input="updateFilters"
        />
      </div>

      <!-- Categoria -->
      <div>
        <label for="category" class="text-sm font-medium block mb-1"
          >Categoria</label
        >
        <Select
          v-model="filterData.category"
          @update:model-value="updateFilters"
        >
          <SelectTrigger>
            <SelectValue placeholder="Todas as categorias" />
          </SelectTrigger>
          <SelectContent>
            <!-- Corrigido: Adicionamos uma opção para "Todas as categorias" com valor específico -->
            <SelectItem value="all">Todas as categorias</SelectItem>
            <SelectItem
              v-for="(category, index) in categories"
              :key="index"
              :value="category.id"
              >{{ category.name }}</SelectItem
            >
          </SelectContent>
        </Select>
      </div>

      <!-- Local -->
      <div>
        <label for="location" class="text-sm font-medium block mb-1"
          >Local</label
        >
        <input
          id="location"
          type="text"
          placeholder="Onde foi perdido"
          class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
          v-model="filterData.location"
          @input="updateFilters"
        />
      </div>

      <!-- Data -->
      <div>
        <label for="date" class="text-sm font-medium block mb-1">Data</label>
        <input
          id="date"
          type="date"
          class="w-full rounded-md border border-input bg-background px-3 py-2 text-sm"
          v-model="filterData.date"
          @change="updateFilters"
        />
      </div>

      <!-- Botões -->
      <div class="pt-2 flex gap-2">
        <Button variant="outline" class="flex-1" @click="resetFilters">
          Limpar
        </Button>
        <Button class="flex-1" @click="applyFilters"> Aplicar </Button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch, onMounted } from "vue";
import { useRoute } from "vue-router";
import { Button } from "@/ui/components/button";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/ui/components/select";
import { categoryService } from "@/services/categoryService";
import { ICategory } from "@/admin/types/ICategory";

const route = useRoute();
const loading = ref(true);
const categories = ref<ICategory[]>([]);

const filterData = ref({
  keyword: "",
  category: "all",
  location: "",
  date: "",
});

const emit = defineEmits(["filter"]);

const updateFilters = () => {
  emit("filter", {
    ...filterData.value,
    category:
      filterData.value.category === "all" ? "" : filterData.value.category,
  });
};

const applyFilters = () => {
  updateFilters();
};

const resetFilters = () => {
  filterData.value = {
    keyword: "",
    category: "all",
    location: "",
    date: "",
  };
  updateFilters();
};

const fetchCategories = async () => {
  try {
    loading.value = true;
    const response = await categoryService.getAll();
    categories.value = response.data.data || response.data;
  } catch (err: any) {
    console.error("Error fetching categories:", err);
  } finally {
    loading.value = false;
  }
};

onMounted(async () => {
  await fetchCategories();
  const queryKeyword = route.query.keyword as string;
  const queryCategory = route.query.category as string;
  const queryLocation = route.query.location as string;
  const queryDate = route.query.date as string;

  if (queryKeyword || queryCategory || queryLocation || queryDate) {
    filterData.value = {
      keyword: queryKeyword || "",
      category: queryCategory || "all",
      location: queryLocation || "",
      date: queryDate || "",
    };
  }
});
</script>
