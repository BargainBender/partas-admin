<script setup>
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const isDropdownOpen = ref(false);

const toggleDropdown = () => {
  isDropdownOpen.value = !isDropdownOpen.value;
};

</script>

<template>
  <nav class="fixed h-[70px] bg-gradient-to-b from-green-400 to-blue-400 w-full z-50 p-10">
    <div class="flex items-center justify-between h-full">
      <Link href="/dashboard" class="flex ml-2 md:mr-24">
      <img src="/partas_logo.png" class="h-8 mr-3" alt="Logo" />
      <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">Partas Bus Inc</span>
      </Link>

      <span class="self-center text-xl text-green-800 font-semibold sm:text-2xl whitespace-nowrap dark:text-white">Ticket
        Reservation System</span>

      <!-- Dropdown container -->
      <div class="relative inline-block text-left">
        <!-- Trigger button -->
        <button @click="toggleDropdown" type="button"
          class="inline-flex justify-center items-center w-full px-2 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full hover:bg-gray-50 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:bg-gray-200"
          id="dropdown-button" aria-haspopup="true" aria-expanded="true">
          {{ $page.props.auth.user.name }}
          <!-- Dropdown icon -->
          <svg :class="{ 'transform rotate-180': isDropdownOpen }" class="w-4 h-4 ml-2 -mr-1"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
          </svg>
        </button>

        <!-- Dropdown menu -->
        <div v-if="isDropdownOpen"
          class="absolute right-0 my-2 bg-white border border-gray-300 rounded-md shadow-lg w-max overflow-hidden"
          aria-labelledby="dropdown-button">
          <!-- Dropdown items -->
          <span class="block px-4 py-2 text-sm text-gray-700">Email: <span class="font-bold">{{ $page.props.auth.user.email }}</span></span>
          <hr>
          <div class="my-2">
            <Link href="/profile"
            class="flex px-4 py-2  text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
            role="menuitem">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          <span class="px-2">
            Profile
          </span>
          </Link>
          <Link :href="route('logout')" method="post"
            class="flex px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
            role="menuitem">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M11.25 9l-3 3m0 0l3 3m-3-3h7.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>

          <span class="px-2">
            Sign out
          </span>
          </Link>
          </div>
        </div>
      </div>

    </div>
  </nav>
</template>

