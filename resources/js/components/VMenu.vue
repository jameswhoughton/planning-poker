<script lang="ts" setup>
import { onMounted, onUnmounted, ref, Ref } from 'vue';

const showMenu: Ref<boolean> = ref<boolean>(false)
const containerRef: Ref<HTMLElement | null> = ref<HTMLElement | null>(null)

function handleClick(e: MouseEvent): void {
    if (containerRef.value && e.target && containerRef.value.contains(e.target as Node)) return

    showMenu.value = false
}

onMounted(() => document.addEventListener('click', handleClick))
onUnmounted(() => document.removeEventListener('click', handleClick))
</script>

<template>
    <div class="relative overflow-visible flex items-center" ref="containerRef">
        <button
            class="cursor-pointer focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 rounded"
            @click="showMenu = !showMenu">
            <svg class="w-8 stroke-stone-900 dark:stroke-stone-100" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg" aria-label="Menu icon">
                <path d="M20 7L4 7" stroke-width="1.5" stroke-linecap="round" />
                <path d="M20 12L4 12" stroke-width="1.5" stroke-linecap="round" />
                <path d="M20 17L4 17" stroke-width="1.5" stroke-linecap="round" />
            </svg>
            <span class="sr-only">Menu</span>
        </button>
        <div class="absolute right-0 w-48 top-[65px] shadow-sm" v-show="showMenu" @keyup.esc="showMenu = false"
            @click="showMenu = false">
            <ul>
                <slot />
            </ul>
        </div>
    </div>
</template>
