<template>
  <Badge :variant="variant">{{ label }}</Badge>
</template>

<script setup lang="ts">
import { computed, ref } from "vue";
import { Badge } from "@/ui/components/badge";

interface IProps {
  value: boolean;
}

type TVariant =
  | "default"
  | "secondary"
  | "destructive"
  | "outline"
  | null
  | undefined;

const props = defineProps<IProps>();

const variant = ref<TVariant>("default");

const label = computed(() => {
  const value = props.value;
  variant.value = value ? "default" : "outline";
  return value ? "Activo" : "Inactivo";
});
</script>
