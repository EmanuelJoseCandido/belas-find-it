<template>
  <div>
    <!-- Loading -->
    <LoadingState v-if="props.loading" />

    <!-- Error -->
    <ErrorAlert v-else-if="props.error" :message="props.error" />

    <!-- Empty -->
    <EmptyState
      v-else-if="!props.loading && props.items.length === 0"
      :title="String(props.emptyStateTitle)"
      :description="String(props.emptyStateDescription)"
      :icon="props.emptyStateIcon"
      :button-text="props.emptyStateButtonText"
      @action="$emit('create')"
    />

    <!-- Table -->
    <div v-else class="rounded-md border bg-white dark:bg-gray-950">
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead
              v-for="column in props.columns"
              :key="column.key"
              :class="column.class"
            >
              {{ column.label }}
            </TableHead>
          </TableRow>
        </TableHeader>

        <TableBody>
          <TableRow v-for="item in props.items" :key="getItemKey(item)">
            <TableCell v-for="column in props.columns" :key="column.key">
              <slot :name="column.key" :item="item">
                {{ getItemValue(item, column.key) }}
              </slot>
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/ui/components/table";
import LoadingState from "./LoadingState.vue";
import ErrorAlert from "./ErrorAlert.vue";
import EmptyState from "./EmptyState.vue";

interface Column {
  key: string;
  label: string;
  class?: string;
}

interface IProps {
  items: any[];
  columns: Column[];
  loading?: boolean;
  error?: string | null;
  keyField?: string;
  emptyStateTitle?: string;
  emptyStateDescription?: string;
  emptyStateIcon?: any;
  emptyStateButtonText?: string;
}

const props = defineProps<IProps>();

const emit = defineEmits<{
  (e: "create"): void;
  (e: "edit", item: any): void;
  (e: "delete", item: any): void;
  (e: "restore", item: any): void;
  (e: "forceDelete", item: any): void;
}>();

const getItemKey = (item: any): string | number => {
  return item[props.keyField ?? "id"];
};

const getItemValue = (item: any, key: string): any => {
  if (key.includes(".")) {
    return key.split(".").reduce((acc, part) => acc?.[part], item) ?? "";
  }
  return item[key];
};
</script>
