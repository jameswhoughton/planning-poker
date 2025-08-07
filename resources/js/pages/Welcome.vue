<script setup lang="ts">
import Logo from '@/components/logo.vue';
import VButton from '@/components/VButton.vue';
import { Head, router } from '@inertiajs/vue3';

defineProps<{
    lastRoomUuid: string,
}>()
</script>

<template>

    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div class="flex flex-col gap-12 md:flex-row h-screen">
        <div
            class="h-[60vh] md:h-full w-full md:w-[60%] flex items-center justify-center flex-col gap-12 flex-shrink-0 bg-linear-to-b from-slate-400 to-slate-200 dark:from-slate-900 dark:to-slate-600">
            <Logo class="w-48" />
            <h1 class="text-3xl">Planning Poker</h1>
        </div>
        <div class="w-full flex items-center justify-center gap-4">
            <form @submit.prevent="router.post('/')">
                <VButton type="submit">Create Room</VButton>
            </form>
            <VButton v-if="lastRoomUuid !== ''" is="a" :href="`/room/${lastRoomUuid}`">Go to your last room</VButton>
        </div>
    </div>
</template>
