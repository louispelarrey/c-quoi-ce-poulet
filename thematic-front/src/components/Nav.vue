<script setup>
import { ref } from 'vue'

function logout() {
  localStorage.removeItem("token");
  window.location.href = "/";
}

const token = localStorage.getItem("token");

const user = ref(null);

</script>

<template>
  <!--Nav-->
  <div id="header" class="w-full z-30 top-0 text-white bg-purple-400">
    <div class="w-full container mx-auto flex flex-wrap items-center justify-between mt-0 py-2">
      <div class="pl-4 flex items-center">
        <router-link class="toggleColour text-white no-underline hover:no-underline font-bold text-2xl lg:text-4xl" to="/">
          Challenge
        </router-link>
      </div>
      <div class="w-full flex-grow lg:flex lg:items-center lg:w-auto hidden mt-2 lg:mt-0 bg-white lg:bg-transparent text-black p-4 lg:p-0 z-20" id="nav-content">
        <ul class="list-reset lg:flex justify-end flex-1 items-center">
          <li class="mr-3">
            <a class="inline-block py-2 px-4 text-black font-bold no-underline">
              <router-link to="/">Home</router-link>
            </a>
          </li>
          <li class="mr-3">
            <div v-if="token">
              <a class="cursor-pointer inline-block text-black no-underline hover:text-gray-800 hover:text-underline py-2 px-4" @click="logout">Logout</a>
            </div>
            <div v-else>
              <router-link class="inline-block text-black no-underline hover:text-gray-800 hover:text-underline py-2 px-4" to="/login">Login</router-link>
              <router-link class="inline-block text-black no-underline hover:text-gray-800 hover:text-underline py-2 px-4" to="/register">Register</router-link>
            </div>
          </li>
          <li class="mr-3">
            <router-link class="inline-block text-black no-underline hover:text-gray-800 hover:text-underline py-2 px-4" to="/profile" >Profile</router-link>
          </li>
        </ul>
        <button
            class="ml-2 mx-auto lg:mx-0 hover:underline bg-white text-gray-800 font-bold rounded-full shadow opacity-75 focus:outline-none focus:shadow-outline transform transition hover:scale-105 duration-300 ease-in-out"
        >
          <router-link class="inline-block text-black no-underline hover:text-gray-800 hover:text-underline py-2 px-4" to="/restaurants/new" >
            Inscrire un restaurant
          </router-link>
        </button>
        <button
            class="mx-auto lg:mx-0 hover:underline bg-white text-gray-800 font-bold rounded-full mt-4 lg:mt-0 py-4 px-8 shadow opacity-75 focus:outline-none focus:shadow-outline transform transition hover:scale-105 duration-300 ease-in-out"
        >
          Panier
        </button>
      </div>
    </div>
    <hr class="border-b border-gray-100 opacity-25 my-0 py-0" />
  </div>
</template>

<style scoped>
.read-the-docs {
  color: #888;
}
</style>
