<template>
  <Toaster />
  <router-view v-slot="{ Component, route }">
    <div :key="route.name">
      <Component :is="Component" />
    </div>
  </router-view>
</template>
<script setup lang="ts">
import { onMounted } from "vue";
import { useAuthStore } from "@/auth/stores/authStore";
import { Toaster } from "@/ui/components/toast";

const authStore = useAuthStore();

onMounted(async () => {
  console.log("entrei aqui")
  await authStore.getMe();
});
</script>
