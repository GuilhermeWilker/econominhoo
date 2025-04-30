<script setup lang="ts">
import { router, useForm, usePage } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import { toast } from 'vue-sonner';

const page = usePage();

const props = defineProps({
    selectedDay: String,
    showModal: Boolean,
});

const emit = defineEmits(['close']);

const fullDate = computed(() => {
    const day = props.selectedDay?.padStart(2, '0') || '01';
    const now = new Date();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const year = now.getFullYear();

    return `${year}-${month}-${day}`;
});

const form = useForm({
    user_id: page.props.auth?.user.id,
    date: null as string | null,
    type: '',
    category: '',
    amount: null,
    description: null,
});

watch(
    () => props.showModal,
    (isVisible) => {
        if (isVisible) {
            form.reset();
            form.date = fullDate.value;
        }
    },
);
</script>

<template>
    <div v-show="showModal" class="bg-opacity-50 fixed inset-0 z-10 flex items-center justify-center bg-black">
        <div class="relative w-96 space-y-4 rounded-md border border-zinc-300 bg-white p-6 shadow-lg">
            <!-- Botão de fechar -->
            <button @click="emit('close')" class="absolute top-2 right-5 text-3xl text-zinc-600 hover:text-zinc-900">&times;</button>

            <h2 class="text-lg font-semibold">Adicionar Boleto - Dia <span v-text="props.selectedDay"></span></h2>

            <form
                @submit.prevent="
                    form.post('/transaction', {
                        onSuccess: () => {
                            emit('close');
                            toast(fullDate, {
                                description: 'A transação foi adicionada com sucesso.',
                                action: {
                                    label: 'Fechar',
                                },
                            });

                            form.reset();
                            router.reload({ only: ['transactionsByDay'] });
                        },
                    })
                "
                class="space-y-4"
            >
                <div>
                    <div v-if="form.type === 'investment'">
                        <label class="block text-sm">Ação ou Ativo</label>
                        <input type="text" placeholder="PRIO3" class="w-full rounded-sm border border-zinc-400 p-2 px-4" v-model="form.description" />
                    </div>
                    <div v-else>
                        <label class="block text-sm">Descrição</label>
                        <input
                            type="text"
                            placeholder="Descrição"
                            class="w-full rounded-sm border border-zinc-400 p-2 px-4"
                            v-model="form.description"
                        />
                    </div>
                </div>

                <input type="hidden" v-model="form.date" />

                <div class="flex items-center gap-2">
                    <div class="w-full">
                        <label class="block text-sm">Valor</label>
                        <input
                            type="text"
                            placeholder="2.400,00"
                            class="w-full rounded-sm border border-zinc-400 p-2 px-4 placeholder:text-sm"
                            v-model="form.amount"
                            @blur="form.amount = parseFloat(String(form.amount).replace(',', '.'))"
                        />
                    </div>

                    <div class="w-full">
                        <label class="block text-sm">Tipo</label>
                        <select class="w-full rounded-sm border border-zinc-400 p-2" v-model="form.type">
                            <option value="" disabled selected>Selecione o tipo</option>
                            <option value="investment">Investimento</option>
                            <option value="income">Entrada</option>
                            <option value="expense">Gasto</option>
                        </select>
                    </div>
                </div>

                <div class="w-full" v-if="form.type === 'investment'">
                    <label class="block text-sm">Categoria de investimento</label>
                    <select class="w-full rounded-sm border border-zinc-400 p-2" v-model="form.category">
                        <option value="" disabled selected>Selecione a categoria</option>
                        <option value="Ações">Ações</option>
                        <option value="Fii">Fii</option>
                        <option value="ETF">ETF</option>
                        <option value="CDB">CDB</option>
                        <option value="LCI/LCA">LCI/LCA</option>
                        <option value="Criptoativo">Criptoativo</option>
                        <option value="Outro">Outro</option>
                    </select>
                </div>

                <div class="w-full" v-if="form.type === 'expense'">
                    <label class="block text-sm">Categoria</label>
                    <select class="w-full rounded-sm border border-zinc-400 p-2" v-model="form.category">
                        <option value="Contas">Conta</option>
                        <option value="Mercado" selected>Mercado</option>
                        <option value="Moradia">Moradia</option>
                        <option value="Saúde">Saúde</option>
                        <option value="Lazer">Lazer</option>
                        <option value="Outro">Outro</option>
                    </select>
                </div>

                <button class="w-full rounded-sm bg-zinc-900 p-2 px-6 text-sm text-white">Adicionar Boleto</button>
            </form>
        </div>
    </div>
</template>
