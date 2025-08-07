<script setup lang="ts">
withDefaults(defineProps<{
    label?: string,
    required?: boolean,
    error?: string,
    name: string,
}>(), {
    label: '',
    required: false,
    error: '',
})

const model = defineModel<string>()
</script>

<template>
    <div class="flex flex-col gap-1">
        <label v-if="label !== ''" :for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{
            label }} <span v-if="required" class="text-red-500">*</span></label>
        <input type="text" v-model="model" :name="name"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
            :required="required">
        <div class="text-sm text-red-500" v-if="error !== ''">{{ error }}</div>
    </div>
</template>
