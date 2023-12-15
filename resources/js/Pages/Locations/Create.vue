<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { defineProps, watchEffect } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { reactive } from 'vue';
import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

const $toast = useToast();

const form = reactive({
    location: "",
});

function submit() {
    router.post(route("location.store"), form)
}

const props = defineProps({
    error: String,
});

watchEffect(() => {
    if (props.error) {
        $toast.error(props.error.split(':')[1], {
            'duration': 5000,
        });
    }
});

</script>

<template>
    <Head title="Create Location" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden ma-8 w-100  bg-base-100 rounded-lg">
                    <div class="flex m-6 md:items-center">
                        <form class="w-full max-w-sm" @submit.prevent="submit">
                            <div class="md:w-1/3">
                                <InputLabel for="location" class="" value="Location" />
                            </div>
                            <div class="block w-full">
                                <TextInput id="location" type="text" v-model="form.location" required />
                            </div>

                            <div class="py-3 md:w-1/3">
                                <PrimaryButton type="submit"> Submit </PrimaryButton>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
