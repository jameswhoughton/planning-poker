<script setup lang="ts">
import { Player } from '@/pages/Room.vue';
import { ComputedRef, computed } from 'vue';
import PlayerStatus from './PlayerStatus.vue';

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
    <div
        class="border-4 rounded-lg w-28 h-40 md:w-40 text-xl md:h-56 flex flex-col gap-6 md:text-3xl p-3 items-center justify-center relative">
        <PlayerStatus class="text-sm absolute top-3 right-3" :last-active="player.updated_at" />
        <span class="w-full overflow-hidden text-ellipsis text-center" :title="player.name">{{ player.name }}</span>
        <span>{{ score }}</span>
    </div>
</template>
