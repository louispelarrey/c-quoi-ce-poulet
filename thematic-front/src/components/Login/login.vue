<script setup lang="ts">


import { useRoute, useRouter } from "vue-router";
import {ref} from "vue";
import ErrorMessage from "../Error/ErrorMessage.vue";

const router = useRouter();
const route = useRoute();
const errorCode = ref(0);

const email = ref('')
const password = ref('')
const submit = () => {
  fetch(import.meta.env.VITE_API_URL+"auth", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json"!
    },
    body: JSON.stringify({
      email: email.value,
      password: password.value,
    }),
  })
      .then((res) => res.json())
      .then((data) => {
        if (data.code === 401) {
          errorCode.value = 401;
        } else {
          if (data.error && !data.token) {
            errorCode.value = 500;
          } else {
            localStorage.setItem("token", data.token);
            router.push("/");
          }
        }
      });
}

</script>

<template>
  <div class="p-6 rounded-lg shadow-lg bg-white max-w-md">
    <form @submit.prevent="submit">
      <div class="form-group mb-6">
        <label for="exampleInputEmail2" class="form-label inline-block mb-2 text-gray-700">Email address</label>
        <input type="email" name="email" v-model="email" required
         class="form-control
        block
        w-full
        px-3
        py-1.5
        text-base
        font-normal
        text-gray-700
        bg-white bg-clip-padding
        border border-solid border-gray-300
        rounded
        transition
        ease-in-out
        m-0
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="exampleInputEmail2"
               aria-describedby="emailHelp" placeholder="Enter email">
      </div>
      <div class="form-group mb-6">
        <label for="exampleInputPassword2" class="form-label inline-block mb-2 text-gray-700">Password</label>
        <input type="password" name="password" v-model="password" required class="form-control block
        w-full
        px-3
        py-1.5
        text-base
        font-normal
        text-gray-700
        bg-white bg-clip-padding
        border border-solid border-gray-300
        rounded
        transition
        ease-in-out
        m-0
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="exampleInputPassword2"
               placeholder="Password">
      </div>
      <div class="flex justify-between items-center mb-6">
        <div class="form-group form-check">
          <input type="checkbox"
                 class="form-check-input appearance-none h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top bg-no-repeat bg-center bg-contain float-left mr-2 cursor-pointer"
                 id="exampleCheck2">
          <label class="form-check-label inline-block text-gray-800" for="exampleCheck2">Remember me</label>
        </div>
      </div>
        <a href="#!"
          class="text-blue-600 hover:text-blue-700 focus:text-blue-700 transition duration-200 ease-in-out">Forgot
        password?</a>
        <ErrorMessage v-if="errorCode" :code="errorCode" />
      <button type="submit" 
        class="
        w-full
        px-6
        py-2.5
        bg-blue-600
        text-white
        font-medium
        text-xs
        leading-tight
        uppercase
        rounded
        shadow-md
        hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">
        Login
      </button>
      <p class="text-gray-800 mt-6 text-center">Not a member? 
        <router-link to="/register" class="text-blue-600 hover:text-blue-700 focus:text-blue-700 transition duration-200 ease-in-out">Register</router-link>
      </p>
    </form>
  </div>
</template>


