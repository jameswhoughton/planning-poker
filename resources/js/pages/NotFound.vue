<script setup lang="ts">
import VButton from '@/components/VButton.vue';

</script>

<template>
    <div class="h-screen w-screen flex items-center justify-center p-6">
        <div class="flex flex-col gap-6 items-start">
            <h1 class="text-3xl">This room does not exist or has expired.</h1>
            <p class="text-lg">Click the button below to return to the home page.</p>
            <VButton is="a" href="/">Go home</VButton>
        </div>
    </div>
</template>
