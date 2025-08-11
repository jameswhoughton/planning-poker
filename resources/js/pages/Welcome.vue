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
            class="h-[60vh] md:h-full w-full md:w-[60%] flex items-center justify-center flex-col gap-12 flex-shrink-0 from-slate-400 to-slate-200 dark:from-slate-900 bg-slate-200 dark:bg-slate-700 border-b-2 border-blue-600 md:border-b-0 md:border-r-2">
            <Logo class="w-48" />
            <h1 class="text-4xl">Planning Poker</h1>
        </div>
        <div class="w-full flex flex-col justify-center gap-4">
            <div class="mb-6 text-lg">
                <h2 class="text-3xl mb-6">Welcome!</h2>
                <p class="mb-3">Streamline your sprint planning and make estimation quick, fair, and fun.</p>
                <ul class="pl-5 list-disc mb-3">
                    <li>No accounts required.</li>
                    <li>Supports up to 10 players per room.</li>
                </ul>
                <p>Click the button below to get started.</p>
            </div>
            <div class="flex gap-4">
                <form @submit.prevent=" router.post('/')">
                    <VButton type="submit">Create Room</VButton>
                </form>
                <VButton v-if="lastRoomUuid !== ''" is="a" :href="`/room/${lastRoomUuid}`" variant="secondary">Go to
                    your
                    last room</VButton>
            </div>
        </div>
    </div>
</template>
