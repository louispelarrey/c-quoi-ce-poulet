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
        console.log(data)
        restaurant.value = data
        menus.value = data.meals
      }
    });

</script>

<template>
  <h1 class="text-4xl text-center mb-10">Menus du restaurant {{restaurant.name}}</h1>
  <p class="text-center" v-if="restaurant.meals.length === 0">Il n'y a aucun menu pour le moment</p>
  <div class="menu grid lg:grid-cols-3 md:grid-cols-2 sm:grid-cols-1 gap-10 my-5">
    <Meal :menu="menu" v-for="menu in menus" :key="menu"/>
  </div>
</template>