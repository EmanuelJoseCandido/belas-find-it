<template>
  <Badge :variant="variant" class="flex items-center gap-1.5">
    <component v-if="showIcon" :is="currentIcon" class="h-3.5 w-3.5" />
    {{ label }}
  </Badge>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { Badge } from "@/ui/components/badge";
import { Lock as LockIcon, Unlock as UnlockIcon } from "lucide-vue-next";

interface CustomStatus {
  active: boolean;
  label: string;
  variant: string;
  icon?: any;
}

interface CustomStatuses {
  [key: string]: CustomStatus;
}

const props = defineProps({
  isLogin: {
    type: [Boolean, String],
    required: true,
  },
  blockLabel: {
    type: String,
    default: "Bloqueado",
  },
  unblockLabel: {
    type: String,
    default: "Desbloqueado",
  },
  activeVariant: {
    type: String,
    default: "default",
  },
  inactiveVariant: {
    type: String,
    default: "destructive",
  },
  customStatuses: {
    type: Object as () => CustomStatuses,
    default: () => ({}),
  },
  showIcon: {
    type: Boolean,
    default: true,
  },
  blockedIcon: {
    type: Object,
    default: () => LockIcon,
  },
  unblockedIcon: {
    type: Object,
    default: () => UnlockIcon,
  },
});

const isActive = computed(() => {
  if (typeof props.isLogin === "boolean") {
    return props.isLogin;
  } else if (props.isLogin === "active" || props.isLogin === "ativo") {
    return true;
  } else if (
    typeof props.isLogin === "string" &&
    props.isLogin in props.customStatuses
  ) {
    return props.customStatuses[props.isLogin].active;
  }
  return !props.isLogin;
});

const label = computed(() => {
  if (
    typeof props.isLogin === "string" &&
    props.isLogin in props.customStatuses
  ) {
    return props.customStatuses[props.isLogin].label;
  }
  return isActive.value ? props.unblockLabel : props.blockLabel;
});

const variant = computed(() => {
  if (
    typeof props.isLogin === "string" &&
    props.isLogin in props.customStatuses
  ) {
    return props.customStatuses[props.isLogin].variant;
  }
  return isActive.value ? props.activeVariant : props.inactiveVariant;
});

const currentIcon = computed(() => {
  if (
    typeof props.isLogin === "string" &&
    props.isLogin in props.customStatuses &&
    props.customStatuses[props.isLogin].icon
  ) {
    return props.customStatuses[props.isLogin].icon;
  }
  return isActive.value ? props.unblockedIcon : props.blockedIcon;
});
</script>
