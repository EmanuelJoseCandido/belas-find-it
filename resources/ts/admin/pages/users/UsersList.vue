<template>
  <div>
    <div
      class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6"
    >
      <h2 class="text-2xl font-bold">Gerenciar Usuários</h2>

      <div class="flex flex-col sm:flex-row gap-4">
        <!-- Role Filter -->
        <div class="flex items-center space-x-2">
          <Select v-model="roleFilter" @update:modelValue="applyFilters">
            <SelectTrigger class="w-[180px]">
              <SelectValue placeholder="Filtrar por tipo" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="all">Todos os usuários</SelectItem>
              <SelectItem value="admin">Administradores</SelectItem>
              <SelectItem value="moderator">Moderadores</SelectItem>
              <SelectItem value="user">Usuários</SelectItem>
            </SelectContent>
          </Select>
        </div>

        <!-- Create User Button -->
        <Button @click="openCreateModal">
          <PlusIcon class="h-4 w-4 mr-2" />
          Novo Usuário
        </Button>
      </div>
    </div>

    <DataTable
      :items="filteredUsers"
      :columns="columns"
      :loading="loading"
      :error="error"
      :empty-state-title="'Nenhum usuário encontrado'"
      :empty-state-description="'Crie um novo usuário ou ajuste seus filtros para visualizar usuários.'"
      :empty-state-icon="UsersIcon"
      :empty-state-button-text="'Novo Usuário'"
      @create="openCreateModal"
    >
      <!-- Role column -->
      <template #role="{ item }">
        <Badge :variant="getRoleBadgeVariant(item.role)">
          {{ getRoleLabel(item.role) }}
        </Badge>
      </template>

      <!-- Active column -->
      <template #is_active="{ item }">
        <StatusBadge :value="!item.is_active" />
      </template>

      <!-- Email column -->
      <template #email="{ item }">
        <div class="truncate max-w-[200px]">
          {{ item.email }}
        </div>
      </template>

      <!-- Phone column -->
      <template #phone="{ item }">
        {{ item.phone || "—" }}
      </template>

      <template #created_at="{ item }">
        {{ timeAgo(item.created_at) }}
      </template>

      <template #updated_at="{ item }">
        {{ timeAgo(item.updated_at) }}
      </template>

      <!-- Actions column -->
      <template #actions="{ item }">
        <ActionButtons
          :item="item"
          @edit="openEditModal"
          @delete="confirmDelete"
          @restore="restoreUser"
          @forceDelete="confirmForceDelete"
          @active="confirmActiveUser"
          @inactive="confirmInactiveUser"
          :disabled="authStore.user?.id === item.id"
          :show-active="!item.is_active && !item.deleted_at"
          :show-inactive="item.is_active && !item.deleted_at"
        />
      </template>
    </DataTable>

    <!-- Form Dialog para Criar/Editar -->
    <FormDialog
      :key="formKey"
      v-model="showModal"
      :title="isEditing ? 'Editar Usuário' : 'Novo Usuário'"
      :description="
        isEditing
          ? 'Edite os detalhes do usuário existente.'
          : 'Preencha os campos para criar um novo usuário.'
      "
      :schema="userSchema"
      :form-values="formValues"
      :loading="isSubmitting"
      cancel-text="Cancelar"
      :submit-text="isEditing ? 'Salvar' : 'Criar'"
      @submit="saveUser"
      @cancel="handleCancelForm"
      class="sm:max-w-xl fle gap-y-4"
    >
      <!-- Nome -->
      <Field name="name" v-slot="{ field, errorMessage }">
        <div class="space-y-2">
          <Label for="name">Nome Completo</Label>
          <Input
            id="name"
            v-bind="field"
            placeholder="Digite o nome completo"

            :class="{ 'border-red-500': errorMessage }"
          />
          <p v-if="errorMessage" class="text-xs text-red-600">
            {{ errorMessage }}
          </p>
        </div>
      </Field>

      <!-- Email -->
      <Field name="email" v-slot="{ field, errorMessage }">
        <div class="space-y-2">
          <Label for="email">Email</Label>
          <Input
            id="email"
            v-bind="field"
            type="email"
            placeholder="email@exemplo.com"
            :class="{ 'border-red-500': errorMessage }"
          />
          <p v-if="errorMessage" class="text-xs text-red-600">
            {{ errorMessage }}
          </p>
        </div>
      </Field>

      <!-- Phone -->
      <Field name="phone" v-slot="{ field, errorMessage }">
        <div class="space-y-2">
          <Label for="phone">Telefone</Label>
          <Input
            id="phone"
            v-bind="field"
            placeholder="939 722 301"
            :class="{ 'border-red-500': errorMessage }"
          />
          <p v-if="errorMessage" class="text-xs text-red-600">
            {{ errorMessage }}
          </p>
        </div>
      </Field>

      <!-- Gender and Role fields - Side-by-side -->
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <!-- Gender -->
        <Field
          name="gender"
          v-slot="{ field, errorMessage, value, handleChange }"
        >
          <div class="space-y-2">
            <Label for="gender">Gênero</Label>
            <Select :value="value" @update:modelValue="handleChange">
              <SelectTrigger :class="{ 'border-red-500': errorMessage }">
                <SelectValue placeholder="Selecione um gênero" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="masculino">Masculino</SelectItem>
                <SelectItem value="feminino">Feminino</SelectItem>
                <SelectItem value="outro">Outro</SelectItem>
              </SelectContent>
            </Select>
            <p v-if="errorMessage" class="text-xs text-red-600">
              {{ errorMessage }}
            </p>
          </div>
        </Field>

        <!-- Role -->
        <Field
          name="role"
          v-slot="{ field, errorMessage, value, handleChange }"
        >
          <div class="space-y-2">
            <Label for="role">Tipo de Usuário</Label>
            <Select :value="value" @update:modelValue="handleChange">
              <SelectTrigger :class="{ 'border-red-500': errorMessage }">
                <SelectValue placeholder="Selecione um tipo" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="admin">Administrador</SelectItem>
                <SelectItem value="moderator">Moderador</SelectItem>
                <SelectItem value="user">Usuário</SelectItem>
              </SelectContent>
            </Select>
            <p v-if="errorMessage" class="text-xs text-red-600">
              {{ errorMessage }}
            </p>
          </div>
        </Field>
      </div>

      <!-- Password fields only for new users - Side-by-side -->
      <div v-if="!isEditing" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <Field name="password" v-slot="{ field, errorMessage }">
          <div class="space-y-2">
            <Label for="password">Senha</Label>
            <Input
              id="password"
              v-bind="field"
              type="password"
              placeholder="Digite a senha"
              :class="{ 'border-red-500': errorMessage }"
            />
            <p v-if="errorMessage" class="text-xs text-red-600">
              {{ errorMessage }}
            </p>
          </div>
        </Field>

        <Field name="password_confirmation" v-slot="{ field, errorMessage }">
          <div class="space-y-2">
            <Label for="password_confirmation">Confirmar Senha</Label>
            <Input
              id="password_confirmation"
              v-bind="field"
              type="password"
              placeholder="Confirme a senha"
              :class="{ 'border-red-500': errorMessage }"
            />
            <p v-if="errorMessage" class="text-xs text-red-600">
              {{ errorMessage }}
            </p>
          </div>
        </Field>
      </div>

      <div class="mt-6">
        <Field name="is_active" v-slot="{ field, errorMessage }">
          <div class="flex items-center justify-between space-y-2">
            <div>
              <Label for="is_active">Status</Label>
              <p class="text-sm text-muted-foreground">
                Definir o usuário como ativo ou inativo
              </p>
            </div>
            <Switch
              id="is_active"
              :checked="field.value"
              @update:checked="field.onChange"
              :class="{ 'border-red-500': errorMessage }"
            />
          </div>
          <p v-if="errorMessage" class="text-xs text-red-600">
            {{ errorMessage }}
          </p>
        </Field>
      </div>
    </FormDialog>

    <!-- Delete Confirmation Dialog -->
    <ConfirmDialog
      v-model="showDeleteModal"
      :title="'Confirmar Exclusão'"
      :description="getDeleteDescription()"
      :confirm-text="forceDelete ? 'Excluir Permanentemente' : 'Excluir'"
      :confirm-variant="'destructive'"
      :loading="isSubmitting"
      @confirm="deleteUser"
    />

    <!-- Active/Inactive Confirmation Dialog -->
    <ConfirmDialog
      v-model="showActiveModal"
      :title="
        activeAction === 'active' ? 'Desactivar Usuário' : 'Activar Usuário'
      "
      :description="getActiveDescription()"
      :confirm-text="activeAction === 'active' ? 'Desactivar' : 'Activar'"
      :confirm-variant="activeAction === 'active' ? 'destructive' : 'default'"
      :loading="isSubmitting"
      @confirm="toggleUserActive"
    />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { z } from "zod";
import { Field } from "vee-validate";
import { toFormValidator } from "@vee-validate/zod";
import { toast } from "@/ui/components/toast";
import { Button } from "@/ui/components/button";
import { Input } from "@/ui/components/input";
import { Label } from "@/ui/components/label";
import { Badge } from "@/ui/components/badge";
import { Switch } from "@/ui/components/switch";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/ui/components/select";
import { Users as UsersIcon, Plus as PlusIcon } from "lucide-vue-next";

import { useAuthStore } from "@/auth/stores/authStore";
import { userService } from "@/services/userService";

// Componentes reutilizáveis
import DataTable from "@/admin/components/common/DataTable.vue";
import FormDialog from "@/admin/components/common/FormDialog.vue";
import ConfirmDialog from "@/admin/components/common/ConfirmDialog.vue";
import ActionButtons from "@/admin/components/common/ActionButtons.vue";
import StatusBadge from "@/admin/components/common/StatusBadge.vue";

import { useDateFormat } from "@/composables/useDateFormat";

import { IUser } from "@/admin/types/IUser";
// Auth Store
const authStore = useAuthStore();
const { timeAgo } = useDateFormat();

// Definição das colunas
const columns = [
  { key: "id", label: "ID", class: "w-[80px]" },
  { key: "name", label: "Nome" },
  { key: "email", label: "Email" },
  { key: "role", label: "Tipo" },
  { key: "phone", label: "Telefone" },
  { key: "is_active", label: "Status" },
  { key: "created_at", label: "Criado em", format: "date" },
  { key: "updated_at", label: "Actualizado em", format: "date" },
  { key: "actions", label: "Ações", class: "text-right" },
];

// Estados
const formKey = ref(0);
const users = ref<IUser[]>([]);
const loading = ref<boolean>(true);
const error = ref<string | null>(null);
const showModal = ref<boolean>(false);
const showDeleteModal = ref<boolean>(false);
const showActiveModal = ref<boolean>(false);
const isEditing = ref<boolean>(false);
const isSubmitting = ref<boolean>(false);
const userToEdit = ref<IUser | null>(null);
const userToDelete = ref<IUser | null>(null);
const userToActive = ref<IUser | null>(null);
const forceDelete = ref<boolean>(false);
const activeAction = ref<"active" | "inactive">("active");
const roleFilter = ref<string>("all");

// Validação do formulário
const userSchema = toFormValidator(
  z
    .object({
      name: z
        .string({ required_error: "O nome é obrigatório" })
        .min(3, "O nome deve ter pelo menos 3 caracteres")
        .max(100, "O nome deve ter no máximo 100 caracteres"),
      email: z
        .string({ required_error: "O email é obrigatório" })
        .email("Email inválido"),
      phone: z
        .string({ required_error: "O telefone é obrigatório" })
        .min(9, "O telefone deve ter pelo menos 9 dígitos"),
      gender: z.enum(["masculino", "feminino", "outro"], {
        required_error: "O gênero é obrigatório",
      }),
      role: z.enum(["admin", "moderator", "user"], {
        required_error: "O tipo de usuário é obrigatório",
      }),
      is_active: z.boolean().default(true),
      password: z
        .string()
        .min(8, "A senha deve ter pelo menos 8 caracteres")
        .regex(
          /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)/,
          "A senha deve conter pelo menos uma letra maiúscula, uma minúscula e um número"
        )
        .optional(),
      password_confirmation: z.string().optional(),
    })
    .refine(
      (data) => {
        if (!data.password && !data.password_confirmation) return true;
        return data.password === data.password_confirmation;
      },
      {
        message: "As senhas não coincidem",
        path: ["password_confirmation"],
      }
    )
);

// Computed values
const formValues = computed(() => {
  if (isEditing.value && userToEdit.value) {
    return {
      name: userToEdit.value.name,
      email: userToEdit.value.email,
      phone: userToEdit.value.phone || "",
      gender: userToEdit.value.gender,
      role: userToEdit.value.role,
      is_active: !userToEdit.value.is_active,
    };
  }
  return {
    name: "",
    email: "",
    phone: "",
    gender: "",
    role: "user",
    password: "",
    password_confirmation: "",
  };
});

// Filtered users based on selected role
const filteredUsers = computed(() => {
  if (roleFilter.value === "all") {
    return users.value;
  }
  return users.value.filter((user) => user.role === roleFilter.value);
});

// Métodos
const fetchUsers = async () => {
  try {
    loading.value = true;
    error.value = null;
    const response = await userService.getAll();
    users.value = response.data.data || response.data;
  } catch (err: any) {
    console.error("Error fetching users:", err);
    error.value = err.message || "Erro ao carregar usuários";
    toast({
      title: "Erro",
      description: "Não foi possível carregar os usuários",
      variant: "destructive",
    });
  } finally {
    loading.value = false;
  }
};

const applyFilters = () => {
  // This function is called when filter values change
  // No need for additional implementation as we're using a computed property
};

const openCreateModal = () => {
  isEditing.value = false;
  formKey.value++;
  userToEdit.value = null;
  showModal.value = true;
};

import { useForm } from "vee-validate";

const { resetForm } = useForm();

const openEditModal = (user: IUser) => {
  isEditing.value = true;
  userToEdit.value = user;

  formKey.value++; // para forçar recriação do FormDialog

  showModal.value = true;

  setTimeout(() => {
    resetForm({
      values: {
        name: user.name,
        email: user.email,
        phone: user.phone || "",
        gender: user.gender,
        role: user.role,
      },
    });
  }, 100);
};

const handleCancelForm = () => {
  // Reset is handled by FormDialog component
};

const saveUser = async (values: Record<string, any>) => {
  try {
    isSubmitting.value = true;

    if (isEditing.value && userToEdit.value) {
      // Update existing user
      await userService.update(userToEdit.value.id, values);
      toast({
        title: "Sucesso",
        description: "Usuário atualizado com sucesso",
      });
    } else {
      // Create new user
      await userService.create(values);
      toast({
        title: "Sucesso",
        description: "Novo usuário criado com sucesso",
      });
    }

    // Refresh the users list
    await fetchUsers();

    // Close the modal
    showModal.value = false;
    isEditing.value = false;
    userToEdit.value = null;
  } catch (err: any) {
    console.error("Error saving user:", err);
    toast({
      title: "Erro",
      description: err.response?.data?.message || "Erro ao salvar usuário",
      variant: "destructive",
    });
  } finally {
    isSubmitting.value = false;
  }
};

const confirmDelete = (user: IUser) => {
  userToDelete.value = user;
  forceDelete.value = false;
  showDeleteModal.value = true;
};

const confirmForceDelete = (user: IUser) => {
  userToDelete.value = user;
  forceDelete.value = true;
  showDeleteModal.value = true;
};

const getDeleteDescription = () => {
  if (!userToDelete.value) return "";
  return `Tem certeza que deseja excluir o usuário "${
    userToDelete.value.name
  }"? ${
    forceDelete.value
      ? "Esta ação é permanente e não pode ser desfeita."
      : "O usuário pode ser restaurado posteriormente."
  }`;
};

const deleteUser = async () => {
  if (!userToDelete.value) return;

  try {
    isSubmitting.value = true;

    if (forceDelete.value) {
      // Permanently delete
      await userService.forceDelete(userToDelete.value.id);
      toast({
        title: "Sucesso",
        description: "Usuário excluído permanentemente",
      });
    } else {
      // Soft delete
      await userService.delete(userToDelete.value.id);
      toast({
        title: "Sucesso",
        description: "Usuário excluído com sucesso",
      });
    }

    // Refresh the users list
    await fetchUsers();

    // Close the confirmation modal
    showDeleteModal.value = false;
    userToDelete.value = null;
  } catch (err: any) {
    console.error("Error deleting user:", err);
    toast({
      title: "Erro",
      description: err.response?.data?.message || "Erro ao excluir usuário",
      variant: "destructive",
    });
  } finally {
    isSubmitting.value = false;
  }
};

const restoreUser = async (user: IUser) => {
  try {
    isSubmitting.value = true;

    await userService.restore(user.id);
    toast({
      title: "Sucesso",
      description: "Usuário restaurado com sucesso",
    });

    // Refresh the users list
    await fetchUsers();
  } catch (err: any) {
    console.error("Error restoring user:", err);
    toast({
      title: "Erro",
      description: err.response?.data?.message || "Erro ao restaurar usuário",
      variant: "destructive",
    });
  } finally {
    isSubmitting.value = false;
  }
};

// Active/Inactive user functionality
const confirmActiveUser = (user: IUser) => {
  userToActive.value = user;
  activeAction.value = "active";
  showActiveModal.value = true;
};

const confirmInactiveUser = (user: IUser) => {
  userToActive.value = user;
  activeAction.value = "inactive";
  showActiveModal.value = true;
};

const getActiveDescription = () => {
  if (!userToActive.value) return "";

  if (activeAction.value === "active") {
    return `Tem certeza que deseja desactivar o usuário "${userToActive.value.name}"? Usuários inactivos não poderão acessar o sistema.`;
  } else {
    return `Tem certeza que deseja activar o usuário "${userToActive.value.name}"? O usuário poderá acessar o sistema novamente.`;
  }
};

const toggleUserActive = async () => {
  if (!userToActive.value) return;

  try {
    isSubmitting.value = true;

    if (activeAction.value === "active") {
      await userService.active(userToActive.value.id);
      toast({
        title: "Sucesso",
        description: "Usuário bloqueado com sucesso",
      });
    } else {
      await userService.inactive(userToActive.value.id);
      toast({
        title: "Sucesso",
        description: "Usuário desbloqueado com sucesso",
      });
    }

    // Refresh the users list
    await fetchUsers();

    // Close the confirmation modal
    showActiveModal.value = false;
    userToActive.value = null;
  } catch (err: any) {
    console.error("Error toggling user active status:", err);
    toast({
      title: "Erro",
      description:
        err.response?.data?.message ||
        `Erro ao ${
          activeAction.value === "active" ? "bloquear" : "desbloquear"
        } usuário`,
      variant: "destructive",
    });
  } finally {
    isSubmitting.value = false;
  }
};

// Helper functions
const formatDate = (dateString: string): string => {
  try {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat("pt-BR", {
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
    }).format(date);
  } catch (e) {
    return dateString;
  }
};

const getRoleBadgeVariant = (
  role: string
): "default" | "secondary" | "destructive" | "outline" | null | undefined => {
  switch (role) {
    case "admin":
      return "destructive";
    case "moderator":
      return "secondary";
    case "user":
    default:
      return "default";
  }
};

const getRoleLabel = (role: string): string => {
  switch (role) {
    case "admin":
      return "Administrador";
    case "moderator":
      return "Moderador";
    case "user":
      return "Usuário";
    default:
      return role;
  }
};

// Initialize
onMounted(() => {
  fetchUsers();
});
</script>
