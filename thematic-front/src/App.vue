<script setup>
import Nav from "./components/Nav.vue";
import Footer from "./components/Footer.vue";
import { RouterView } from "vue-router";
const token = localStorage.getItem('token')

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