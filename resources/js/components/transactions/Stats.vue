<script setup>
const props = defineProps({
    title: String,
    cost: Number | String,
    type: String,
    color: String,
});

const emit = defineEmits(['hoverType']);
</script>

<template>
    <div class="group relative">
        <p @mouseenter="emit('hoverType', props.type)" @mouseleave="emit('hoverType', null)" class="w-fit">
            {{ title }}
        </p>
        <div class="flex items-center gap-2">
            <div
                @mouseenter="emit('hoverType', props.type)"
                @mouseleave="emit('hoverType', null)"
                class="h-1 rounded-md"
                :style="{ width: cost + '%' }"
                :class="props.color ? props.color : 'bg-zinc-500'"
            ></div>
            <p
                class="absolute top-0 right-2 z-10 scale-95 rounded-md px-2 text-xs font-medium text-white opacity-0 transition-all duration-300 ease-in-out group-hover:scale-100 group-hover:opacity-100"
                :class="props.color ? props.color : 'bg-zinc-500'"
            >
                {{ cost + '%' }}
            </p>
        </div>
    </div>
</template>

<style scoped>
.tooltip {
    position: relative;
    display: inline-block;
}

/* Tooltip text */
.tooltip .tooltiptext {
    visibility: hidden;
    width: 120px;
    background-color: black;
    color: #fff;
    text-align: center;
    padding: 5px 0;
    border-radius: 6px;

    /* Position the tooltip text - see examples below! */
    position: absolute;
    z-index: 1;
}

/* Show the tooltip text when you mouse over the tooltip container */
.tooltip:hover .tooltiptext {
    visibility: visible;
}
</style>
