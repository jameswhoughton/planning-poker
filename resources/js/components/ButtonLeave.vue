<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import ModalConfirm from './Modals/ModalConfirm.vue';
import { ref, Ref } from 'vue';
import VMenuItem from './VMenuItem.vue';

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
    <Teleport to="body">
        <ModalConfirm v-model="showConfirm" @confirm="leave" @reject="showConfirm = false">
            <template #title>Leave room</template>
            <template #default>
                Are you sure you want to leave?
            </template>
        </ModalConfirm>
    </Teleport>
    <VMenuItem @click="showConfirm = true" type="submit">Leave</VMenuItem>
</template>
