<script setup lang="ts">
import VButton from '../VButton.vue';
import VModal from '../VModal.vue';
</script>

<template>
	<VModal :show="true">
		<template #title>Room has been deleted.</template>
		<VButton is="a" href="/" full-width>Return home</VButton>
	</VModal>
</template>
