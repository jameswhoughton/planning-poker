<script setup lang="ts">
import VButton from '@/components/VButton.vue';
import VModal from '@/components/VModal.vue';
import { InertiaForm, useForm } from '@inertiajs/vue3';
import VInput from '../VInput.vue';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    roomUuid: string,
    joinable: boolean,
}>()

const form: InertiaForm<{ name: string }> = useForm<{ name: string }>({ name: '' })

function createPlayer() {
    const url = `/room/${props.roomUuid}/player`

    form.post(url)
}

const showModal = ref<boolean>(false)

onMounted(() => showModal.value = true)
</script>

<template>
    <VModal :model-value="showModal" blur-background :dismissable="false">
        <template #title>
            Join room
        </template>
        <template #default>
            <form v-if="joinable" class="flex flex-col gap-3" @submit.prevent="createPlayer">
                <VInput v-model="form.name" label="Enter your name" required name="name"
                    :error="form.errors.name ?? ''" />
                <VButton type="submit" full-width>
                    Join
                </VButton>
            </form>
            <div v-else>
                <div class="mb-3">Unfortunately this room is at capacity</div>
                <VButton is="a" href="/" full-width>Return home</VButton>
            </div>
        </template>
    </VModal>
</template>
