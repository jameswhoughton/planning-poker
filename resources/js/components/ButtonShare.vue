<script setup lang="ts">
import VButton from './VButton.vue';
import { toast } from 'vue3-toastify';

function share(): void {
    const clipboardItem = new ClipboardItem({
        'text/plain': window.location.href,
    })

    window.navigator.clipboard.write([clipboardItem])

    toast('url copied to clipboard')
}
</script>

<template>
    <VButton type="button" @click="share">Share</VButton>
</template>
