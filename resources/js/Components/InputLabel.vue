<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>

    <label class="flex py-2 text-sm font-bold text-white normalcase">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>

</template>
