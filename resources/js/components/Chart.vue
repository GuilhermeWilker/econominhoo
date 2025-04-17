<script setup>
import { Chart, registerables } from 'chart.js';
import { onMounted, ref } from 'vue';

Chart.register(...registerables);

const props = defineProps({
    gastos: Number,
    entradas: Number,
    investimentos: Number,
});

const chartRef = ref(null);

onMounted(() => {
    if (!chartRef.value) return;

    new Chart(chartRef.value, {
        type: 'pie',
        data: {
            labels: ['Gastos', 'Entradas', 'Investimentos'],
            datasets: [
                {
                    data: [props.gastos, props.entradas, props.investimentos],
                    backgroundColor: ['#f97316', '#22c55e', '#3b82f6'],
                    borderColor: '#fff',
                    borderWidth: 2,
                },
            ],
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
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
