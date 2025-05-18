<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl font-bold">Gerenciar Categorias</h2>
      <Button @click="openCreateModal">
        <PlusIcon class="h-4 w-4 mr-2" />
        Nova Categoria
      </Button>
    </div>

    <DataTable
      :items="categories"
      :columns="columns"
      :loading="loading"
      :error="error"
      :empty-state-title="'Nenhuma categoria encontrada'"
      :empty-state-description="'Crie uma nova categoria para começar a organizar seus itens.'"
      :empty-state-icon="FolderIcon"
      :empty-state-button-text="'Nova Categoria'"
      @create="openCreateModal"
    >
      <!-- Slot para coluna de status -->
      <template #status="{ item }">
        <StatusBadge :status="!item.deleted_at" />
      </template>

      <!-- Slot para coluna de quantidade de itens -->
      <template #items_count="{ item }">
        {{ item.items?.length || 0 }}
      </template>

      <!-- Slot para coluna de ações -->
      <template #actions="{ item }">
        <ActionButtons
          :item="item"
          @edit="openEditModal"
          @delete="confirmDelete"
          @restore="restoreCategory"
          @forceDelete="confirmForceDelete"
        />
      </template>
    </DataTable>

    <!-- Form Dialog para Criar/Editar -->
    <FormDialog
      :key="formKey"
      v-model="showModal"
      :title="isEditing ? 'Editar Categoria' : 'Nova Categoria'"
      :description="
        isEditing
          ? 'Edite os detalhes da categoria existente.'
          : 'Preencha os campos para criar uma nova categoria.'
      "
      :schema="categorySchema"
      :form-values="formValues"
      :loading="isSubmitting"
      cancel-text="Cancelar"
      :submit-text="isEditing ? 'Salvar' : 'Criar'"
      @submit="saveCategory"
      @cancel="handleCancelForm"
    >
      <Field name="name" v-slot="{ field, errorMessage }">
        <div class="space-y-2">
          <Label for="name">Nome da Categoria</Label>
          <Input
            id="name"
            v-bind="field"
            placeholder="Digite o nome da categoria"
            :class="{ 'border-red-500': errorMessage }"
          />
          <p v-if="errorMessage" class="text-xs text-red-600">
            {{ errorMessage }}
          </p>
        </div>
      </Field>
    </FormDialog>

    <!-- Delete Confirmation Dialog -->
    <ConfirmDialog
      v-model="showDeleteModal"
      :title="'Confirmar Exclusão'"
      :description="getDeleteDescription()"
      :confirm-text="forceDelete ? 'Excluir Permanentemente' : 'Excluir'"
      :loading="isSubmitting"
      @confirm="deleteCategory"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { z } from "zod";
import { Field, FieldProps, Form } from "vee-validate";
import { toFormValidator } from "@vee-validate/zod";
import { toast } from "@/ui/components/toast";
import { Button } from "@/ui/components/button";
import { Input } from "@/ui/components/input";
import { Label } from "@/ui/components/label";
import { Plus as PlusIcon, Folder as FolderIcon } from "lucide-vue-next";

import { categoryService } from "@/services/categoryService";

// Componentes reutilizáveis
import DataTable from "@/admin/components/common/DataTable.vue";
import FormDialog from "@/admin/components/common/FormDialog.vue";
import ConfirmDialog from "@/admin/components/common/ConfirmDialog.vue";
import ActionButtons from "@/admin/components/common/ActionButtons.vue";
import StatusBadge from "@/admin/components/common/StatusBadge.vue";

// Definição das colunas
const columns = [
  { key: "id", label: "ID", class: "w-[80px]" },
  { key: "name", label: "Nome" },
  { key: "status", label: "Status" },
  { key: "items_count", label: "Qtd. Itens" },
  { key: "actions", label: "Ações", class: "text-right" },
];

// Tipagem das categorias e outros estados
interface Category {
  id: number;
  name: string;
  deleted_at: string | null;
  items: any[];
}
const formKey = ref(0);
const categories = ref<Category[]>([]);
const loading = ref<boolean>(true);
const error = ref<string | null>(null);
const showModal = ref<boolean>(false);
const showDeleteModal = ref<boolean>(false);
const isEditing = ref<boolean>(false);
const isSubmitting = ref<boolean>(false);
const categoryToEdit = ref<Category | null>(null);
const categoryToDelete = ref<Category | null>(null);
const forceDelete = ref<boolean>(false);

// Validação do formulário
const categorySchema = toFormValidator(
  z.object({
    name: z
      .string({ required_error: "O nome é obrigatório" })
      .min(3, "O nome deve ter pelo menos 3 caracteres")
      .max(50, "O nome deve ter no máximo 50 caracteres"),
  })
);

// Computed values
const formValues = computed(() => {
  if (isEditing.value && categoryToEdit.value) {
    return {
      name: categoryToEdit.value.name,
    };
  }
  return {
    name: "",
  };
});

// Métodos
const fetchCategories = async () => {
  try {
    loading.value = true;
    error.value = null;
    const response = await categoryService.getAll();
    categories.value = response.data.data || response.data;
  } catch (err: any) {
    console.error("Error fetching categories:", err);
    error.value = err.message || "Erro ao carregar categorias";
    toast({
      title: "Erro",
      description: "Não foi possível carregar as categorias",
      variant: "destructive",
    });
  } finally {
    loading.value = false;
  }
};

const openCreateModal = () => {
  isEditing.value = false;
  formKey.value++;
  categoryToEdit.value = null;
  showModal.value = true;
};

// Adicione esta função
const resetFormWithValues = () => {
  // Forçar uma atualização do formulário após o modal abrir
  setTimeout(() => {
    if (showModal.value) {
      // Aqui usamos o DOM para resetar os valores dos inputs
      const nameInput = document.getElementById("name");
      if (nameInput && categoryToEdit.value) {
        (nameInput as HTMLInputElement).value = categoryToEdit.value.name;

        // Dispara um evento de input para o VeeValidate atualizar seu estado interno
        const event = new Event("input", { bubbles: true });
        nameInput.dispatchEvent(event);
      }
    }
  }, 100);
};

// Modifique a função openEditModal
const openEditModal = (category: Category) => {
  isEditing.value = true;
  categoryToEdit.value = category;
  formKey.value++;
  showModal.value = true;
  resetFormWithValues();
};

const handleCancelForm = () => {
  // Reset the form is handled by the FormDialog component
};

const saveCategory = async (values: { name: string }) => {
  try {
    isSubmitting.value = true;

    if (isEditing.value && categoryToEdit.value) {
      // Update existing category
      await categoryService.update(categoryToEdit.value.id, values);
      toast({
        title: "Sucesso",
        description: "Categoria atualizada com sucesso",
      });
    } else {
      // Create new category
      await categoryService.create(values);
      toast({
        title: "Sucesso",
        description: "Nova categoria criada com sucesso",
      });
    }

    // Refresh the categories list
    await fetchCategories();

    // Close the modal
    showModal.value = false;
    isEditing.value = false;
    categoryToEdit.value = null;
  } catch (err: any) {
    console.error("Error saving category:", err);
    toast({
      title: "Erro",
      description: err.response?.data?.message || "Erro ao salvar categoria",
      variant: "destructive",
    });
  } finally {
    isSubmitting.value = false;
  }
};

const confirmDelete = (category: Category) => {
  categoryToDelete.value = category;
  forceDelete.value = false;
  showDeleteModal.value = true;
};

const confirmForceDelete = (category: Category) => {
  categoryToDelete.value = category;
  forceDelete.value = true;
  showDeleteModal.value = true;
};

const getDeleteDescription = () => {
  if (!categoryToDelete.value) return "";
  return `Tem certeza que deseja excluir a categoria "${
    categoryToDelete.value.name
  }"? ${
    forceDelete.value
      ? "Esta ação é permanente e não pode ser desfeita."
      : "A categoria pode ser restaurada posteriormente."
  }`;
};

const deleteCategory = async () => {
  if (!categoryToDelete.value) return;

  try {
    isSubmitting.value = true;

    if (forceDelete.value) {
      // Permanently delete
      await categoryService.forceDelete(categoryToDelete.value.id);
      toast({
        title: "Sucesso",
        description: "Categoria excluída permanentemente",
      });
    } else {
      // Soft delete
      await categoryService.delete(categoryToDelete.value.id);
      toast({
        title: "Sucesso",
        description: "Categoria excluída com sucesso",
      });
    }

    // Refresh the categories list
    await fetchCategories();

    // Close the confirmation modal
    showDeleteModal.value = false;
    categoryToDelete.value = null;
  } catch (err: any) {
    console.error("Error deleting category:", err);
    toast({
      title: "Erro",
      description: err.response?.data?.message || "Erro ao excluir categoria",
      variant: "destructive",
    });
  } finally {
    isSubmitting.value = false;
  }
};

const restoreCategory = async (category: Category) => {
  try {
    isSubmitting.value = true;

    await categoryService.restore(category.id);
    toast({
      title: "Sucesso",
      description: "Categoria restaurada com sucesso",
    });

    // Refresh the categories list
    await fetchCategories();
  } catch (err: any) {
    console.error("Error restoring category:", err);
    toast({
      title: "Erro",
      description: err.response?.data?.message || "Erro ao restaurar categoria",
      variant: "destructive",
    });
  } finally {
    isSubmitting.value = false;
  }
};

// Initialize
onMounted(() => {
  fetchCategories();
});
</script>
