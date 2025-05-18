<template>
  <div class="flex items-center justify-end gap-2">
    <Button
      v-if="showEdit && !item.deleted_at"
      variant="outline"
      size="sm"
      @click="$emit('edit', item)"
    >
      <EditIcon class="h-4 w-4 mr-2" />
      {{ editText }}
    </Button>
    <Button
      v-if="showDelete && !item.deleted_at"
      variant="outline"
      size="sm"
      @click="$emit('delete', item)"
    >
      <TrashIcon class="h-4 w-4 mr-2" />
      {{ deleteText }}
    </Button>
    <Button
      v-if="showRestore && item.deleted_at"
      variant="outline"
      size="sm"
      @click="$emit('restore', item)"
    >
      <RefreshCwIcon class="h-4 w-4 mr-2" />
      {{ restoreText }}
    </Button>
    <Button
      v-if="showForceDelete && item.deleted_at"
      variant="destructive"
      size="sm"
      @click="$emit('forceDelete', item)"
    >
      <Trash2Icon class="h-4 w-4 mr-2" />
      {{ forceDeleteText }}
    </Button>
    <slot></slot>
  </div>
</template>

<script setup lang="ts">
import { Button } from "@/ui/components/button";
import {
  Edit as EditIcon,
  Trash as TrashIcon,
  Trash2 as Trash2Icon,
  RefreshCw as RefreshCwIcon,
} from "lucide-vue-next";

const props = defineProps({
  item: {
    type: Object,
    required: true
  },
  showEdit: {
    type: Boolean,
    default: true
  },
  showDelete: {
    type: Boolean,
    default: true
  },
  showRestore: {
    type: Boolean,
    default: true
  },
  showForceDelete: {
    type: Boolean,
    default: true
  },
  editText: {
    type: String,
    default: 'Editar'
  },
  deleteText: {
    type: String,
    default: 'Excluir'
  },
  restoreText: {
    type: String,
    default: 'Restaurar'
  },
  forceDeleteText: {
    type: String,
    default: 'Excluir Perm.'
  }
});

defineEmits(['edit', 'delete', 'restore', 'forceDelete']);
</script>
