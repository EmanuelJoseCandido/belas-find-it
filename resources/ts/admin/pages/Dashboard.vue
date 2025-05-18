<template>
  <div>
    <h2 class="text-2xl font-bold mb-6">Painel de Controle</h2>

    <!-- Stats Overview -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
      <Card>
        <CardContent class="p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-muted-foreground">Total de Itens</p>
              <p class="text-2xl font-bold">{{ stats.totalItems }}</p>
            </div>
            <div class="bg-primary/10 p-3 rounded-full">
              <PackageIcon class="h-6 w-6 text-primary" />
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-muted-foreground">Itens Perdidos</p>
              <p class="text-2xl font-bold">{{ stats.lostItems }}</p>
            </div>
            <div class="bg-primary/10 p-3 rounded-full">
              <HelpCircleIcon class="h-6 w-6 text-primary" />
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-muted-foreground">Itens Encontrados</p>
              <p class="text-2xl font-bold">{{ stats.foundItems }}</p>
            </div>
            <div class="bg-primary/10 p-3 rounded-full">
              <SearchIcon class="h-6 w-6 text-primary" />
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardContent class="p-6">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-muted-foreground">Usuários</p>
              <p class="text-2xl font-bold">{{ stats.totalUsers }}</p>
            </div>
            <div class="bg-primary/10 p-3 rounded-full">
              <UsersIcon class="h-6 w-6 text-primary" />
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Recent Items and Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Recent Items -->
      <Card>
        <CardHeader class="px-6">
          <CardTitle>Itens Recentes</CardTitle>
          <CardDescription
            >Últimos itens cadastrados na plataforma</CardDescription
          >
        </CardHeader>
        <CardContent class="px-6">
          <div v-if="loading.items" class="flex justify-center py-6">
            <Loader2 class="h-8 w-8 animate-spin text-primary" />
          </div>
          <div v-else-if="recentItems.length === 0" class="py-6 text-center">
            <PackageIcon class="h-12 w-12 mx-auto text-muted-foreground mb-3" />
            <p class="text-muted-foreground">
              Nenhum item cadastrado recentemente
            </p>
          </div>
          <div v-else class="space-y-4">
            <div
              v-for="item in recentItems"
              :key="item.id"
              class="flex items-start gap-4 py-2"
            >
              <div class="flex-shrink-0">
                <Avatar>
                  <AvatarImage
                    :src="item.image || '/api/placeholder/40/40'"
                    alt="Item image"
                  />
                  <AvatarFallback>{{
                    getItemInitials(item.title)
                  }}</AvatarFallback>
                </Avatar>
              </div>
              <div class="flex-grow min-w-0">
                <div class="flex items-center gap-2">
                  <h4 class="font-medium truncate">{{ item.title }}</h4>
                  <Badge :variant="getStatusVariant(item.status)">{{
                    getStatusLabel(item.status)
                  }}</Badge>
                </div>
                <p class="text-sm text-muted-foreground truncate">
                  {{ item.location || "Local não informado" }}
                </p>
                <p class="text-xs text-muted-foreground">
                  {{ formatDate(item.created_at) }}
                </p>
              </div>
            </div>
          </div>
        </CardContent>
        <CardFooter class="px-6 pt-0 border-t">
          <Button
            variant="outline"
            class="w-full"
            @click="navigateTo('/admin/items')"
          >
            Ver Todos os Itens
          </Button>
        </CardFooter>
      </Card>

      <!-- Recent Activities & Messages -->
      <Card>
        <CardHeader class="px-6 pb-3">
          <div class="flex items-center justify-between">
            <CardTitle>Atividades Recentes</CardTitle>
          </div>
        </CardHeader>

        <Tabs v-model="activeTab" class="w-full">
          <div class="px-6 mb-4">
            <TabsList class="grid grid-cols-2 w-[250px]">
              <TabsTrigger value="activities">Atividades</TabsTrigger>
              <TabsTrigger value="messages">Mensagens</TabsTrigger>
            </TabsList>
          </div>

          <CardContent class="px-6">
            <!-- Activities Tab -->
            <TabsContent value="activities" class="m-0 p-0">
              <div v-if="loading.activities" class="flex justify-center py-6">
                <Loader2 class="h-8 w-8 animate-spin text-primary" />
              </div>
              <div
                v-else-if="recentActivities.length === 0"
                class="py-6 text-center"
              >
                <ActivityIcon
                  class="h-12 w-12 mx-auto text-muted-foreground mb-3"
                />
                <p class="text-muted-foreground">Nenhuma atividade recente</p>
              </div>
              <div v-else class="space-y-4">
                <div
                  v-for="(activity, index) in recentActivities"
                  :key="index"
                  class="flex items-start gap-4 py-2"
                >
                  <div class="bg-primary/10 p-2 rounded-full flex-shrink-0">
                    <component
                      :is="activity.icon"
                      class="h-4 w-4 text-primary"
                    />
                  </div>
                  <div>
                    <p class="font-medium">{{ activity.description }}</p>
                    <p class="text-sm text-muted-foreground mt-1">
                      {{ activity.user }}
                    </p>
                    <p class="text-xs text-muted-foreground">
                      {{ formatTimeAgo(activity.time) }}
                    </p>
                  </div>
                </div>
              </div>
            </TabsContent>

            <!-- Messages Tab -->
            <TabsContent value="messages" class="m-0 p-0">
              <div v-if="loading.messages" class="flex justify-center py-6">
                <Loader2 class="h-8 w-8 animate-spin text-primary" />
              </div>
              <div
                v-else-if="recentMessages.length === 0"
                class="py-6 text-center"
              >
                <MessageSquareIcon
                  class="h-12 w-12 mx-auto text-muted-foreground mb-3"
                />
                <p class="text-muted-foreground">Nenhuma mensagem recente</p>
              </div>
              <div v-else class="space-y-4">
                <div
                  v-for="message in recentMessages"
                  :key="message.id"
                  class="flex items-start gap-4 py-2"
                >
                  <div class="flex-shrink-0">
                    <Avatar>
                      <AvatarFallback>{{
                        getInitials(message.name)
                      }}</AvatarFallback>
                    </Avatar>
                  </div>
                  <div class="flex-grow min-w-0">
                    <div class="flex items-center gap-2">
                      <h4 class="font-medium">{{ message.name }}</h4>
                      <Badge>{{ message.status }}</Badge>
                    </div>
                    <p class="text-sm truncate">{{ message.subject }}</p>
                    <p class="text-xs text-muted-foreground">
                      {{ formatDate(message.created_at) }}
                    </p>
                  </div>
                </div>
              </div>
            </TabsContent>
          </CardContent>
        </Tabs>

        <CardFooter class="px-6 pt-0 border-t">
          <Button
            variant="outline"
            class="w-full"
            @click="
              navigateTo(
                activeTab === 'messages'
                  ? '/admin/messages'
                  : '/admin/activities'
              )
            "
          >
            Ver Tudo
          </Button>
        </CardFooter>
      </Card>
    </div>

    <!-- System Overview -->
    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Top Categories -->
      <Card>
        <CardHeader class="px-6">
          <CardTitle>Categorias Populares</CardTitle>
          <CardDescription>Distribuição de itens por categoria</CardDescription>
        </CardHeader>
        <CardContent class="px-6">
          <div v-if="loading.categories" class="flex justify-center py-6">
            <Loader2 class="h-8 w-8 animate-spin text-primary" />
          </div>
          <div v-else class="space-y-4">
            <div
              v-for="category in topCategories"
              :key="category.id"
              class="flex items-center gap-2"
            >
              <div class="flex-grow">
                <div class="flex justify-between mb-1">
                  <span class="text-sm font-medium">{{ category.name }}</span>
                  <span class="text-sm text-muted-foreground">{{
                    category.count
                  }}</span>
                </div>
                <div class="bg-muted rounded-full h-2">
                  <div
                    class="bg-primary h-2 rounded-full"
                    :style="{ width: `${category.percentage}%` }"
                  ></div>
                </div>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Monthly Trends -->
      <Card>
        <CardHeader class="px-6">
          <CardTitle>Tendências Mensais</CardTitle>
          <CardDescription>Itens cadastrados nos últimos meses</CardDescription>
        </CardHeader>
        <CardContent class="px-6">
          <div v-if="loading.trends" class="flex justify-center py-6">
            <Loader2 class="h-8 w-8 animate-spin text-primary" />
          </div>
          <div v-else class="h-[200px]">
            <!-- Simplificado: Apenas para demonstração. Em produção, use uma biblioteca de gráficos. -->
            <div class="flex h-full items-end gap-2">
              <div
                v-for="item in monthlyTrends"
                :key="item.month"
                class="flex-1 flex flex-col items-center"
              >
                <div
                  class="w-full bg-primary rounded-t h-[0%]"
                  :style="{ height: `${item.percentage}%` }"
                ></div>
                <span class="text-xs mt-2">{{ item.month }}</span>
              </div>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Quick Actions -->
      <Card>
        <CardHeader class="px-6">
          <CardTitle>Ações Rápidas</CardTitle>
          <CardDescription>Atalhos para tarefas comuns</CardDescription>
        </CardHeader>
        <CardContent class="px-6">
          <div class="space-y-2">
            <Button variant="outline" class="w-full justify-start text-left">
              <PlusCircleIcon class="h-4 w-4 mr-2" />
              Adicionar Nova Categoria
            </Button>
            <Button variant="outline" class="w-full justify-start text-left">
              <UserPlusIcon class="h-4 w-4 mr-2" />
              Criar Novo Usuário
            </Button>
            <Button variant="outline" class="w-full justify-start text-left">
              <ListChecksIcon class="h-4 w-4 mr-2" />
              Gerenciar Reclamações
            </Button>
            <Button variant="outline" class="w-full justify-start text-left">
              <FileTextIcon class="h-4 w-4 mr-2" />
              Gerar Relatório
            </Button>
          </div>
        </CardContent>
      </Card>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import { useRouter } from "vue-router";
import {
  Card,
  CardContent,
  CardFooter,
  CardHeader,
  CardDescription,
  CardTitle,
} from "@/ui/components/card";
import { Avatar, AvatarImage, AvatarFallback } from "@/ui/components/avatar";
import { Button } from "@/ui/components/button";
import { Badge } from "@/ui/components/badge";
import { Tabs, TabsContent, TabsList, TabsTrigger } from "@/ui/components/tabs";
import {
  Package as PackageIcon,
  HelpCircle as HelpCircleIcon,
  Search as SearchIcon,
  Users as UsersIcon,
  MessageSquare as MessageSquareIcon,
  Activity as ActivityIcon,
  Clock as ClockIcon,
  UserPlus as UserPlusIcon,
  PlusCircle as PlusCircleIcon,
  FileText as FileTextIcon,
  ListChecks as ListChecksIcon,
  Plus as PlusIcon,
  User as UserIcon,
  Edit as EditIcon,
  Check as CheckIcon,
  Loader2,
} from "lucide-vue-next";

const router = useRouter();
const activeTab = ref("activities");

// Loading states
const loading = ref({
  items: true,
  activities: true,
  messages: true,
  categories: true,
  trends: true,
});

// Statistics
const stats = ref({
  totalItems: 124, // Mock data
  lostItems: 58,
  foundItems: 42,
  recoveredItems: 24,
  totalUsers: 67,
});

// Recent items
const recentItems = ref([
  {
    id: 1,
    title: "Carteira de Couro Marrom",
    status: "perdido",
    location: "Parque Central",
    created_at: "2025-05-15T14:30:00",
    image: null,
  },
  {
    id: 2,
    title: "Relógio de Pulso Prateado",
    status: "achado",
    location: "Praça Central",
    created_at: "2025-05-14T10:15:00",
    image: null,
  },
  {
    id: 3,
    title: "Documento de Identidade",
    status: "achado",
    location: "Escola Municipal",
    created_at: "2025-05-12T09:45:00",
    image: null,
  },
  {
    id: 4,
    title: "Notebook Dell",
    status: "resgatado",
    location: "Cafeteria Central",
    created_at: "2025-05-10T16:20:00",
    image: null,
  },
]);

// Recent activities
const recentActivities = ref([
  {
    icon: PlusIcon,
    description: "Novo item cadastrado: Carteira de Couro Marrom",
    user: "João Silva",
    time: "2025-05-15T14:30:00",
  },
  {
    icon: UserIcon,
    description: "Novo usuário registrado",
    user: "Maria Oliveira",
    time: "2025-05-14T10:15:00",
  },
  {
    icon: EditIcon,
    description: "Item atualizado: Relógio de Pulso Prateado",
    user: "Carlos Santos",
    time: "2025-05-13T16:45:00",
  },
  {
    icon: CheckIcon,
    description: "Item resgatado: Notebook Dell",
    user: "Ana Costa",
    time: "2025-05-10T16:20:00",
  },
]);

// Recent messages
const recentMessages = ref([
  {
    id: 1,
    name: "Maria Oliveira",
    subject: "Dúvida sobre item perdido",
    status: "Pendente",
    created_at: "2025-05-15T09:30:00",
  },
  {
    id: 2,
    name: "Carlos Santos",
    subject: "Problema ao cadastrar item",
    status: "Em andamento",
    created_at: "2025-05-14T14:15:00",
  },
  {
    id: 3,
    name: "Ana Costa",
    subject: "Elogio ao sistema",
    status: "Resolvido",
    created_at: "2025-05-12T11:45:00",
  },
  {
    id: 4,
    name: "Pedro Souza",
    subject: "Sugestão de melhoria",
    status: "Pendente",
    created_at: "2025-05-10T16:20:00",
  },
]);

// Top categories
const topCategories = ref([
  { id: 1, name: "Documentos", count: 28, percentage: 80 },
  { id: 2, name: "Eletrônicos", count: 24, percentage: 70 },
  { id: 3, name: "Acessórios", count: 18, percentage: 50 },
  { id: 4, name: "Vestuário", count: 12, percentage: 35 },
  { id: 5, name: "Outros", count: 8, percentage: 25 },
]);

// Monthly trends
const monthlyTrends = ref([
  { month: "Jan", count: 12, percentage: 40 },
  { month: "Fev", count: 18, percentage: 60 },
  { month: "Mar", count: 24, percentage: 80 },
  { month: "Abr", count: 15, percentage: 50 },
  { month: "Mai", count: 30, percentage: 100 },
  { month: "Jun", count: 10, percentage: 33 },
]);

// Methods
const getInitials = (name: string): string => {
  return name
    .split(" ")
    .map((part) => part.charAt(0))
    .join("")
    .toUpperCase()
    .substring(0, 2);
};

const getItemInitials = (title: string): string => {
  return title
    .split(" ")
    .map((part) => part.charAt(0))
    .join("")
    .toUpperCase()
    .substring(0, 2);
};

const getStatusVariant = (status: string): string => {
  switch (status) {
    case "perdido":
      return "destructive";
    case "achado":
      return "secondary";
    case "resgatado":
      return "default";
    default:
      return "outline";
  }
};

const getStatusLabel = (status: string): string => {
  switch (status) {
    case "perdido":
      return "Perdido";
    case "achado":
      return "Achado";
    case "resgatado":
      return "Resgatado";
    default:
      return status;
  }
};

const formatDate = (dateString: string): string => {
  try {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat("pt-BR", {
      day: "2-digit",
      month: "2-digit",
      year: "numeric",
    }).format(date);
  } catch (e) {
    return dateString;
  }
};

const formatTimeAgo = (dateString: string): string => {
  try {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now.getTime() - date.getTime()) / 1000);

    if (diffInSeconds < 60) return "agora mesmo";

    const diffInMinutes = Math.floor(diffInSeconds / 60);
    if (diffInMinutes < 60) return `${diffInMinutes} min atrás`;

    const diffInHours = Math.floor(diffInMinutes / 60);
    if (diffInHours < 24) return `${diffInHours} h atrás`;

    const diffInDays = Math.floor(diffInHours / 24);
    if (diffInDays < 30) return `${diffInDays} dias atrás`;

    return formatDate(dateString);
  } catch (e) {
    return dateString;
  }
};

const navigateTo = (path: string): void => {
  router.push(path);
};

// Fetch data
const fetchData = async (): Promise<void> => {
  try {
    // Simulate API calls
    await new Promise((resolve) => setTimeout(resolve, 1000));

    // In a real application, replace these with actual API calls
    // const statsResponse = await axios.get('/api/admin/stats');
    // stats.value = statsResponse.data;

    // const itemsResponse = await axios.get('/api/admin/items/recent');
    // recentItems.value = itemsResponse.data;

    // etc.
  } catch (error) {
    console.error("Error fetching dashboard data:", error);
  } finally {
    // Complete loading
    loading.value.items = false;
    loading.value.activities = false;
    loading.value.messages = false;
    loading.value.categories = false;
    loading.value.trends = false;
  }
};

// Initialize
onMounted(() => {
  fetchData();
});
</script>
