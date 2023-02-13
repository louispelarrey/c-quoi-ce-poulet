<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

const email = ref('');
const router = useRouter();

const submit = () => {
    fetch(import.meta.env.VITE_API_URL + "forgot-password/", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: JSON.stringify({
            email: email.value,
        }),
    })
        .then((res) => res.json())
        .then((data) => {
            console.log(data)
            if (data.error) {
                alert(data.message);
            } else {
                router.push("/login");
            }
        });
};

</script>

<template>
    <div class="p-6 rounded-lg shadow-lg bg-white max-w-md">
        <h2 class="text-lg font-medium mb-6 text-center">Forgot Password</h2>
        <form @submit.prevent="submit">
            <div class="form-group mb-6">
                <label for="exampleInputEmail2" class="form-label inline-block mb-2 text-gray-700">Email address</label>
                <input type="email" name="email" v-model="email" required class="form-control
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
                Reset Password
            </button>
            <p class="text-gray-800 mt-6 text-center">Remember your password ?
                <router-link to="/login"
                    class="text-blue-600 hover:text-blue-700 focus:text-blue-700 transition duration-200 ease-in-out">Login</router-link>
            </p>
        </form>
    </div>
</template>
  
