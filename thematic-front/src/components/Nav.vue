<script setup>
import { ref } from 'vue'

function logout() {
  localStorage.removeItem("token");
  window.location.href = "/";
}

function toggleNav() {
  const nav = document.getElementById("nav-content");
  nav.classList.toggle("hidden");

}

const token = localStorage.getItem("token");

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
              <a class=" cursor-pointer inline-block text-black no-underline hover:text-gray-800 hover:text-underline py-2 px-4" @click="logout">Logout</a>
            </div>
            <div v-else>
              <router-link class="inline-block text-black no-underline hover:text-gray-800 hover:text-underline py-2 px-4" to="/login">Login</router-link>
            </div>
          </li>
          <li class="mr-3">
            <router-link class="inline-block text-black no-underline hover:text-gray-800 hover:text-underline py-2 px-4" to="/profile" >Profile</router-link>
          </li>
        </ul>
        <button
            id="navAction"
            class="mx-auto lg:mx-0 hover:underline bg-white text-gray-800 font-bold rounded-full mt-4 lg:mt-0 py-4 px-8 shadow opacity-75 focus:outline-none focus:shadow-outline transform transition hover:scale-105 duration-300 ease-in-out"
        >
          Action
        </button>
      </div>
      <div class="space-y-2 hidden cursor-pointer" id="burger_button" @click="toggleNav">
        <span class="block w-8 h-0.5 bg-gray-600" />
        <span class="block w-8 h-0.5 bg-gray-600" />
        <span class="block w-5 h-0.5 bg-gray-600" />
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

<script>
export default {
  data() {
    return {
      isMobile: false
    }
  },
  created() {
    window.addEventListener("resize", this.myEventHandler);
  },
  destroyed() {
    window.removeEventListener("resize", this.myEventHandler);
  },
  methods: {
    myEventHandler(e) {
      if (window.matchMedia("(max-width: 1024px)").matches) {
        document.getElementById("burger_button").classList.remove("hidden");
      }else {
        document.getElementById("burger_button").classList.add("hidden");
      }
    }
  }
}
</script>
