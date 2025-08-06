<script setup lang="ts">
import VButton from '@/components/VButton.vue';
import VModal from '@/components/VModal.vue';
import { InertiaForm, useForm } from '@inertiajs/vue3';
import { ref, Ref } from 'vue';

const props = defineProps<{
    roomUuid: string,
}>()

const modal: Ref<HTMLDialogElement | null> = ref<HTMLDialogElement | null>(null)
const playerForm: InertiaForm<{ name: string }> = useForm<{ name: string }>({ name: '' })

function createPlayer() {
    const url = `/room/${props.roomUuid}/player`

    playerForm.post(url)
}
</script>

<template>
    <VModal ref="modal" :show="true">
        <template #title>
            Join Room
        </template>
        <template #default>
            <form class="p-4 md:p-5" @submit.prevent="createPlayer">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Enter
                            your
                            name</label>
                        <input type="text" v-model="playerForm.name"
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                            required>
                    </div>
                </div>
                <VButton type="submit">
                    Join
                </VButton>
            </form>
        </template>
    </VModal>
</template>
