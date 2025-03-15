<template>
    <WebsiteLayout>
        <!-- Hero Section -->
        <section class="bg-primary text-white py-16 px-20">
            <div class="container">
                <div class="max-w-3xl mx-auto text-center">
                    <h1 class="text-3xl md:text-5xl font-bold mb-4">
                        Sobre o EncontraMunicipal
                    </h1>
                    <p class="text-xl text-white/90">
                        Uma iniciativa para conectar pessoas a seus pertences
                        perdidos e ajudar nossa comunidade.
                    </p>
                </div>
            </div>
        </section>

        <!-- Nossa Missão -->
        <section class="py-16 px-20">
            <div class="container">
                <div
                    class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center"
                >
                    <div>
                        <h2 class="text-3xl font-bold mb-6">Nossa Missão</h2>
                        <p class="text-muted-foreground mb-4">
                            O EncontraMunicipal nasceu da necessidade de criar
                            um sistema eficiente e centralizado para ajudar
                            cidadãos a recuperarem seus pertences perdidos e
                            facilitar a devolução de itens encontrados.
                        </p>
                        <p class="text-muted-foreground mb-4">
                            Nossa missão é conectar pessoas a seus objetos
                            perdidos de forma rápida e segura, promovendo
                            valores de cidadania, honestidade e solidariedade em
                            nossa comunidade.
                        </p>
                        <p class="text-muted-foreground">
                            Acreditamos que um sistema de perdidos e achados bem
                            estruturado fortalece os laços comunitários e reduz
                            o estresse e as perdas financeiras causadas por
                            objetos extraviados.
                        </p>
                    </div>
                    <div class="flex justify-center">
                        <img
                            src="/api/placeholder/600/400"
                            alt="Pessoas reunidas"
                            class="rounded-lg shadow-md"
                        />
                    </div>
                </div>
            </div>
        </section>

        <!-- Valores -->
        <section class="py-16 px-20 bg-muted/50">
            <div class="container">
                <h2 class="text-3xl font-bold mb-12 text-center">
                    Nossos Valores
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex justify-center mb-4">
                            <div
                                class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center"
                            >
                                <HeartIcon class="h-8 w-8 text-primary" />
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold text-center mb-2">
                            Solidariedade
                        </h3>
                        <p class="text-center text-muted-foreground">
                            Acreditamos no poder de ajudar o próximo. Cada item
                            devolvido representa um gesto de empatia que
                            fortalece nossa comunidade.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex justify-center mb-4">
                            <div
                                class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center"
                            >
                                <ShieldIcon class="h-8 w-8 text-primary" />
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold text-center mb-2">
                            Confiança
                        </h3>
                        <p class="text-center text-muted-foreground">
                            Nosso sistema é construído sobre a confiança entre
                            cidadãos e a certeza de que pessoas honestas podem
                            fazer a diferença.
                        </p>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <div class="flex justify-center mb-4">
                            <div
                                class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center"
                            >
                                <UsersIcon class="h-8 w-8 text-primary" />
                            </div>
                        </div>
                        <h3 class="text-xl font-semibold text-center mb-2">
                            Comunidade
                        </h3>
                        <p class="text-center text-muted-foreground">
                            Fortalecemos os laços comunitários ao criar pontes
                            entre pessoas e promover o espírito de cooperação
                            mútua.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Como Funciona -->
        <section class="py-16 px-20">
            <div class="container">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">Como Funciona</h2>
                    <p class="text-muted-foreground max-w-2xl mx-auto">
                        Nossa plataforma foi desenvolvida para ser intuitiva e
                        eficiente, conectando pessoas a seus pertences perdidos.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                    <div>
                        <div class="space-y-8">
                            <div class="flex">
                                <div
                                    class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center font-bold text-lg mr-4 flex-shrink-0"
                                >
                                    1
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold mb-2">
                                        Cadastro de Itens
                                    </h3>
                                    <p class="text-muted-foreground">
                                        Cidadãos podem cadastrar itens perdidos
                                        ou encontrados, fornecendo descrições
                                        detalhadas, fotos e informações de
                                        localização. Quanto mais detalhes,
                                        maiores as chances de conexão.
                                    </p>
                                </div>
                            </div>

                            <div class="flex">
                                <div
                                    class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center font-bold text-lg mr-4 flex-shrink-0"
                                >
                                    2
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold mb-2">
                                        Sistema de Busca
                                    </h3>
                                    <p class="text-muted-foreground">
                                        Nossa plataforma conta com um sistema
                                        avançado de busca e filtragem,
                                        permitindo aos usuários localizar itens
                                        por categoria, local, data ou
                                        características específicas.
                                    </p>
                                </div>
                            </div>

                            <div class="flex">
                                <div
                                    class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center font-bold text-lg mr-4 flex-shrink-0"
                                >
                                    3
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold mb-2">
                                        Comunicação Segura
                                    </h3>
                                    <p class="text-muted-foreground">
                                        Quando um usuário identifica seu item,
                                        ele pode entrar em contato através da
                                        plataforma, garantindo uma comunicação
                                        segura para combinar a devolução.
                                    </p>
                                </div>
                            </div>

                            <div class="flex">
                                <div
                                    class="w-12 h-12 bg-primary text-white rounded-full flex items-center justify-center font-bold text-lg mr-4 flex-shrink-0"
                                >
                                    4
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold mb-2">
                                        Verificação e Devolução
                                    </h3>
                                    <p class="text-muted-foreground">
                                        Implementamos mecanismos para verificar
                                        a propriedade dos itens e facilitar um
                                        processo de devolução seguro, protegendo
                                        ambas as partes.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <img
                            src="/api/placeholder/600/600"
                            alt="Diagrama de processo"
                            class="rounded-lg shadow-md"
                        />

                        <div
                            class="absolute -top-4 -left-4 bg-white p-4 rounded-lg shadow-md hidden md:block"
                        >
                            <BadgeCheckIcon class="h-6 w-6 text-primary mb-2" />
                            <p class="text-sm font-medium">
                                Taxa de sucesso de 85%
                            </p>
                            <p class="text-xs text-muted-foreground">
                                na recuperação de itens
                            </p>
                        </div>

                        <div
                            class="absolute -bottom-4 -right-4 bg-white p-4 rounded-lg shadow-md hidden md:block"
                        >
                            <ClockIcon class="h-6 w-6 text-primary mb-2" />
                            <p class="text-sm font-medium">
                                Tempo médio de 7 dias
                            </p>
                            <p class="text-xs text-muted-foreground">
                                para resgatar um item
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Estatísticas -->
        <section class="py-16 px-20">
            <div class="container">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">Nosso Impacto</h2>
                    <p class="text-muted-foreground max-w-2xl mx-auto">
                        O EncontraMunicipal tem demonstrado resultados
                        significativos desde sua implementação. Confira alguns
                        números que refletem o impacto positivo em nossa
                        comunidade.
                    </p>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8">
                    <div class="bg-white rounded-lg p-6 text-center shadow-sm">
                        <p class="text-4xl font-bold text-primary mb-2">
                            1.240+
                        </p>
                        <p class="text-muted-foreground">Itens Cadastrados</p>
                    </div>

                    <div class="bg-white rounded-lg p-6 text-center shadow-sm">
                        <p class="text-4xl font-bold text-primary mb-2">85%</p>
                        <p class="text-muted-foreground">Taxa de Recuperação</p>
                    </div>

                    <div class="bg-white rounded-lg p-6 text-center shadow-sm">
                        <p class="text-4xl font-bold text-primary mb-2">7</p>
                        <p class="text-muted-foreground">Dias (tempo médio)</p>
                    </div>

                    <div class="bg-white rounded-lg p-6 text-center shadow-sm">
                        <p class="text-4xl font-bold text-primary mb-2">
                            4.9/5
                        </p>
                        <p class="text-muted-foreground">
                            Satisfação do Usuário
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="py-16 px-20 bg-primary text-white">
            <div class="container text-center">
                <h2 class="text-3xl font-bold mb-4">
                    Junte-se a nós nessa missão
                </h2>
                <p class="text-lg mb-8 text-white/90 max-w-2xl mx-auto">
                    Você perdeu ou encontrou algo? Cadastre-se agora e faça
                    parte dessa rede de solidariedade. Juntos, podemos fazer a
                    diferença em nossa comunidade.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <RouterLink to="/cadastrar">
                        <Button size="lg" variant="secondary"
                            >Cadastrar um Item</Button
                        >
                    </RouterLink>
                    <RouterLink to="/contato">
                        <Button
                            size="lg"
                            variant="outline"
                            class="bg-transparent text-white border-white hover:bg-white/10"
                            >Entre em Contato</Button
                        >
                    </RouterLink>
                </div>
            </div>
        </section>
    </WebsiteLayout>
</template>

<script setup lang="ts">
import { RouterLink } from "vue-router";
import WebsiteLayout from "@/website/layouts/WebsiteLayout.vue";
import { Button } from "@/ui/components/button";
import {
    Heart as HeartIcon,
    Shield as ShieldIcon,
    Users as UsersIcon,
    BadgeCheck as BadgeCheckIcon,
    Clock as ClockIcon,
} from "lucide-vue-next";
</script>
