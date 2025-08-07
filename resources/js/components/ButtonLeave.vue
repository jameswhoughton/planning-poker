<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import VButton from './VButton.vue';
import ModalConfirm from './Modals/ModalConfirm.vue';
import { ref, Ref } from 'vue';

const props = defineProps<{
    roomUuid: string,
    playerId: string,
}>()

const showConfirm: Ref<boolean> = ref<boolean>(false)

function leave(): void {
    router.delete(`/room/${props.roomUuid}/player/${props.playerId}`)
}
</script>

<template>
    <ModalConfirm v-model="showConfirm" @confirm="leave" @reject="showConfirm = false">
        <template #title>Leave room</template>
        <template #default>
            Are you sure you want to leave?
        </template>
    </ModalConfirm>
    <VButton @click="showConfirm = true" type="submit">Leave</VButton>
</template>
