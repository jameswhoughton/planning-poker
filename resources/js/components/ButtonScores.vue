<script setup lang="ts">
import axios from 'axios';
import VButton from './VButton.vue';

const props = defineProps<{
    roomUuid: string,
    showScores: boolean,
}>()

function toggleScores(): void {
    const showScores = props.showScores
    axios.patch(`/api/room/${props.roomUuid}`, {
        showScores: !showScores,
    })
}
</script>

<template>
    <VButton type="button" variant="secondary" @click="toggleScores">{{ showScores ? 'Hide' :
        'Show' }} Scores
    </VButton>
</template>
