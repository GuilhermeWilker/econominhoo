<script setup lang="ts">
import Footer from '@/components/Footer.vue';
import Header from '@/components/Header.vue';
import { indicador } from '@/stores/useIndicator';
</script>

<template>
    <main class="mx-auto max-w-[90%] px-4 py-8 sm:px-6 md:max-w-[80%] md:px-8 lg:px-16">
        <Header v-model:indicador="indicador" />
        <slot />
        <Footer />
    </main>
</template>
