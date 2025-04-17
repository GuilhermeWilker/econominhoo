<script lang="ts" setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref, watchEffect } from 'vue';

import TransactionsModal from '@/components/transactions/Modal.vue';

const props = defineProps({
    hoverType: String,
    indicador: Boolean,
});

const page = usePage();

const showModal = ref(false);
const selectedDay = ref('');

const daysInActualMonth = computed(() => {
    const date = new Date();
    const month = date.getMonth() + 1;
    const year = date.getFullYear();
    return new Date(year, month, 0).getDate();
});

const actualDay = computed(() => new Date().getDate());

const transactions = JSON.parse(JSON.stringify(page.props.transactionsByDay));

const openModal = (day: number) => {
    selectedDay.value = day.toString();
    showModal.value = true;
};

watchEffect(() => {
    if (showModal != showModal) {
        router.reload({ only: ['transactionsByDay'] });
    }
});

const stackedBars = computed(() => {
    const result: Record<number, { color: string; height: number }[]> = {};

    for (const [dayStr, data] of Object.entries(transactions)) {
        const day = parseInt(dayStr);
        const entrada = data.entrada || 0;
        const gasto = data.gasto || 0;
        const investimento = data.investimento || 0;

        const total = entrada + gasto + investimento;

        if (total === 0) continue;

        const stack: { color: string; height: number }[] = [];

        if (entrada) {
            stack.push({ color: 'bg-green-400', height: (entrada / total) * 100 });
        }
        if (gasto) {
            stack.push({ color: 'bg-orange-400', height: (gasto / total) * 100 });
        }
        if (investimento) {
            stack.push({ color: 'bg-blue-400', height: (investimento / total) * 100 });
        }

        // Ordenar do menor para o maior
        stack.sort((a, b) => a.height - b.height);

        result[day] = stack;
    }

    return result;
});
</script>

<template>
    <div>
        <article class="mt-8 flex flex-wrap gap-2">
            <div
                v-for="date in daysInActualMonth"
                :key="date"
                class="group relative flex size-13 cursor-default items-center justify-center overflow-hidden rounded-md border border-zinc-300 transition-all hover:translate-y-0.5 hover:scale-105 hover:shadow-lg"
                :class="actualDay === date ? 'font-bold text-blue-500' : ''"
                @click="openModal(date)"
            >
                <div
                    v-for="(bar, index) in stackedBars[date]"
                    :key="index"
                    class="absolute left-0 w-full transition-all duration-300 ease-in-out"
                    :class="bar.color"
                    :style="{
                        height: `${bar.height}%`,
                        bottom: `${stackedBars[date].slice(0, index).reduce((acc, b) => acc + b.height, 0)}%`,
                        opacity: props.indicador ? 1 : 0,
                    }"
                />

                <div class="z-10">
                    <p>{{ date <= 9 ? '0' + date : date }}</p>
                </div>
            </div>
        </article>

        <TransactionsModal :showModal="showModal" :selectedDay="selectedDay" @close="showModal = false" />
    </div>
</template>
