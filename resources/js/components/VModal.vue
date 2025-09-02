<script lang="ts" setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faXmark } from '@fortawesome/free-solid-svg-icons';
import { ComputedRef, computed, ref, Ref, watch, onMounted } from 'vue';

const props = withDefaults(defineProps<{
    blurBackground?: boolean,
    dismissable?: boolean,
    size?: 'md' | 'lg' | 'xl'
}>(), {
    blurBackground: false,
    dismissable: true,
    size: 'md',
})

const sizeClass: ComputedRef<string> = computed<string>(() => {
    const sizes = {
        md: 'max-w-md',
        lg: 'max-w-lg',
        xl: 'max-w-xl',
    }

    return sizes[props.size]
})

const show = defineModel()

const dialogRef: Ref<HTMLDialogElement | null> = ref<HTMLDialogElement | null>(null)

const emits = defineEmits<{
    (e: 'update:modelValue', v: boolean): void
}>()

function closeModal(e: Event) {
    if (!props.dismissable) {
        e.preventDefault()

        return
    }

    emits('update:modelValue', false)
}

watch(show, (v) => {
    if (dialogRef.value === null) return


    if (v) {
        dialogRef.value.showModal()

        return
    }

    dialogRef.value.close()
}, { immediate: true })
</script>

<template>
    <dialog ref="dialogRef" @cancel="closeModal">
        <div :class="{ 'backdrop-blur-sm': blurBackground }" class="dark:text-white flex overflow-y-auto overflow-x-hidden z-50 fixed justify-center items-center w-full inset-0
        h-full max-h-full backdrop-blur-sm">

            <div :class="sizeClass" class="relative p-4 w-full max-h-full">
                <!-- Modal content -->
                <div class="relative rounded-lg shadow-sm bg-slate-100 dark:bg-slate-800">
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
    </dialog>
</template>
