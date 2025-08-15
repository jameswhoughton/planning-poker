<script setup lang="ts">
import { computed, ComputedRef } from 'vue';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import usePlayerState from '@/composables/playerState';
import { Player } from '@/pages/Room.vue';

dayjs.extend(relativeTime)

const props = defineProps<{
    player: Player,
}>()

const { playerIsActive } = usePlayerState()

const isActive: ComputedRef<boolean> = computed<boolean>(() => playerIsActive(props.player))

const inActiveMessage: ComputedRef<string> = computed<string>(() => `last active ${dayjs(props.player.updated_at).fromNow()}`)
</script>

<template>
    <div class="w-3 h-3 rounded-full" :class="isActive ? 'bg-green-500' : 'bg-slate-500'"
        :title="isActive ? 'active' : inActiveMessage"><span class="sr-only">{{
            isActive ? 'active' : inActiveMessage }}</span></div>
</template>
