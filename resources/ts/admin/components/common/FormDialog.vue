<template>
  <Dialog :open="modelValue" @update:open="updateModel">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>{{ title }}</DialogTitle>
        <DialogDescription>
          {{ description }}
        </DialogDescription>
      </DialogHeader>

      <Form
        ref="formRef"
        @submit="handleSubmit"
        :validation-schema="schema"
        :initial-values="formValues"
        v-slot="{
          errors: formErrors,
          isSubmitting: formSubmitting,
          resetForm: resetFormContext,
        }"
      >
        <slot :reset-form="resetFormContext" :errors="formErrors"></slot>

        <DialogFooter class="mt-6">
          <Button
            type="button"
            variant="outline"
            @click="handleCancel(resetFormContext)"
          >
            {{ cancelText }}
          </Button>
          <Button
            type="submit"
            :disabled="
              loading || formSubmitting || Object.keys(formErrors).length > 0
            "
          >
            <Loader2 v-if="loading" class="h-4 w-4 mr-2 animate-spin" />
            {{ submitText }}
          </Button>
        </DialogFooter>
      </Form>
    </DialogContent>
  </Dialog>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { Form, FormErrors } from "vee-validate";
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
  submitText?: string;
  cancelText?: string;
  schema: object;
  formValues: Record<string, any>;
  loading?: boolean;
}

const props = defineProps<IProps>();

const emit = defineEmits<{
  (e: "update:modelValue", value: boolean): void;
  (e: "submit", values: Record<string, any>): void;
  (e: "cancel"): void;
}>();

const formRef = ref(null);

const updateModel = (value: boolean) => {
  emit("update:modelValue", value);
};

const handleSubmit = (values: Record<string, any>) => {
  emit("submit", values);
};

const handleCancel = (resetForm: () => void) => {
  resetForm();
  emit("cancel");
  emit("update:modelValue", false);
};
</script>
