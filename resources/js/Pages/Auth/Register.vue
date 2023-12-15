<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import ReCaptcha from '@/Components/ReCaptcha.vue'
import { store } from '@/recaptcha'
import { watchEffect, ref } from 'vue'

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const formCompleteAndValid = ref(false)

watchEffect(() => {
    formCompleteAndValid.value = store.recaptchaVerified &&
                        form.name.trim().length !== 0 &&
                        form.email.trim().length !== 0 &&
                        form.password.trim().length !== 0 &&
                        form.password_confirmation.trim().length !== 0
})



const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput
                    id="name"
                    type="text"
                    class="block w-full mt-1"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-2">
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="block w-full mt-1"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-2">
                <InputLabel for="password" value="Password" />

                <TextInput
                    id="password"
                    type="password"
                    class="block w-full mt-1"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-2">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="block w-full mt-1"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError class="mt-2" :message="form.errors.password_confirmation" />
            </div>
            
            <ReCaptcha/>

            <div class="flex flex-col items-end justify-end mt-8 mb-4">

                <PrimaryButton class="ml-4" :class="{ 
                    'opacity-25': !formCompleteAndValid,
                    'cursor-not-allowed': !formCompleteAndValid,
                    }" 
                    :disabled="!formCompleteAndValid">
                    Register
                </PrimaryButton>

                <Link
                    :href="route('login')"
                    class="underline text-sm text-[#a6c9f2] mt-3 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Already have an account?
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
