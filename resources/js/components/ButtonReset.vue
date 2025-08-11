<script setup lang="ts">
import axios from 'axios';
import VButton from './VButton.vue';
import { ref, Ref } from 'vue';
import ModalConfirm from './Modals/ModalConfirm.vue';

const props = defineProps<{
    roomUuid: string,
}>()

const showConfirm: Ref<boolean> = ref<boolean>(false)

function reset(): void {
    showConfirm.value = false

    axios.put(`/api/room/${props.roomUuid}/reset`)
}
</script>

<template>
    <ModalConfirm v-model="showConfirm" @confirm="reset" @reject="showConfirm = false">
        <template #title>Reset scores</template>
        <div>Are you sure you want to reset the scores for all players?</div>
    </ModalConfirm>
    <VButton type="button" variant="secondary" @click="showConfirm = true">Reset Scores</VButton>
</template>
