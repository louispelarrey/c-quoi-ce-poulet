<script setup>
import { ref } from 'vue'
// check if the user is the admin via his token
// if not, redirect to home page

const token = localStorage.getItem('token')

const restaurants = ref([])

fetch(import.meta.env.VITE_API_URL+"restaurants", {
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
        restaurants.value = data
      }
    });

const deleteRestaurants = (id) => {
  fetch(import.meta.env.VITE_API_URL+"restaurants/"+id, {
    method: "DELETE",
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
          alert('User deleted successfully')
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
                Name
              </th>
              <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                Tags
              </th>
              <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                Opening Date
              </th>
              <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                address
              </th>
              <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                Actions
              </th>
            </tr>
            </thead>
            <tbody>
            <tr class="border-b" v-for="restaurant in restaurants">
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                {{ restaurant.name }}
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                <div>
                  <span v-for="tag in restaurant.tags" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-blue-800">
                    {{ tag.name }}
                  </span>
                </div>
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                <p>Ouverture : {{ restaurant.openingTime[0] }}</p>
                <p>Fermeture : {{ restaurant.openingTime[1] }}</p>
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                {{ restaurant.address }}
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                <button class="mx-auto lg:mx-0 hover:underline gradient text-black font-bold rounded-full my-6 py-4 px-8 shadow-lg focus:outline-none focus:shadow-outline transform transition hover:scale-105 duration-300 ease-in-out">
                  Edit</button>
                <button @click="deleteRestaurants(restaurant.id)"
                        class="mx-auto lg:mx-0 hover:underline gradient text-red font-bold rounded-full my-6 py-4 px-8 shadow-lg focus:outline-none focus:shadow-outline transform transition hover:scale-105 duration-300 ease-in-out">
                  Delete</button>
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
.read-the-docs {
  color: #888;
}
</style>
