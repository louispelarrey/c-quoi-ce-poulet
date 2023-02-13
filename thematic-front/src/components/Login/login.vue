<script setup lang="ts">

import { useRoute, useRouter } from "vue-router";
import { ref } from "vue";
import ErrorMessage from "../Error/ErrorMessage.vue";

const router = useRouter();
const route = useRoute();
const errorCode = ref(0);
const updated = ref(route.query.updated === "success");

const email = ref('')
const password = ref('')
const submit = () => {
  fetch(import.meta.env.VITE_API_URL + "auth", {
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
  <div>
    <form @submit.prevent="submit">
      <h3>Login</h3>
      <div>
        <label for="exampleInputEmail2">Email address</label>
        <input type="email" name="email" v-model="email" required id="exampleInputEmail2" aria-describedby="emailHelp"
          placeholder="Enter email">
      </div>
      <div>
        <label for="exampleInputPassword2">Password</label>
        <input type="password" name="password" v-model="password" required id="exampleInputPassword2"
          placeholder="Password">
      </div>
      <div>
        <div>
          <input type="checkbox" id="exampleCheck2">
          <label for="exampleCheck2">Remember me</label>
        </div>
      </div>
      <router-link to="/forgot-password">Forgot password ?</router-link>
      <ErrorMessage v-if="errorCode" :code="errorCode" />
      <p v-if="updated">Your password has been updated successfully.</p>
      <button type="submit">
        Login
      </button>
      <p>Not a member?
        <router-link to="/register">Register</router-link>
      </p>
    </form>
  </div>
</template>

<style scoped>
  form {
    width: 500px;
    margin: 50px auto;
    border: 1px solid #ddd;
    padding: 40px;
    text-align: center;
    box-shadow: 0 2px 3px #ccc;
  }
  h3 {
    margin-bottom: 30px;
    font-size: 24px;
  }
  label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
  }
  input {
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    box-sizing: border-box;
    border: 1px solid #ddd;
    font-size: 16px;
  }
  button {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: #fff;
    border: none;
    font-size: 16px;
    cursor: pointer;
  }
  p {
    margin-top: 30px;
    font-size: 14px;
  }
  a {
    color: #0077ff;
    text-decoration: none;
  }
  .error {
    color: red;
  }
</style>