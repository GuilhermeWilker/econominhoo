<script lang="ts" setup>
import Status from '@/components/Status.vue';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { indicador } from '@/stores/useIndicator';
import { usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Toaster, toast } from 'vue-sonner';

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
        <Toaster position="top-right" />

        <section class="flex flex-col gap-5 px-4 md:flex-row md:px-8">
            <article class="mt-4 w-full md:w-6/12 lg:w-7/12">
                <h3 class="text-4xl font-bold">Econominhoo - finanças de um jeito fácil</h3>

                <p class="my-1 text-zinc-600">
                    Você possui um saldo de <span class="font-semibold text-green-500">{{ __formatMoney(saldo) }}</span> na sua conta. E um gasto
                    geral de <span class="font-semibold text-red-500">R$ {{ __formatMoney(totalGastos) }}</span
                    >.
                </p>

                <TransactionsCalendar :hoverType="hoverType" :indicador="indicador" />
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
                    <div class="h-72">
                        <button
                            v-if="page.props.transactions.data.length > 5"
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

                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead class="w-[100px]"> Descrição </TableHead>
                                    <TableHead>Quantia (R$)</TableHead>
                                    <TableHead>Tipo</TableHead>
                                    <TableHead class="text-right"> Data </TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="transaction in page.props.transactions.data" :key="transaction.id">
                                    <TableCell class="max-w-14 truncate font-medium">{{ transaction.description }}</TableCell>
                                    <TableCell>{{ __formatMoney(transaction.amount) }}</TableCell>
                                    <TableCell>
                                        <Status :type="transaction.type" />
                                    </TableCell>
                                    <TableCell class="text-right"> {{ transaction.date }} </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>

                        <div class="mt-4 flex justify-end gap-2">
                            <button
                                v-if="page.props.transactions.prev_page_url"
                                class="mb-3 block w-fit cursor-pointer rounded-sm border-zinc-950 bg-zinc-800 p-1 px-4 text-sm text-white hover:bg-zinc-900"
                                @click="$inertia.visit(page.props.transactions.prev_page_url, { only: ['transactions'], preserveScroll: true })"
                            >
                                Anterior
                            </button>

                            <button
                                v-if="page.props.transactions.next_page_url"
                                class="mb-3 block w-fit cursor-pointer rounded-sm border-zinc-950 bg-zinc-800 p-1 px-4 text-sm text-white hover:bg-zinc-900"
                                @click="$inertia.visit(page.props.transactions.next_page_url, { only: ['transactions'], preserveScroll: true })"
                            >
                                Próxima
                            </button>
                        </div>
                    </div>
                </div>
            </article>
        </section>
    </DefaultLayout>
</template>
