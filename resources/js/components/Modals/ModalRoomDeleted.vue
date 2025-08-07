<script setup lang="ts">
import VButton from '../VButton.vue';
import VModal from '../VModal.vue';
</script>

<template>
    <VModal :model-value="true" blur-background :dismissable="false">
        <template #title>The room has been deleted</template>
        <div class="mb-3">
            A player has deleted the room, please return to the home page if you need to create another one.
        </div>
        <VButton is="a" href="/" full-width>Return home</VButton>
    </VModal>
</template>
