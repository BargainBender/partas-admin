<script setup>
import { store } from '@/store.js'
import { Link } from '@inertiajs/vue3';
const props = defineProps(["icon", "fill", "class", "label", "href", "method", "current"])
</script>

<template>
    <!-- <li :class="`flex items-center w-full ${props.class}`"> -->
    <Link :href="props.href" class="w-full" :method="props.method">
        <li :class="[
            'grid grid-cols-[50px_1fr] items-center w-full overflow-x-clip transition py-3 pl-5',
            store.drawerOpened ? '' : 'place-items-start pr-3',
            props.current ? 'bg-blue-100 text-base-100' : 'hover:bg-blue-100 hover:text-base-100',
            props.class
        ]">
            <v-icon :name="props.icon" scale="2" :fill="props.fill"/>
            <p class="whitespace-nowrap" v-if="props.label && store.drawerOpened">{{ props.label }}</p>
        </li>
    </Link>
</template>