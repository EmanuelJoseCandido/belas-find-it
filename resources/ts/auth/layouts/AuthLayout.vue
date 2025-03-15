<template>
    <div
        class="flex min-h-screen items-center justify-center bg-gray-50 py-6 px-4 sm:px-6 lg:px-8"
    >
        <div class="w-full max-w-md">
            <div class="text-center mb-4">
                <div
                    class="mx-auto h-10 w-10 rounded-full bg-primary-500 flex items-center justify-center"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="h-6 w-6 text-white"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                    >
                        <path
                            fill-rule="evenodd"
                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                            clip-rule="evenodd"
                        />
                    </svg>
                </div>
                <h1 class="mt-2 text-2xl font-bold text-gray-900">AppName</h1>
            </div>

            <Card
                class="w-full p-8 shadow-md border border-gray-200 rounded-xl"
            >
                <slot />
            </Card>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Card } from "@/ui/components/card";
</script>
