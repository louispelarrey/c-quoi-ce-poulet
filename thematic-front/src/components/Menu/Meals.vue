<script setup lang="ts">
import { ref } from 'vue'
import {useRoute} from "vue-router";

const route = useRoute()
const id = route.params.id

const token = localStorage.getItem('token')

fetch(import.meta.env.VITE_API_URL+"restaurants/"+id+"", {
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
        menus.value = data
      }
    });

const menus = ref({})

</script>

<template>
    <div class="menu">
      <mealItem :menu="menu" v-for="menu in menus" :key="menu"/>
    </div>
</template>