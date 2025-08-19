<script setup lang="ts">
import VButton from '../VButton.vue';
import VModal from '../VModal.vue';

withDefaults(defineProps<{
    variant?: 'primary' | 'destructive',
}>(), {
    variant: 'primary',
})

const showModal = defineModel<boolean>({ default: false })

defineEmits<{
    (e: 'confirm'): void,
    (e: 'reject'): void,
}>()
</script>

<template>
    <VModal v-model="showModal" dismissable @close="$emit('reject')">
        <template #title>
            <slot name="title" />
        </template>
        <template #default>
            <slot />
            <div class="mt-3 flex gap-3">
                <VButton variant="secondary" full-width @click="$emit('reject')">Cancel</VButton>
                <VButton :variant="variant" full-width @click="$emit('confirm')">Continue</VButton>
            </div>
        </template>
    </VModal>
</template>
