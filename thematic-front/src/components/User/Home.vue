<script setup>
import {ref} from 'vue'
import {useRoute, useRouter} from "vue-router";

const router = useRouter();
const route = useRoute();

const token = localStorage.getItem('token')

const restaurants = ref({})

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

const command = {
  meals: [
    {
      id: 1,
      name: 'Pizza',
      price: 10,
      img: 'https://www.lesfoodies.com/wp-content/uploads/2019/05/pizza-vegetarienne-1.jpg',

    }
  ],
}


</script>

<template>
  <section class="bg-white border-b py-8">
    <div class="container mx-auto flex flex-wrap pt-4 pb-12">
      <h1 class="w-full my-2 text-5xl font-bold leading-tight text-center text-gray-800">
        Liste des restaurants
      </h1>
      <div v-for="restaurant in restaurants" class="md:w-1/3 p-6 flex flex-col relative">
        <div class="cursor-pointer" @click="getMenu(restaurant)" :style="{ backgroundImage: `url(${restaurant.image ? restaurant.image : '../src/assets/Images/img_resto_global.jpg'})`, backgroundSize: 'cover', minHeight: '80%' }">
          <div class="bg-red flex-none mt-auto bg-transparent rounded-b rounded-t-none overflow-hidden shadow p-6" style="min-height: 30vh">
          </div>
        </div>
        <div class="flex-none mt-auto bg-white rounded-b rounded-t-none overflow-hidden shadow p-3">
          <div class="text-center">
              <p class="text-xl px-6">
                {{ restaurant.name }}
              </p>
            <div v-for="tag in restaurant.tags">
              <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded-full text-pink-600 bg-red-200 uppercase last:mr-0 mr-1">
                  {{tag.name}}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
