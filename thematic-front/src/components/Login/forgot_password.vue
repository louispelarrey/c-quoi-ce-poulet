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
                router.push("/reset-password");
            }
        });
};

</script>

<template>
    <div>
        <form @submit.prevent="submit">
            <h3>Forgot Password</h3>
            <div>
                <label for="exampleInputEmail2">Email address</label>
                <input type="email" name="email" v-model="email" required id="exampleInputEmail2"
                    aria-describedby="emailHelp" placeholder="Enter email">
            </div>
            <button type="submit">
                Reset Password
            </button>
            <p>Remember your password ?
                <router-link to="/login">Login</router-link>
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
