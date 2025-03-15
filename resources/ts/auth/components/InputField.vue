<template>
    <div class="mb-4">
        <Label :for="name" class="block text-sm font-medium text-gray-700">
            {{ label }}
        </Label>
        <Input
            :id="name"
            :name="name"
            :type="type"
            :placeholder="placeholder"
            :value="modelValue"
            @input="
                $emit(
                    'update:modelValue',
                    ($event.target as HTMLInputElement).value
                )
            "
            class="mt-1 block w-full"
            :class="{ 'border-red-500': error }"
        />
        <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    </div>
</template>

<script setup lang="ts">
import { Label } from "@/ui/components/label";
import { Input } from "@/ui/components/input";

defineProps<{
    name: string;
    label: string;
    modelValue: string;
    type?: string;
    placeholder?: string;
    error?: string;
}>();

defineEmits(["update:modelValue"]);
</script>
