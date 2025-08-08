<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import ModalConfirm from './Modals/ModalConfirm.vue';
import { ref, Ref } from 'vue';
import VMenuItem from './VMenuItem.vue';

const props = defineProps<{
    roomUuid: string,
}>()

const showConfirm: Ref<boolean> = ref<boolean>(false)

function deleteRoom(): void {
    router.delete(`/room/${props.roomUuid}`)
}
</script>

<template>
    <Teleport to="body">
        <ModalConfirm v-model="showConfirm" @confirm="deleteRoom" @reject="showConfirm = false">
            <template #title>Delete room</template>
            <template #default>
                Are you sure you want to continue? This will affect all players currently in the room
            </template>
        </ModalConfirm>
    </Teleport>
    <VMenuItem @click="showConfirm = true" type="submit">
        Destroy Room
    </VMenuItem>
</template>
