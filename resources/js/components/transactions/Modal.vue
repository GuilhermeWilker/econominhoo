<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps({
      selectedDay: String,
      showModal: Boolean,
})

const emit = defineEmits(['close'])

const fullDate = computed(() => {
      const day = (props.selectedDay || '').padStart(2, '0')
      const now = new Date()
      const month = String(now.getMonth() + 1).padStart(2, '0')
      const year = now.getFullYear()

      return `${day}/${month}/${year}`
})
</script>

<template>
      <div v-show="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-10">
            <div class="bg-white border rounded-md p-6 space-y-4 shadow-lg border-zinc-300 w-96 relative">
                  <!-- Botão de fechar -->
                  <button @click="emit('close')"
                        class="absolute top-2 right-5 text-zinc-600 hover:text-zinc-900 text-3xl">
                        &times;
                  </button>

                  <h2 class="text-lg font-semibold">Adicionar Boleto - Dia <span v-text="props.selectedDay"></span></h2>

                  <form action="" class="space-y-4">
                        <div>
                              <label class="block text-sm">Descrição</label>
                              <input type="text" placeholder="Descrição"
                                    class="w-full border border-zinc-400 p-2 px-4 rounded-sm" />
                        </div>

                        <div class="flex gap-2 items-center">
                              <div class="w-full">
                                    <label class="block text-sm">Valor</label>
                                    <input type="number" placeholder="2 400,00"
                                          class="w-full border border-zinc-400 p-2 px-4 rounded-sm placeholder:text-sm" />
                              </div>

                              <div class="w-full">
                                    <label class="block text-sm">Status</label>
                                    <select class="w-full border border-zinc-400 p-2 rounded-sm">
                                          <option>Recorrente</option>
                                          <option>Entrada</option>
                                          <option>Gasto</option>
                                    </select>
                              </div>
                        </div>

                        <button class="bg-zinc-900 text-white rounded-sm p-2 px-6 w-full text-sm">
                              Adicionar Boleto
                        </button>
                  </form>
            </div>
      </div>
</template>