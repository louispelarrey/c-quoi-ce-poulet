<script setup>
import {ref} from 'vue'
import {useRoute, useRouter} from "vue-router";

const router = useRouter();
const route = useRoute();

const token = localStorage.getItem('token')

const restaurants = ref({})

</script>

<template>
  <section class="bg-white border-b py-8">
    <div class="container max-w-5xl mx-auto m-8">
      <h1 class="w-full my-2 text-3xl font-bold leading-tight text-center text-gray-800">
        C Quoi ce poulet ?
      </h1>
    </div>
  </section>
  <section class="bg-white border-b py-8">
    <div class="container mx-auto flex flex-wrap pt-4 pb-12">
      <h1 class="w-full my-2 text-5xl font-bold leading-tight text-center text-gray-800">
        Title
      </h1>

    </div>
  </section>
</template>
