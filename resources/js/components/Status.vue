<script lang="ts" setup>
const props = defineProps({
    type: String,
});

const statusColor = (type: string) => {
    const color = {
        expense: 'bg-orange-200 text-sm text-orange-600 border border-orange-600',
        investment: 'bg-blue-200 text-sm text-blue-600 border border-blue-600',
        income: 'bg-green-200 text-sm text-green-600 border border-green-600',
    };

    return color[type as keyof typeof color] ?? 'bg-zinc-700 text-white';
};

const type = (status: string) => {
    const type = {
        expense: 'Gasto',
        investment: 'Investimento',
        income: 'Entrada',
    };

    return type[status as keyof typeof type] ?? type;
};
</script>

<template>
    <div :class="`w-fit rounded-sm px-3 ${statusColor(props.type)}`" v-text="type(props.type)"></div>
</template>
