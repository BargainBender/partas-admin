<script setup>
import DrawerItem from './DrawerItem.vue';
import { store } from '@/store.js'

function toggleDrawer() {
    store.drawerOpened = !store.drawerOpened
}

</script>
<template>
    <div :class="['transition-[width] sticky top-0 h-screen bg-base-100 flex flex-col justify-between items-center', store.drawerOpened ? 'w-[280px]' : 'w-[80px]']">
        <ul class="flex flex-col justify-between items-start w-full">
            <li class="w-full">
                <button @click="toggleDrawer()" :class="['w-full transition flex items-start hover:bg-blue-100 hover:text-base-100 py-3 pl-5', store.drawerOpened ? '' : 'pr-3']">
                    <v-icon name="fa-chevron-right" scale="2" :class="['transition', store.drawerOpened ? 'rotate-180 -translate-x-1' : '']"/>
                </button>
            </li>
            <DrawerItem icon="ri-dashboard-3-fill" label="Dashboard" href="/dashboard" :current="route().current() === 'dashboard'"/>
            <DrawerItem icon="fa-bus" label="Bus Management" href="/buses" :current="route().current() === 'buses'"/>
            <DrawerItem icon="ri-pin-distance-fill" label="Route Management" href="/busroutes" :current="route().current() === 'busroutes'"/>
            <DrawerItem icon="bi-calendar-week-fill" label="Schedule Management" href="/schedules" :current="route().current() === 'schedules'"/>
            <DrawerItem icon="fa-map-marked" label="Location Management" href="/locations" :current="route().current() === 'locations'"/>
        </ul>
        <ul class="flex flex-col justify-between items-start w-full">
            <DrawerItem icon="bi-gear-wide-connected" label="Settings" href="/profile" :current="route().current() === 'profile'"/>
            <DrawerItem icon="io-exit" fill="#df5342" class="pl-6" label="Log out" :href="route('logout')" method="POST"/>
        </ul>
    </div>
</template>