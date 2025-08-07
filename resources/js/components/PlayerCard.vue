<script setup lang="ts">
import { Player } from '@/pages/Room.vue';
import { ComputedRef, computed } from 'vue';

const props = defineProps<{
    player: Player,
    showScore: boolean,
}>()

const score: ComputedRef<string> = computed<string>(() => {
    if (props.player.score === null) return '-'

    if (props.showScore) return props.player.score.toString()

    return '?'
})
</script>

<template>
    <div class="border-4 rounded-lg w-48 h-64 flex flex-col gap-6 text-3xl p-3 items-center justify-center">
        <span class="w-full overflow-hidden text-ellipsis text-center" :title="player.name">{{ player.name }}</span>
        <span>{{ score }}</span>
    </div>
</template>
