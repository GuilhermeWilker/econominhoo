<script lang="ts" setup>
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Toaster } from 'vue-sonner';

import Chart from '@/components/Chart.vue';
import InvestmentsChart from '@/components/investments/Chart.vue';
import TransactionsChart from '@/components/transactions/Chart.vue';
import TransactionsStats from '@/components/transactions/Stats.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
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
const saldo = totalEntradas - totalGastos - totalInvestimentos;
</script>

<template>
    <DefaultLayout>
        <Toaster position="top-right" />
        <section class="px-4 md:px-8">
            <artcile class="flex flex-col gap-5 md:flex-row">
                <article class="mt-4 w-full md:w-6/12 lg:w-7/12">
                    <h3 class="text-4xl font-bold">Econominhoo - finanças de um jeito fácil</h3>

                    <p class="my-1 text-zinc-600">
                        Você possui um saldo de <span class="font-semibold text-green-500">{{ __formatMoney(saldo) }}</span> na sua conta. E um gasto
                        geral de <span class="font-semibold text-red-500">R$ {{ __formatMoney(totalGastos) }}</span
                        >.
                    </p>
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
                </article>
            </artcile>

            <article class="mt-8 flex flex-col gap-5 md:flex-row">
                <div class="w-full space-y-8 md:w-6/12 lg:w-7/12">
                    <div class="size-80">
                        <div v-if="totalGeral < 1">
                            <p>Você ainda não possui transações feitas no Econominhoo..</p>
                            <p class="float mx-auto my-2 block w-fit text-8xl">😨</p>
                        </div>
                        <Chart v-else :gastos="totalGastos" :entradas="totalEntradas" :investimentos="totalInvestimentos" />
                    </div>

                    <div class="size-80" v-if="$page.props.transactions.data.length > 0">
                        <TransactionsChart :transactions="$page.props.transactions.data" />
                    </div>
                </div>

                <div class="w-full space-y-2 md:w-6/12 lg:w-7/12">
                    <div class="mb-18 h-72">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="w-[100px]"> </TableHead>
                                    <TableHead>Sigla</TableHead>
                                    <TableHead class="text-right">Valor atual</TableHead>
                                    <TableHead class="text-right"> Variação de mercado </TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="stock in $page.props.portfolio" :key="stock.id">
                                    <TableCell>
                                        <img :src="stock.logo_url" class="size-8 rounded-sm" alt="" />
                                    </TableCell>
                                    <TableCell>{{ stock.symbol }}</TableCell>
                                    <TableCell class="text-right"> R$ {{ stock.current_value }}</TableCell>
                                    <TableCell class="text-right">
                                        {{ stock.market_variation }} ({{ stock.market_variation_pct }}%)
                                        <span v-if="stock.market_variation_pct >= 0" class="text-green-600"> ▲ </span>
                                        <span v-else class="text-red-600">▼</span>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                    <div class="size-80" v-if="$page.props.transactions.data.length > 0">
                        <InvestmentsChart :transactions="$page.props.transactions.data" />
                    </div>
                </div>
            </article>
        </section>
    </DefaultLayout>
</template>

<style scoped>
@keyframes float {
    0%,
    100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-7px);
    }
}

.float {
    animation: float 2s ease-in-out infinite;
}
</style>
