<script setup>
import { ref } from 'vue'

const token = localStorage.getItem('token')

const users = ref([])
const reports = ref([])

fetch(import.meta.env.VITE_API_URL+"reports?status=['']", {
  method: "GET",
  headers: {
    "Content-Type": "application/json",
    "Accept": "application/json",
    'Authorization': `Bearer ${token}`,
  }
})
  .then((res) => res.json())
  .then((data) => {
    if (data.error) {
      alert(data.error);
    } else {
      reports.value = data
    }
  });

const changeReport = (id, state) => {
  fetch(import.meta.env.VITE_API_URL+"reports/"+id, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      'Authorization': `Bearer ${token}`,
    },
    //send to body the state of the report
    body: JSON.stringify({
      status: [state]
    })
  })
  .then((res) => res.json())
  .then((data) => {
    if (data.error) {
      alert(data.error);
    } else {
      alert('Report state modified successfully')
      location.reload()
    }
  });
}

</script>

<template>
  <div class="flex flex-col">
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
        <div class="overflow-hidden">
          <table class="min-w-full">
            <thead class="border-b">
            <tr>
              <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                User reported
              </th>
              <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                Report by
              </th>
              <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                Report reason
              </th>
              <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                Report Status
              </th>
              <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                Actions
              </th>
            </tr>
            </thead>
            <tbody>
            <tr class="border-b" v-for="report in reports">
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                {{ report.reportedUser.firstname }} {{ report.reportedUser.lastname }}
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                {{ report.reportedBy.firstname }} {{ report.reportedBy.lastname }}
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                {{ report.reason }}
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap"
                  :style="{ color: `${report.status[0] === 'closed' ? 'red' : (report.status[0] === 'in_progress' ? 'orange' : 'green')}` }"
              >
                {{ report.status[0] }}
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                <button @click="changeReport(report.id, 'in_progress')" class="mx-auto lg:mx-0 hover:underline gradient text-black font-bold rounded-full my-6 py-4 px-8 shadow-lg focus:outline-none focus:shadow-outline transform transition hover:scale-105 duration-300 ease-in-out">
                  Pending</button>
                <button @click="changeReport(report.id, 'closed')"
                  class="mx-auto lg:mx-0 hover:underline gradient text-red font-bold rounded-full my-6 py-4 px-8 shadow-lg focus:outline-none focus:shadow-outline transform transition hover:scale-105 duration-300 ease-in-out">
                  Closed</button>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>
