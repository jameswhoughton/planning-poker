<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import VButton from './VButton.vue';

defineProps<{
    roomUuid: string,
}>()
</script>

<template>
    <form @submit.prevent="router.delete(`/room/${roomUuid}`)">
        <VButton type="submit">Destroy Room</VButton>
    </form>
</template>
