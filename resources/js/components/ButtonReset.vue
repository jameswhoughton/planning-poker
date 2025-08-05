<script setup lang="ts">
import axios from 'axios';
import VButton from './VButton.vue';
import { toast } from 'vue3-toastify';

const props = defineProps<{
    roomUuid: string,
}>()

function reset(): void {
    axios.put(`/api/room/${props.roomUuid}/reset`)
        .then(() => toast('Scores reset'))
}
</script>

<template>
    <VButton type="button" @click="reset">Reset Scores</VButton>
</template>
