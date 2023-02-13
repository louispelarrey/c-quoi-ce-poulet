<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

const password = ref("");
const passwordConfirm = ref("");
const router = useRouter();

const submit = () => {
  const token = router.currentRoute.value.params.token;
  fetch(import.meta.env.VITE_API_URL + "forgot-password/" + token, {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json"
    },
    body: JSON.stringify({
      password: password.value,
    }),
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.error) {
        alert(data.message);
      } else {
        router.push("/login?updated=success");
      }
    });
};

</script>
<template>
  <div>
    <form @submit.prevent="submit">
      <h3>Reset Password</h3>
      <div>
        <label for="password">New Password</label>
        <input type="password" name="password" v-model="password" required id="password"
          placeholder="Enter your new password" />
      </div>
      <div>
        <label for="passwordConfirm">Confirm Password</label>
        <input type="password" name="passwordConfirm" v-model="passwordConfirm" required id="passwordConfirm"
          placeholder="Confirm your new password" />
      </div>
      <button type="submit">
        Reset Password
      </button>
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