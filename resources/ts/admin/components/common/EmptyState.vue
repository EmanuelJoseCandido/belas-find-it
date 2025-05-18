<template>
  <Card>
    <CardContent class="p-6 text-center">
      <component
        :is="icon"
        class="h-12 w-12 mx-auto text-muted-foreground mb-4"
        v-if="icon"
      />
      <h3 class="text-lg font-medium mb-2">{{ title }}</h3>
      <p class="text-muted-foreground mb-4">{{ description }}</p>
      <Button v-if="buttonText" @click="$emit('action')">
        <PlusIcon v-if="showPlusIcon" class="h-4 w-4 mr-2" />
        {{ buttonText }}
      </Button>
    </CardContent>
  </Card>
</template>

<script setup lang="ts">
import { Card, CardContent } from "@/ui/components/card";
import { Button } from "@/ui/components/button";
import { Plus as PlusIcon } from "lucide-vue-next";

interface IProps {
  title: string;
  description: string;
  icon: any;
  buttonText?: string;
  showPlusIcon?: boolean;
}

const props = defineProps<IProps>();

defineEmits(["action"]);
</script>
