<script setup>
import {reactive, ref} from 'vue'
import {useRoute, useRouter} from "vue-router";
import RestaurantForm from "./RestaurantForm.vue";
import MealForm from "../Meal/MealForm.vue";

const router = useRouter();
const route = useRoute();

const token = localStorage.getItem('token')

const restaurants = ref({})
const addModelOpen = ref(false)

fetch(import.meta.env.VITE_API_URL + "restaurants?owner.id=5", {
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

const editRestaurant = (restaurant) => {
  restaurant.openingTime = [restaurant.openingTime, restaurant.closingTime]
  restaurant.owner = '/api/users/' + restaurant.owner.id
  fetch(import.meta.env.VITE_API_URL + "restaurants/" + restaurant.id, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      'Authorization': `Bearer ${token}`,
    },
    body: JSON.stringify(restaurant)
  })
      .then((res) => res.json())
      .then((data) => {
        if (data.detail) {
          alert(data.violations[0].message);
        } else {
          alert('Your restaurant datas has been updated')
        }
      });
}

const editMeal = (editedMeal) => {
  fetch(import.meta.env.VITE_API_URL + "meals", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      'Authorization': `Bearer ${token}`,
    },
    body: JSON.stringify(editedMeal)
  })
      .then(res => {
        if (res.ok !== true) {
          alert(data.violations[0].message);
        } else {
          alert('Your meal has been added')
        }
      });
}

const addMeal = (mealAdded) => {
  console.log(mealAdded)
  mealAdded.price = parseFloat(mealAdded.price)
  mealAdded.restaurant = 'api/restaurants/'+mealAdded.restaurant
  fetch(import.meta.env.VITE_API_URL + "meals", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      'Authorization': `Bearer ${token}`,
    },
    body: JSON.stringify(mealAdded)
  })
      .then(res => {
        if (res.ok !== true) {
          alert('marche po');
        } else {
          alert('Your meal has been added')
        }
      });
}

const deleteMeal = (mealId) => {
  fetch(import.meta.env.VITE_API_URL + "meals/" + mealId, {
    method: "DELETE",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      'Authorization': `Bearer ${token}`,
    },
  })
      .then(res => {
        if (res.ok !== true) {
        } else {
          restaurants.value.meals = restaurants.value.meals.filter(meal => meal.id !== mealId)
        }
      });
}


</script>

<template>
  <div v-for="restaurant in restaurants" :key="restaurant.id" class="text-center w-full">
    <RestaurantForm
        :restaurantEdit="restaurant"
        label="Your restaurant"
        @submit-restaurant="editRestaurant"
        buttonLabel="Edit"
    />
    <div class="my-5">
      <button class=" mt-2 px-6 py-2.5 bg-purple-600 text-white font-medium text-xs uppercase rounded shadow-md
        hover:bg-purple-700 hover:shadow-lg focus:bg-purple-800 focus:shadow-lg focus:outline-none focus:ring-0 transition duration-150 ease-in-out"
              @click="() => addModelOpen = true">Add Meal</button>
      <div v-if="addModelOpen" class="border-2 border-box mt-2">
        <MealForm
            :restaurantId="restaurant.id"
            label="Add a meal"
            @submit-meal="addMeal"
            buttonLabel="Add"
        />
      </div>
    </div>
    <h2 class="text-2xl text-purple-600 my-5">Actuals Meals</h2>
    <div class="grid grid-cols-2 gap-5 justify-between">
      <div v-for="meal in restaurant.meals" :key="meal.id">
        <div
            class="p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
          <MealForm
              :mealEdit="meal"
              label="Your meal"
              @submit-meal="addMeal"
              buttonLabel="Edit"
          />
          <button class="mt-2 px-6 py-2.5 bg-red-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md
        hover:bg-red-700 hover:shadow-lg focus:bg-red-800 focus:shadow-lg focus:outline-none focus:ring-0 transition duration-150 ease-in-out" @click="deleteMeal(meal.id)">Delete</button>
        </div>
      </div>
    </div>
  </div>
</template>
