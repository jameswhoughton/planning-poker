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
        class="relative inline-flex items-center justify-center p-0.5 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 hover:cursor-pointer">
        <span :class="{ 'w-full': fullWidth }"
            class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-transparent group-hover:dark:bg-transparent">
            <slot />
        </span>
    </component>
</template>
