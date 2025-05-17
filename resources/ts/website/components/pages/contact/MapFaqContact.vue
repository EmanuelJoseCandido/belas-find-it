<template>
  <section class="py-16 px-20 bg-muted/50">
    <div class="container">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Mapa -->
        <div>
          <h2 class="text-2xl font-bold mb-6">Localização</h2>
          <div class="bg-white rounded-lg overflow-hidden shadow-sm h-96">
            <!-- Aqui seria inserido o mapa, usando um placeholder por enquanto -->
            <div
              class="w-full h-full bg-slate-200 flex items-center justify-center"
            >
              <MapIcon class="h-16 w-16 text-muted-foreground/50" />
            </div>
          </div>
        </div>

        <!-- FAQ -->
        <div>
          <h2 class="text-2xl font-bold mb-6">Perguntas Frequentes</h2>

          <Accordion type="single" collapsible class="space-y-4">
            <AccordionItem value="item-1" class="bg-white rounded-lg">
              <AccordionTrigger class="px-4"
                >Como funciona o processo de devolução de um
                item?</AccordionTrigger
              >
              <AccordionContent class="px-4 pb-4">
                Quando alguém identifica um item como seu, entra em contato
                através da plataforma. Verificamos a propriedade pedindo
                informações específicas sobre o item. Após confirmação,
                facilitamos o contato entre as partes para a devolução segura.
              </AccordionContent>
            </AccordionItem>

            <AccordionItem value="item-2" class="bg-white rounded-lg">
              <AccordionTrigger class="px-4"
                >Quais informações devo fornecer ao cadastrar um
                item?</AccordionTrigger
              >
              <AccordionContent class="px-4 pb-4">
                Recomendamos fornecer uma descrição detalhada do item, incluindo
                cor, tamanho, marca, características específicas, local onde foi
                perdido/encontrado e a data. Fotos claras também são muito úteis
                para facilitar a identificação.
              </AccordionContent>
            </AccordionItem>

            <AccordionItem value="item-3" class="bg-white rounded-lg">
              <AccordionTrigger class="px-4"
                >O serviço tem algum custo?</AccordionTrigger
              >
              <AccordionContent class="px-4 pb-4">
                Não, o EncontraJá é um serviço público gratuito oferecido pela
                prefeitura municipal como parte das iniciativas de cidadania e
                apoio à comunidade.
              </AccordionContent>
            </AccordionItem>

            <AccordionItem value="item-4" class="bg-white rounded-lg">
              <AccordionTrigger class="px-4"
                >Por quanto tempo os itens ficam cadastrados no
                sistema?</AccordionTrigger
              >
              <AccordionContent class="px-4 pb-4">
                Os itens permanecem no sistema por até 6 meses. Após esse
                período, caso não sejam reclamados, podem ser destinados a
                doação ou outro fim adequado, conforme regulamentação municipal.
              </AccordionContent>
            </AccordionItem>

            <AccordionItem value="item-5" class="bg-white rounded-lg">
              <AccordionTrigger class="px-4"
                >Posso cadastrar documentos encontrados?</AccordionTrigger
              >
              <AccordionContent class="px-4 pb-4">
                Sim, documentos pessoais são frequentemente cadastrados. Por
                segurança, forneça apenas informações gerais, como o tipo de
                documento e nome. Não divulgue números de documentos ou
                informações sensíveis através da plataforma.
              </AccordionContent>
            </AccordionItem>
          </Accordion>

          <div class="mt-6">
            <RouterLink to="/sobre">
              <Button variant="outline">Saiba Mais Sobre Nós</Button>
            </RouterLink>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { RouterLink } from "vue-router";

import {
  Accordion,
  AccordionItem,
  AccordionTrigger,
  AccordionContent,
} from "@/ui/components/accordion";

import { Button } from "@/ui/components/button";

import { Map as MapIcon } from "lucide-vue-next";
</script>

<style scoped></style>
