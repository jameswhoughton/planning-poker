<script lang="ts" setup>
import { faBars } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
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
            <FontAwesomeIcon class="text-stone-900 dark:text-stone-100" size="xl" :icon="faBars" />
            <span class="sr-only">Menu</span>
        </button>
        <div class="absolute right-0 w-48 top-[65px] shadow-sm z-99" v-show="showMenu" @keyup.esc="showMenu = false"
            @click="showMenu = false">
            <ul>
                <slot />
            </ul>
        </div>
    </div>
</template>
