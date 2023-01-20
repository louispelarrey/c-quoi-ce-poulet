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
  userAdmin = Object.values(user.roles).includes('ROLE_ADMIN');
  if (!userAdmin) {
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
    <div class="relative">
      <div id="viewcontainer" class="fitPageHeight mx-auto">
        <div v-if="userAdmin" class="flex flex-row">
          <navAdmin/>
          <RouterView/>
        </div>
        <div v-else>
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
}
</style>