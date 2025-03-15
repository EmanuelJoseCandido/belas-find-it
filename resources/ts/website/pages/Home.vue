<template>
    <div class="min-h-screen bg-gradient-to-b from-slate-50 to-slate-100">
      <!-- Header -->
      <header class="sticky top-0 z-50 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
        <div class="container flex h-16 items-center">
          <RouterLink to="/" class="flex items-center">
            <KeyIcon class="w-6 h-6 mr-2 text-primary" />
            <span class="font-bold text-xl">EncontraMunicipal</span>
          </RouterLink>
          <nav class="ml-auto flex gap-6">
            <RouterLink to="/" class="text-sm font-medium transition-colors hover:text-primary">Início</RouterLink>
            <RouterLink to="/perdidos" class="text-sm font-medium transition-colors hover:text-primary">Perdidos</RouterLink>
            <RouterLink to="/achados" class="text-sm font-medium transition-colors hover:text-primary">Achados</RouterLink>
            <RouterLink to="/cadastrar" class="text-sm font-medium transition-colors hover:text-primary">Cadastrar Item</RouterLink>
            <RouterLink to="/contato" class="text-sm font-medium transition-colors hover:text-primary">Contato</RouterLink>
          </nav>
          <div class="ml-4 flex items-center gap-4">
            <Button variant="outline" size="sm" class="hidden md:flex">
              <UserIcon class="mr-2 h-4 w-4" />
              Entrar
            </Button>
            <Button size="sm" class="hidden md:flex">Cadastrar-se</Button>
          </div>
        </div>
      </header>
  
      <!-- Hero Section -->
      <section class="container py-16 md:py-24">
        <div class="grid gap-6 lg:grid-cols-2 lg:gap-12 items-center">
          <div class="space-y-4">
            <div class="inline-block rounded-lg bg-primary/10 px-3 py-1 text-sm text-primary">
              Novo Serviço Municipal
            </div>
            <h1 class="text-4xl font-bold tracking-tighter sm:text-5xl md:text-6xl">
              Perdeu ou Encontrou Algo?
            </h1>
            <p class="text-muted-foreground text-lg md:text-xl">
              Ajudamos você a reencontrar itens perdidos ou devolver objetos encontrados no município.
            </p>
            <div class="flex flex-col sm:flex-row gap-3">
              <Button size="lg">
                <SearchIcon class="mr-2 h-4 w-4" />
                Procurar Item Perdido
              </Button>
              <Button size="lg" variant="outline">
                <PlusIcon class="mr-2 h-4 w-4" />
                Cadastrar Item Encontrado
              </Button>
            </div>
          </div>
          <div class="relative">
            <div class="absolute -top-12 -left-12 h-72 w-72 rounded-full bg-primary/20 blur-3xl"></div>
            <img
              src="/api/placeholder/600/400"
              alt="Ilustração de perdidos e achados"
              class="mx-auto w-full max-w-lg rounded-lg object-cover shadow-xl"
            />
          </div>
        </div>
      </section>
  
      <!-- Como Funciona -->
      <section class="container py-12 md:py-16">
        <h2 class="text-3xl font-bold text-center mb-10">Como Funciona</h2>
        <div class="grid md:grid-cols-3 gap-8">
          <div class="relative group">
            <div class="absolute inset-0 bg-gradient-to-r from-primary/60 to-primary/50 rounded-lg blur-sm group-hover:blur transition-all duration-500"></div>
            <div class="relative p-6 bg-white rounded-lg shadow-lg">
              <div class="rounded-full bg-primary/10 w-12 h-12 flex items-center justify-center mb-4">
                <ClipboardListIcon class="h-6 w-6 text-primary" />
              </div>
              <h3 class="text-xl font-bold mb-2">1. Cadastre</h3>
              <p class="text-muted-foreground">Cadastre o item perdido ou encontrado com detalhes e fotos.</p>
            </div>
          </div>
          <div class="relative group">
            <div class="absolute inset-0 bg-gradient-to-r from-amber-500/60 to-amber-500/50 rounded-lg blur-sm group-hover:blur transition-all duration-500"></div>
            <div class="relative p-6 bg-white rounded-lg shadow-lg">
              <div class="rounded-full bg-amber-500/10 w-12 h-12 flex items-center justify-center mb-4">
                <SearchIcon class="h-6 w-6 text-amber-500" />
              </div>
              <h3 class="text-xl font-bold mb-2">2. Procure</h3>
              <p class="text-muted-foreground">Encontre correspondências entre itens perdidos e achados.</p>
            </div>
          </div>
          <div class="relative group">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/60 to-emerald-600/50 rounded-lg blur-sm group-hover:blur transition-all duration-500"></div>
            <div class="relative p-6 bg-white rounded-lg shadow-lg">
              <div class="rounded-full bg-emerald-600/10 w-12 h-12 flex items-center justify-center mb-4">
                <HeartIcon class="h-6 w-6 text-emerald-600" />
              </div>
              <h3 class="text-xl font-bold mb-2">3. Recupere</h3>
              <p class="text-muted-foreground">Entre em contato e combine a devolução do item de forma segura.</p>
            </div>
          </div>
        </div>
      </section>
  
      <!-- Itens Recentes -->
      <section class="container py-12 md:py-16">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-3xl font-bold">Itens Recentes</h2>
          <Button variant="outline">Ver Todos</Button>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
          <Card>
            <CardHeader>
              <Badge variant="secondary">Perdido</Badge>
              <CardTitle class="mt-2">Carteira Marrom</CardTitle>
              <CardDescription>Perdida no Parque Central</CardDescription>
            </CardHeader>
            <CardContent>
              <img
                src="/api/placeholder/300/200"
                alt="Carteira"
                class="w-full rounded-md object-cover"
              />
              <div class="mt-4 flex items-center text-muted-foreground text-sm">
                <CalendarIcon class="mr-1 h-4 w-4" />
                <span>Hoje</span>
                <MapPinIcon class="ml-4 mr-1 h-4 w-4" />
                <span>Parque Central</span>
              </div>
            </CardContent>
            <CardFooter>
              <Button variant="outline" class="w-full">Ver Detalhes</Button>
            </CardFooter>
          </Card>
          <Card>
            <CardHeader>
              <Badge variant="success">Achado</Badge>
              <CardTitle class="mt-2">Óculos de Sol</CardTitle>
              <CardDescription>Encontrado na Praça Principal</CardDescription>
            </CardHeader>
            <CardContent>
              <img
                src="/api/placeholder/300/200"
                alt="Óculos"
                class="w-full rounded-md object-cover"
              />
              <div class="mt-4 flex items-center text-muted-foreground text-sm">
                <CalendarIcon class="mr-1 h-4 w-4" />
                <span>Ontem</span>
                <MapPinIcon class="ml-4 mr-1 h-4 w-4" />
                <span>Praça Principal</span>
              </div>
            </CardContent>
            <CardFooter>
              <Button variant="outline" class="w-full">Ver Detalhes</Button>
            </CardFooter>
          </Card>
          <Card>
            <CardHeader>
              <Badge variant="secondary">Perdido</Badge>
              <CardTitle class="mt-2">Chaves com Chaveiro</CardTitle>
              <CardDescription>Perdidas no Terminal de Ônibus</CardDescription>
            </CardHeader>
            <CardContent>
              <img
                src="/api/placeholder/300/200"
                alt="Chaves"
                class="w-full rounded-md object-cover"
              />
              <div class="mt-4 flex items-center text-muted-foreground text-sm">
                <CalendarIcon class="mr-1 h-4 w-4" />
                <span>Há 2 dias</span>
                <MapPinIcon class="ml-4 mr-1 h-4 w-4" />
                <span>Terminal de Ônibus</span>
              </div>
            </CardContent>
            <CardFooter>
              <Button variant="outline" class="w-full">Ver Detalhes</Button>
            </CardFooter>
          </Card>
        </div>
      </section>
  
      <!-- Estatísticas -->
      <section class="container py-12 md:py-16 bg-primary/5 -mx-4 px-4 rounded-lg">
        <h2 class="text-3xl font-bold text-center mb-10">Impacto na Comunidade</h2>
        <div class="grid md:grid-cols-3 gap-8 text-center">
          <div>
            <div class="text-4xl font-bold text-primary mb-2">147</div>
            <p class="text-muted-foreground">Itens Recuperados</p>
          </div>
          <div>
            <div class="text-4xl font-bold text-primary mb-2">234</div>
            <p class="text-muted-foreground">Itens Cadastrados</p>
          </div>
          <div>
            <div class="text-4xl font-bold text-primary mb-2">92%</div>
            <p class="text-muted-foreground">Taxa de Sucesso</p>
          </div>
        </div>
      </section>
  
      <!-- Depoimentos -->
      <section class="container py-12 md:py-16">
        <h2 class="text-3xl font-bold text-center mb-10">Depoimentos</h2>
        <div class="grid md:grid-cols-2 gap-8">
          <Card>
            <CardContent class="pt-6">
              <blockquote class="space-y-2">
                <p class="text-muted-foreground">
                  "Perdi minha carteira com todos os documentos e dinheiro no centro da cidade. 
                  Graças a esta plataforma consegui recuperar tudo intacto no mesmo dia!"
                </p>
              </blockquote>
              <div class="flex items-center mt-4">
                <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                  <UserIcon class="h-5 w-5 text-primary" />
                </div>
                <div class="ml-3">
                  <p class="font-semibold">Ana Silva</p>
                  <p class="text-sm text-muted-foreground">Moradora</p>
                </div>
              </div>
            </CardContent>
          </Card>
          <Card>
            <CardContent class="pt-6">
              <blockquote class="space-y-2">
                <p class="text-muted-foreground">
                  "Encontrei um celular no parque e não sabia como encontrar o dono. 
                  Cadastrei aqui e em menos de 24 horas consegui devolvê-lo."
                </p>
              </blockquote>
              <div class="flex items-center mt-4">
                <div class="h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center">
                  <UserIcon class="h-5 w-5 text-primary" />
                </div>
                <div class="ml-3">
                  <p class="font-semibold">Carlos Oliveira</p>
                  <p class="text-sm text-muted-foreground">Comerciante</p>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </section>
  
      <!-- Footer -->
      <footer class="border-t bg-background">
        <div class="container py-8">
          <div class="grid gap-8 md:grid-cols-3">
            <div>
              <div class="flex items-center">
                <KeyIcon class="w-6 h-6 mr-2 text-primary" />
                <span class="font-bold text-xl">EncontraMunicipal</span>
              </div>
              <p class="mt-2 text-muted-foreground">
                Conectando pessoas e objetos perdidos em nosso município.
              </p>
            </div>
            <div class="space-y-2">
              <h3 class="font-semibold">Links Rápidos</h3>
              <div class="grid grid-cols-2 gap-2">
                <RouterLink to="/" class="text-sm text-muted-foreground hover:text-primary">Início</RouterLink>
                <RouterLink to="/perdidos" class="text-sm text-muted-foreground hover:text-primary">Perdidos</RouterLink>
                <RouterLink to="/achados" class="text-sm text-muted-foreground hover:text-primary">Achados</RouterLink>
                <RouterLink to="/cadastrar" class="text-sm text-muted-foreground hover:text-primary">Cadastrar Item</RouterLink>
                <RouterLink to="/sobre" class="text-sm text-muted-foreground hover:text-primary">Sobre Nós</RouterLink>
                <RouterLink to="/contato" class="text-sm text-muted-foreground hover:text-primary">Contato</RouterLink>
              </div>
            </div>
            <div class="space-y-2">
              <h3 class="font-semibold">Contato</h3>
              <p class="text-sm text-muted-foreground">contato@encontramunicipal.gov.br</p>
              <p class="text-sm text-muted-foreground">(00) 1234-5678</p>
              <div class="flex items-center gap-4 mt-4">
                <Button variant="outline" size="icon" class="rounded-full">
                  <FacebookIcon class="h-4 w-4" />
                </Button>
                <Button variant="outline" size="icon" class="rounded-full">
                  <InstagramIcon class="h-4 w-4" />
                </Button>
                <Button variant="outline" size="icon" class="rounded-full">
                  <TwitterIcon class="h-4 w-4" />
                </Button>
              </div>
            </div>
          </div>
          <div class="border-t mt-8 pt-4 text-center text-sm text-muted-foreground">
            © 2025 EncontraMunicipal. Todos os direitos reservados.
          </div>
        </div>
      </footer>
    </div>
  </template>
  
  <script setup lang="ts">
  import { RouterLink } from 'vue-router'
  import { Button } from '@/ui/components/button'
  import { Card, CardContent, CardFooter, CardHeader, CardTitle, CardDescription } from '@/ui/components/card'
  import { Badge } from '@/ui/components/badge'
  import { 
    KeyIcon, 
    UserIcon, 
    SearchIcon, 
    PlusIcon, 
    ClipboardListIcon, 
    HeartIcon,
    CalendarIcon,
    MapPinIcon,
    FacebookIcon,
    InstagramIcon,
    TwitterIcon
  } from 'lucide-vue-next'
  </script>