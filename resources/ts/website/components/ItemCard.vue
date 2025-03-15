<template>
    <Card class="h-full hover:shadow-md transition-shadow">
        <CardHeader>
            <Badge :variant="statusColor">{{ statusLabel }}</Badge>
            <CardTitle class="mt-2 line-clamp-1">{{ item.title }}</CardTitle>
            <CardDescription class="line-clamp-2">{{
                item.description
            }}</CardDescription>
        </CardHeader>

        <CardContent>
            <div class="aspect-video w-full overflow-hidden rounded-md mb-3">
                <img
                    :src="imageUrl"
                    :alt="item.title"
                    class="h-full w-full object-cover"
                />
            </div>

            <div
                class="flex flex-wrap items-center gap-y-2 text-muted-foreground text-sm"
            >
                <div class="flex items-center mr-4">
                    <CalendarIcon class="mr-1 h-4 w-4" />
                    <span>{{ formattedDate }}</span>
                </div>
                <div class="flex items-center">
                    <MapPinIcon class="mr-1 h-4 w-4" />
                    <span>{{ item.location || "Local não informado" }}</span>
                </div>
            </div>
        </CardContent>

        <CardFooter>
            <Button variant="outline" class="w-full" @click="viewDetails"
                >Ver Detalhes</Button
            >
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

// Navigate to item details
const viewDetails = () => {
    const path = `/${props.item.status === "achado" ? "achados" : "perdidos"}/${
        props.item.id
    }`;
    router.push(path);
};
</script>
