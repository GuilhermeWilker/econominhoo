<script lang="ts" setup>
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref, watchEffect } from 'vue';

import TransactionsModal from '@/components/transactions/Modal.vue';

const props = defineProps({
    hoverType: String,
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
</script>

<template>
    <div>
        <article class="mt-4 flex flex-wrap gap-2">
            <div
                v-for="date in daysInActualMonth"
                :key="date"
                class="group relative flex size-16 cursor-default items-center justify-center overflow-hidden rounded-md border transition-all hover:translate-y-0.5 hover:shadow-md"
                :class="actualDay === date ? 'font-bold text-blue-500' : ''"
                @click="openModal(date)"
            >
                <div
                    class="absolute top-0 left-0 z-0 w-full bg-yellow-400 transition-all duration-300 ease-in-out"
                    :style="{
                        height: `${transactions?.[date]?.gasto || 0}%`,
                        opacity: props.hoverType === 'gasto' ? 1 : 0,
                    }"
                ></div>

                <div
                    class="absolute bottom-0 left-0 z-0 w-full bg-green-400 transition-all duration-300 ease-in-out"
                    :style="{
                        height: `${transactions?.[date]?.entrada || 0}%`,
                        opacity: props.hoverType === 'entrada' ? 1 : 0,
                    }"
                ></div>

                <div
                    class="absolute bottom-0 left-0 z-0 w-full bg-blue-400 transition-all duration-300 ease-in-out"
                    :style="{
                        height: `${transactions?.[date]?.investimento || 0}%`,
                        opacity: props.hoverType === 'investimento' ? 1 : 0,
                    }"
                ></div>

                <div class="z-10">
                    <p>{{ date <= 9 ? '0' + date : date }}</p>
                </div>
            </div>
        </article>

        <TransactionsModal :showModal="showModal" :selectedDay="selectedDay" @close="showModal = false" />
    </div>
</template>
