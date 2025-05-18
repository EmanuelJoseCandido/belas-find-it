<template>
  <Alert variant="destructive" class="mb-6">
    <AlertCircleIcon class="h-4 w-4 mr-2" />
    <AlertTitle>Erro</AlertTitle>
    <AlertDescription>{{ message }}</AlertDescription>
  </Alert>
</template>

<script setup lang="ts">
import { Alert, AlertTitle, AlertDescription } from "@/ui/components/alert";
import { AlertCircle as AlertCircleIcon } from "lucide-vue-next";

const props = defineProps<{
  message: string;
}>();
</script>
