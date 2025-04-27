<script lang="ts" setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

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

// Calcular o dia da semana que o mês atual começa (0 = domingo, 1 = segunda, ...)
const firstDayOfMonth = computed(() => {
    const date = new Date();
    date.setDate(1); // Configura o primeiro dia do mês
    return date.getDay();
});

const actualDay = computed(() => new Date().getDate());

const transactions = computed(() => JSON.parse(JSON.stringify(page.props.transactionsByDay)));

const openModal = (day: number) => {
    selectedDay.value = day.toString();
    showModal.value = true;
};

const stackedBars = computed(() => {
    const result: Record<number, { color: string; height: number }[]> = {};

    for (const [dayStr, data] of Object.entries(transactions.value)) {
        const day = parseInt(dayStr);
        const entrada = data.entrada || 0;
        const gasto = data.gasto || 0;
        const investimento = data.investimento || 0;

        const total = entrada + gasto + investimento;

        if (total === 0) continue;

        const stack: { color: string; height: number }[] = [];

        if (entrada) {
            stack.push({ color: 'bg-green-400', height: (entrada / total) * 100, type: 'entrada' });
        }
        if (gasto) {
            stack.push({ color: 'bg-orange-400', height: (gasto / total) * 100, type: 'gasto' });
        }
        if (investimento) {
            stack.push({ color: 'bg-blue-400', height: (investimento / total) * 100, type: 'investimento' });
        }

        // Ordenar do menor para o maior
        stack.sort((a, b) => a.height - b.height);

        result[day] = stack;
    }

    return result;
});

const closeModal = () => {
    showModal.value = false;
    router.reload({ only: ['transactionsByDay'] });
};
</script>

<template>
    <div>
        <article class="mt-8 flex flex-wrap justify-start gap-2">
            <!-- Células vazias para os dias do mês anterior -->
            <div v-for="n in firstDayOfMonth" :key="'empty-' + n" class="flex size-13"></div>

            <!-- Dias do mês atual -->
            <div
                v-for="i in daysInActualMonth"
                :key="i"
                class="group relative flex size-13 cursor-default items-center justify-center overflow-hidden rounded-md border border-zinc-300 transition-all hover:translate-y-0.5 hover:scale-105 hover:shadow-lg"
                :class="actualDay === i ? 'font-bold text-blue-500' : ''"
                @click="openModal(i)"
            >
                <div
                    v-for="(bar, index) in stackedBars[i]"
                    :key="index"
                    class="absolute left-0 w-full transition-all duration-300 ease-in-out"
                    :class="bar.color"
                    :style="{
                        height: `${bar.height}%`,
                        bottom: `${stackedBars[i].slice(0, index).reduce((acc, b) => acc + b.height, 0)}%`,
                        opacity: props.indicador || props.hoverType === bar.type ? 1 : 0,
                    }"
                />
                <div class="z-10">
                    <p>{{ i <= 9 ? '0' + i : i }}</p>
                </div>
            </div>
        </article>

        <TransactionsModal :showModal="showModal" :selectedDay="selectedDay" @close="closeModal" />
    </div>
</template>
