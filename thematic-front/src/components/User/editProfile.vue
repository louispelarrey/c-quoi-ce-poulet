<script setup>
import { ref } from 'vue'

const token = localStorage.getItem('token')

const user = ref({})
const email = ref('')

const base64Url = token.split('.')[1];
const base64 = base64Url.replace('-', '+').replace('_', '/');
const decodedToken = JSON.parse(window.atob(base64));
const actualUsername = decodedToken.username;

fetch( 'https://localhost/users?email[]=' + actualUsername, {
  method: 'GET',
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
      user.value = data[0]
      email.value = data[0].email
    }
  });

const submit = () => {
  console.log("aaaaaaaaaaaa");
  fetch("https://localhost/users/"+user.value.id, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      'Authorization': `Bearer ${token}`,
    },
    body: JSON.stringify({
      email: email.value,
    })
  })
  .then((res) => res.json())
  .then((data) => {
    if (data.detail) {
      alert(data.violations[0].message);
    } else {
      fetch("https://localhost/auth", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "Accept": "application/json"
        },
        body: JSON.stringify({
          email: email.value,
          password: password.value,
        }),
      })
      alert('Your profile has been updated')
    }
  });
}

</script>

<template>
  <div class="h-full">

    <div class="border-b-2 block md:flex">

      <div class="w-full md:w-2/5 p-4 sm:p-6 lg:p-8 bg-white shadow-md">
        <div class="flex justify-between">
          <span class="text-xl font-semibold block">Your Profile</span>
        </div>
        <span class="text-gray-600">Information on your account</span>
        <div class="w-full p-8 mx-2 flex justify-center">
          <img id="showImage" class="max-w-xs w-32 items-center border" src="https://images.unsplash.com/photo-1511367461989-f85a21fda167?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1631&q=80" alt="">
        </div>
      </div>

      <div class="w-full md:w-3/5 p-8 bg-white lg:ml-4 shadow-md">
        <div class="rounded  shadow p-6">
          <div class="pb-4">
              <label for="about" class="font-semibold text-gray-700 block pb-1">Email</label>
              <input id="email" class="border-1  rounded-r px-4 py-2 w-full" v-model="email" />
              <span class="text-gray-600 pt-4 block opacity-70">Personal login information of your account</span>
              <div class="pt-4 cursor-pointer " >
                <a @click="submit" class="-mt-2 text-md font-bold text-white bg-gray-700 rounded-full px-5 py-2 hover:bg-gray-800">Edit</a>
              </div>
          </div>
        </div>
      </div>

    </div>

  </div>
</template>

<style scoped>
.read-the-docs {
  color: #888;
}
</style>
