<script setup lang="ts">
import { Player } from '@/pages/Room.vue';
import { computed, ComputedRef, } from 'vue';
import VModal from '../VModal.vue';

const props = defineProps<{
    players: Player[]
}>()

const showModal = defineModel()

const scores: ComputedRef<Record<string, string>> = computed<Record<string, string>>(() => {
    const scores: Record<string, string> = {
        high: 'no data',
        low: 'no data',
        average: 'no data',
    }

    const responders = props.players
        .filter((p: Player): p is Player & { score: number } => p.score !== null)

    if (responders.length === 0) return scores

    const sortedResponders = responders
        .sort((a, b): number => {
            if (a.score === b.score) return 0

            return a.score < b.score ? 1 : -1
        })
    const maxIndex: number = sortedResponders.length - 1

    scores.high = `${sortedResponders[0].name} (${sortedResponders[0].score})`
    scores.low = `${sortedResponders[maxIndex].name} (${sortedResponders[maxIndex].score})`
    scores.average = (sortedResponders.map((p) => p.score).reduce((a: number, b: number) => a + b) / sortedResponders.length).toFixed(2)

    return scores

})
</script>

<template>
    <VModal v-model="showModal">
        <template #title>Results</template>
        <ul class="flex flex-col gap-3">
            <li>
                <strong>High Score:</strong> {{ scores.high }}
            </li>
            <li>
                <strong>Low Score:</strong> {{ scores.low }}
            </li>
            <li>
                <strong>Average Score:</strong> {{ scores.average }}
            </li>
        </ul>
    </VModal>
</template>
