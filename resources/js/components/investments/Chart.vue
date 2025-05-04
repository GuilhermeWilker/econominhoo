<script setup>
import { Chart, registerables } from 'chart.js';
import { computed, onMounted, ref } from 'vue';

Chart.register(...registerables);

const props = defineProps({
    transactions: Array,
});

const investments = computed(() => {
    return props.transactions.filter((transaction) => transaction.type === 'investment');
});

const chartRef = ref(null);

onMounted(() => {
    if (!chartRef.value) return;

    new Chart(chartRef.value, {
        type: 'doughnut',
        data: {
            labels: [...investments.value.map((t) => t.description)],
            datasets: [
                {
                    data: [...investments.value.map((t) => t.amount)],
                    backgroundColor: ['#3b82f6', '#2563eb', '#6366f1', '#4f46e5', '#ec4899', '#db2777'],
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
                    text: 'Distribuição de Investimentos',
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
