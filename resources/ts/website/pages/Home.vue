<template>
  <WebsiteLayout>
    <!-- Hero Section -->
    <section class="relative">
      <div
        class="bg-gradient-to-r from-primary/90 to-primary text-white py-16 md:py-24 px-20"
      >
        <div class="container relative z-10">
          <div class="max-w-2xl">
            <h1 class="text-3xl md:text-5xl font-bold mb-4">
              Encontre e recupere seus pertences perdidos
            </h1>
            <p class="text-lg mb-8 text-white/90">
              Nossa plataforma de perdidos e achados municipal conecta você aos
              seus itens. Cadastre itens perdidos ou encontrados e ajude nossa
              comunidade.
            </p>
            <div class="flex flex-wrap gap-4">
              <RouterLink to="/perdidos">
                <Button size="lg" variant="secondary"
                  >Ver Itens Perdidos</Button
                >
              </RouterLink>
              <RouterLink to="/achados">
                <Button
                  size="lg"
                  variant="outline"
                  class="bg-transparent text-white border-white hover:bg-white/10"
                  >Ver Itens Achados</Button
                >
              </RouterLink>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 bg-background">
      <div class="container">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
          <div class="bg-white rounded-lg p-6 text-center shadow-sm">
            <p class="text-3xl font-bold text-primary mb-1">
              {{ stats.totalItems }}
            </p>
            <p class="text-muted-foreground">Itens Cadastrados</p>
          </div>
          <div class="bg-white rounded-lg p-6 text-center shadow-sm">
            <p class="text-3xl font-bold text-primary mb-1">
              {{ stats.lostItems }}
            </p>
            <p class="text-muted-foreground">Itens Perdidos</p>
          </div>
          <div class="bg-white rounded-lg p-6 text-center shadow-sm">
            <p class="text-3xl font-bold text-primary mb-1">
              {{ stats.foundItems }}
            </p>
            <p class="text-muted-foreground">Itens Achados</p>
          </div>
          <div class="bg-white rounded-lg p-6 text-center shadow-sm">
            <p class="text-3xl font-bold text-primary mb-1">
              {{ stats.recoveredItems }}
            </p>
            <p class="text-muted-foreground">Itens Recuperados</p>
          </div>
        </div>
      </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-16 px-20 bg-muted/50">
      <div class="container">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold mb-4">Como Funciona</h2>
          <p class="text-muted-foreground max-w-2xl mx-auto">
            Nossa plataforma conecta pessoas que perderam seus pertences com
            aquelas que encontraram algo. Veja como é fácil utilizar o sistema.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex justify-center mb-4">
              <div
                class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center"
              >
                <ClipboardListIcon class="h-8 w-8 text-primary" />
              </div>
            </div>
            <h3 class="text-xl font-semibold text-center mb-2">
              1. Cadastre o Item
            </h3>
            <p class="text-center text-muted-foreground">
              Perdeu algo? Registre detalhes como local, data e características.
              Encontrou algo? Cadastre para que o dono possa localizar.
            </p>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex justify-center mb-4">
              <div
                class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center"
              >
                <SearchIcon class="h-8 w-8 text-primary" />
              </div>
            </div>
            <h3 class="text-xl font-semibold text-center mb-2">
              2. Busque na Plataforma
            </h3>
            <p class="text-center text-muted-foreground">
              Utilize nossos filtros avançados para buscar itens por categoria,
              localização, data ou palavras-chave.
            </p>
          </div>

          <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex justify-center mb-4">
              <div
                class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center"
              >
                <MessageSquareIcon class="h-8 w-8 text-primary" />
              </div>
            </div>
            <h3 class="text-xl font-semibold text-center mb-2">
              3. Entre em Contato
            </h3>
            <p class="text-center text-muted-foreground">
              Encontrou seu item ou é dono de algo achado? Entre em contato para
              combinar a devolução de forma segura.
            </p>
          </div>
        </div>

        <div class="text-center mt-12">
          <RouterLink :to="{ name: 'register-item' }">
            <Button size="lg">Cadastrar um Item</Button>
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- Recent Items Section -->
    <section class="py-16 px-20">
      <div class="container">
        <div class="flex justify-between items-center mb-8">
          <h2 class="text-2xl font-bold">Itens Recentes</h2>
          <div class="flex gap-2">
            <Button
              variant="outline"
              :class="{
                'bg-primary text-white hover:bg-primary/90':
                  activeTab === 'todos',
              }"
              @click="setActiveTab('todos')"
            >
              Todos
            </Button>
            <Button
              variant="outline"
              :class="{
                'bg-primary text-white hover:bg-primary/90':
                  activeTab === 'perdido',
              }"
              @click="setActiveTab('perdido')"
            >
              Perdidos
            </Button>
            <Button
              variant="outline"
              :class="{
                'bg-primary text-white hover:bg-primary/90':
                  activeTab === 'achado',
              }"
              @click="setActiveTab('achado')"
            >
              Achados
            </Button>
          </div>
        </div>

        <div v-if="isLoading" class="py-12 flex justify-center">
          <div
            class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"
          ></div>
        </div>

        <div v-else-if="filteredItems.length === 0" class="py-12 text-center">
          <FileSearchIcon
            class="mx-auto h-12 w-12 text-muted-foreground mb-4"
          />
          <h3 class="text-lg font-medium mb-2">Nenhum item encontrado</h3>
          <p class="text-muted-foreground">
            Não há itens cadastrados nesta categoria no momento.
          </p>
        </div>

        <div
          v-else
          class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
        >
          <ItemCard v-for="item in filteredItems" :key="item.id" :item="item" />
        </div>

        <div class="text-center mt-8">
          <RouterLink
            :to="activeTab === 'todos' ? '/perdidos' : `/${activeTab}s`"
          >
            <Button variant="outline">Ver Mais Itens</Button>
          </RouterLink>
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-16 px-20 bg-muted/50">
      <div class="container">
        <div class="text-center mb-12">
          <h2 class="text-3xl font-bold mb-4">Depoimentos</h2>
          <p class="text-muted-foreground max-w-2xl mx-auto">
            Conheça histórias de pessoas que recuperaram seus pertences através
            da nossa plataforma.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <Card v-for="(testimonial, index) in testimonials" :key="index">
            <CardContent class="pt-6">
              <div class="flex items-center gap-4 mb-4">
                <Avatar>
                  <AvatarImage
                    :src="`/api/placeholder/${40 + index}/${40 + index}`"
                  />
                  <AvatarFallback>{{
                    getInitials(testimonial.name)
                  }}</AvatarFallback>
                </Avatar>
                <div>
                  <p class="font-medium">
                    {{ testimonial.name }}
                  </p>
                  <p class="text-sm text-muted-foreground">
                    {{ testimonial.role }}
                  </p>
                </div>
              </div>

              <p class="italic text-muted-foreground">
                "{{ testimonial.content }}"
              </p>
            </CardContent>
          </Card>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-primary text-white">
      <div class="container text-center">
        <h2 class="text-3xl font-bold mb-4">Perdeu ou encontrou algo?</h2>
        <p class="text-lg mb-8 text-white/90 max-w-2xl mx-auto">
          Não perca tempo! Cadastre itens perdidos ou encontrados em nossa
          plataforma e aumente as chances de recuperação.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
          <RouterLink :to="{ name: 'register-item' }">
            <Button size="lg" variant="secondary">Cadastrar um Item</Button>
          </RouterLink>
          <RouterLink :to="{ name: 'about' }">
            <Button
              size="lg"
              variant="outline"
              class="bg-transparent text-white border-white hover:bg-white/10"
              >Saiba Mais</Button
            >
          </RouterLink>
        </div>
      </div>
    </section>
  </WebsiteLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { RouterLink } from "vue-router";
import WebsiteLayout from "@/website/layouts/WebsiteLayout.vue";
import ItemCard from "@/website/components/ItemCard.vue";
import { Button } from "@/ui/components/button";
import { Card, CardContent } from "@/ui/components/card";
import { Avatar, AvatarImage, AvatarFallback } from "@/ui/components/avatar";
import { itemService } from "@/services/itemService";
import {
  ClipboardList as ClipboardListIcon,
  Search as SearchIcon,
  MessageSquare as MessageSquareIcon,
  FileSearch as FileSearchIcon,
} from "lucide-vue-next";

// Estado
const isLoading = ref(true);
const activeTab = ref("todos");
const recentItems = ref<any[]>([]);

// Estatísticas
const stats = ref({
  totalItems: 124,
  lostItems: 58,
  foundItems: 42,
  recoveredItems: 24,
});

// Depoimentos
const testimonials = ref([
  {
    name: "Ana Paula",
    role: "Moradora",
    content:
      "Perdi minha carteira com todos os documentos no parque. Em menos de 24 horas, recebi uma mensagem de alguém que a encontrou. O sistema foi fundamental para recuperar meus pertences!",
  },
  {
    name: "Carlos Mendes",
    role: "Comerciante",
    content:
      "Encontrei um celular na calçada da minha loja e não sabia como achar o dono. Cadastrei no sistema e em poucos dias a proprietária entrou em contato. Fiquei muito feliz em poder ajudar.",
  },
  {
    name: "Mariana Costa",
    role: "Estudante",
    content:
      "Esqueci meu notebook na biblioteca municipal e fiquei desesperada. Graças à plataforma, o funcionário que o encontrou conseguiu me localizar rapidamente. Serviço essencial para a comunidade!",
  },
]);

// Métodos
const setActiveTab = (tab: string) => {
  activeTab.value = tab;
};

const getInitials = (name: string) => {
  return name
    .split(" ")
    .map((part) => part.charAt(0))
    .join("")
    .toUpperCase()
    .substring(0, 2);
};

// Computed
const filteredItems = computed(() => {
  if (activeTab.value === "todos") {
    return recentItems.value;
  }

  console.log("activeTab: ", activeTab.value);
  return recentItems.value.filter((item) => item.status === activeTab.value);
});

// Buscar itens recentes
const fetchRecentItems = async () => {
  isLoading.value = true;

  try {
    const { data } = await itemService.getAll();
    recentItems.value = data.data;
  } catch (error) {
    console.error("Erro ao buscar itens recentes:", error);
  } finally {
    isLoading.value = false;
  }
};

const fetchStats = async () => {
  try {
    const { data } = await itemService.getCountsByStatus();

    stats.value.totalItems = data.data.total;
    stats.value.lostItems = data.data.perdido;
    stats.value.foundItems = data.data.achado;
    stats.value.recoveredItems = data.data.resgatado;
  } catch (error) {
    console.error("Erro ao buscar itens recentes:", error);
  } finally {
  }
};

// Inicializar
onMounted(() => {
  fetchStats();
  fetchRecentItems();
});
</script>
