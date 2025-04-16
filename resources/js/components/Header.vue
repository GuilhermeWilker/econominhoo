<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
const page = usePage();

const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

const currentMonth = new Date().getMonth();
const currentMonthName = months[currentMonth];

const indicador = ref(false);
</script>

<template>
    <div class="mb-2 flex w-full items-end justify-end gap-10">
        <p @click="indicador = !indicador" class="w-fit cursor-pointer select-none">
            Indicador <span class="font-semibold text-zinc-700">{{ indicador ? 'Ativo' : 'Desligado' }}</span>
        </p>
        <p>
            Mês atual: <span class="font-semibold text-zinc-700">{{ currentMonthName }}</span>
        </p>

        <p class="inline-block w-fit font-medium text-zinc-700">
            {{ page.props.auth?.user.name }}
        </p>
    </div>
</template>
