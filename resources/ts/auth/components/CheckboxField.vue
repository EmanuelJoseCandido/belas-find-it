<template>
  <div>
    <div class="flex items-center space-x-2">
      <Checkbox :id="id" :name="name" v-model="isChecked" />
      <label
        :for="id"
        class="text-sm leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
      >
        <slot></slot>
      </label>
    </div>
    <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
import { Checkbox } from "@/ui/components/checkbox";
import { computed } from "vue";

const props = defineProps<{
  id: string;
  name: string;
  modelValue: boolean;
  error?: string;
}>();

const emit = defineEmits(["update:modelValue"]);

const isChecked = computed({
  get: () => props.modelValue,
  set: (value) => emit("update:modelValue", value),
});
</script>
