<script setup lang="ts">
import { Ref, ref } from 'vue';
import VButton from './VButton.vue';
import VModal from './VModal.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faQuestionCircle } from '@fortawesome/free-regular-svg-icons';

const showModal: Ref<boolean> = ref<boolean>(false)
</script>

<template>
    <VButton variant="secondary" @click="showModal = true">
        <FontAwesomeIcon :icon="faQuestionCircle" size="lg" />
        <span class="sr-only">help</span>
    </VButton>
    <VModal v-model="showModal">
        <template #title>
            Help
        </template>
        <p class="mb-4">Rooms and any associated players will be deleted after 60 minutes of inactivity. Rooms are also
            deleted
            when the last player leaves.</p>
        <p class="mb-4">Players can either be active or inactive, this is indicated by the circle at the top right of
            each player card (green = active, grey = inactive).</p>
        <p class="mb-4">A player will become inactive if there has been no activity within the space of 5 minutes.</p>
        <p>Inactive players can be hidden and excluded when calculating the results.</p>
    </VModal>
</template>:w
