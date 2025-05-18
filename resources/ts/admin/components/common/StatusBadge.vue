<template>
  <Badge :variant="variant">{{ label }}</Badge>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { Badge } from "@/ui/components/badge";

const props = defineProps({
  status: {
    type: [Boolean, String],
    required: true,
  },
  activeLabel: {
    type: String,
    default: "Ativo",
  },
  inactiveLabel: {
    type: String,
    default: "Inativo",
  },
  activeVariant: {
    type: String,
    default: "default",
  },
  inactiveVariant: {
    type: String,
    default: "outline",
  },
  customStatuses: {
    type: Object,
    default: () => ({}),
  },
});

const isActive = computed(() => {
  if (typeof props.status === "boolean") {
    return props.status;
  } else if (props.status === "active" || props.status === "ativo") {
    return true;
  } else if (
    typeof props.status === "string" &&
    props.status in props.customStatuses
  ) {
    return props.customStatuses[props.status].active;
  }
  return !props.status;
});

const label = computed(() => {
  if (
    typeof props.status === "string" &&
    props.status in props.customStatuses
  ) {
    return props.customStatuses[props.status].label;
  }
  return isActive.value ? props.activeLabel : props.inactiveLabel;
});

const variant = computed(() => {
  if (
    typeof props.status === "string" &&
    props.status in props.customStatuses
  ) {
    return props.customStatuses[props.status].variant;
  }
  return isActive.value ? props.activeVariant : props.inactiveVariant;
});
</script>
