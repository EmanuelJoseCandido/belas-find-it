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
                        <SelectItem value="1">Documentos</SelectItem>
                        <SelectItem value="2">Eletrônicos</SelectItem>
                        <SelectItem value="3">Acessórios</SelectItem>
                        <SelectItem value="4">Joias</SelectItem>
                        <SelectItem value="5">Outros</SelectItem>
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
                <label for="date" class="text-sm font-medium block mb-1"
                    >Data</label
                >
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

const route = useRoute();

// Definir modelo de dados para filtros
const filterData = ref({
    keyword: "",
    // Corrigido: Inicializar com "all" em vez de string vazia
    category: "all",
    location: "",
    date: "",
});

// Emitir evento quando os filtros são atualizados
const emit = defineEmits(["filter"]);

// Atualizar filtros em tempo real (debounce poderia ser adicionado em produção)
const updateFilters = () => {
    emit("filter", {
        ...filterData.value,
        // Corrigido: Não enviar "all" como categoria, enviar string vazia para o componente pai
        // para manter a compatibilidade com a lógica existente
        category:
            filterData.value.category === "all"
                ? ""
                : filterData.value.category,
    });
};

// Aplicar filtros (para o botão Aplicar)
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

onMounted(() => {
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
