<script setup>
const props = defineProps({
      title: String,
      cost: Number | String,
      type: String,
      color: String
})

const emit = defineEmits(['hoverType']) 
</script>

<template>
      <div @mouseenter="emit('hoverType', props.type)" @mouseleave="emit('hoverType', null)">
            <p>{{ title }}</p>
            <div class="h-1 rounded-md" :style="{ width: cost + '%' }" :class="props.color ? props.color
                  : 'bg-zinc-500'"></div>
      </div>
</template>
