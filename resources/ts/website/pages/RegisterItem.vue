<template>
    <WebsiteLayout>
        <div class="container py-8 px-20">
            <div class="mb-8">
                <h1 class="text-3xl font-bold mb-2">Cadastrar Item</h1>
                <p class="text-muted-foreground">
                    Preencha o formulário abaixo com os detalhes do item perdido
                    ou encontrado. Quanto mais informações você fornecer,
                    maiores as chances de conexão.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="md:col-span-2">
                    <form @submit.prevent="handleSubmit" class="space-y-6">
                        <!-- Tipo de Item -->
                        <div class="p-6 bg-white rounded-lg shadow-sm border">
                            <h2 class="text-xl font-semibold mb-4">
                                Tipo de Item
                            </h2>

                            <div class="grid grid-cols-2 gap-4">
                                <div
                                    class="border rounded-lg p-4 cursor-pointer transition-colors"
                                    :class="
                                        itemType === 'perdido'
                                            ? 'bg-primary/10 border-primary'
                                            : 'hover:bg-muted'
                                    "
                                    @click="itemType = 'perdido'"
                                >
                                    <div class="flex justify-center mb-2">
                                        <HelpCircleIcon
                                            class="h-8 w-8"
                                            :class="
                                                itemType === 'perdido'
                                                    ? 'text-primary'
                                                    : 'text-muted-foreground'
                                            "
                                        />
                                    </div>
                                    <h3 class="font-medium text-center">
                                        Item Perdido
                                    </h3>
                                    <p
                                        class="text-center text-sm text-muted-foreground mt-1"
                                    >
                                        Perdi um item e estou procurando
                                    </p>
                                </div>

                                <div
                                    class="border rounded-lg p-4 cursor-pointer transition-colors"
                                    :class="
                                        itemType === 'achado'
                                            ? 'bg-primary/10 border-primary'
                                            : 'hover:bg-muted'
                                    "
                                    @click="itemType = 'achado'"
                                >
                                    <div class="flex justify-center mb-2">
                                        <SearchIcon
                                            class="h-8 w-8"
                                            :class="
                                                itemType === 'achado'
                                                    ? 'text-primary'
                                                    : 'text-muted-foreground'
                                            "
                                        />
                                    </div>
                                    <h3 class="font-medium text-center">
                                        Item Encontrado
                                    </h3>
                                    <p
                                        class="text-center text-sm text-muted-foreground mt-1"
                                    >
                                        Encontrei um item e quero devolvê-lo
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Informações Básicas -->
                        <div class="p-6 bg-white rounded-lg shadow-sm border">
                            <h2 class="text-xl font-semibold mb-4">
                                Informações Básicas
                            </h2>

                            <div class="space-y-4">
                                <div>
                                    <Label for="title">Título</Label>
                                    <Input
                                        id="title"
                                        v-model="form.title"
                                        placeholder="Ex: Carteira de couro marrom"
                                        required
                                        maxlength="100"
                                    />
                                    <p
                                        class="text-xs text-muted-foreground mt-1"
                                    >
                                        Título breve e descritivo para o item
                                    </p>
                                </div>

                                <div>
                                    <Label for="description">Descrição</Label>
                                    <Textarea
                                        id="description"
                                        v-model="form.description"
                                        placeholder="Descreva o item com detalhes: cor, tamanho, marca, características especiais, etc."
                                        rows="4"
                                        required
                                        maxlength="500"
                                    />
                                    <p
                                        class="text-xs text-muted-foreground mt-1"
                                    >
                                        {{ form.description.length }}/500
                                        caracteres
                                    </p>
                                </div>

                                <div>
                                    <Label for="category">Categoria</Label>
                                    <Select v-model="form.category" required>
                                        <SelectTrigger id="category">
                                            <SelectValue
                                                placeholder="Selecione uma categoria"
                                            />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="category in categories"
                                                :key="category.id"
                                                :value="category.id.toString()"
                                            >
                                                {{ category.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>
                        </div>

                        <!-- Local e Data -->
                        <div class="p-6 bg-white rounded-lg shadow-sm border">
                            <h2 class="text-xl font-semibold mb-4">
                                Local e Data
                            </h2>

                            <div class="space-y-4">
                                <div>
                                    <Label for="location">Local</Label>
                                    <Input
                                        id="location"
                                        v-model="form.location"
                                        placeholder="Ex: Parque Central, próximo ao chafariz"
                                        required
                                    />
                                    <p
                                        class="text-xs text-muted-foreground mt-1"
                                    >
                                        Descreva o local com o máximo de
                                        detalhes possível
                                    </p>
                                </div>

                                <div>
                                    <Label for="date">Data</Label>
                                    <Input
                                        id="date"
                                        v-model="form.date"
                                        type="date"
                                        required
                                        :max="today"
                                    />
                                    <p
                                        class="text-xs text-muted-foreground mt-1"
                                    >
                                        Data em que o item foi
                                        {{
                                            itemType === "perdido"
                                                ? "perdido"
                                                : "encontrado"
                                        }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Imagem -->
                        <div class="p-6 bg-white rounded-lg shadow-sm border">
                            <h2 class="text-xl font-semibold mb-4">Imagem</h2>

                            <div class="space-y-4">
                                <div
                                    class="border-2 border-dashed rounded-lg p-6 text-center"
                                    :class="{ 'border-primary': isDragging }"
                                >
                                    <div
                                        v-if="!previewImage"
                                        class="cursor-pointer"
                                        @click="openFilePicker"
                                        @dragover.prevent="isDragging = true"
                                        @dragleave.prevent="isDragging = false"
                                        @drop.prevent="handleDrop"
                                    >
                                        <UploadIcon
                                            class="h-12 w-12 mx-auto text-muted-foreground mb-2"
                                        />
                                        <p class="font-medium">
                                            Arraste uma imagem ou clique para
                                            escolher
                                        </p>
                                        <p
                                            class="text-sm text-muted-foreground mt-1"
                                        >
                                            JPG ou PNG até 5MB
                                        </p>
                                    </div>

                                    <div v-else class="relative">
                                        <img
                                            :src="previewImage"
                                            alt="Preview"
                                            class="max-h-64 mx-auto rounded"
                                        />
                                        <Button
                                            variant="destructive"
                                            size="icon"
                                            class="absolute top-2 right-2 h-8 w-8 rounded-full"
                                            @click.prevent="clearImage"
                                        >
                                            <XIcon class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>

                                <Input
                                    ref="fileInput"
                                    id="image"
                                    type="file"
                                    class="hidden"
                                    accept="image/jpeg,image/png"
                                    @change="handleFileChange"
                                />

                                <p class="text-xs text-muted-foreground">
                                    Uma imagem clara do item ajuda na
                                    identificação. Não inclua informações
                                    pessoais visíveis.
                                </p>
                            </div>
                        </div>

                        <!-- Informações de Contato -->
                        <div class="p-6 bg-white rounded-lg shadow-sm border">
                            <h2 class="text-xl font-semibold mb-4">
                                Informações de Contato
                            </h2>

                            <div
                                v-if="!isAuthenticated"
                                class="mb-4 p-4 bg-muted rounded-lg"
                            >
                                <div class="flex items-start gap-3">
                                    <InfoIcon
                                        class="h-5 w-5 text-primary mt-0.5"
                                    />
                                    <div>
                                        <p class="font-medium">
                                            Faça login para facilitar o cadastro
                                        </p>
                                        <p
                                            class="text-sm text-muted-foreground"
                                        >
                                            Ao fazer login, suas informações de
                                            contato serão preenchidas
                                            automaticamente e você poderá
                                            gerenciar seus itens.
                                        </p>
                                        <div class="mt-2">
                                            <RouterLink to="/auth/login">
                                                <Button
                                                    variant="outline"
                                                    size="sm"
                                                    >Fazer Login</Button
                                                >
                                            </RouterLink>
                                            <RouterLink
                                                to="/auth/register"
                                                class="ml-2"
                                            >
                                                <Button size="sm"
                                                    >Cadastrar-se</Button
                                                >
                                            </RouterLink>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <Label for="name">Nome Completo</Label>
                                    <Input
                                        id="name"
                                        v-model="form.name"
                                        placeholder="Seu nome completo"
                                        required
                                    />
                                </div>

                                <div>
                                    <Label for="email">E-mail</Label>
                                    <Input
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        placeholder="seu@email.com"
                                        required
                                    />
                                </div>

                                <div>
                                    <Label for="phone">Telefone</Label>
                                    <Input
                                        id="phone"
                                        v-model="form.phone"
                                        placeholder="(00) 00000-0000"
                                        required
                                    />
                                </div>

                                <div class="flex items-start gap-2">
                                    <Checkbox
                                        id="terms"
                                        v-model:checked="form.acceptTerms"
                                        required
                                    />
                                    <div>
                                        <Label for="terms" class="text-sm">
                                            Aceito os
                                            <a
                                                href="#"
                                                class="text-primary hover:underline"
                                                >Termos de Uso</a
                                            >
                                            e
                                            <a
                                                href="#"
                                                class="text-primary hover:underline"
                                                >Política de Privacidade</a
                                            >
                                        </Label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botão de Envio -->
                        <div class="flex justify-end">
                            <Button
                                type="submit"
                                size="lg"
                                :disabled="isSubmitting || !form.acceptTerms"
                            >
                                <Loader2
                                    v-if="isSubmitting"
                                    class="mr-2 h-4 w-4 animate-spin"
                                />
                                Cadastrar Item
                            </Button>
                        </div>
                    </form>
                </div>

                <!-- Sidebar com dicas -->
                <div>
                    <div
                        class="bg-white rounded-lg shadow-sm border p-6 sticky top-20"
                    >
                        <h2 class="text-xl font-semibold mb-4">
                            Dicas para um bom cadastro
                        </h2>

                        <div class="space-y-4">
                            <div class="flex gap-3">
                                <BadgeCheckIcon
                                    class="h-5 w-5 text-primary flex-shrink-0 mt-0.5"
                                />
                                <div>
                                    <p class="font-medium">Seja detalhista</p>
                                    <p class="text-sm text-muted-foreground">
                                        Quanto mais detalhes você fornecer sobre
                                        o item, maiores as chances de
                                        identificação.
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <CameraIcon
                                    class="h-5 w-5 text-primary flex-shrink-0 mt-0.5"
                                />
                                <div>
                                    <p class="font-medium">
                                        Adicione uma foto clara
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        Imagens nítidas do item aumentam
                                        significativamente as chances de
                                        reconhecimento.
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <MapPinIcon
                                    class="h-5 w-5 text-primary flex-shrink-0 mt-0.5"
                                />
                                <div>
                                    <p class="font-medium">
                                        Descreva o local precisamente
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        Indique pontos de referência próximos
                                        para facilitar a localização.
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <CalendarIcon
                                    class="h-5 w-5 text-primary flex-shrink-0 mt-0.5"
                                />
                                <div>
                                    <p class="font-medium">
                                        Informe a data correta
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        A data correta ajuda a conectar itens
                                        perdidos e achados no mesmo período.
                                    </p>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <ShieldCheckIcon
                                    class="h-5 w-5 text-primary flex-shrink-0 mt-0.5"
                                />
                                <div>
                                    <p class="font-medium">
                                        Proteja suas informações
                                    </p>
                                    <p class="text-sm text-muted-foreground">
                                        Evite compartilhar documentos pessoais,
                                        senhas ou informações confidenciais.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <Separator class="my-6" />

                        <div>
                            <h3 class="font-medium mb-2">Precisa de ajuda?</h3>
                            <p class="text-sm text-muted-foreground mb-4">
                                Se tiver dúvidas sobre como cadastrar um item,
                                entre em contato conosco.
                            </p>
                            <RouterLink to="/contato">
                                <Button variant="outline" class="w-full"
                                    >Fale Conosco</Button
                                >
                            </RouterLink>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Success Modal -->
            <Dialog :open="showSuccessModal" @close="showSuccessModal = false">
                <DialogContent class="sm:max-w-md">
                    <DialogHeader>
                        <DialogTitle>Item Cadastrado com Sucesso!</DialogTitle>
                        <DialogDescription>
                            Seu item foi cadastrado e já está disponível em
                            nossa plataforma.
                        </DialogDescription>
                    </DialogHeader>

                    <div class="flex items-center justify-center py-6">
                        <div class="bg-primary/10 rounded-full p-3">
                            <CheckIcon class="h-8 w-8 text-primary" />
                        </div>
                    </div>

                    <p class="text-center mb-4">
                        Agradecemos por utilizar nossa plataforma. Esperamos que
                        seu item
                        {{
                            itemType === "perdido"
                                ? "seja encontrado"
                                : "seja devolvido ao dono"
                        }}
                        em breve.
                    </p>

                    <DialogFooter class="sm:justify-center">
                        <Button @click="showSuccessModal = false"
                            >Fechar</Button
                        >
                        <Button variant="outline" @click="goToItemPage"
                            >Ver Item Cadastrado</Button
                        >
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </WebsiteLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from "vue";
import { RouterLink, useRouter } from "vue-router";
import WebsiteLayout from "@/website/layouts/WebsiteLayout.vue";
import { Label } from "@/ui/components/label";
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/ui/components/select";

import { Input } from "@/ui/components/input";
import { Button } from "@/ui/components/button";
import { Textarea } from "@/ui/components/textarea";
import { Checkbox } from "@/ui/components/checkbox";
import { Separator } from "@/ui/components/separator";

import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogDescription,
    DialogFooter,
} from "@/ui/components/dialog";

import {
    HelpCircle as HelpCircleIcon,
    Search as SearchIcon,
    Upload as UploadIcon,
    X as XIcon,
    Info as InfoIcon,
    BadgeCheck as BadgeCheckIcon,
    Camera as CameraIcon,
    MapPin as MapPinIcon,
    Calendar as CalendarIcon,
    ShieldCheck as ShieldCheckIcon,
    Check as CheckIcon,
    Loader2,
} from "lucide-vue-next";

// Router
const router = useRouter();

// Estado
const itemType = ref("perdido");
const isAuthenticated = ref(false); // Simulação - em um ambiente real, seria verificado o estado de autenticação
const isDragging = ref(false);
const previewImage = ref("");
const fileInput = ref(null);
const isSubmitting = ref(false);
const showSuccessModal = ref(false);
const createdItemId = ref(null);

// Categorias
const categories = ref([
    { id: 1, name: "Documentos" },
    { id: 2, name: "Eletrônicos" },
    { id: 3, name: "Acessórios" },
    { id: 4, name: "Vestuário" },
    { id: 5, name: "Outros" },
]);

// Formulário
const form = ref({
    title: "",
    description: "",
    category: "",
    location: "",
    date: "",
    image: null,
    name: "",
    email: "",
    phone: "",
    acceptTerms: false,
});

// Computed
const today = computed(() => {
    const now = new Date();
    return now.toISOString().split("T")[0];
});

// Métodos
const openFilePicker = () => {
    fileInput.value.click();
};

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 5 * 1024 * 1024) {
            alert("O arquivo é muito grande. O tamanho máximo é 5MB.");
            return;
        }

        if (!["image/jpeg", "image/png"].includes(file.type)) {
            alert("Apenas arquivos JPG e PNG são aceitos.");
            return;
        }

        form.value.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const handleDrop = (event) => {
    isDragging.value = false;
    const files = event.dataTransfer.files;
    if (files.length > 0) {
        const file = files[0];
        if (file.size > 5 * 1024 * 1024) {
            alert("O arquivo é muito grande. O tamanho máximo é 5MB.");
            return;
        }

        if (!["image/jpeg", "image/png"].includes(file.type)) {
            alert("Apenas arquivos JPG e PNG são aceitos.");
            return;
        }

        form.value.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const clearImage = () => {
    form.value.image = null;
    previewImage.value = "";
    if (fileInput.value) {
        fileInput.value.value = "";
    }
};

const handleSubmit = async () => {
    isSubmitting.value = true;

    try {
        // Em um ambiente real, enviaríamos os dados para a API
        // const formData = new FormData();
        // Object.entries(form.value).forEach(([key, value]) => {
        //   formData.append(key, value);
        // });
        // formData.append('status', itemType.value);

        // const response = await fetch('/api/items', {
        //   method: 'POST',
        //   body: formData
        // });

        // const data = await response.json();
        // createdItemId.value = data.id;

        // Simulando uma requisição
        await new Promise((resolve) => setTimeout(resolve, 1500));

        // Simulação de ID gerado pelo backend
        createdItemId.value = 123;

        // Mostrar modal de sucesso
        showSuccessModal.value = true;

        // Resetar formulário
        resetForm();
    } catch (error) {
        console.error("Erro ao cadastrar item:", error);
        alert(
            "Ocorreu um erro ao cadastrar o item. Tente novamente mais tarde."
        );
    } finally {
        isSubmitting.value = false;
    }
};

const resetForm = () => {
    form.value = {
        title: "",
        description: "",
        category: "",
        location: "",
        date: "",
        image: null,
        name: "",
        email: "",
        phone: "",
        acceptTerms: false,
    };
    clearImage();
};

const goToItemPage = () => {
    showSuccessModal.value = false;
    router.push(`/${itemType.value}s/${createdItemId.value}`);
};

// Inicializar
onMounted(() => {
    // Em um ambiente real, poderíamos buscar os dados do usuário logado
    if (isAuthenticated.value) {
        form.value.name = "Usuário Logado";
        form.value.email = "usuario@exemplo.com";
        form.value.phone = "(00) 12345-6789";
    }
});
</script>
