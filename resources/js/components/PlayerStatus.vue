<script setup lang="ts">
import { computed, ComputedRef, ref, Ref } from 'vue';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';

dayjs.extend(relativeTime)

const props = defineProps<{
    lastActive: Date,
}>()

const currentTime: Ref<Date> = ref<Date>(new Date)

const isActive: ComputedRef<boolean> = computed<boolean>(() => dayjs(currentTime.value).diff(dayjs(props.lastActive), 'minute') < 5)

const inActiveMessage: ComputedRef<string> = computed<string>(() => `last active ${dayjs(props.lastActive).fromNow()}`)

// Update the current time every 10 seconds
setInterval(() => currentTime.value = new Date, 10000)
</script>

<template>
    <div class="w-3 h-3 rounded-full" :class="isActive ? 'bg-green-500' : 'bg-slate-500'"
        :title="isActive ? 'active' : inActiveMessage"><span class="sr-only">{{
            isActive ? 'active' : inActiveMessage }}</span></div>
</template>
