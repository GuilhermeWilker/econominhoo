<script lang="ts" setup>
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

import TransactionsCalendar from '@/components/transactions/Calendar.vue';
import TransactionsStats from '@/components/transactions/Stats.vue';
import DefaultLayout from '@/layouts/DefaultLayout.vue';

interface Transaction {
    gasto?: number;
    entrada?: number;
}

const hoverType = ref<string | null>(null);

const hovering = (type: string | null) => {
    hoverType.value = type;
};

const __formatMoney = (valor: number) => {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(valor);
};

const page = usePage();

const transactions: Record<string, Transaction> = JSON.parse(JSON.stringify(page.props.transactionsByDay));

const totalGastos = Object.values(transactions).reduce((acc, item: any) => {
    return acc + (item.gasto || 0);
}, 0);

const totalEntradas = Object.values(transactions).reduce((acc, item: any) => {
    return acc + (item.entrada || 0);
}, 0);

const totalInvestimentos = Object.values(transactions).reduce((acc, item: any) => {
    return acc + (item.investimento || 0);
}, 0);

const totalGeral = totalEntradas + totalGastos + totalInvestimentos;
const saldo = totalEntradas - totalGastos;
</script>

<template>
    <DefaultLayout>
        <section class="flex flex-col gap-5 px-4 md:flex-row md:px-8">
            <article class="mt-4 w-full md:w-6/12 lg:w-7/12">
                <h3 class="text-4xl font-bold">Econominhoo - finanças de um jeito fácil</h3>

                <p class="my-1 text-zinc-600">
                    Você possui um saldo de <span class="font-semibold text-green-500">{{ __formatMoney(saldo) }}</span> na sua conta. E um gasto
                    geral de <span class="font-semibold text-red-500">R$ {{ __formatMoney(totalGastos) }}</span
                    >.
                </p>

                <TransactionsCalendar :hoverType="hoverType" />
            </article>

            <article class="w-full md:w-5/12 lg:w-3/5">
                <div class="mt-4 w-80 space-y-2 overflow-x-hidden">
                    <TransactionsStats
                        title="Despesas Gerais"
                        :cost="((totalGastos / totalGeral) * 100).toFixed(1)"
                        @hoverType="hovering"
                        color="bg-orange-500"
                        :hovertype="hoverType"
                        type="gasto"
                    />

                    <TransactionsStats
                        title="Entradas"
                        color="bg-green-500"
                        :cost="((saldo / totalGeral) * 100).toFixed(1)"
                        @hoverType="hovering"
                        type="entrada"
                    />

                    <TransactionsStats
                        title="Investimentos"
                        color="bg-blue-500"
                        :cost="((totalInvestimentos / totalGeral) * 100).toFixed(1)"
                        @hoverType="hovering"
                        type="investimento"
                    />
                </div>

                <div class="my-12 space-y-2">
                    <div class="mb-4">
                        <button class="ml-auto block w-fit rounded-sm bg-zinc-900 p-2 px-6 text-sm text-white">Adicionar Boleto</button>
                    </div>

                    <div
                        class="mx-auto flex w-96 items-center justify-between rounded-sm border border-zinc-300 p-4 transition-all hover:w-full hover:shadow-md"
                    >
                        #11 Boleto de energia

                        <span class="inline-block rounded-full bg-blue-200 px-4 text-sm text-blue-600">Pago</span>
                    </div>

                    <div
                        class="mx-auto flex w-96 items-center justify-between rounded-sm border border-zinc-300 p-4 transition-all hover:w-full hover:shadow-md"
                    >
                        #02 Boleto de água
                        <span class="inline-block rounded-full bg-orange-200 px-4 text-sm text-orange-600">Pendente</span>
                    </div>

                    <div
                        class="mx-auto flex w-96 items-center justify-between rounded-sm border border-zinc-300 p-4 transition-all hover:w-full hover:shadow-md"
                    >
                        #21 Boleto de energia
                        <span class="inline-block rounded-full bg-red-200 px-4 text-sm text-red-600">Atrasado</span>
                    </div>

                    <div
                        class="group mx-auto flex w-96 items-center justify-between rounded-sm border border-zinc-300 p-4 transition-all hover:w-full hover:shadow-md"
                    >
                        <p class="text-sm group-hover:text-[16px]">#24 Boleto Faculdade - <span class="font-semibold">R$ 1.350,40</span></p>
                        <span class="inline-block rounded-full bg-orange-200 px-4 text-sm text-orange-600">Pendente</span>
                    </div>
                </div>
            </article>
        </section>
    </DefaultLayout>
</template>
