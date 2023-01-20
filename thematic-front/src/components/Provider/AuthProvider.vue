<template>
  <slot :login="login" :user="user"></slot>
</template>

<script setup>
import { ref, provide } from "vue";
import router from "../../router/index.js";
const user = ref(null);

const token = localStorage.getItem("token");
if (localStorage.getItem("token")) {
  user.value = JSON.parse(atob(token.split(".")[1])).user_id;
}else {
  router.push({ name: "login" });
}



function login(data) {
  user.value = data;
}
function logout() {
  user.value = null;
}
provide("userProvider|user", user);
provide("userProvider|login", login);
</script>