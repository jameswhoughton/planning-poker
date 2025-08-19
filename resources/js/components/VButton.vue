<script setup lang="ts">
import { ComputedRef, computed } from 'vue';

type Variant = 'primary' | 'secondary' | 'destructive'

const props = withDefaults(defineProps<{
    type?: 'submit' | 'button',
    href?: string,
    is?: 'button' | 'a',
    fullWidth?: boolean,
    variant?: Variant,
}>(), {
    type: 'button',
    is: 'button',
    href: '',
    fullWidth: false,
    default: 'primary',
    variant: 'primary',
})

const variantClasses: ComputedRef<string> = computed<string>(() => {
    const classes: Record<Variant, string> = {
        primary: 'text-white bg-blue-600 hover:bg-blue-700',
        secondary: 'dark:text-white bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600',
        destructive: 'text-white bg-red-600 hover:bg-red-700',
    }

    return classes[props.variant]
})

</script>

<template>
    <component :is="is" :type="type" :href="href" :class="[variantClasses, fullWidth ? 'w-full' : '']"
        class="focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-sm text-sm px-4 py-2 text-center transition-colors hover:cursor-pointer">
        <slot />
    </component>
</template>
