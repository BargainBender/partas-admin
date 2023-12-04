<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Carousel from '@/Components/Carousel.vue';
import { Head } from '@inertiajs/vue3';

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="flex justify-center">
            <div class="p-6 dark:border-gray-700 mt-14 bg-blue-400">
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <img class="h-auto max-w-full rounded-lg" src="https://placehold.co/800?text=Image+1&font=roboto" alt="">
                    </div>
                    <div>
                        <img class="h-auto max-w-full rounded-lg" src="https://placehold.co/800?text=Image+2&font=roboto" alt="">
                    </div>
                    <div>
                        <img class="h-auto max-w-full rounded-lg" src="https://placehold.co/800?text=Image+3&font=roboto" alt="">
                    </div>
                    <div>
                        <img class="h-auto max-w-full rounded-lg" src="https://placehold.co/800?text=Image+4&font=roboto" alt="">
                    </div>
                    <div>
                        <img class="h-auto max-w-full rounded-lg" src="images/carousel1.png" alt="">
                    </div>
                </div>
            </div>
    </div>
    </AuthenticatedLayout>
</template>
