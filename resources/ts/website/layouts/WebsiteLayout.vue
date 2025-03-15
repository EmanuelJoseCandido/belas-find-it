<template>
    <div
        class="min-h-screen flex flex-col bg-gradient-to-b from-slate-50 to-slate-100"
    >
        <!-- Header -->
        <TheHeader />

        <!-- Main Content -->
        <main class="flex-grow">
            <slot />
        </main>

        <!-- Footer -->
        <TheFooter />
    </div>
</template>

<script setup lang="ts">
import TheHeader from "@/website/components/partials/TheHeader.vue";
import TheFooter from "@/website/components/partials/TheFooter.vue";
</script>
