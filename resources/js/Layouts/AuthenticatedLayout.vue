<script setup>
import Sidebar from '@/Components/Sidebar.vue'

defineProps({
    count_reservation: Number
})

import {store} from '@/store'

</script>

<template>
    <div>
        <div :class="['transition-[grid] min-h-screen h-full bg-base-300 grid', store.drawerOpened ? 'grid-cols-[280px_1fr]' : 'grid-cols-[80px_1fr]']">
            <Sidebar/>
            <slot />
        </div>
    </div>
</template>
