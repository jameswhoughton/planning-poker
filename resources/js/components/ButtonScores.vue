<script setup lang="ts">
import axios from 'axios';
import VButton from './VButton.vue';

const props = defineProps<{
    roomUuid: string,
    showScores: boolean,
}>()

const emits = defineEmits<{
    (e: 'show'): void
}>()

function toggleScores(): void {
    const showScores = props.showScores
    axios.patch(`/api/room/${props.roomUuid}`, {
        showScores: !showScores,
    })

    if (!showScores) {
        emits('show')
    }
}
</script>

<template>
    <VButton type="button" variant="secondary" @click="toggleScores">{{ showScores ? 'Hide' :
        'Show' }} Scores
    </VButton>
</template>
