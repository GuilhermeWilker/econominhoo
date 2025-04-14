<script setup lang="ts">
import { usePage } from '@inertiajs/vue3'

const page = usePage()

const months = [
      'Janeiro',
      'Fevereiro',
      'Março',
      'Abril',
      'Maio',
      'Junho',
      'Julho',
      'Agosto',
      'Setembro',
      'Outubro',
      'Novembro',
      'Dezembro'
]

const currentMonth = new Date().getMonth()
const currentMonthName = months[currentMonth]
</script>


<template>
      <div class="mb-2 w-full flex items-end justify-end gap-14">
            <p>
                  Mês atual: <span class="font-semibold text-zinc-700">{{ currentMonthName }}</span>
            </p>

            <p class="inline-block w-fit font-medium text-zinc-700">
                  {{ page.props.auth?.user.name }}
            </p>
      </div>
</template>