<template>
  <WebsiteLayout>
    <!-- Hero Section -->
    <section class="bg-primary text-white py-16 px-20">
      <div class="container">
        <div class="max-w-3xl mx-auto text-center">
          <h1 class="text-3xl md:text-5xl font-bold mb-4">Entre em Contacto</h1>
          <p class="text-xl text-white/90">
            Estamos aqui para ajudar. Entre em contacto connosco para dúvidas,
            sugestões ou feedback.
          </p>
        </div>
      </div>
    </section>

    <!-- Conteúdo Principal -->
    <section class="py-16 px-20">
      <div class="container">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Informações de Contacto -->
          <div class="lg:col-span-1 space-y-8">
            <div>
              <h2 class="text-2xl font-bold mb-6">Informações de Contacto</h2>
              <p class="text-muted-foreground mb-8">
                Nosso time está disponível para ajudar você com qualquer questão
                relacionada a itens perdidos ou encontrados.
              </p>
            </div>

            <div class="space-y-6">
              <div class="flex gap-4 items-start">
                <div class="bg-primary/10 p-3 rounded-full">
                  <MapPinIcon class="h-6 w-6 text-primary" />
                </div>
                <div>
                  <h3 class="font-medium mb-1">Endereço</h3>
                  <p class="text-muted-foreground">Luanda, Talatona</p>
                </div>
              </div>

              <div class="flex gap-4 items-start">
                <div class="bg-primary/10 p-3 rounded-full">
                  <PhoneIcon class="h-6 w-6 text-primary" />
                </div>
                <div>
                  <h3 class="font-medium mb-1">Telefone</h3>
                  <p class="text-muted-foreground">+244 939 722 301</p>
                  <p class="text-muted-foreground">
                    Segunda a Sexta: 08h às 18h
                  </p>
                </div>
              </div>

              <div class="flex gap-4 items-start">
                <div class="bg-primary/10 p-3 rounded-full">
                  <MailIcon class="h-6 w-6 text-primary" />
                </div>
                <div>
                  <h3 class="font-medium mb-1">E-mail</h3>
                  <p class="text-muted-foreground">contacto@encontraja.co.ao</p>
                </div>
              </div>

              <div class="flex gap-4 items-start">
                <div class="bg-primary/10 p-3 rounded-full">
                  <ClockIcon class="h-6 w-6 text-primary" />
                </div>
                <div>
                  <h3 class="font-medium mb-1">Horário de Atendimento</h3>
                  <p class="text-muted-foreground">
                    Segunda a Sexta: 08h às 18h
                  </p>
                  <p class="text-muted-foreground">Sábado: 09h às 13h</p>
                </div>
              </div>
            </div>

            <!-- Redes Sociais -->
            <div>
              <h3 class="font-medium mb-3">Redes Sociais</h3>
              <div class="flex gap-3">
                <Button variant="outline" size="icon" class="rounded-full">
                  <FacebookIcon class="h-5 w-5" />
                </Button>
                <Button variant="outline" size="icon" class="rounded-full">
                  <InstagramIcon class="h-5 w-5" />
                </Button>
                <Button variant="outline" size="icon" class="rounded-full">
                  <TwitterIcon class="h-5 w-5" />
                </Button>
                <Button variant="outline" size="icon" class="rounded-full">
                  <YoutubeIcon class="h-5 w-5" />
                </Button>
              </div>
            </div>
          </div>

          <!-- Formulário de Contacto -->
          <FormContact />
        </div>
      </div>
    </section>

    <!-- Mapa e FAQ -->
    <MapFaqContact />
  </WebsiteLayout>
</template>

<script setup lang="ts">
import { ref } from "vue";
import WebsiteLayout from "@/website/layouts/WebsiteLayout.vue";

import { Button } from "@/ui/components/button";

import FormContact from "@/website/components/pages/contact/FormContact.vue";
import MapFaqContact from "@/website/components/pages/contact/MapFaqContact.vue";

import {
  MapPin as MapPinIcon,
  Phone as PhoneIcon,
  Mail as MailIcon,
  Clock as ClockIcon,
  Facebook as FacebookIcon,
  Instagram as InstagramIcon,
  Twitter as TwitterIcon,
  Youtube as YoutubeIcon,
} from "lucide-vue-next";
</script>
