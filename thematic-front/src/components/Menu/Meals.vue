<script setup lang="ts">
import { ref } from 'vue'
import mealItem from './meal.vue'

//TODO: Get meals from API

const meals = ref([
  {
    "id": "1",
    "name": "Spaghetti Bolognese",
    "picture": "https://www.example.com/images/spaghetti.jpg",
    "price": 12.99,
    "description": "Tender spaghetti noodles in a rich and flavorful meat sauce, topped with Parmesan cheese.",
  },
  {
    "id": "2",
    "name": "Chicken Parmesan",
    "picture": "https://www.example.com/images/chicken-parmesan.jpg",
    "price": 14.99,
    "description": "Breaded chicken breast topped with marinara sauce and melted mozzarella cheese, served with spaghetti.",
  },
  {
    "id": "3",
    "name": "Lasagna",
    "picture": "https://www.example.com/images/lasagna.jpg",
    "price": 11.99,
    "description": "Layers of pasta, meat sauce, and cheese, topped with Parmesan cheese.",
  },
  {
    "id": "4",
    "name": "Chicken Alfredo",
    "picture": "https://www.example.com/images/chicken-alfredo.jpg",
    "price": 13.99,
    "description": "Chicken breast sautéed with mushrooms in a creamy garlic Parmesan sauce, served over fettuccine.",
  },
  {
    "id": "5",
    "name": "Chicken Marsala",
    "picture": "https://www.example.com/images/chicken-marsala.jpg",
    "price": 14.99,
    "description": "Chicken breast sautéed with mushrooms in a Marsala wine sauce, served over fettuccine.",
  },
  {
    "id": "6",
    "name": "Spaghetti Bolognese",
    "picture": "https://www.example.com/images/spaghetti.jpg",
    "price": 12.99,
    "description": "Tender spaghetti noodles in a rich and flavorful meat sauce, topped with Parmesan cheese.",
  },
  {
    "id": "7",
    "name": "Chicken Parmesan",
    "picture": "https://www.example.com/images/chicken-parmesan.jpg",
    "price": 14.99,
    "description": "Breaded chicken breast topped with marinara sauce and melted mozzarella cheese, served with spaghetti.",
  }
]);

</script>

<template>
    <div class="menu">
      <meal-item v-for="meal in meals" :key="meal.id"/>
    </div>
</template>