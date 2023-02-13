<script setup>
import Nav from "./components/Nav.vue";
import Footer from "./components/Footer.vue";
import { RouterView } from "vue-router";
import NavAdmin from "./components/Admin/navAdmin.vue";
import {ref} from "vue";
import AuthProvider from "./components/Provider/AuthProvider.vue";

const token = localStorage.getItem('token')
let userAdmin = ref(false)


if (token) {
  const user = JSON.parse(atob(token.split('.')[1]))
  userAdmin.value = Object.values(user.roles).includes('ROLE_ADMIN');
  if (!userAdmin.value) {
    document.addEventListener("DOMContentLoaded", function(event) {
      document.querySelector('#viewcontainer').classList.add('mt-10')
      document.querySelector('#viewcontainer').classList.add('container')
    });
  }
}
</script>

<template>
  <AuthProvider>
    <Nav/>
    <div>
      <div id="viewcontainer" class="fitPageHeight mx-auto">
        <div v-if="userAdmin" class="flex flex-row full-height ">
          <navAdmin />
          <RouterView/>
        </div>
        <div v-else class="flex flex-row container_page">
          <RouterView/>
        </div>
      </div>
    </div>
    <Footer/>
  </AuthProvider>
</template>

<style>
.fitPageHeight {
  min-height: 70vh;
  align-items: center;
  justify-content: center;
}
.container_page {
  margin: 0 auto;
  width: 80vw;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>