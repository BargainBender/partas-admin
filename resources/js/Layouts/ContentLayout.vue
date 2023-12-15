<script setup>

const props = defineProps(["title"])

</script>

<template>
    <div class="w-full">
      <div class="container p-10">
        <h1 class="text-2xl font-bold mb-8">{{ props.title }}</h1>
        <slot/>
      </div>
    </div>
</template>
