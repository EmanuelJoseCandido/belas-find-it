<template>
    <div class="mb-4">
        <Label :for="name" class="block text-sm font-medium text-gray-700">
            {{ label }}
        </Label>
        <Select v-model="selectedValue" @update:model-value="handleUpdate">
            <SelectTrigger
                :id="name"
                :name="name"
                :class="{ 'border-red-500 ring-red-500': error }"
            >
                <SelectValue :placeholder="placeholder" />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectItem
                        v-for="option in options"
                        :key="option.value"
                        :value="option.value"
                    >
                        {{ option.label }}
                    </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>
        <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from "vue";
import { Label } from "@/ui/components/label";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/ui/components/select";

interface Option {
    value: string;
    label: string;
}

const props = defineProps<{
    name: string;
    label: string;
    modelValue: string;
    options: Option[];
    placeholder?: string;
    error?: string;
}>();

const emit = defineEmits(["update:modelValue"]);

// Componente controlado: definir estado local
const selectedValue = ref(props.modelValue);

// Manter o valor do componente sincronizado com o v-model externo
watch(
    () => props.modelValue,
    (newValue) => {
        selectedValue.value = newValue;
    }
);

// Emitir evento quando o valor for alterado
const handleUpdate = (value: string) => {
    emit("update:modelValue", value);
};
</script>
