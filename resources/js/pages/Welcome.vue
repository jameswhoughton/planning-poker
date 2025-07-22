<script setup lang="ts">
import VButton from '@/components/VButton.vue';
import { Head, router } from '@inertiajs/vue3';

function submit() {
    router.post('/')
}
</script>

<template>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <h1 class="text-3xl">Planning Poker</h1>
    <form @submit.prevent="submit">
        <VButton type="submit">Create Room</VButton>
    </form>
</template>
