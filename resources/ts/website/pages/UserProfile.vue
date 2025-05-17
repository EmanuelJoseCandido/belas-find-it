<template>
    <WebsiteLayout>
        <!-- Hero Section -->
        <section class="bg-primary text-white py-12 px-20">
            <div class="container">
                <div class="max-w-3xl">
                    <h1 class="text-3xl md:text-4xl font-bold mb-4">
                        Meu Perfil
                    </h1>
                    <p class="text-xl text-white/90">
                        Gerencie suas informações e itens cadastrados
                    </p>
                </div>
            </div>
        </section>

        <!-- Conteúdo Principal -->
        <div class="container py-8 px-20">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Menu lateral -->
                <div class="md:col-span-1">
                    <Card>
                        <CardContent class="p-0">
                            <div class="p-6 text-center border-b">
                                <Avatar class="mx-auto mb-3 h-20 w-20">
                                    <AvatarImage :src="profileImage" />
                                    <AvatarFallback>{{
                                        userInitials
                                    }}</AvatarFallback>
                                </Avatar>
                                <h3 class="font-semibold text-lg">
                                    {{ user?.name || "Carregando..." }}
                                </h3>
                                <p class="text-sm text-muted-foreground">
                                    {{ user?.email || "" }}
                                </p>
                                <Badge class="mt-2">{{ userRoleLabel }}</Badge>
                            </div>

                            <nav>
                                <button
                                    v-for="(item, index) in navItems"
                                    :key="index"
                                    class="w-full flex items-center p-4 text-left transition-colors hover:bg-muted"
                                    :class="{
                                        'bg-muted font-medium':
                                            activeTab === item.id,
                                    }"
                                    @click="activeTab = item.id"
                                >
                                    <component
                                        :is="item.icon"
                                        class="h-5 w-5 mr-3"
                                    />
                                    {{ item.label }}
                                </button>
                            </nav>

                            <div class="p-4 border-t">
                                <Button
                                    variant="outline"
                                    class="w-full"
                                    @click="logout"
                                >
                                    <LogOutIcon class="h-4 w-4 mr-2" />
                                    Sair
                                </Button>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Conteúdo principal -->
                <div class="md:col-span-3">
                    <!-- Tab: Painel -->
                    <div v-if="activeTab === 'dashboard'">
                        <h2 class="text-2xl font-bold mb-6">Visão Geral</h2>

                        <!-- Estatísticas -->
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                            <Card>
                                <CardContent class="py-6">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <div>
                                            <p
                                                class="text-sm text-muted-foreground"
                                            >
                                                Itens Perdidos
                                            </p>
                                            <p class="text-2xl font-bold">
                                                {{ stats.lostItems }}
                                            </p>
                                        </div>
                                        <div
                                            class="bg-primary/10 p-3 rounded-full"
                                        >
                                            <MapPinIcon
                                                class="h-6 w-6 text-primary"
                                            />
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>

                            <Card>
                                <CardContent class="py-6">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <div>
                                            <p
                                                class="text-sm text-muted-foreground"
                                            >
                                                Itens Encontrados
                                            </p>
                                            <p class="text-2xl font-bold">
                                                {{ stats.foundItems }}
                                            </p>
                                        </div>
                                        <div
                                            class="bg-primary/10 p-3 rounded-full"
                                        >
                                            <SearchIcon
                                                class="h-6 w-6 text-primary"
                                            />
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>

                            <Card>
                                <CardContent class="py-6">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <div>
                                            <p
                                                class="text-sm text-muted-foreground"
                                            >
                                                Itens Recuperados
                                            </p>
                                            <p class="text-2xl font-bold">
                                                {{ stats.recoveredItems }}
                                            </p>
                                        </div>
                                        <div
                                            class="bg-primary/10 p-3 rounded-full"
                                        >
                                            <CheckCircleIcon
                                                class="h-6 w-6 text-primary"
                                            />
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                        </div>

                        <!-- Ações rápidas -->
                        <h3 class="text-lg font-medium mb-3">Ações Rápidas</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                            <Card>
                                <CardContent class="p-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="bg-primary/10 p-3 rounded-full"
                                        >
                                            <PlusIcon
                                                class="h-6 w-6 text-primary"
                                            />
                                        </div>
                                        <div>
                                            <h4 class="font-medium">
                                                Cadastrar Item
                                            </h4>
                                            <p
                                                class="text-sm text-muted-foreground"
                                            >
                                                Perdeu ou encontrou algo?
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <RouterLink to="/cadastrar-item">
                                            <Button class="w-full"
                                                >Cadastrar Item</Button
                                            >
                                        </RouterLink>
                                    </div>
                                </CardContent>
                            </Card>

                            <Card>
                                <CardContent class="p-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="bg-primary/10 p-3 rounded-full"
                                        >
                                            <EditIcon
                                                class="h-6 w-6 text-primary"
                                            />
                                        </div>
                                        <div>
                                            <h4 class="font-medium">
                                                Atualizar Perfil
                                            </h4>
                                            <p
                                                class="text-sm text-muted-foreground"
                                            >
                                                Atualize suas informações
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <Button
                                            variant="outline"
                                            class="w-full"
                                            @click="activeTab = 'profile'"
                                        >
                                            Editar Perfil
                                        </Button>
                                    </div>
                                </CardContent>
                            </Card>
                        </div>

                        <!-- Atividade recente -->
                        <h3 class="text-lg font-medium mb-3">
                            Atividade Recente
                        </h3>
                        <Card>
                            <CardContent>
                                <div
                                    v-if="activities.length === 0"
                                    class="py-20 text-center"
                                >
                                    <InboxIcon
                                        class="h-12 w-12 mx-auto text-muted-foreground mb-3"
                                    />
                                    <p class="text-muted-foreground">
                                        Nenhuma atividade recente
                                    </p>
                                </div>
                                <div v-else class="space-y-4">
                                    <div
                                        v-for="(activity, index) in activities"
                                        :key="index"
                                        class="flex items-start gap-4 py-2"
                                    >
                                        <div
                                            :class="`bg-${activity.color}/10 p-2 rounded-full`"
                                        >
                                            <component
                                                :is="activity.icon"
                                                class="h-5 w-5"
                                                :class="`text-${activity.color}`"
                                            />
                                        </div>
                                        <div>
                                            <p class="font-medium">
                                                {{ activity.title }}
                                            </p>
                                            <p
                                                class="text-sm text-muted-foreground"
                                            >
                                                {{ activity.description }}
                                            </p>
                                            <p
                                                class="text-xs text-muted-foreground mt-1"
                                            >
                                                {{ activity.time }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Tab: Meus Itens -->
                    <div v-if="activeTab === 'items'">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold">Meus Itens</h2>
                            <RouterLink to="/cadastrar-item">
                                <Button>
                                    <PlusIcon class="h-4 w-4 mr-2" />
                                    Cadastrar Item
                                </Button>
                            </RouterLink>
                        </div>

                        <!-- Filtros -->
                        <div class="mb-6 flex flex-wrap gap-3">
                            <Button
                                variant="outline"
                                :class="{
                                    'bg-primary text-white':
                                        itemsFilter === 'all',
                                }"
                                @click="itemsFilter = 'all'"
                            >
                                Todos
                            </Button>
                            <Button
                                variant="outline"
                                :class="{
                                    'bg-primary text-white':
                                        itemsFilter === 'lost',
                                }"
                                @click="itemsFilter = 'lost'"
                            >
                                Perdidos
                            </Button>
                            <Button
                                variant="outline"
                                :class="{
                                    'bg-primary text-white':
                                        itemsFilter === 'found',
                                }"
                                @click="itemsFilter = 'found'"
                            >
                                Encontrados
                            </Button>
                            <Button
                                variant="outline"
                                :class="{
                                    'bg-primary text-white':
                                        itemsFilter === 'recovered',
                                }"
                                @click="itemsFilter = 'recovered'"
                            >
                                Recuperados
                            </Button>
                        </div>

                        <!-- Lista de itens -->
                        <div v-if="isLoading" class="py-20 text-center">
                            <div
                                class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary mx-auto"
                            ></div>
                        </div>

                        <div
                            v-else-if="filteredItems.length === 0"
                            class="py-20 text-center"
                        >
                            <InboxIcon
                                class="h-12 w-12 mx-auto text-muted-foreground mb-3"
                            />
                            <p class="text-lg font-medium">
                                Nenhum item encontrado
                            </p>
                            <p class="text-muted-foreground mb-4">
                                Você ainda não cadastrou nenhum item nesta
                                categoria.
                            </p>
                            <RouterLink to="/cadastrar-item">
                                <Button>Cadastrar Novo Item</Button>
                            </RouterLink>
                        </div>

                        <div
                            v-else
                            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"
                        >
                            <ItemCard
                                v-for="item in filteredItems"
                                :key="item.id"
                                :item="item"
                            />
                        </div>
                    </div>

                    <!-- Tab: Mensagens -->
                    <div v-if="activeTab === 'messages'">
                        <h2 class="text-2xl font-bold mb-6">Mensagens</h2>

                        <div
                            v-if="messages.length === 0"
                            class="py-20 text-center"
                        >
                            <MailIcon
                                class="h-12 w-12 mx-auto text-muted-foreground mb-3"
                            />
                            <p class="text-lg font-medium">Nenhuma mensagem</p>
                            <p class="text-muted-foreground">
                                Você não possui mensagens ainda.
                            </p>
                        </div>

                        <div v-else class="space-y-4">
                            <Card
                                v-for="(message, index) in messages"
                                :key="index"
                            >
                                <CardContent class="p-6">
                                    <div
                                        class="flex items-start justify-between"
                                    >
                                        <div class="flex items-start gap-4">
                                            <Avatar>
                                                <AvatarFallback>{{
                                                    getInitials(message.sender)
                                                }}</AvatarFallback>
                                            </Avatar>
                                            <div>
                                                <h4 class="font-medium">
                                                    {{ message.sender }}
                                                </h4>
                                                <p
                                                    class="text-sm text-muted-foreground"
                                                >
                                                    {{ message.item }}
                                                </p>
                                                <p class="mt-2">
                                                    {{ message.content }}
                                                </p>
                                                <p
                                                    class="text-xs text-muted-foreground mt-2"
                                                >
                                                    {{ message.date }}
                                                </p>
                                            </div>
                                        </div>
                                        <Badge>{{ message.status }}</Badge>
                                    </div>
                                    <div
                                        class="mt-4 pt-4 border-t flex gap-2 justify-end"
                                    >
                                        <Button variant="outline" size="sm">
                                            <ReplyIcon class="h-4 w-4 mr-2" />
                                            Responder
                                        </Button>
                                        <Button size="sm">Ver Detalhes</Button>
                                    </div>
                                </CardContent>
                            </Card>
                        </div>
                    </div>

                    <!-- Tab: Perfil -->
                    <div v-if="activeTab === 'profile'">
                        <EditProfile v-bind="user" />
                    </div>

                    <!-- Tab: Configurações -->
                    <div v-if="activeTab === 'settings'">
                        <h2 class="text-2xl font-bold mb-6">Configurações</h2>

                        <Card>
                            <CardContent class="p-6">
                                <h3 class="font-medium mb-4">Notificações</h3>

                                <div class="space-y-4">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <div>
                                            <p class="font-medium">
                                                Notificações por Email
                                            </p>
                                            <p
                                                class="text-sm text-muted-foreground"
                                            >
                                                Receba emails sobre atualizações
                                                de itens
                                            </p>
                                        </div>
                                        <Switch
                                            v-model="
                                                settings.emailNotifications
                                            "
                                        />
                                    </div>

                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <div>
                                            <p class="font-medium">
                                                Notificações por SMS
                                            </p>
                                            <p
                                                class="text-sm text-muted-foreground"
                                            >
                                                Receba mensagens de texto sobre
                                                atividades importantes
                                            </p>
                                        </div>
                                        <Switch
                                            v-model="settings.smsNotifications"
                                        />
                                    </div>

                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <div>
                                            <p class="font-medium">
                                                Boletim Informativo
                                            </p>
                                            <p
                                                class="text-sm text-muted-foreground"
                                            >
                                                Receba nossa newsletter mensal
                                            </p>
                                        </div>
                                        <Switch v-model="settings.newsletter" />
                                    </div>
                                </div>

                                <Separator class="my-6" />

                                <h3 class="font-medium mb-4">Privacidade</h3>

                                <div class="space-y-4">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <div>
                                            <p class="font-medium">
                                                Perfil Público
                                            </p>
                                            <p
                                                class="text-sm text-muted-foreground"
                                            >
                                                Permitir que outros usuários
                                                vejam seu perfil
                                            </p>
                                        </div>
                                        <Switch
                                            v-model="settings.publicProfile"
                                        />
                                    </div>

                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <div>
                                            <p class="font-medium">
                                                Exibir Telefone
                                            </p>
                                            <p
                                                class="text-sm text-muted-foreground"
                                            >
                                                Mostrar seu telefone para outros
                                                usuários
                                            </p>
                                        </div>
                                        <Switch v-model="settings.showPhone" />
                                    </div>
                                </div>

                                <div class="flex justify-end mt-6">
                                    <Button
                                        @click="saveSettings"
                                        :disabled="isUpdating"
                                    >
                                        <Loader2
                                            v-if="isUpdating"
                                            class="h-4 w-4 mr-2 animate-spin"
                                        />
                                        Salvar Configurações
                                    </Button>
                                </div>
                            </CardContent>
                        </Card>

                        <Card class="mt-6">
                            <CardContent class="p-6">
                                <h3 class="font-medium text-destructive mb-4">
                                    Zona de Perigo
                                </h3>

                                <p class="text-sm text-muted-foreground mb-4">
                                    Estas ações são permanentes e não podem ser
                                    desfeitas.
                                </p>

                                <div class="space-y-4">
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <div>
                                            <p class="font-medium">
                                                Excluir Conta
                                            </p>
                                            <p
                                                class="text-sm text-muted-foreground"
                                            >
                                                Remover permanentemente sua
                                                conta e todos os dados
                                                associados
                                            </p>
                                        </div>
                                        <Button
                                            variant="destructive"
                                            size="sm"
                                            @click="confirmDeleteAccount"
                                        >
                                            Excluir Conta
                                        </Button>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmação de exclusão de conta -->
        <Dialog
            :open="showDeleteConfirm"
            @update:open="showDeleteConfirm = $event"
        >
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Excluir Conta</DialogTitle>
                    <DialogDescription>
                        Esta ação é permanente e não pode ser desfeita. Todos os
                        seus dados serão removidos permanentemente.
                    </DialogDescription>
                </DialogHeader>

                <div class="py-4">
                    <p class="text-sm text-muted-foreground mb-4">
                        Digite
                        <span class="font-mono font-bold">excluir</span> para
                        confirmar:
                    </p>
                    <Input v-model="deleteConfirmText" placeholder="excluir" />
                </div>

                <DialogFooter>
                    <Button variant="outline" @click="showDeleteConfirm = false"
                        >Cancelar</Button
                    >
                    <Button
                        variant="destructive"
                        :disabled="
                            deleteConfirmText !== 'excluir' || isDeleting
                        "
                        @click="deleteAccount"
                    >
                        <Loader2
                            v-if="isDeleting"
                            class="h-4 w-4 mr-2 animate-spin"
                        />
                        Excluir Permanentemente
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </WebsiteLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "@/auth/stores/authStore";
import { toast } from "@/ui/components/toast";
import WebsiteLayout from "@/website/layouts/WebsiteLayout.vue";
import ItemCard from "@/website/components/ItemCard.vue";
import EditProfile from "@/website/components/user/EditProfile.vue";
import { Card, CardContent } from "@/ui/components/card";
import { Avatar, AvatarImage, AvatarFallback } from "@/ui/components/avatar";
import { Button } from "@/ui/components/button";
import { Badge } from "@/ui/components/badge";
import { Input } from "@/ui/components/input";
import { Separator } from "@/ui/components/separator";
import { Switch } from "@/ui/components/switch";
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from "@/ui/components/dialog";
import {
    User as UserIcon,
    Package as PackageIcon,
    MessageSquare as MessageSquareIcon,
    Settings as SettingsIcon,
    LogOut as LogOutIcon,
    Home as HomeIcon,
    Plus as PlusIcon,
    Edit as EditIcon,
    MapPin as MapPinIcon,
    Search as SearchIcon,
    CheckCircle as CheckCircleIcon,
    Mail as MailIcon,
    Inbox as InboxIcon,
    Reply as ReplyIcon,
    Loader2,
} from "lucide-vue-next";

// Router e Auth
const router = useRouter();
const authStore = useAuthStore();
const user = computed(() => authStore.user);

// Estado da página
const activeTab = ref("dashboard");
const isUpdating = ref(false);
const isLoading = ref(false);
const isDeleting = ref(false);
const showDeleteConfirm = ref(false);
const deleteConfirmText = ref("");
const itemsFilter = ref("all");

// Dados do perfil
const profileImage = ref("/api/placeholder/100/100");
const userInitials = computed(() => {
    if (!user.value?.name) return "U";
    return user.value.name
        .split(" ")
        .map((part) => part.charAt(0))
        .join("")
        .toUpperCase()
        .substring(0, 2);
});

const userRoleLabel = computed(() => {
    if (!user.value?.role) return "Usuário";
    switch (user.value.role) {
        case "admin":
            return "Administrador";
        case "moderator":
            return "Moderador";
        default:
            return "Usuário";
    }
});

// Dados do formulário de perfil
const profileForm = ref({
    name: "",
    email: "",
    phone: "",
    gender: "",
});

// Dados do formulário de senha
const passwordForm = ref({
    currentPassword: "",
    newPassword: "",
    confirmPassword: "",
});

// Configurações do usuário
const settings = ref({
    emailNotifications: true,
    smsNotifications: false,
    newsletter: true,
    publicProfile: true,
    showPhone: false,
});

// Estatísticas do usuário
const stats = ref({
    lostItems: 2,
    foundItems: 3,
    recoveredItems: 1,
});

// Itens do usuário (simulados)
const userItems = ref([
    {
        id: 1,
        title: "Carteira de Couro Marrom",
        description:
            "Carteira de couro marrom perdida no Parque Central, contém documentos pessoais.",
        status: "perdido",
        location: "Parque Central",
        created_at: "2025-03-10T15:30:00",
        category_id: 1,
    },
    {
        id: 2,
        title: "Relógio de Pulso Prateado",
        description:
            "Relógio de pulso prateado encontrado na Praça Central, marca Casio.",
        status: "achado",
        location: "Praça Central",
        created_at: "2025-03-10T15:30:00",
        found_date: "2025-03-09T18:30:00",
        category_id: 3,
    },
    {
        id: 3,
        title: "Óculos de Grau Preto",
        description:
            "Óculos de grau com armação preta, perdido na Biblioteca Municipal.",
        status: "perdido",
        location: "Biblioteca Municipal",
        created_at: "2025-03-09T18:45:00",
        category_id: 3,
    },
    {
        id: 4,
        title: "Chaves com Chaveiro",
        description:
            "Molho de chaves com chaveiro de guitarra, encontrado no Terminal de Ônibus.",
        status: "achado",
        location: "Terminal de Ônibus",
        created_at: "2025-03-12T09:15:00",
        found_date: "2025-03-11T17:00:00",
        category_id: 5,
    },
    {
        id: 5,
        title: "Notebook Dell",
        description: "Notebook Dell Inspiron preto, encontrado na cafeteria.",
        status: "achado",
        location: "Cafeteria Central",
        created_at: "2025-03-12T09:15:00",
        found_date: "2025-03-11T17:00:00",
        category_id: 5,
    },
]);

// Navitems
const navItems = [
    { id: "dashboard", label: "Painel", icon: HomeIcon },
    { id: "items", label: "Meus Itens", icon: PackageIcon },
    { id: "messages", label: "Mensagens", icon: MessageSquareIcon },
    { id: "profile", label: "Perfil", icon: UserIcon },
    { id: "settings", label: "Configurações", icon: SettingsIcon },
];

// Mensagens do usuário (simuladas)
const messages = ref([
    {
        id: 1,
        sender: "Maria Oliveira",
        item: "Carteira de Couro Marrom",
        content:
            "Olá, encontrei uma carteira que corresponde à sua descrição. Podemos combinar um local para verificar se é realmente a sua?",
        date: "15/03/2025",
        status: "Não lida",
    },
    {
        id: 2,
        sender: "Carlos Santos",
        item: "Óculos de Grau Preto",
        content:
            "Boa tarde! Vi seu anúncio sobre os óculos perdidos. Encontrei um par semelhante na biblioteca. Pode me descrever algum detalhe específico para confirmar?",
        date: "12/03/2025",
        status: "Lida",
    },
]);

// Atividades recentes (simuladas)
const activities = ref([
    {
        icon: PlusIcon,
        title: "Novo item cadastrado",
        description:
            'Você cadastrou "Carteira de Couro Marrom" como item perdido',
        time: "Hoje, 14:30",
        color: "primary",
    },
    {
        icon: MessageSquareIcon,
        title: "Nova mensagem recebida",
        description:
            'Maria Oliveira enviou uma mensagem sobre "Carteira de Couro Marrom"',
        time: "Hoje, 15:45",
        color: "primary",
    },
    {
        icon: CheckCircleIcon,
        title: "Item recuperado",
        description: 'Seu item "Bicicleta Caloi" foi marcado como recuperado',
        time: "10/03/2025",
        color: "primary",
    },
]);

// Computed
const filteredItems = computed(() => {
    if (itemsFilter.value === "all") {
        return userItems.value;
    } else if (itemsFilter.value === "lost") {
        return userItems.value.filter((item) => item.status === "perdido");
    } else if (itemsFilter.value === "found") {
        return userItems.value.filter((item) => item.status === "achado");
    } else if (itemsFilter.value === "recovered") {
        return userItems.value.filter((item) => item.status === "resgatado");
    }
    return userItems.value;
});

// Métodos
const logout = async () => {
    try {
        await authStore.logout();
        router.push("/auth/login");
        toast({
            title: "Logout realizado",
            description: "Você foi desconectado com sucesso.",
        });
    } catch (error) {
        console.error("Erro ao fazer logout:", error);
    }
};
const saveSettings = async () => {
    try {
        isUpdating.value = true;

        // Simulando uma requisição
        await new Promise((resolve) => setTimeout(resolve, 1000));

        toast({
            title: "Configurações salvas",
            description: "Suas preferências foram atualizadas com sucesso",
        });
    } catch (error) {
        console.error("Erro ao salvar configurações:", error);
        toast({
            title: "Erro",
            description: "Não foi possível salvar as configurações",
            variant: "destructive",
        });
    } finally {
        isUpdating.value = false;
    }
};

const confirmDeleteAccount = () => {
    showDeleteConfirm.value = true;
};

const deleteAccount = async () => {
    try {
        isDeleting.value = true;

        // Simulando uma requisição
        await new Promise((resolve) => setTimeout(resolve, 2000));

        // Logout após excluir a conta
        await authStore.logout();

        router.push("/");

        toast({
            title: "Conta excluída",
            description: "Sua conta foi excluída permanentemente",
        });
    } catch (error) {
        console.error("Erro ao excluir conta:", error);
        toast({
            title: "Erro",
            description: "Não foi possível excluir a conta",
            variant: "destructive",
        });
    } finally {
        isDeleting.value = false;
        showDeleteConfirm.value = false;
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

// Inicialização
onMounted(() => {
    // Carrega dados do usuário para o formulário

    if (user.value) {
        profileForm.value = {
            name: user.value.name,
            email: user.value.email,
            phone: user.value.phone || "",
            gender: user.value.gender || "",
        };
    }
});
</script>
