<template>
    <div class="bg-white p-4 rounded-lg shadow-sm border">
        <h3 class="font-medium text-lg mb-4">Filtros</h3>

        <!-- Busca por palavra-chave -->
        <div class="mb-4">
            <Label for="search" class="mb-1 block">Buscar</Label>
            <div class="relative">
                <Search
                    class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground"
                />
                <Input
                    id="search"
                    v-model="filters.keyword"
                    placeholder="Buscar item..."
                    class="pl-9"
                    @input="handleInputDebounce"
                />
            </div>
        </div>

        <!-- Filtro por categoria -->
        <div class="mb-4">
            <Label for="category" class="mb-1 block">Categoria</Label>
            <Select
                v-model="filters.category"
                @update:model-value="emitFilters"
            >
                <SelectTrigger id="category">
                    <SelectValue placeholder="Todas as categorias" />
                </SelectTrigger>
                <SelectContent>
                    <SelectItem value="">Todas as categorias</SelectItem>
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

        <!-- Filtro por localização -->
        <div class="mb-4">
            <Label for="location" class="mb-1 block">Localização</Label>
            <Input
                id="location"
                v-model="filters.location"
                placeholder="Qualquer local"
                @input="handleInputDebounce"
            />
        </div>

        <!-- Filtro por data -->
        <div class="mb-4">
            <Label for="date" class="mb-1 block">Data</Label>
            <Input
                id="date"
                v-model="filters.date"
                type="date"
                @change="emitFilters"
            />
        </div>

        <!-- Botões -->
        <div class="flex flex-col gap-2 mt-6">
            <Button @click="emitFilters">Aplicar Filtros</Button>
            <Button variant="outline" @click="resetFilters"
                >Limpar Filtros</Button
            >
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";
import { Button } from "@/ui/components/button";
import { Input } from "@/ui/components/input";
import { Label } from "@/ui/components/label";
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/ui/components/select";
import { Search } from "lucide-vue-next";

// Interface para categorias
interface Category {
    id: number;
    name: string;
}

// Props e Emits
const props = defineProps<{
    initialFilters?: {
        keyword?: string;
        category?: string;
        location?: string;
        date?: string;
    };
}>();

const emit = defineEmits<{
    (
        e: "filter",
        filters: {
            keyword: string;
            category: string;
            location: string;
            date: string;
        }
    ): void;
}>();

// Estado
const categories = ref<Category[]>([]);
const debounceTimeout = ref<number | null>(null);

// Filtros
const filters = reactive({
    keyword: props.initialFilters?.keyword || "",
    category: props.initialFilters?.category || "",
    location: props.initialFilters?.location || "",
    date: props.initialFilters?.date || "",
});

// Buscar categorias
const fetchCategories = async () => {
    try {
        // Em um ambiente real, buscaríamos da API
        // const response = await fetch('/api/categories');
        // categories.value = await response.json();

        // Dados de exemplo por enquanto
        categories.value = [
            { id: 1, name: "Documentos" },
            { id: 2, name: "Eletrônicos" },
            { id: 3, name: "Acessórios" },
            { id: 4, name: "Vestuário" },
            { id: 5, name: "Outros" },
        ];
    } catch (error) {
        console.error("Erro ao buscar categorias:", error);
    }
};

// Função para emitir filtros
const emitFilters = () => {
    emit("filter", { ...filters });
};

// Função para resetar filtros
const resetFilters = () => {
    filters.keyword = "";
    filters.category = "";
    filters.location = "";
    filters.date = "";
    emitFilters();
};

// Controle de debounce para inputs de texto
const handleInputDebounce = () => {
    if (debounceTimeout.value) {
        clearTimeout(debounceTimeout.value);
    }

    debounceTimeout.value = setTimeout(() => {
        emitFilters();
    }, 500);
};

// Hooks
onMounted(() => {
    fetchCategories();
});
</script>
