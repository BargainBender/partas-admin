<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { defineProps, watchEffect } from 'vue';
import { reactive, ref } from 'vue'

import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';

import { useToast } from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

const $toast = useToast();

const props = defineProps({
  busroute: Object,
  locations: Object,
  error: String
})

watchEffect(() => {
  if (props.error) {
    $toast.error(props.error.split(':')[1], {
      'duration': 5000,
    });
  }
})

const form = reactive({
  _method: 'put',
  origin: props.busroute.origin,
  destination: props.busroute.destination,
})

function update() {
  router.post(`/busroute/${props.busroute.id}`, {
    _method: 'put',
    origin: form.origin,
    destination: form.destination,
  })
}

</script>



<template>
  
  <Head title="Update Bus Route" />

  <AuthenticatedLayout>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-hidden ma-8 w-100  bg-base-100 rounded-lg">
          <div class="overflow-x-auto  m-8 ">
            <form @submit.prevent="update">
              <div class="mb-4">
                <InputLabel for="origin" class="block font-medium text-gray-700">
                  Select Origin:
                </InputLabel>
                <select id="route" v-model="form.origin"
                  class="mt-1 block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:outline-none focus:border-indigo-500">
                  <option v-for="location in locations" :value="location.location">
                    {{ location.location }} </option>
                </select>
              </div>
              <div class="mb-4">
                <InputLabel for="origin" class="block font-medium text-gray-700">
                  Select Origin:
                </InputLabel>
                <select id="route" v-model="form.destination"
                  class="mt-1 block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:outline-none focus:border-indigo-500">
                  <option v-for="location in locations" :value="location.location">
                    {{ location.location }} </option>
                </select>
              </div>
              <PrimaryButton>Submit</PrimaryButton>
            </form>
          </div>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>

</template>
