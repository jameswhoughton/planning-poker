<script lang="ts">
export type Card = {
    label: string,
    value: number,
}
</script>
<script setup lang="ts">
import { type Player } from '@/pages/Room.vue'
import axios from 'axios';

const props = defineProps<{
    cards: Card[],
    player: Player,
    roomId: string,
}>()

function setScore(score: number | null) {
    axios.patch(`/api/room/${props.roomId}/player/${props.player.id}`, { score })
}
</script>

<template>
    <div class="flex gap-4 flex-wrap">
        <button
            class="w-24 h-36 border-4 border-slate-200 dark:border-slate-700 text-3xl hover:cursor-pointer rounded-lg"
            @click="setScore(null)">Clear</button>
        <button
            class="w-24 h-36 border-4 border-slate-200 dark:border-slate-700 text-3xl hover:cursor-pointer rounded-lg"
            :class="{ '!border-blue-600': card.value === player.score }" v-for="(card, i) in cards" :key="i"
            @click="setScore(card.value)">{{
                card.label }}</button>
    </div>
</template>
