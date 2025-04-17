<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
const page = usePage();

const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];

const currentMonth = new Date().getMonth();
const currentMonthName = months[currentMonth];

const emit = defineEmits(['update:indicador']);
const props = defineProps({
    indicador: Boolean,
});

const toggleIndicador = () => {
    emit('update:indicador', !props.indicador);
};
</script>

<template>
    <div class="mb-2 flex w-full items-end justify-end gap-10">
        <div class="mr-auto w-fit">
            <Link v-if="page.url === '/'" href="/detailed" class="underline-offset-4 hover:underline"> Ver mais detalhes </Link>

            <Link v-else="page.url === '/detailed'" href="/" class="underline-offset-4 hover:underline"> Voltar a home </Link>
        </div>
        <p v-if="page.url === '/'" @click="toggleIndicador" class="w-fit cursor-pointer select-none">
            Indicadores <span class="font-semibold text-zinc-700">{{ indicador ? 'Ativos' : 'Desligado' }}</span>
        </p>

        <p>
            Mês atual: <span class="font-semibold text-zinc-700">{{ currentMonthName }}</span>
        </p>

        <p class="inline-block w-fit font-medium text-zinc-700">
            {{ page.props.auth?.user.name }}
        </p>
    </div>
</template>
