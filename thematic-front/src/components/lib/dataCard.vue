<script setup>
import {ref} from 'vue'

//set title, description and image as props
const props = defineProps({
  title: String,
  description: String,
  image: String,
})
</script>

<template>
  <div class="md:w-1/3 p-6 flex flex-col relative">
    <div v-bind:style="{ backgroundImage: 'url(' + restaurant.image + ')' }" style="background-size: cover">
      <div class="flex-1 bg-transparent rounded-t rounded-b-none overflow-hidden shadow">
        <div class="w-full font-bold text-xl text-gray-800 px-6">
          {{ props.title }}
        </div>
      </div>
      <div class="flex-none mt-auto bg-transparent rounded-b rounded-t-none overflow-hidden shadow p-6">
        <div class="flex items-center justify-start">
          <button
              @click="getRestaurant(restaurant)"
              class="mx-auto lg:mx-0 hover:underline gradient text-black font-bold rounded-full my-6 py-4 px-8 shadow-lg focus:outline-none focus:shadow-outline transform transition hover:scale-105 duration-300 ease-in-out">
            Go
          </button>
        </div>
      </div>
    </div>
    <div class="flex-none mt-auto bg-white rounded-b rounded-t-none overflow-hidden shadow p-3">
      <div class="text-center">
        <p class="text-xl px-6">
          {{ restaurant.nom }}
        </p>
        <p class="text-small px-6">
          {{restaurant.description}}
        </p>
      </div>
    </div>
  </div>
</template>