<script setup>
import { Chart, registerables } from 'chart.js';
import { computed, onMounted, ref } from 'vue';

Chart.register(...registerables);

const props = defineProps({
    transactions: Array,
});

const expenses = computed(() => {
    return props.transactions.filter((transaction) => transaction.type === 'expense');
});

const chartRef = ref(null);

onMounted(() => {
    if (!chartRef.value) return;

    new Chart(chartRef.value, {
        type: 'doughnut',
        data: {
            labels: [...expenses.value.map((t) => t.category)],
            datasets: [
                {
                    data: [...expenses.value.map((t) => t.amount)],
                    backgroundColor: ['#f97316', '#f43f5e', '#6366f1', '#22d3ee', '#10b981', '#3b82f6'],
                    borderColor: '#eee',
                    borderWidth: 3,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Distribuição de Gastos',
                    font: {
                        size: 14,
                        weight: 'bold',
                    },
                },
                legend: {
                    display: false,
                    position: 'right',
                },
                tooltip: {
                    callbacks: {
                        label: (context) => {
                            const value = context.parsed;
                            return `R$ ${value.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`;
                        },
                    },
                },
            },
        },
    });
});
</script>

<template>
    <canvas ref="chartRef"></canvas>
</template>
