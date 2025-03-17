<template>
    <div>
        <div class="flex items-center space-x-2">
            <Checkbox
                :id="id"
                :name="name"
                :checked="modelValue"
                @update:checked="$emit('update:modelValue', $event)"
            />
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

defineProps<{
    id: string;
    name: string;
    modelValue: boolean;
    error?: string;
}>();

defineEmits(["update:modelValue"]);
</script>
