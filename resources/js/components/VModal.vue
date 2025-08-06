<script lang="ts" setup>
defineProps<{
    show: boolean,
}>()
</script>

<template>
    <div v-show="show">
        <div class=" flex overflow-y-auto overflow-x-hidden z-50 fixed justify-center items-center w-full md:inset-0
        h-full max-h-full backdrop-blur-sm">

            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <!-- Modal header -->
                    <div
                        class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <slot name="title" />
                        </h3>
                    </div>
                    <!-- Modal body -->
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
