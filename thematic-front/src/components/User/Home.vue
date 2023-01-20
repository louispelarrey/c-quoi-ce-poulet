<script setup>
import {ref} from 'vue'
import {useRoute, useRouter} from "vue-router";

const router = useRouter();
const route = useRoute();

// get token props from App.vue

const token = route.params.token

const props = defineProps({
  token: String
})

let restaurants = ref({})

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

// const restaurants = ref([
//   {
//     'id': 1,
//     'nom': 'test 1',
//     'adresse': 'aaaaa',
//     'description': 'aaaaa',
//     'heureOuverture': '12:00',
//     'type': 'burger',
//     'image': 'https://www.spoon-restaurant.com/wp-content/uploads/2022/06/Spoon_cLe_Bonbon-1-scaled.jpg'
//   }, {
//     'id': 2,
//     'nom': 'test 2',
//     'adresse': 'aaaaa',
//     'description': 'aaaaa',
//     'heureOuverture': '12:00',
//     'type': 'africain',
//     'image': 'https://www.spoon-restaurant.com/wp-content/uploads/2022/06/Spoon_cLe_Bonbon-1-scaled.jpg'
//   }, {
//     'id': 3,
//     'nom': 'test 3',
//     'adresse': 'aaaaa',
//     'description': 'aaaaa',
//     'heureOuverture': '12:00',
//     'type': 'chinois',
//     'image': 'https://www.spoon-restaurant.com/wp-content/uploads/2022/06/Spoon_cLe_Bonbon-1-scaled.jpg'
//   }, {
//     'id': 4,
//     'nom': 'test 4',
//     'adresse': 'aaaaa',
//     'description': 'aaaaa',
//     'heureOuverture': '12:00',
//     'type': 'chinois',
//     'image': 'https://www.spoon-restaurant.com/wp-content/uploads/2022/06/Spoon_cLe_Bonbon-1-scaled.jpg'
//   },
// ])


</script>

<template>
  <section class="bg-white border-b py-8">
    <div class="container max-w-5xl mx-auto m-8">
      <h1 class="w-full my-2 text-3xl font-bold leading-tight text-center text-gray-800">
        C Quoi ce poulet ?
      </h1>
    </div>
  </section>
  <section class="bg-white border-b py-8">
    <div class="container mx-auto flex flex-wrap pt-4 pb-12">
      <h1 class="w-full my-2 text-5xl font-bold leading-tight text-center text-gray-800">
        Title
      </h1>

      <div v-for="restaurant in restaurants" class="md:w-1/3 p-6 flex flex-col relative">
        <div v-bind:style="{ backgroundImage: 'url(' + restaurant.image + ')' }" style="background-size: cover; min-height: 80%">
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
    </div>
  </section>
</template>
