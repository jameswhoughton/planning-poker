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
    <VModal v-model="showModal" size="xl">
        <template #title>
            Help
        </template>
        <h2 class="font-bold text-lg mb-4">Getting Started</h2>
        <p class="mb-4">Click the <span class="inline-block bg-blue-600 rounded-sm text-xs px-2 py-1 mx-2">Share</span>
            button to
            copy the url and share it with your team.</p>
        <p class="mb-4">Select your estimate by clicking on a number, your choice will be hidden from other players
            until <span
                class="inline-block dark:text-white bg-slate-200 dark:bg-slate-700 rounded-sm text-xs px-2 py-1 mx-2">Show
                Scores</span> is
            clicked.</p>
        <p class="mb-4">You can leave the room at anytime using the hamburger menu, if you rejoin the room, you will
            have to
            re-enter your name.</p>
        <hr class="border-b dark:border-gray-600 border-slate-300 my-4 border-t-0" />
        <h2 class="font-bold text-lg mb-4">Notes</h2>
        <p class="mb-4">Rooms and any associated players will be deleted after 60 minutes of inactivity. Rooms are
            also
            deleted
            when the last player leaves.</p>
        <p class="mb-4">Players can either be active or inactive, this is indicated by the circle at the top right
            of
            each player card (green = active, grey = inactive).</p>
        <p class="mb-4">A player will become inactive if there has been no activity within the space of 5 minutes.
        </p>
        <p>Inactive players can be hidden and excluded when calculating the results.</p>
    </VModal>
</template>:w
