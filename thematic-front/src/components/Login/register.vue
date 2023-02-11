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
  <div class="p-6 rounded-lg shadow-lg bg-white flex flex-column container_register mx-auto mt-20">
    <form @submit.prevent="submit">
      <div class="grid grid-cols-2">
          <div class="form-group mb-6 mx-4">
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
          <div class="form-group mb-6 mx-4">
          <label for="exampleInputPassword2" class="form-label inline-block mb-2 text-gray-700">Firtsname</label>
          <input type="text" name="firstname" v-model="firstname" required class="form-control block
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
                 placeholder="Firstname">
        </div>
          <div class="form-group mb-6 mx-4">
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
                  focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="password"
                   placeholder="Password">
          </div>
          <div class="form-group mb-6 mx-4">
            <label for="exampleInputPassword2" class="form-label inline-block mb-2 text-gray-700">Confirm
              Password</label>
            <input type="password" name="password_confirm" v-model="password_confirm" required class="form-control block
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
        focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" id="password_confirm"
                   placeholder="Repeated Password">
          </div>
          <div class="form-group mb-6 mx-4">
            <label for="exampleInputPassword2" class="form-label inline-block mb-2 text-gray-700">Lastname</label>
            <input type="text" name="lastname" v-model="lastname" required class="form-control block
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
                   placeholder="Lastname">
          </div>
          <div class="form-group mb-6 mx-4">
            <label for="exampleInputPassword2" class="form-label inline-block mb-2 text-gray-700">Phone number</label>
            <input type="tel" name="NumberPhone" v-model="NumberPhone" required class="form-control block
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
                   placeholder="NumberPhone">
          </div>
          <div class="form-group mb-6 mx-4 col-span-2">
            <label for="exampleInputPassword2" class="form-label inline-block mb-2 text-gray-700">Address</label>
            <input type="string" name="address" v-model="address" required class="form-control block
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
                   placeholder="address">
          </div>
      </div>
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
        Sign in
      </button>
    </form>
  </div>
</template>

<style scoped>
  .container_register {
    width: 50%;
    height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 1px solid #ccc;
  }
</style>


