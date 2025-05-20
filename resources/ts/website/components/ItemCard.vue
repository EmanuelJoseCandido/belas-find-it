<template>
  <Card
    class="h-full overflow-hidden hover:shadow-md transition-shadow border border-border/40 group"
  >
    <!-- Status badge floating on image -->
    <div class="relative">
      <div class="aspect-video w-full overflow-hidden">
        <img
          :src="imageUrl"
          :alt="item.title"
          class="h-full w-full object-cover transform transition-transform group-hover:scale-105"
        />
      </div>
      <Badge
        :variant="statusColor"
        class="absolute top-3 right-3 shadow-md font-semibold py-1"
      >
        {{ statusLabel }}
      </Badge>
    </div>

    <CardHeader class="pb-2">
      <CardTitle class="line-clamp-1 text-lg">{{ item.title }}</CardTitle>
      <CardDescription class="line-clamp-2 text-sm">
        {{ item.description }}
      </CardDescription>
    </CardHeader>

    <CardContent class="pb-3">
      <div class="flex flex-col gap-2 text-muted-foreground text-sm">
        <div class="flex items-center">
          <CalendarIcon class="mr-2 h-4 w-4 text-primary/70" />
          <span>{{ formattedDate }}</span>
        </div>
        <div class="flex items-center overflow-hidden">
          <MapPinIcon class="mr-2 h-4 w-4 flex-shrink-0 text-primary/70" />
          <span class="truncate">{{
            item.location || "Local não informado"
          }}</span>
        </div>
      </div>
    </CardContent>

    <CardFooter class="pt-0">
      <Button
        variant="outline"
        class="w-full hover:bg-primary hover:text-primary-foreground transition-colors"
        @click="viewDetails"
      >
        Ver Detalhes
      </Button>
    </CardFooter>
  </Card>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { useRouter } from "vue-router";
import {
  Card,
  CardContent,
  CardFooter,
  CardHeader,
  CardTitle,
  CardDescription,
} from "@/ui/components/card";
import { Badge } from "@/ui/components/badge";
import { Button } from "@/ui/components/button";
import { CalendarIcon, MapPinIcon } from "lucide-vue-next";

interface ItemProps {
  item: {
    id: number;
    title: string;
    description: string;
    status: string;
    image?: string;
    location?: string;
    created_at: string;
    found_date?: string;
  };
}

const props = defineProps<ItemProps>();
const router = useRouter();

// Status colors and labels
const statusColor = computed(() => {
  switch (props.item.status) {
    case "perdido":
      return "destructive";
    case "achado":
      return "secondary";
    case "resgatado":
      return "default";
    default:
      return "outline";
  }
});

const statusLabel = computed(() => {
  switch (props.item.status) {
    case "perdido":
      return "Perdido";
    case "achado":
      return "Achado";
    case "resgatado":
      return "Resgatado";
    default:
      return props.item.status;
  }
});

// Format date
const formattedDate = computed(() => {
  const date = props.item.found_date || props.item.created_at;
  if (!date) return "Data não informada";

  try {
    return new Date(date).toLocaleDateString("pt-BR");
  } catch (e) {
    return date;
  }
});

// Image URL (fallback to placeholder if no image)
const imageUrl = computed(() => {
  return props.item.image || "/api/placeholder/400/300";
});

// O problema está aqui - corrigindo para usar uma rota direta em vez de route nesting
const viewDetails = () => {
  // Usar uma rota direta em vez de rotas aninhadas
  const path =
    props.item.status === "achado"
      ? `/achados/${props.item.id}`
      : `/perdidos/${props.item.id}`;

  router.push(path);
};
</script>
