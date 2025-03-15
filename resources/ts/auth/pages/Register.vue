<template>
    <AuthLayout>
        <div class="mb-3">
            <h2 class="text-xl font-bold text-center">Registro</h2>
            <p class="text-gray-500 text-center text-sm mt-1">
                Crie sua conta para começar
            </p>
        </div>

        <form @submit="onSubmit">
            <div class="grid grid-cols-2 gap-3">
                <InputField
                    name="name"
                    label="Nome completo"
                    v-model="form.name"
                    placeholder="Digite seu nome"
                    :error="errors.name"
                    class="col-span-2"
                />
                <InputField
                    name="email"
                    label="Email"
                    v-model="form.email"
                    type="email"
                    placeholder="seu@email.com"
                    :error="errors.email"
                    class="col-span-2"
                />
                <InputField
                    name="phone"
                    label="Telefone"
                    v-model="form.phone"
                    type="tel"
                    placeholder="(00) 00000-0000"
                    :error="errors.phone"
                    class="col-span-2 md:col-span-1"
                />
                <SelectField
                    name="gender"
                    label="Gênero"
                    v-model="form.gender"
                    :options="genderOptions"
                    placeholder="Selecione"
                    :error="errors.gender"
                    class="col-span-2 md:col-span-1"
                />
                <InputField
                    name="password"
                    label="Senha"
                    v-model="form.password"
                    type="password"
                    placeholder="Senha"
                    :error="errors.password"
                />
                <InputField
                    name="password_confirmation"
                    label="Confirme a senha"
                    v-model="form.password_confirmation"
                    type="password"
                    placeholder="Confirme"
                    :error="errors.password_confirmation"
                />
            </div>

            <div class="my-3">
                <div class="flex items-center mt-3 mb-3">
                    <input
                        id="terms"
                        type="checkbox"
                        v-model="form.terms"
                        class="h-4 w-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                    />
                    <label for="terms" class="ml-2 block text-xs text-gray-700">
                        Eu concordo com os
                        <a
                            href="#"
                            class="text-primary-600 hover:text-primary-500"
                            >Termos</a
                        >
                        e
                        <a
                            href="#"
                            class="text-primary-600 hover:text-primary-500"
                            >Política de Privacidade</a
                        >
                    </label>
                </div>
                <div>
                    <p v-if="errors.terms" class="ml-6 text-xs text-red-600">
                        {{ errors.terms }}
                    </p>
                </div>
            </div>

            <Button type="submit" class="w-full" :disabled="isLoading">
                <span v-if="isLoading">Carregando...</span>
                <span v-else>Criar conta</span>
            </Button>
        </form>

        <p class="text-xs text-center mt-3">
            Já tem uma conta?
            <router-link
                :to="{ name: 'auth-login' }"
                class="text-primary-600 font-medium hover:text-primary-500"
            >
                Faça login
            </router-link>
        </p>
    </AuthLayout>
</template>

<script setup lang="ts">
import { reactive, ref } from "vue";
import { z } from "zod";
import { toTypedSchema } from "@vee-validate/zod";
import { useForm } from "vee-validate";
import { toast } from "@/ui/components/toast";
import { h } from "vue";

import AuthLayout from "@/auth/layouts/AuthLayout.vue";
import InputField from "@/auth/components/InputField.vue";
import SelectField from "@/auth/components/SelectField.vue";
import { Button } from "@/ui/components/button";

// Opções de gênero
const genderOptions = [
    { value: "masculino", label: "Masculino" },
    { value: "feminino", label: "Feminino" },
    { value: "outro", label: "Outro" },
];

// Estado do formulário
const form = reactive({
    name: "",
    email: "",
    phone: "",
    gender: "",
    password: "",
    password_confirmation: "",
    terms: false,
});

// Estado de erros
const errors = reactive({
    name: "",
    email: "",
    phone: "",
    gender: "",
    password: "",
    password_confirmation: "",
    terms: "",
});

// Estado de carregamento
const isLoading = ref(false);

// Esquema de validação zod
const formSchema = toTypedSchema(
    z
        .object({
            name: z
                .string({
                    required_error: "O campo é obrigatório",
                })
                .min(2, "Nome muito curto")
                .max(100, "Nome muito longo"),
            email: z
                .string({
                    required_error: "O campo é obrigatório",
                })
                .email("Digite um email válido"),
            phone: z
                .string({
                    required_error: "O campo é obrigatório",
                })
                .min(10, "Número de telefone inválido"),
            gender: z.enum(
                ["masculino", "feminino", "outro", "prefiro_nao_informar"],
                {
                    errorMap: () => ({ message: "Selecione um gênero" }),
                }
            ),
            password: z
                .string({
                    required_error: "O campo é obrigatório",
                })
                .min(8, "Mínimo de 8 caracteres"),
            password_confirmation: z.string({
                required_error: "O campo é obrigatório",
            }),
            terms: z.literal(true, {
                errorMap: () => ({ message: "Você precisa aceitar os termos" }),
            }),
        })
        .refine((data: any) => data.password === data.password_confirmation, {
            message: "Senhas não coincidem",
            path: ["password_confirmation"],
        })
);

// Configuração do formulário vee-validate (sem conectar ao UI ainda)
const { validate } = useForm({
    validationSchema: formSchema,
});

// Função para lidar com o envio do formulário
const onSubmit = async (event: Event) => {
    event.preventDefault();

    // Limpar erros anteriores
    Object.keys(errors).forEach((key) => {
        errors[key as keyof typeof errors] = "";
    });

    // Validar com vee-validate + zod
    const result = await validate(form);

    if (!result.valid) {
        // Atribuir erros de validação
        const validationErrors = result.errors;
        Object.entries(validationErrors).forEach(([key, value]) => {
            if (key in errors) {
                errors[key as keyof typeof errors] = value as string;
            }
        });
        return;
    }

    try {
        isLoading.value = true;

        // Simulando uma chamada de API
        await new Promise((resolve) => setTimeout(resolve, 1000));
        console.log("Registrando...", form);

        // Notificação de sucesso
        toast({
            title: "Conta criada com sucesso!",
            description: h(
                "p",
                { class: "text-sm" },
                "Você será redirecionado para o login."
            ),
        });

        // Redirecionar após registro bem-sucedido
        // router.push({ name: 'auth-login' });
    } catch (error) {
        console.error("Erro ao registrar", error);
        toast({
            title: "Erro ao criar conta",
            description: h(
                "p",
                { class: "text-sm" },
                "Ocorreu um erro ao processar seu registro. Tente novamente."
            ),
            variant: "destructive",
        });
    } finally {
        isLoading.value = false;
    }
};
</script>
