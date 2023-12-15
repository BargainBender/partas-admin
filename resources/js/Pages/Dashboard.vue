<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <ContentLayout title="Dashboard">
      <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">

        <DashboardCard title="Basic Statistics">
          <div class="flex justify-between">
            <p>Total buses</p>
            <p class="text-green-400">{{ props.totalBuses }}</p>
          </div>
          <div class="flex justify-between">
            <p>Available locations</p>
            <p class="text-green-400">{{ props.totalLocations }}</p>
          </div>
          <div class="flex justify-between">
            <p>Available routes</p>
            <p class="text-green-400">{{ props.totalRoutes }}</p>
          </div>
          <div class="flex justify-between">
            <p>Upcoming schedules today</p>
            <p class="text-green-400">{{ props.upcomingSchedulesCount }}</p>
          </div>
          <div class="flex justify-between">
            <p>Upcoming schedules tomorrow</p>
            <p class="text-green-400">{{ props.upcomingSchedulesTomorrowCount}}</p>
          </div>
          <div class="flex justify-between">
            <p>Database size</p>
            <p class="text-green-400">{{ parseFloat(props.databaseSize[0].size_in_mb).toFixed(2) }} mb</p>
          </div>
        </DashboardCard>

        <DashboardCard title="Bus Types" inner-class="!pb-0">
          <div class="h-[90%]">
            <svg id="busTypes" class="!h-full"></svg>
          </div>
        </DashboardCard>

        <DashboardCard title="Recent Updates" inner-class="!px-4 overflow-y-scroll">
          <ul class="flex flex-col gap-2" v-if="props.latestUpdates.length > 0">
            <li v-for="update in props.latestUpdates" class="bg-base-100 p-2">
              <p class="text-xs">
                [{{ formatDateString(update.created_at, true) }}]
              </p>
              <p>{{ update.message }}</p>
            </li>
          </ul>
          <p v-else>No recent updates.</p>
        </DashboardCard>

        <DashboardCard title="Upcoming Schedules" class="col-start-1 col-end-3 w-full row-span-3" inner-class="!px-4 overflow-y-scroll">
          <ul class="flex flex-col gap-2" v-if="props.upcomingSchedules.length > 0">
            <li v-for="schedule in props.upcomingSchedules" class="bg-base-100 p-2">
              <div class="flex justify-between">
                <p>{{ formatDateString(schedule.departure_time) }} → {{ formatDateString(schedule.arrival_time) }}</p>
                <p>{{ schedule.route.origin }} → {{ schedule.route.destination }}</p>
                <p>{{ schedule.bus.code }}</p>
              </div>
            </li>
          </ul>
          <p v-else>No upcoming schedules.</p>
        </DashboardCard>
        
      </div>
    </ContentLayout>
</AuthenticatedLayout></template>

<script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import DashboardCard from '@/Components/DashboardCard.vue'
  import ContentLayout from '@/Layouts/ContentLayout.vue'
  import { Head } from '@inertiajs/vue3';
  import { watchEffect } from 'vue'
  import { onMounted } from 'vue';
  import * as d3 from "d3"


  // import { useToast } from 'vue-toast-notification';
  // import 'vue-toast-notification/dist/theme-sugar.css';
  // const $toast = useToast();

  const props = defineProps({
      totalBuses: Number,
      totalLocations: Number,
      totalRoutes: Number,
      latestUpdates: Object,
      busTypes: Object,
      upcomingSchedules: Object,
      upcomingSchedulesTomorrow: Object,
      upcomingSchedulesCount: Object,
      upcomingSchedulesTomorrowCount: Object,
      weekSchedules: Object,
      databaseSize: Object
  })

  console.log(props.weekSchedules)

  const busTypes = props.busTypes.map(({type, count}) => ({
    name: type,
    value: count
  }))
  
  console.log(props.latestUpdates)

  function formatDateString(inputDateString, includeSeconds = false) {
    // Parse the input date string
    const date = new Date(inputDateString);

    // Define options for formatting
    const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: 'numeric', second: 'numeric', timeZoneName: 'short' };

    // Format the date components
    const formattedDateString = date.toLocaleString('en-US', options);

    // Extract the day of the week and construct the final string
    const dayOfWeek = formattedDateString.split(',')[0];
    const hours = (date.getHours() % 12 || 12).toString().padStart(2, '0');
    const minutes = date.getMinutes().toString().padStart(2, '0');
    const seconds = date.getSeconds().toString().padStart(2, '0');
    const ampm = date.getHours() >= 12 ? 'PM' : 'AM';

    // Construct the final formatted date string
    const finalDateString = includeSeconds ?
        `${dayOfWeek} ${hours}:${minutes}:${seconds} ${ampm}` :
        `${dayOfWeek} ${hours}:${minutes} ${ampm}`;

    return finalDateString;
  }

  onMounted(() => {
    let data = busTypes
    
    const width = 300;
    const height = Math.min(width, 300);

    // Create the color scale.
    const color = d3.scaleOrdinal()
        .domain(data.map(d => d.name))
        .range(d3.quantize(t => d3.interpolateSpectral(t * 0.8 + 0.1), data.length).reverse())

    // Create the pie layout and arc generator.
    const pie = d3.pie()
        .sort(null)
        .value(d => d.value);

    const arc = d3.arc()
        .innerRadius(0)
        .outerRadius(Math.min(width, height) / 2 - 1);

    const labelRadius = arc.outerRadius()() * 0.6;

    // A separate arc generator for labels.
    const arcLabel = d3.arc()
        .innerRadius(labelRadius)
        .outerRadius(labelRadius);

    const arcs = pie(data);

    // Create the SVG container.
    const svg = d3.select("#busTypes")
        .attr("width", width)
        .attr("height", height)
        .attr("viewBox", [-width / 2, -height / 2, width, height])
        .attr("style", "max-width: 100%; height: auto; font: 15px sans-serif;");

    // Add a sector path for each value.
    svg.append("g")
        .attr("stroke", "white")
      .selectAll()
      .data(arcs)
      .join("path")
        .attr("fill", d => color(d.data.name))
        .attr("d", arc)
      .append("title")
        .text(d => `${d.data.name}: ${d.data.value.toLocaleString("en-US")}`);

    // Create a new arc generator to place a label close to the edge.
    // The label shows the value if there is enough room.
    svg.append("g")
        .attr("text-anchor", "middle")
      .selectAll()
      .data(arcs)
      .join("text")
        .attr("transform", d => `translate(${arcLabel.centroid(d)})`)
        .call(text => text.append("tspan")
            .attr("y", "-0.4em")
            .attr("font-weight", "bold")
            .text(d => d.data.name))
        .call(text => text.filter(d => (d.endAngle - d.startAngle) > 0.25).append("tspan")
            .attr("x", 0)
            .attr("y", "0.7em")
            .attr("fill-opacity", 0.7)
            .text(d => d.data.value.toLocaleString("en-US")));

  })

</script>
