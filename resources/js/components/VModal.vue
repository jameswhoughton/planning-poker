<script lang="ts" setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faXmark } from '@fortawesome/free-solid-svg-icons';

withDefaults(defineProps<{
    blurBackground?: boolean,
    dismissable?: boolean,
}>(), {
    blurBackground: false,
    dismissable: true,
})

const show = defineModel()
</script>

<template>
    <div v-show="show">
        <div :class="{ 'backdrop-blur-sm': blurBackground }" class=" flex overflow-y-auto overflow-x-hidden z-50 fixed justify-center items-center w-full inset-0
        h-full max-h-full backdrop-blur-sm">

            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative rounded-lg shadow-sm bg-slate-200 dark:bg-slate-800">
                    <!-- Modal header -->
                    <div
                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-slate-300">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <slot name="title" />
                        </h3>
                        <button class="cursor-pointer" v-if="dismissable" @click="show = false">
                            <FontAwesomeIcon :icon="faXmark" />
                            <span class="sr-only">Close</span>
                        </button>
                    </div>
                    <div class="p-4 md:p-5">
                        <!-- Modal body -->
                        <slot />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
