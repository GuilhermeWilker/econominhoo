<script setup lang="ts">
import Footer from '@/components/Footer.vue';
import Header from '@/components/Header.vue';
</script>

<template>
    <main class="mx-auto max-w-[90%] px-4 py-8 sm:px-6 md:max-w-[80%] md:px-8 lg:px-16">
        <Header />
        <slot />
        <Footer />
    </main>
</template>
