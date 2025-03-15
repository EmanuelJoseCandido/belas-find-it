<template>
    <WebsiteLayout>
        <div class="container py-8">
            <!-- Navegação de volta -->
            <div class="mb-6">
                <Button
                    variant="outline"
                    class="flex gap-2 items-center"
                    @click="goBack"
                >
                    <ArrowLeft class="h-4 w-4" />
                    <span>Voltar</span>
                </Button>
            </div>

            <!-- Carregando -->
            <div v-if="isLoading" class="py-12 flex justify-center">
                <div
                    class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"
                ></div>
            </div>

            <!-- Erro -->
            <div v-else-if="error" class="py-12 text-center">
                <AlertTriangleIcon
                    class="mx-auto h-12 w-12 text-destructive mb-4"
                />
                <h3 class="text-lg font-medium mb-1">Erro ao carregar item</h3>
                <p class="text-muted-foreground mb-4">{{ error }}</p>
                <Button @click="fetchItem">Tentar Novamente</Button>
            </div>

            <!-- Conteúdo do Item -->
            <div v-else-if="item" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Imagem e Informações -->
                <div class="md:col-span-2 space-y-6">
                    <!-- Status Badge -->
                    <Badge :variant="statusColor" class="text-base py-1 px-4">{{
                        statusLabel
                    }}</Badge>

                    <!-- Título e Descrição -->
                    <div>
                        <h1 class="text-3xl font-bold mb-2">
                            {{ item.title }}
                        </h1>
                        <p class="text-muted-foreground">
                            {{ item.description }}
                        </p>
                    </div>

                    <!-- Imagem principal -->
                    <div
                        class="bg-accent/20 rounded-lg overflow-hidden aspect-video"
                    >
                        <img
                            :src="imageUrl"
                            :alt="item.title"
                            class="w-full h-full object-contain"
                        />
                    </div>

                    <!-- Detalhes em cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <Card>
                            <CardHeader class="pb-2">
                                <CardTitle class="text-base"
                                    >Categoria</CardTitle
                                >
                            </CardHeader>
                            <CardContent>
                                <p>{{ categoryName }}</p>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader class="pb-2">
                                <CardTitle class="text-base">Local</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <p>{{ item.location || "Não informado" }}</p>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader class="pb-2">
                                <CardTitle class="text-base"
                                    >Data do Ocorrido</CardTitle
                                >
                            </CardHeader>
                            <CardContent>
                                <p>
                                    {{
                                        formatDate(
                                            item.found_date || item.created_at
                                        )
                                    }}
                                </p>
                            </CardContent>
                        </Card>

                        <Card>
                            <CardHeader class="pb-2">
                                <CardTitle class="text-base"
                                    >Data do Registro</CardTitle
                                >
                            </CardHeader>
                            <CardContent>
                                <p>{{ formatDate(item.created_at) }}</p>
                            </CardContent>
                        </Card>
                    </div>
                </div>

                <!-- Sidebar com formulário de contato -->
                <div>
                    <Card>
                        <CardHeader>
                            <CardTitle>Entre em Contato</CardTitle>
                            <CardDescription>
                                {{
                                    item.status === "perdido"
                                        ? "Você encontrou este item? Entre em contato com o dono."
                                        : "Este item é seu? Entre em contato para recuperá-lo."
                                }}
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <form
                                @submit.prevent="handleContactSubmit"
                                class="space-y-4"
                            >
                                <div>
                                    <Label for="name">Nome Completo</Label>
                                    <Input
                                        id="name"
                                        v-model="contactForm.name"
                                        placeholder="Seu nome"
                                        required
                                    />
                                </div>

                                <div>
                                    <Label for="email">E-mail</Label>
                                    <Input
                                        id="email"
                                        v-model="contactForm.email"
                                        type="email"
                                        placeholder="seu@email.com"
                                        required
                                    />
                                </div>

                                <div>
                                    <Label for="phone">Telefone</Label>
                                    <Input
                                        id="phone"
                                        v-model="contactForm.phone"
                                        placeholder="(00) 00000-0000"
                                    />
                                </div>

                                <div>
                                    <Label for="message">Mensagem</Label>
                                    <Textarea
                                        id="message"
                                        v-model="contactForm.message"
                                        placeholder="Descreva detalhes que comprovem que você é o dono deste item ou explique como encontrou o item perdido..."
                                        rows="4"
                                        required
                                    />
                                </div>

                                <Alert
                                    v-if="contactSuccess"
                                    variant="success"
                                    class="mb-4"
                                >
                                    <CheckCircle2 class="h-4 w-4 mr-2" />
                                    <AlertTitle>Mensagem enviada!</AlertTitle>
                                    <AlertDescription>
                                        Sua mensagem foi enviada com sucesso. Em
                                        breve entraremos em contato.
                                    </AlertDescription>
                                </Alert>

                                <Alert
                                    v-if="contactError"
                                    variant="destructive"
                                    class="mb-4"
                                >
                                    <AlertTriangleIcon class="h-4 w-4 mr-2" />
                                    <AlertTitle>Erro ao enviar</AlertTitle>
                                    <AlertDescription>
                                        Houve um problema ao enviar sua
                                        mensagem. Tente novamente mais tarde.
                                    </AlertDescription>
                                </Alert>

                                <Button
                                    type="submit"
                                    class="w-full"
                                    :disabled="contactLoading"
                                >
                                    <Loader2
                                        v-if="contactLoading"
                                        class="mr-2 h-4 w-4 animate-spin"
                                    />
                                    Enviar Mensagem
                                </Button>
                            </form>
                        </CardContent>
                    </Card>

                    <!-- Info sobre o usuário que cadastrou -->
                    <Card class="mt-4">
                        <CardHeader>
                            <CardTitle>Reportado por</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="flex items-center gap-3">
                                <Avatar>
                                    <AvatarImage src="/api/placeholder/40/40" />
                                    <AvatarFallback>{{
                                        getInitials(
                                            item.user?.name || "Usuário"
                                        )
                                    }}</AvatarFallback>
                                </Avatar>
                                <div>
                                    <p class="font-medium">
                                        {{
                                            item.user?.name ||
                                            "Usuário do Sistema"
                                        }}
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        Membro desde
                                        {{
                                            formatDate(
                                                item.user?.created_at ||
                                                    item.created_at
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </WebsiteLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useRoute, useRouter } from "vue-router";
import WebsiteLayout from "@/website/layouts/WebsiteLayout.vue";
import {
    Card,
    CardContent,
    CardHeader,
    CardTitle,
    CardDescription,
} from "@/ui/components/card";

import { Badge } from "@/ui/components/badge";
import { Button } from "@/ui/components/button";

import { Avatar, AvatarImage, AvatarFallback } from "@/ui/components/avatar";

import { Textarea } from "@/ui/components/textarea";

import { Alert, AlertDescription, AlertTitle } from "@/ui/components/alert";

import { Label } from "@/ui/components/label";
import { Input } from "@/ui/components/input";

import {
    ArrowLeft,
    AlertTriangle as AlertTriangleIcon,
    CheckCircle2,
    Loader2,
} from "lucide-vue-next";

// Router
const route = useRoute();
const router = useRouter();

// Estado
const isLoading = ref(true);
const error = ref<string | null>(null);
const item = ref<any>(null);
const categories = ref<any[]>([
    { id: 1, name: "Documentos" },
    { id: 2, name: "Eletrônicos" },
    { id: 3, name: "Acessórios" },
    { id: 4, name: "Vestuário" },
    { id: 5, name: "Outros" },
]);

// Estado do formulário de contato
const contactForm = ref({
    name: "",
    email: "",
    phone: "",
    message: "",
});
const contactLoading = ref(false);
const contactSuccess = ref(false);
const contactError = ref(false);

// Computed
const imageUrl = computed(() => {
    return item.value?.image || "/api/placeholder/800/600";
});

const statusColor = computed(() => {
    if (!item.value) return "default";

    switch (item.value.status) {
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
    if (!item.value) return "";

    switch (item.value.status) {
        case "perdido":
            return "Perdido";
        case "achado":
            return "Achado";
        case "resgatado":
            return "Resgatado";
        default:
            return item.value.status;
    }
});

const categoryName = computed(() => {
    const category = categories.value.find(
        (c) => c.id === item.value?.category_id
    );
    return category ? category.name : "Não categorizado";
});

// Métodos
const goBack = () => {
    router.back();
};

const fetchItem = async () => {
    isLoading.value = true;
    error.value = null;

    try {
        // Em um ambiente real, faríamos uma requisição à API
        // const response = await fetch(`/api/items/${route.params.id}`);
        // if (!response.ok) throw new Error('Não foi possível carregar o item');
        // item.value = await response.json();

        // Simulando dados para demonstração
        await new Promise((resolve) => setTimeout(resolve, 800));

        const mockItems = {
            perdidos: [
                {
                    id: 1,
                    title: "Carteira de Couro Marrom",
                    description:
                        "Carteira de couro marrom perdida no Parque Central, contém documentos pessoais incluindo RG, CPF e cartão de crédito. Se encontrada, por favor entre em contato o mais rápido possível.",
                    status: "perdido",
                    location: "Parque Central",
                    created_at: "2025-03-10T15:30:00",
                    category_id: 1,
                    user: {
                        name: "João Silva",
                        created_at: "2024-06-15T10:20:00",
                    },
                },
                {
                    id: 2,
                    title: "Chaves com Chaveiro de Guitarra",
                    description:
                        "Molho de chaves com um chaveiro de guitarra, perdido próximo ao Terminal de Ônibus.",
                    status: "perdido",
                    location: "Terminal de Ônibus",
                    created_at: "2025-03-12T09:15:00",
                    category_id: 5,
                    user: {
                        name: "Maria Souza",
                        created_at: "2024-08-05T14:30:00",
                    },
                },
            ],
            achados: [
                {
                    id: 1,
                    title: "Relógio de Pulso Prateado",
                    description:
                        "Relógio de pulso prateado encontrado na Praça Central, marca Casio modelo A168. Encontrado no banco próximo ao chafariz. Está em bom estado e funcionando.",
                    status: "achado",
                    location: "Praça Central",
                    created_at: "2025-03-10T15:30:00",
                    found_date: "2025-03-09T18:30:00",
                    category_id: 3,
                    user: {
                        name: "Carlos Ferreira",
                        created_at: "2024-01-20T09:15:00",
                    },
                },
                {
                    id: 2,
                    title: "Documento de Identidade",
                    description:
                        "RG encontrado próximo à Escola Municipal, nome: Maria Silva.",
                    status: "achado",
                    location: "Escola Municipal",
                    created_at: "2025-03-12T09:15:00",
                    found_date: "2025-03-11T17:00:00",
                    category_id: 1,
                    user: {
                        name: "Ana Costa",
                        created_at: "2024-05-12T16:40:00",
                    },
                },
            ],
        };

        const itemType = route.path.includes("perdidos")
            ? "perdidos"
            : "achados";
        const foundItem = mockItems[itemType].find(
            (i) => i.id === parseInt(route.params.id as string)
        );

        if (!foundItem) {
            throw new Error("Item não encontrado");
        }

        item.value = foundItem;
    } catch (err) {
        console.error("Erro ao buscar item:", err);
        error.value = err instanceof Error ? err.message : "Erro desconhecido";
    } finally {
        isLoading.value = false;
    }
};

const formatDate = (dateString: string) => {
    if (!dateString) return "Data não informada";

    try {
        return new Date(dateString).toLocaleDateString("pt-BR", {
            day: "2-digit",
            month: "2-digit",
            year: "numeric",
        });
    } catch (e) {
        return dateString;
    }
};

const getInitials = (name: string) => {
    return name
        .split(" ")
        .map((part) => part.charAt(0))
        .join("")
        .toUpperCase()
        .substring(0, 2);
};

const handleContactSubmit = async () => {
    contactLoading.value = true;
    contactSuccess.value = false;
    contactError.value = false;

    try {
        // Em um ambiente real, faríamos uma requisição à API
        // const response = await fetch('/api/contact', {
        //   method: 'POST',
        //   headers: { 'Content-Type': 'application/json' },
        //   body: JSON.stringify({
        //     itemId: item.value.id,
        //     ...contactForm.value
        //   })
        // });
        // if (!response.ok) throw new Error('Erro ao enviar mensagem');

        // Simulando requisição
        await new Promise((resolve) => setTimeout(resolve, 1500));

        contactSuccess.value = true;
        contactForm.value = {
            name: "",
            email: "",
            phone: "",
            message: "",
        };
    } catch (err) {
        console.error("Erro ao enviar mensagem:", err);
        contactError.value = true;
    } finally {
        contactLoading.value = false;
    }
};

// Inicializar
onMounted(() => {
    fetchItem();
});
</script>
