<script lang="ts" setup>
import { ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

import Footer from '@/components/Footer.vue'
import Header from '@/components/Header.vue'
import TransactionsCalendar from '@/components/transactions/Calendar.vue'
import TransactionsStats from '@/components/transactions/Stats.vue'

interface Transaction {
      gasto?: number;
      entrada?: number;
}

const hoverType = ref<string | null>(null)

const hovering = (type: string | null) => {
      hoverType.value = type
}

const __formatMoney = (valor: number) => {
      return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
      }).format(valor)
}

const page = usePage()

const transactions: Record<string, Transaction> = JSON.parse(JSON.stringify(page.props.transactionsByDay))

const totalGastos = Object.values(transactions).reduce((acc, item: any) => {
      return acc + (item.gasto || 0)
}, 0)

const totalEntradas = Object.values(transactions).reduce((acc, item: any) => {
      return acc + (item.entrada || 0)
}, 0)

const totalGeral = totalEntradas + totalGastos
const saldo = totalEntradas - totalGastos

</script>

<template>
      <main class="py-8  px-4 sm:px-6 md:px-8 lg:px-16 max-w-[90%] md:max-w-[80%] mx-auto">
            <Header />

            <section class="flex flex-col md:flex-row gap-5 px-4 md:px-8">

                  <article class="mt-4 w-full md:w-6/12 lg:w-7/12">
                        <h3 class="text-4xl font-bold">
                              Econominhoo - finanças de um jeito fácil
                        </h3>

                        <p class="my-1 text-zinc-600">
                              Você possui um saldo de <span class="font-semibold text-green-500">{{
                                    __formatMoney(saldo) }}</span> na
                              sua conta. E um gasto geral de <span class="font-semibold text-red-500">R$
                                    {{ __formatMoney(totalGastos) }}</span>.
                        </p>

                        <TransactionsCalendar :hoverType="hoverType" />
                  </article>

                  <article class="w-full md:w-5/12 lg:w-3/5">
                        <div class="mt-4 w-96 overflow-x-hidden space-y-2">

                              <TransactionsStats title="Despesas Gerais" :cost="((totalGastos / totalGeral) * 100)"
                                    @hoverType="hovering" color="bg-orange-500" :hovertype="hoverType" type="gasto" />

                              <TransactionsStats title="Investimentos" color="bg-blue-500"
                                    :cost="((saldo / totalGeral) * 100).toFixed(1)" @hoverType="hovering"
                                    type="entrada" />

                              <TransactionsStats title="Cartão de Crédito" :cost="43" type="gasto" />
                        </div>


                        <div class="my-12 space-y-2">
                              <div class="mb-4">
                                    <button
                                          class="bg-zinc-900 text-white rounded-sm p-2 px-6 w-fit block ml-auto text-sm">
                                          Adicionar Boleto
                                    </button>
                              </div>

                              <div
                                    class="w-96 p-4 rounded-sm border border-zinc-300 flex items-center justify-between hover:w-full hover:shadow-md transition-all mx-auto">
                                    #11 Boleto de energia

                                    <span
                                          class="inline-block bg-blue-200 px-4 rounded-full text-sm text-blue-600">Pago</span>
                              </div>


                              <div
                                    class="w-96 p-4 rounded-sm border border-zinc-300 flex items-center justify-between hover:w-full hover:shadow-md transition-all mx-auto">
                                    #02 Boleto de água
                                    <span
                                          class="inline-block bg-orange-200 px-4 rounded-full text-sm text-orange-600">Pendente</span>
                              </div>


                              <div
                                    class="w-96 p-4 rounded-sm border border-zinc-300 flex items-center justify-between hover:w-full hover:shadow-md transition-all mx-auto">
                                    #21 Boleto de energia
                                    <span
                                          class="inline-block bg-red-200 px-4 rounded-full text-sm text-red-600">Atrasado</span>
                              </div>

                              <div
                                    class="w-96 p-4 rounded-sm border border-zinc-300 flex items-center justify-between hover:w-full hover:shadow-md transition-all mx-auto group">
                                    <p class="text-sm group-hover:text-[16px]">
                                          #24 Boleto Faculdade - <span class="font-semibold">R$ 1.350,40</span>
                                    </p>
                                    <span
                                          class="inline-block bg-orange-200 px-4 rounded-full text-sm text-orange-600">Pendente</span>
                              </div>
                        </div>
                  </article>
            </section>

            <Footer />
      </main>
</template>