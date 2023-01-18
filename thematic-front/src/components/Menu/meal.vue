<script setup lang="ts">
import { ref, reactive } from 'vue'

//TODO: Get meal from API
const meal = ref({
  "id": "6",
  "name": "Spaghetti Bolognese",
  "picture": "https://www.example.com/images/spaghetti.jpg",
  "price": 12.99,
  "description": "Tender spaghetti noodles in a rich and flavorful meat sauce, topped with Parmesan cheese.",
})

let mealCounter = ref(0)
const isMorethanOneInCart = ref(false)

const toggleCartHandler = () => {
  isMorethanOneInCart.value = mealCounter.value > 0
}

const addToCart = () => {
  mealCounter.value++
  toggleCartHandler()
}

const removeFromCart = () => {
  mealCounter.value--
  toggleCartHandler()
}
</script>

<template>
  <div v-bind:meal="meal.id" class="meal">
    <img :src="meal.picture" :alt="meal.name" />
    <h3>{{ meal.name }}</h3>
    <h4>{{ meal.price }} €</h4>
    <p>{{ meal.description }}</p>
    <div v-if="isMorethanOneInCart">
      <input type="button" value="+" @click="addToCart" />
      <span>{{ mealCounter }}</span>
      <input type="button" value="-" @click="removeFromCart" />
    </div>
    <input v-else type="button" value="Ajouter au panier" @click="addToCart" />
  </div>
</template>

<style scoped>
  .meal {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
    padding: 1rem;
    border: 1px solid #ccc;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.26);
  }

  .meal img {
    width: 30%;
    height: 10rem;
    object-fit: cover;
    border-radius: 12px 12px 0 0;
  }

  .meal h3 {
    margin: 1rem 0;
    font-size: 1.25rem;
  }

  .meal h4 {
    margin: 0.5rem 0;
    font-size: 1rem;
    color: #888;
  }

  .meal p {
    margin: 1rem 0;
    font-size: 0.9rem;
    color: #888;
  }
</style>
