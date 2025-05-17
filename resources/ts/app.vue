<template>
  <Toaster />
  <router-view v-slot="{ Component, route }">
    <div :key="route.name">
      <Component :is="Component" />
    </div>
  </router-view>
</template>
<script setup lang="ts">
import { Toaster } from "@/ui/components/toast";
</script>
