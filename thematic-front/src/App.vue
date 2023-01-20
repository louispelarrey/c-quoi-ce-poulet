<script setup>
import Nav from "./components/Nav.vue";
import Footer from "./components/Footer.vue";
import { RouterView } from "vue-router";
const token = localStorage.getItem('token')
<<<<<<< Updated upstream
=======

let userAdmin = ref(false)
if (token) {
  const user = JSON.parse(atob(token.split('.')[1]))
  // check if toke is epxired
  if (user.exp * 1000 < Date.now()) {
    localStorage.removeItem('token')
    location.href = '/login'
  } else {
    userAdmin = Object.values(user.roles).includes('ROLE_ADMIN');
    if (!userAdmin) {
      document.addEventListener("DOMContentLoaded", function(event) {
        document.querySelector('#viewcontainer').classList.add('mt-10')
        document.querySelector('#viewcontainer').classList.add('container')
      });
    }
  }

}
>>>>>>> Stashed changes

</script>
<template>
  <Nav/>
  <div class="container fitPageHeight mx-auto mt-10">
    <RouterView />
  </div>
  <Footer/>
</template>

<style>
  .fitPageHeight {
    min-height: 70vh;
  }
</style>