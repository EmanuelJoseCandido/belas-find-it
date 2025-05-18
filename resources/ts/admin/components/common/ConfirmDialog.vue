<template>
  <Dialog :open="props.modelValue" @update:open="updateModel">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>{{ props.title }}</DialogTitle>
        <DialogDescription>
          {{ props.description }}
        </DialogDescription>
      </DialogHeader>

      <DialogFooter>
        <Button v-if="props.cancelText" variant="outline" @click="cancel">
          {{ props.cancelText }}
        </Button>
        <Button
          :variant="props.confirmVariant"
          :disabled="props.loading"
          @click="confirm"
        >
          <Loader2 v-if="props.loading" class="h-4 w-4 mr-2 animate-spin" />
          {{ props.confirmText }}
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>

<script setup lang="ts">
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/ui/components/dialog";
import { Button } from "@/ui/components/button";
import { Loader2 } from "lucide-vue-next";

interface IProps {
  modelValue: boolean;
  title?: string;
  description?: string;
  confirmText?: string;
  cancelText?: string;
  confirmVariant?: "default" | "destructive" | "outline" | "secondary";
  loading?: boolean;
}

const props = defineProps<IProps>();

const emit = defineEmits<{
  (e: "update:modelValue", value: boolean): void;
  (e: "confirm"): void;
  (e: "cancel"): void;
}>();

const updateModel = (value: boolean) => {
  emit("update:modelValue", value);
};

const confirm = () => {
  emit("confirm");
};

const cancel = () => {
  emit("cancel");
  emit("update:modelValue", false);
};
</script>
