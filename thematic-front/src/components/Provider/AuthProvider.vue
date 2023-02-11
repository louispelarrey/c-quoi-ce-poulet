<template>
  <slot :login="login" :user="user"></slot>
</template>

<script setup>
import { ref, provide } from "vue";
const user = ref(null);

function login(data) {
  user.value = data;
}
function logout() {
  user.value = null;
}

provide("AuthProvider|logout", logout);
provide("AuthProvider|user", user);
provide("AuthProvider|login", login);
</script>