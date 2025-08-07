<script setup lang="ts">
withDefaults(defineProps<{
    type?: 'submit' | 'button',
    href?: string,
    is?: 'button' | 'a',
    fullWidth?: boolean,
}>(), {
    type: 'button',
    is: 'button',
    href: '',
    fullWidth: false,
})

</script>

<template>
    <component :is="is" :type="type" :href="href" :class="{ 'w-full': fullWidth }"
        class="text-white bg-gradient-to-b from-purple-600 to-indigo-500 hover:from-purple-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-sm text-sm px-4 py-2 text-center transition-colors hover:cursor-pointer">
        <slot />
    </component>
</template>
