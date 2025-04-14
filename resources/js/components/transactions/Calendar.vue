<script lang="ts" setup>
import { computed, ref } from 'vue'
import { usePage } from '@inertiajs/vue3'

import TransactionsModal from '@/components/transactions/Modal.vue'

const props = defineProps({
      hoverType: String,
})

const page = usePage()

const showModal = ref(false)
const selectedDay = ref('')

const daysInActualMonth = computed(() => {
      const date = new Date()
      const month = date.getMonth() + 1
      const year = date.getFullYear()
      return new Date(year, month, 0).getDate()
})

const actualDay = computed(() => new Date().getDate())

const transactions = JSON.parse(JSON.stringify(page.props.transactionsByDay))

const openModal = (day: number) => {
      selectedDay.value = day.toString()
      showModal.value = true
}
</script>

<template>
      <div>
            <article class="mt-4 flex flex-wrap gap-2">
                  <div v-for="date in daysInActualMonth" :key="date"
                        class="relative size-16 border flex items-center justify-center rounded-md overflow-hidden transition-all cursor-default group hover:translate-y-0.5 hover:shadow-md"
                        :class="actualDay === date ? 'text-blue-500 font-bold' : ''" @click="openModal(date)">

                        <div class="absolute top-0 left-0 w-full z-0 bg-yellow-400" :style="{
                              height: `${transactions?.[date]?.gasto || 0}%`,
                              opacity: props.hoverType === 'gasto' ? 1 : 0
                        }"></div>

                        <div class="absolute bottom-0 left-0 w-full z-0 bg-blue-400" :style="{
                              height: `${transactions?.[date]?.entrada || 0}%`,
                              opacity: props.hoverType === 'entrada' ? 1 : 0
                        }"></div>

                        <div class="z-10 ">
                              <p>{{ date <= 9 ? '0' + date : date }}</p>
                        </div>
                  </div>
            </article>

            <TransactionsModal :showModal="showModal" :selectedDay="selectedDay" @close="showModal = false" />
      </div>
</template>
