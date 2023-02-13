<script setup lang="ts">


import { useRoute, useRouter } from "vue-router";
import { ref } from "vue";

const router = useRouter();
const route = useRoute();

const email = ref('')
const password = ref('')
const password_confirm = ref('')
const firstname = ref('')
const lastname = ref('')
const NumberPhone = ref('')
const address = ref('')

const submit = () => {
  if (password.value !== password_confirm.value) {
    alert('your passwords don\'t match')
  } else {

  }
  fetch(import.meta.env.VITE_API_URL+"users", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json"
    },
    body: JSON.stringify({
      email: email.value,
      plainPassword: password.value,
      firstname: firstname.value,
      lastname: lastname.value,
      numberPhone: NumberPhone.value,
      address: address.value,
    }),
  })
      .then((res) => res.json())
      .then((data) => {
        if (data.detail) {
          alert(data.violations[0].message);
        } else {
          router.push("/login");
        }
      });
}
</script>

<template>
  <div>
    <form @submit.prevent="submit">
      <h3>Register</h3>
      <div>
        <label for="exampleInputEmail2">Email address</label>
        <input type="email" name="email" v-model="email" required id="exampleInputEmail2" aria-describedby="emailHelp" placeholder="Enter email">
      </div>
      <div>
        <label for="exampleInputPassword2">Password</label>
        <input type="password" name="password" v-model="password" required id="password" placeholder="Password">
      </div>
      <div>
        <label for="exampleInputPassword2">Confirm password</label>
        <input type="password" name="password_confirm" v-model="password_confirm" required id="password_confirm" placeholder="Repeated Password">
      </div>
      <div>
        <label for="exampleInputPassword2">Firstname</label>
        <input type="text" name="firstname" v-model="firstname" required id="exampleInputPassword2" placeholder="Firstname">
      </div>
      <div>
        <label for="exampleInputPassword2">Lastname</label>
        <input type="text" name="lastname" v-model="lastname" required id="exampleInputPassword2" placeholder="Lastname">
      </div>
      <div>
        <label for="exampleInputPassword2">Phone number</label>
        <input type="tel" name="NumberPhone" v-model="NumberPhone" required id="exampleInputPassword2" placeholder="NumberPhone">
      </div>
      <div>
        <label for="exampleInputPassword2">Address</label>
        <input type="string" name="address" v-model="address" required id="exampleInputPassword2" placeholder="address">
      </div>
      <button type="submit">Sign in</button>
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
</style>
