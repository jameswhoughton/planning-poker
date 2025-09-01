<script setup lang="ts">
withDefaults(defineProps<{
    type?: 'button' | 'submit'
}>(), {
    type: 'button',
})

defineEmits<{
    (e: 'click'): void,
}>()
</script>

<template>
    <li class="bg-slate-100 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 py-1">
        <button
            class="py-2.5 px-4 block w-full text-left cursor-pointer focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded"
            :type="type" @click="$emit('click')">
            <slot />
        </button>
    </li>
</template>
