<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import { reactive, watch } from 'vue'
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import { getMonth, addMonths } from 'date-fns'

const props = defineProps({
  busSchedule: Object,
  routes: Object,
  buses: Object

})

const form = reactive({
  arrival_time: props.busSchedule.arrival_time,
  departure_time: props.busSchedule.departure_time,
  departure_time_input: "",
  arrival_time_input: "",

  bus_id: props.busSchedule.bus_id,
  route_id: props.busSchedule.route_id
})

function timestampToIso8601(timestamp) {
  let date = new Date(timestamp)
  let year = date.getFullYear()
  let month = ('0' + (date.getMonth() + 1)).slice(-2) // Months are zero based
  let day = ('0' + date.getDate()).slice(-2)
  let hours = ('0' + date.getHours()).slice(-2)
  let minutes = ('0' + date.getMinutes()).slice(-2)

  let formattedDate = `${year}-${month}-${day}T${hours}:${minutes}`

  return formattedDate // Outputs: yyyy-MM-ddThh:mm
}

watch(() => form.departure_time_input, (timestamp, _) => form.departure_time = timestampToIso8601(timestamp))
watch(() => form.arrival_time_input, (timestamp, _) => form.arrival_time = timestampToIso8601(timestamp))

form.arrival_time_input = new Date(props.busSchedule.arrival_time).getTime()
form.departure_time_input = new Date(props.busSchedule.departure_time).getTime()

function update() {
  router.post(`/schedule/${props.busSchedule.id}`, {
    _method: 'put',
    arrival_time: form.arrival_time,
    departure_time: form.departure_time,
    bus_id: form.bus_id,
    route_id: form.route_id
  })
}

</script>



<template>
  <Head title="Update Bus Schedule" />

  <AuthenticatedLayout>


    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="ma-8 w-100 bg-base-100 rounded-lg">
          <div class="mx-8 ">
            <form @submit.prevent="update">
              <div class="md:w-1/3">
                <InputLabel for="departure_time" class="" value="Departure Time" />
              </div>
              <div class="block w-full">

                <!-- <TextInput id="departure_time" type="datetime-local" v-model="form.departure_time" required /> -->
                <VueDatePicker v-model="form.departure_time_input" model-type="timestamp" :required="true" :is24="false"
                  :min-date="new Date()" :max-date="form.arrival_time_input" :max-time="form.arrival_time_input" />
              </div>
              <div>
                <InputLabel for="arrival_time" value="Arrival Time" />
              </div>
              <div class="block w-full">
                <!-- <TextInput id="arrival_time" type="datetime-local" v-model="form.arrival_time" required /> -->
                <VueDatePicker v-model="form.arrival_time_input" model-type="timestamp" :required="true" :is24="false"
                  :min-date="form.departure_time_input || new Date()" :min-time="form.departure_time_input" />
              </div>

              <InputLabel for="route" class="block font-medium text-gray-700">Select Bus Route:</InputLabel>
              <select id="route" v-model="form.route_id"
                class="mt-1 block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:outline-none focus:border-indigo-500">
                <option v-for="route in routes" :value="route.id">{{ route.origin }} - {{ route.destination }}</option>
              </select>

              <InputLabel for="bus" class="block font-medium text-gray-700">Select Bus </InputLabel>
              <select id="route" v-model="form.bus_id"
                class="mt-1 block w-full px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:outline-none focus:border-indigo-500">
                <option v-for="bus in buses" :value="bus.id">{{ bus.code }} - {{ bus.type }}</option>
              </select>

              <div class=" py-3 md:w-1/3">
                <PrimaryButton type="submit"> Submit </PrimaryButton>
              </div>

            </form>
          </div>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>
