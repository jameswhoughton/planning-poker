<script setup lang="ts">
import { ref, Ref } from 'vue';
import ModalEditName from './Modals/ModalEditName.vue';
import VMenuItem from './VMenuItem.vue';

defineProps<{
    roomUuid: string,
    playerId: string,
}>()

const showModal: Ref<boolean> = ref<boolean>(false)
</script>

<template>
    <VMenuItem @click="showModal = true">Edit Name</VMenuItem>
    <Teleport to="body">
        <ModalEditName :room-uuid="roomUuid" :player-id="playerId" v-model="showModal" />
    </Teleport>
</template>
