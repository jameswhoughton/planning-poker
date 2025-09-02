<script setup lang="ts">
import VButton from './VButton.vue';
import { toast } from 'vue3-toastify';

function share(): void {
    window.navigator.clipboard.writeText(window.location.href)
        .then(() => toast('URL copied to clipboard'))
        .catch((e: DOMException) => {
            console.error(e)

            toast('Unable to copy, please check your browser permissions')
        })
}

const canShare: boolean = typeof window.navigator.clipboard?.writeText === 'function'
</script>

<template>
    <VButton v-if="canShare" type="button" @click="share">Share</VButton>
</template>
