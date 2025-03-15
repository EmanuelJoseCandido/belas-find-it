<template>
    <WebsiteLayout>
        <!-- Hero Section -->
        <section class="bg-primary text-white py-16 px-20">
            <div class="container">
                <div class="max-w-3xl mx-auto text-center">
                    <h1 class="text-3xl md:text-5xl font-bold mb-4">
                        Entre em Contato
                    </h1>
                    <p class="text-xl text-white/90">
                        Estamos aqui para ajudar. Entre em contato conosco para
                        dúvidas, sugestões ou feedback.
                    </p>
                </div>
            </div>
        </section>

        <!-- Conteúdo Principal -->
        <section class="py-16 px-20">
            <div class="container">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Informações de Contato -->
                    <div class="lg:col-span-1 space-y-8">
                        <div>
                            <h2 class="text-2xl font-bold mb-6">
                                Informações de Contato
                            </h2>
                            <p class="text-muted-foreground mb-8">
                                Nosso time está disponível para ajudar você com
                                qualquer questão relacionada a itens perdidos ou
                                encontrados.
                            </p>
                        </div>

                        <div class="space-y-6">
                            <div class="flex gap-4 items-start">
                                <div class="bg-primary/10 p-3 rounded-full">
                                    <MapPinIcon class="h-6 w-6 text-primary" />
                                </div>
                                <div>
                                    <h3 class="font-medium mb-1">Endereço</h3>
                                    <p class="text-muted-foreground">
                                        Prefeitura Municipal
                                    </p>
                                    <p class="text-muted-foreground">
                                        Av. Principal, 1000 - Centro
                                    </p>
                                    <p class="text-muted-foreground">
                                        CEP: 00000-000
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-4 items-start">
                                <div class="bg-primary/10 p-3 rounded-full">
                                    <PhoneIcon class="h-6 w-6 text-primary" />
                                </div>
                                <div>
                                    <h3 class="font-medium mb-1">Telefone</h3>
                                    <p class="text-muted-foreground">
                                        +244 939 722 301
                                    </p>
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
                                    <p class="text-muted-foreground">
                                        contato@encontraja.co.ao
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-4 items-start">
                                <div class="bg-primary/10 p-3 rounded-full">
                                    <ClockIcon class="h-6 w-6 text-primary" />
                                </div>
                                <div>
                                    <h3 class="font-medium mb-1">
                                        Horário de Atendimento
                                    </h3>
                                    <p class="text-muted-foreground">
                                        Segunda a Sexta: 08h às 18h
                                    </p>
                                    <p class="text-muted-foreground">
                                        Sábado: 09h às 13h
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Redes Sociais -->
                        <div>
                            <h3 class="font-medium mb-3">Redes Sociais</h3>
                            <div class="flex gap-3">
                                <Button
                                    variant="outline"
                                    size="icon"
                                    class="rounded-full"
                                >
                                    <FacebookIcon class="h-5 w-5" />
                                </Button>
                                <Button
                                    variant="outline"
                                    size="icon"
                                    class="rounded-full"
                                >
                                    <InstagramIcon class="h-5 w-5" />
                                </Button>
                                <Button
                                    variant="outline"
                                    size="icon"
                                    class="rounded-full"
                                >
                                    <TwitterIcon class="h-5 w-5" />
                                </Button>
                                <Button
                                    variant="outline"
                                    size="icon"
                                    class="rounded-full"
                                >
                                    <YoutubeIcon class="h-5 w-5" />
                                </Button>
                            </div>
                        </div>
                    </div>

                    <!-- Formulário de Contato -->
                    <div class="lg:col-span-2">
                        <Card>
                            <CardHeader>
                                <CardTitle>Envie sua Mensagem</CardTitle>
                                <CardDescription>
                                    Preencha o formulário abaixo para entrar em
                                    contato conosco. Responderemos o mais breve
                                    possível.
                                </CardDescription>
                            </CardHeader>
                            <CardContent>
                                <form
                                    @submit.prevent="handleSubmit"
                                    class="space-y-6"
                                >
                                    <!-- Nome e Email -->
                                    <div
                                        class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                                    >
                                        <div class="space-y-2">
                                            <Label for="name"
                                                >Nome Completo</Label
                                            >
                                            <Input
                                                id="name"
                                                v-model="form.name"
                                                placeholder="Seu nome"
                                                required
                                            />
                                        </div>
                                        <div class="space-y-2">
                                            <Label for="email">E-mail</Label>
                                            <Input
                                                id="email"
                                                v-model="form.email"
                                                type="email"
                                                placeholder="seu@email.com"
                                                required
                                            />
                                        </div>
                                    </div>

                                    <!-- Telefone e Assunto -->
                                    <div
                                        class="grid grid-cols-1 sm:grid-cols-2 gap-4"
                                    >
                                        <div class="space-y-2">
                                            <Label for="phone">Telefone</Label>
                                            <Input
                                                id="phone"
                                                v-model="form.phone"
                                                placeholder="(00) 00000-0000"
                                            />
                                        </div>
                                        <div class="space-y-2">
                                            <Label for="subject">Assunto</Label>
                                            <Select
                                                v-model="form.subject"
                                                required
                                            >
                                                <SelectTrigger id="subject">
                                                    <SelectValue
                                                        placeholder="Selecione o assunto"
                                                    />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem value="duvida"
                                                        >Dúvida</SelectItem
                                                    >
                                                    <SelectItem value="sugestao"
                                                        >Sugestão</SelectItem
                                                    >
                                                    <SelectItem
                                                        value="reclamacao"
                                                        >Reclamação</SelectItem
                                                    >
                                                    <SelectItem value="elogio"
                                                        >Elogio</SelectItem
                                                    >
                                                    <SelectItem value="outro"
                                                        >Outro</SelectItem
                                                    >
                                                </SelectContent>
                                            </Select>
                                        </div>
                                    </div>

                                    <!-- Mensagem -->
                                    <div class="space-y-2">
                                        <Label for="message">Mensagem</Label>
                                        <Textarea
                                            id="message"
                                            v-model="form.message"
                                            placeholder="Digite sua mensagem..."
                                            rows="6"
                                            required
                                        />
                                    </div>

                                    <!-- Item ID -->
                                    <div class="space-y-2">
                                        <Label for="itemId"
                                            >ID do Item (opcional)</Label
                                        >
                                        <Input
                                            id="itemId"
                                            v-model="form.itemId"
                                            placeholder="Se sua mensagem é sobre um item específico, informe o ID"
                                        />
                                        <p
                                            class="text-xs text-muted-foreground"
                                        >
                                            Caso sua mensagem seja referente a
                                            um item específico, informe o ID
                                            para agilizar o atendimento.
                                        </p>
                                    </div>

                                    <!-- Termos -->
                                    <div class="flex items-start gap-2">
                                        <Checkbox
                                            id="terms"
                                            v-model:checked="form.acceptTerms"
                                            required
                                        />
                                        <div>
                                            <Label for="terms" class="text-sm">
                                                Concordo com o
                                                <a
                                                    href="#"
                                                    class="text-primary hover:underline"
                                                    >Tratamento de Dados
                                                    Pessoais</a
                                                >
                                                conforme a
                                                <a
                                                    href="#"
                                                    class="text-primary hover:underline"
                                                    >Política de Privacidade</a
                                                >.
                                            </Label>
                                        </div>
                                    </div>

                                    <!-- Alertas -->
                                    <Alert
                                        v-if="formSuccess"
                                        variant="success"
                                        class="mb-4"
                                    >
                                        <CheckCircle2 class="h-4 w-4 mr-2" />
                                        <AlertTitle
                                            >Mensagem enviada!</AlertTitle
                                        >
                                        <AlertDescription>
                                            Sua mensagem foi enviada com
                                            sucesso. Em breve entraremos em
                                            contato através do e-mail informado.
                                        </AlertDescription>
                                    </Alert>

                                    <Alert
                                        v-if="formError"
                                        variant="destructive"
                                        class="mb-4"
                                    >
                                        <AlertTriangleIcon
                                            class="h-4 w-4 mr-2"
                                        />
                                        <AlertTitle>Erro ao enviar</AlertTitle>
                                        <AlertDescription>
                                            Houve um problema ao enviar sua
                                            mensagem. Tente novamente mais tarde
                                            ou entre em contato por telefone.
                                        </AlertDescription>
                                    </Alert>

                                    <!-- Botão de Envio -->
                                    <div class="flex justify-end">
                                        <Button
                                            type="submit"
                                            size="lg"
                                            :disabled="
                                                isSubmitting ||
                                                !form.acceptTerms
                                            "
                                        >
                                            <Loader2
                                                v-if="isSubmitting"
                                                class="mr-2 h-4 w-4 animate-spin"
                                            />
                                            Enviar Mensagem
                                        </Button>
                                    </div>
                                </form>
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </section>

        <!-- Mapa e FAQ -->
        <section class="py-16 px-20 bg-muted/50">
            <div class="container">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Mapa -->
                    <div>
                        <h2 class="text-2xl font-bold mb-6">Localização</h2>
                        <div
                            class="bg-white rounded-lg overflow-hidden shadow-sm h-96"
                        >
                            <!-- Aqui seria inserido o mapa, usando um placeholder por enquanto -->
                            <div
                                class="w-full h-full bg-slate-200 flex items-center justify-center"
                            >
                                <MapIcon
                                    class="h-16 w-16 text-muted-foreground/50"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- FAQ -->
                    <div>
                        <h2 class="text-2xl font-bold mb-6">
                            Perguntas Frequentes
                        </h2>

                        <Accordion type="single" collapsible class="space-y-4">
                            <AccordionItem
                                value="item-1"
                                class="bg-white rounded-lg"
                            >
                                <AccordionTrigger class="px-4"
                                    >Como funciona o processo de devolução de um
                                    item?</AccordionTrigger
                                >
                                <AccordionContent class="px-4 pb-4">
                                    Quando alguém identifica um item como seu,
                                    entra em contato através da plataforma.
                                    Verificamos a propriedade pedindo
                                    informações específicas sobre o item. Após
                                    confirmação, facilitamos o contato entre as
                                    partes para a devolução segura.
                                </AccordionContent>
                            </AccordionItem>

                            <AccordionItem
                                value="item-2"
                                class="bg-white rounded-lg"
                            >
                                <AccordionTrigger class="px-4"
                                    >Quais informações devo fornecer ao
                                    cadastrar um item?</AccordionTrigger
                                >
                                <AccordionContent class="px-4 pb-4">
                                    Recomendamos fornecer uma descrição
                                    detalhada do item, incluindo cor, tamanho,
                                    marca, características específicas, local
                                    onde foi perdido/encontrado e a data. Fotos
                                    claras também são muito úteis para facilitar
                                    a identificação.
                                </AccordionContent>
                            </AccordionItem>

                            <AccordionItem
                                value="item-3"
                                class="bg-white rounded-lg"
                            >
                                <AccordionTrigger class="px-4"
                                    >O serviço tem algum
                                    custo?</AccordionTrigger
                                >
                                <AccordionContent class="px-4 pb-4">
                                    Não, o EncontraJá é um serviço
                                    público gratuito oferecido pela prefeitura
                                    municipal como parte das iniciativas de
                                    cidadania e apoio à comunidade.
                                </AccordionContent>
                            </AccordionItem>

                            <AccordionItem
                                value="item-4"
                                class="bg-white rounded-lg"
                            >
                                <AccordionTrigger class="px-4"
                                    >Por quanto tempo os itens ficam cadastrados
                                    no sistema?</AccordionTrigger
                                >
                                <AccordionContent class="px-4 pb-4">
                                    Os itens permanecem no sistema por até 6
                                    meses. Após esse período, caso não sejam
                                    reclamados, podem ser destinados a doação ou
                                    outro fim adequado, conforme regulamentação
                                    municipal.
                                </AccordionContent>
                            </AccordionItem>

                            <AccordionItem
                                value="item-5"
                                class="bg-white rounded-lg"
                            >
                                <AccordionTrigger class="px-4"
                                    >Posso cadastrar documentos
                                    encontrados?</AccordionTrigger
                                >
                                <AccordionContent class="px-4 pb-4">
                                    Sim, documentos pessoais são frequentemente
                                    cadastrados. Por segurança, forneça apenas
                                    informações gerais, como o tipo de documento
                                    e nome. Não divulgue números de documentos
                                    ou informações sensíveis através da
                                    plataforma.
                                </AccordionContent>
                            </AccordionItem>
                        </Accordion>

                        <div class="mt-6">
                            <RouterLink to="/sobre">
                                <Button variant="outline"
                                    >Saiba Mais Sobre Nós</Button
                                >
                            </RouterLink>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </WebsiteLayout>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { RouterLink } from "vue-router";
import WebsiteLayout from "@/website/layouts/WebsiteLayout.vue";

import {
    Card,
    CardHeader,
    CardTitle,
    CardDescription,
    CardContent,
} from "@/ui/components/card";
import {
    Accordion,
    AccordionItem,
    AccordionTrigger,
    AccordionContent,
} from "@/ui/components/accordion";
import {
    Select,
    SelectTrigger,
    SelectValue,
    SelectContent,
    SelectItem,
} from "@/ui/components/select";
import { Alert, AlertTitle, AlertDescription } from "@/ui/components/alert";

import { Label } from "@/ui/components/label";
import { Input } from "@/ui/components/input";
import { Button } from "@/ui/components/button";
import { Textarea } from "@/ui/components/textarea";
import { Checkbox } from "@/ui/components/checkbox";

import {
    MapPin as MapPinIcon,
    Phone as PhoneIcon,
    Mail as MailIcon,
    Clock as ClockIcon,
    Map as MapIcon,
    Facebook as FacebookIcon,
    Instagram as InstagramIcon,
    Twitter as TwitterIcon,
    Youtube as YoutubeIcon,
    CheckCircle2,
    AlertTriangle as AlertTriangleIcon,
    Loader2,
} from "lucide-vue-next";

// Estado do formulário
const form = ref({
    name: "",
    email: "",
    phone: "",
    subject: "",
    message: "",
    itemId: "",
    acceptTerms: false,
});

// Estado dos alertas e submissão
const isSubmitting = ref(false);
const formSuccess = ref(false);
const formError = ref(false);

// Manipular envio do formulário
const handleSubmit = async () => {
    isSubmitting.value = true;
    formSuccess.value = false;
    formError.value = false;

    try {
        // Em um ambiente real, enviaríamos os dados para a API
        // const response = await fetch('/api/contact', {
        //   method: 'POST',
        //   headers: { 'Content-Type': 'application/json' },
        //   body: JSON.stringify(form.value)
        // });
        // if (!response.ok) throw new Error('Erro ao enviar mensagem');

        // Simulando uma requisição
        await new Promise((resolve) => setTimeout(resolve, 1500));

        // Mostrar sucesso
        formSuccess.value = true;

        // Resetar formulário
        form.value = {
            name: "",
            email: "",
            phone: "",
            subject: "",
            message: "",
            itemId: "",
            acceptTerms: false,
        };
    } catch (error) {
        console.error("Erro ao enviar mensagem:", error);
        formError.value = true;
    } finally {
        isSubmitting.value = false;
    }
};
</script>
