<script setup lang="ts">
import { ref } from 'vue'
import {useRoute} from "vue-router";
import Meal from "./Meal.vue";

const route = useRoute()
const id = route.params.id

const token = localStorage.getItem('token')
const menus = ref({})
const restaurant = ref({})

fetch(import.meta.env.VITE_API_URL+"restaurants/"+id, {
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
        restaurant.value = data
        menus.value = data.meals
      }
    });

</script>

<template>
  <div>
    <div>
      <h1 class="text-4xl text-center mb-10">{{restaurant.name}}'s menu</h1>
    </div>
    <div>
      <p class="text-center" v-if="restaurant.meals?.length === 0">There is no meal for now.</p>
      <div class="menu grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-10 my-5">
        <Meal :menu="menu" v-for="menu in menus" :key="menu" :restaurant_id="restaurant.id"/>
      </div>
    </div>
  </div>
</template>