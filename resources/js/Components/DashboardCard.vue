<script setup>
const props = defineProps(["class", "title", "innerClass"])
</script>

<template>
  <div :class="['flex flex-col gap-4 border border-base-100 rounded-t rounded-b max-h-[350px]', props.class]">
    <section class="w-full bg-base-100 px-4 py-2 rounded-t">
      <h2 class="value text-lg">{{ props.title }}</h2>
    </section>
    <div :class="['px-10 pb-4', props.innerClass]">
      <slot />
    </div>
  </div>
</template>