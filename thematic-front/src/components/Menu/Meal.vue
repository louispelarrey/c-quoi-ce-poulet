<script setup>
import { ref } from 'vue'

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

const props = defineProps({
  menu: {
    type: Object,
    required: true
  }
})

const menu = ref(props.menu)

</script>

<template>
  <div v-if="menu.isAvailable" class="meal-container col-span-1 bg-white">
    <img :src="menu" :alt="menu.name" />
    <div class="meal">
      <h3>{{ menu.name }}</h3>
      <h4>{{ menu.price }} €</h4>
      <p>{{ menu.description }}</p>
      <div v-if="isMorethanOneInCart">
        <input style="width: 40px" class="cursor-pointer bg-transparent text-blue-700 font-semibold py-2 px-4 border border-blue-500 rounded"
               value="-"
               @click="removeFromCart" />
        <span class="m-5">{{ mealCounter }}</span>
        <input style="width: 40px" class="cursor-pointer bg-transparent text-blue-700 font-semibold py-2 px-4 border border-blue-500 rounded"
               type="button" value="+"
               @click="addToCart" />

      </div>
      <input class="cursor-pointer bg-transparent text-blue-700 font-semibold py-2 px-4 border border-blue-500 rounded" v-else type="button" value="Ajouter au panier" @click="addToCart" />
    </div>
  </div>

</template>

<style scoped>
.meal-container {
  display: flex;
  flex-direction: row;
  align-items: center;
  padding: 1rem;
  border: 1px solid #ccc;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.26);
  justify-content: space-between;
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
