<script lang="ts" setup>
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Toaster, toast } from 'vue-sonner';

import Chart from '@/components/Chart.vue';
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
const saldo = totalEntradas - totalGastos;
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
                <div class="w-full md:w-6/12 lg:w-7/12">
                    <div class="size-80">
                        <Chart :gastos="totalGastos" :entradas="totalEntradas" :investimentos="totalInvestimentos" />
                    </div>
                </div>

                <div class="w-full md:w-6/12 lg:w-7/12">
                    <button
                        class="mb-3 ml-auto block w-fit cursor-pointer rounded-sm border-zinc-950 bg-zinc-800 p-1 px-4 text-sm text-white hover:bg-zinc-900"
                        @click="
                            () => {
                                toast('Relatório', {
                                    description: 'O download da planilha será feito em alguns segundos..',
                                });
                            }
                        "
                    >
                        Exportar para planilha
                    </button>

                    <div class="h-60 overflow-y-auto">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="w-[100px]"> Invoice </TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead>Method</TableHead>
                                    <TableHead class="text-right"> Amount </TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow>
                                    <TableCell class="font-medium"> INV001 </TableCell>
                                    <TableCell>Paid</TableCell>
                                    <TableCell>Credit Card</TableCell>
                                    <TableCell class="text-right"> $250.00 </TableCell>
                                </TableRow>
                                <TableRow>
                                    <TableCell class="font-medium"> INV001 </TableCell>
                                    <TableCell>Paid</TableCell>
                                    <TableCell>Credit Card</TableCell>
                                    <TableCell class="text-right"> $250.00 </TableCell>
                                </TableRow>
                                <TableRow>
                                    <TableCell class="font-medium"> INV001 </TableCell>
                                    <TableCell>Paid</TableCell>
                                    <TableCell>Credit Card</TableCell>
                                    <TableCell class="text-right"> $250.00 </TableCell>
                                </TableRow>
                                <TableRow>
                                    <TableCell class="font-medium"> INV001 </TableCell>
                                    <TableCell>Paid</TableCell>
                                    <TableCell>Credit Card</TableCell>
                                    <TableCell class="text-right"> $250.00 </TableCell>
                                </TableRow>
                                <TableRow>
                                    <TableCell class="font-medium"> INV001 </TableCell>
                                    <TableCell>Paid</TableCell>
                                    <TableCell>Credit Card</TableCell>
                                    <TableCell class="text-right"> $250.00 </TableCell>
                                </TableRow>
                                <TableRow>
                                    <TableCell class="font-medium"> INV001 </TableCell>
                                    <TableCell>Paid</TableCell>
                                    <TableCell>Credit Card</TableCell>
                                    <TableCell class="text-right"> $250.00 </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </div>
            </article>
        </section>
    </DefaultLayout>
</template>
