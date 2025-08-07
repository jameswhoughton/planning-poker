<script setup lang="ts">
import VButton from '@/components/VButton.vue';
import VModal from '@/components/VModal.vue';
import axios from 'axios';
import { ref, Ref } from 'vue';
import { toast } from 'vue3-toastify';
import VInput from '../VInput.vue';

const props = defineProps<{
    roomUuid: string,
    playerId: string,
}>()

const name: Ref<string> = ref<string>('')
const error: Ref<string> = ref<string>('')

function update() {
    const url = `/api/room/${props.roomUuid}/player/${props.playerId}`

    axios.patch(url, { name: name.value }).then(() => {
        name.value = ''
        showModal.value = false
    }).catch((e) => {
        if (e.status === 422) {
            error.value = e.response.data.message

            return
        }

        console.error(e)

        toast('Unable to update name')
    })
}

const showModal = defineModel()
</script>

<template>
    <VModal v-model="showModal">
        <template #title>
            Edit your name
        </template>
        <template #default>
            <form class="flex flex-col gap-3" @submit.prevent="update">
                <VInput v-model="name" label="Name" name="name" required :error="error" />
                <VButton type="submit" full-width>
                    Update
                </VButton>
            </form>
        </template>
    </VModal>
</template>
