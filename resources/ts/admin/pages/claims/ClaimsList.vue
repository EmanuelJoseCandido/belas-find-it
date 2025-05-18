<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h2 class="text-2xl font-bold">Gerenciar Reclamações</h2>
    </div>

    <DataTable
      :items="claims"
      :columns="columns"
      :loading="loading"
      :error="error"
      :empty-state-title="'Nenhuma reclamação encontrada'"
      :empty-state-description="'Ainda não há reclamações de itens no sistema.'"
      :empty-state-icon="ClipboardIcon"
    >
      <!-- Slot para coluna de status -->
      <template #status="{ item }">
        <Badge :variant="getStatusVariant(item.status)">
          {{ getStatusLabel(item.status) }}
        </Badge>
      </template>

      <!-- Slot para coluna de item -->
      <template #item="{ item }">
        {{ item.item?.title || "Item não disponível" }}
      </template>

      <!-- Slot para coluna de usuário -->
      <template #user="{ item }">
        {{ item.user?.name || "Usuário não disponível" }}
      </template>

      <!-- Slot para coluna de ações -->
      <template #actions="{ item }">
        <ActionButtons
          :item="item"
          @edit="openEditModal"
          @delete="confirmDelete"
          @restore="restoreClaim"
          @forceDelete="confirmForceDelete"
        />
      </template>
    </DataTable>

    <!-- Form Dialog para Editar -->
    <FormDialog
      :key="formKey"
      v-model="showModal"
      title="Atualizar Reivindicação"
      description="Atualize o status e a mensagem para esta reclamação."
      :schema="claimSchema"
      :form-values="formValues"
      :loading="isSubmitting"
      cancel-text="Cancelar"
      submit-text="Salvar"
      @submit="saveClaim"
      @cancel="handleCancelForm"
    >
      <Field
        name="status"
        v-slot="{ field, errorMessage, value, handleChange }"
      >
        <div class="space-y-2">
          <Label for="status">Status</Label>
          <Select
            :id="field.name"
            :value="value"
            @update:modelValue="handleChange"
          >
            <SelectTrigger :class="{ 'border-red-500': errorMessage }">
              <SelectValue placeholder="Selecione um status" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="pendente">Pendente</SelectItem>
              <SelectItem value="aprovado">Aprovado</SelectItem>
              <SelectItem value="rejeitado">Rejeitado</SelectItem>
            </SelectContent>
          </Select>
          <p v-if="errorMessage" class="text-xs text-red-600">
            {{ errorMessage }}
          </p>
        </div>
      </Field>

      <Field name="message" v-slot="{ field, errorMessage }">
        <div class="space-y-2 mt-4">
          <Label for="message">Mensagem (opcional)</Label>
          <Textarea
            id="message"
            v-bind="field"
            placeholder="Adicione uma observação para esta reclamação"
            rows="3"
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
      @confirm="deleteClaim"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { z } from "zod";
import { Field } from "vee-validate";
import { toFormValidator } from "@vee-validate/zod";
import { toast } from "@/ui/components/toast";
import { Badge } from "@/ui/components/badge";
import { Label } from "@/ui/components/label";
import { Textarea } from "@/ui/components/textarea";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/ui/components/select";
import { Clipboard as ClipboardIcon } from "lucide-vue-next";

import { claimService } from "@/services/claimService";

// Componentes reutilizáveis
import DataTable from "@/admin/components/common/DataTable.vue";
import FormDialog from "@/admin/components/common/FormDialog.vue";
import ConfirmDialog from "@/admin/components/common/ConfirmDialog.vue";
import ActionButtons from "@/admin/components/common/ActionButtons.vue";

import { IClaim, TClaimStatus } from "@/admin/types/IClaim";

// Definição das colunas
const columns = [
  { key: "id", label: "ID", class: "w-[80px]" },
  { key: "item", label: "Item" },
  { key: "user", label: "Solicitante" },
  { key: "status", label: "Status" },
  { key: "created_at", label: "Data", format: "date" },
  { key: "actions", label: "Ações", class: "text-right" },
];

const formKey = ref(0);
const claims = ref<IClaim[]>([]);
const loading = ref<boolean>(true);
const error = ref<string | null>(null);
const showModal = ref<boolean>(false);
const showDeleteModal = ref<boolean>(false);
const isSubmitting = ref<boolean>(false);
const claimToEdit = ref<IClaim | null>(null);
const claimToDelete = ref<IClaim | null>(null);
const forceDelete = ref<boolean>(false);

// Validação do formulário
const claimSchema = toFormValidator(
  z.object({
    status: z.enum(["pendente", "aprovado", "rejeitado"] as const, {
      required_error: "O status é obrigatório",
    }),
    message: z.string().nullable().optional(),
  })
);

// Computed values
const formValues = computed(() => {
  if (claimToEdit.value) {
    return {
      status: claimToEdit.value.status,
      message: claimToEdit.value.message || "",
    };
  }
  return {
    status: "pendente" as TClaimStatus,
    message: "",
  };
});

// Métodos
const fetchClaims = async () => {
  try {
    loading.value = true;
    error.value = null;
    const response = await claimService.getAll();
    claims.value = response.data.data || response.data;
  } catch (err: any) {
    console.error("Error fetching claims:", err);
    error.value = err.message || "Erro ao carregar reclamações";
    toast({
      title: "Erro",
      description: "Não foi possível carregar as reclamações",
      variant: "destructive",
    });
  } finally {
    loading.value = false;
  }
};

const getStatusVariant = (status: TClaimStatus) => {
  switch (status) {
    case "aprovado":
      return "default";
    case "rejeitado":
      return "destructive";
    case "pendente":
    default:
      return "secondary";
  }
};

const getStatusLabel = (status: TClaimStatus) => {
  switch (status) {
    case "aprovado":
      return "Aprovado";
    case "rejeitado":
      return "Rejeitado";
    case "pendente":
    default:
      return "Pendente";
  }
};

const resetFormWithValues = () => {
  // Forçar uma atualização do formulário após o modal abrir
  setTimeout(() => {
    if (showModal.value) {
      // Aqui usamos o DOM para resetar os valores dos inputs
      const statusSelect = document.getElementById("status");
      const messageTextarea = document.getElementById("message");

      if (statusSelect && messageTextarea && claimToEdit.value) {
        // Dispara eventos para o VeeValidate atualizar seu estado interno
        const event = new Event("input", { bubbles: true });

        if (messageTextarea) {
          (messageTextarea as HTMLTextAreaElement).value =
            claimToEdit.value.message || "";
          messageTextarea.dispatchEvent(event);
        }
      }
    }
  }, 100);
};

const openEditModal = (claim: IClaim) => {
  claimToEdit.value = claim;
  formKey.value++;
  showModal.value = true;
  resetFormWithValues();
};

const handleCancelForm = () => {
  // Reset the form is handled by the FormDialog component
};

const saveClaim = async (values: Record<string, any>) => {
  try {
    isSubmitting.value = true;

    if (claimToEdit.value) {
      // Update existing claim
      await claimService.update(claimToEdit.value.id, values);
      toast({
        title: "Sucesso",
        description: "Reivindicação atualizada com sucesso",
      });
    }

    // Refresh the claims list
    await fetchClaims();

    // Close the modal
    showModal.value = false;
    claimToEdit.value = null;
  } catch (err: any) {
    console.error("Error saving claim:", err);
    toast({
      title: "Erro",
      description: err.response?.data?.message || "Erro ao salvar reclamação",
      variant: "destructive",
    });
  } finally {
    isSubmitting.value = false;
  }
};

const confirmDelete = (claim: IClaim) => {
  claimToDelete.value = claim;
  forceDelete.value = false;
  showDeleteModal.value = true;
};

const confirmForceDelete = (claim: IClaim) => {
  claimToDelete.value = claim;
  forceDelete.value = true;
  showDeleteModal.value = true;
};

const getDeleteDescription = () => {
  if (!claimToDelete.value) return "";

  const itemName = claimToDelete.value.item?.title || "item não encontrado";
  const userName = claimToDelete.value?.user?.name || "usuário não encontrado";

  return `Tem certeza que deseja excluir a reclamação do item "${itemName}" feita por "${userName}"? ${
    forceDelete.value
      ? "Esta ação é permanente e não pode ser desfeita."
      : "A reclamação pode ser restaurada posteriormente."
  }`;
};

const deleteClaim = async () => {
  if (!claimToDelete.value) return;

  try {
    isSubmitting.value = true;

    if (forceDelete.value) {
      // Permanently delete
      await claimService.forceDelete(claimToDelete.value.id);
      toast({
        title: "Sucesso",
        description: "Reivindicação excluída permanentemente",
      });
    } else {
      // Soft delete
      await claimService.delete(claimToDelete.value.id);
      toast({
        title: "Sucesso",
        description: "Reivindicação excluída com sucesso",
      });
    }

    // Refresh the claims list
    await fetchClaims();

    // Close the confirmation modal
    showDeleteModal.value = false;
    claimToDelete.value = null;
  } catch (err: any) {
    console.error("Error deleting claim:", err);
    toast({
      title: "Erro",
      description: err.response?.data?.message || "Erro ao excluir reclamação",
      variant: "destructive",
    });
  } finally {
    isSubmitting.value = false;
  }
};

const restoreClaim = async (claim: IClaim) => {
  try {
    isSubmitting.value = true;

    await claimService.restore(claim.id);
    toast({
      title: "Sucesso",
      description: "Reivindicação restaurada com sucesso",
    });

    // Refresh the claims list
    await fetchClaims();
  } catch (err: any) {
    console.error("Error restoring claim:", err);
    toast({
      title: "Erro",
      description:
        err.response?.data?.message || "Erro ao restaurar reclamação",
      variant: "destructive",
    });
  } finally {
    isSubmitting.value = false;
  }
};

// Initialize
onMounted(() => {
  fetchClaims();
});
</script>
