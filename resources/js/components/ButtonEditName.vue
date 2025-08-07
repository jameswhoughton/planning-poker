<script setup lang="ts">
import { ref, Ref } from 'vue';
import ModalEditName from './Modals/ModalEditName.vue';
import VButton from './VButton.vue';

defineProps<{
    roomUuid: string,
    playerId: string,
}>()

const showModal: Ref<boolean> = ref<boolean>(false)
</script>

<template>
    <VButton @click="showModal = true">Edit Name</VButton>
    <ModalEditName :room-uuid="roomUuid" :player-id="playerId" v-model="showModal" />
</template>
