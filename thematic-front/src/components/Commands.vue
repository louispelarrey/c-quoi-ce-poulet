<script setup>
import {ref} from 'vue'
import {useRoute, useRouter} from "vue-router";

const router = useRouter();
const route = useRoute();

const token = localStorage.getItem('token')
const actualUserId = JSON.parse(atob(token.split('.')[1])).user_id;

const orders = ref([])
const user = ref({})

user.value = JSON.parse(atob(token.split('.')[1]))
console.log( )

fetch(import.meta.env.VITE_API_URL+"orders", {
  method: "GET",
  headers: {
    "Content-Type": "application/json",
    "Accept": "application/json",
    'Authorization': `Bearer ${token}`,
  }
})
.then((res) => res.json())
.then((data) => {
  if (data.error) {
    alert(data.error);
  } else {
    data.map((order) => {
      if (order.status !== 'accepted'){
        order.status = JSON.parse(order.status)
      }
      orders.value.push(order)

    })
  }
});

const setDeliverer = (order) => {
  fetch(import.meta.env.VITE_API_URL+"orders/"+order+"/accepted", {
    method: "PATCH",
    headers: {
      "Content-Type": "application/merge-patch+json",
      "Accept": "application/json",
      'Authorization': `Bearer ${token}`,
    },
    body: JSON.stringify({})
  })
      .then((res) => res.json())
      .then((data) => {
        if (data.error) {
          alert(data.error);
        } else {
          console.log(data)
        }
      });
}

const confirmDelivery = (order) => {
  fetch(import.meta.env.VITE_API_URL+"orders/"+order+"/delivered", {
    method: "PATCH",
    headers: {
      "Content-Type": "application/merge-patch+json",
      "Accept": "application/json",
      'Authorization': `Bearer ${token}`,
    },
    body: JSON.stringify({})
  })
      .then((res) => res.json())
      .then((data) => {
        if (data.error) {
          alert(data.error);
        } else {
          console.log(data)
        }
      });
}

</script>

<template>
  <section class="bg-white border-b py-8 w-full">
    <div class="container mx-auto flex flex-wrap pt-4 pb-12">
      <h1 class="w-full my-2 text-5xl text-center font-bold leading-tight text-center text-gray-800">
        Orders List
      </h1>
      <div v-for="order in orders" class="rounded bg-white md:w-1/3 m-6 flex flex-col relative"
           :style="order.status?.status !== 'pending' && actualUserId === order.deliverer.id ? 'background-color:rgba(27, 159, 45, 0.4)' : 'background-color:rgba(226, 122, 38, 0.4)'"
      >
        <div class="flex-none mt-auto overflow-hidden p-3">
          <div class="text-center">
            <p v-if="order.status?.status">
              Status : {{ order.status?.status }}
            </p>
            <p v-else>
              Status : {{ order.status }}
            </p>
          </div>
        </div>
        <div class="flex-none mt-auto overflow-hidden p-3">
          <div class="text-center">
            <p class="text-xl px-6">
              order from : {{ order.client.firstname }} {{ order.client.lastname }}
              order from : {{ order.deliverer.id }} {{ order.client.lastname }}
            </p>
          </div>
        </div>
        <div v-if="order.deliverer?.id" class="flex-none mt-auto overflow-hidden p-3">
          <div class="text-center">
            <p class="text-xl px-6">
              Delivered from : {{ order.deliverer.firstname }} {{ order.deliverer.lastname }}
            </p>
          </div>
        </div>
        <div>
          <div v-for="meal in order.meals" class="flex-none mt-auto overflow-hidden p-3">
            <div class="text-center">
              <p class="text-xl px-6">
                {{ meal.name }}
              </p>
              <p class="text-xl px-6">
                {{ meal.price }}
              </p>
            </div>
          </div>
        </div>
        <div class="flex-none mt-auto overflow-hidden p-3">
          <div class="text-center">
            <p class="text-xl px-6">
              Meal from : {{ order.restaurantUser.firstname }} {{ order.restaurantUser.lastname }}
            </p>
          </div>
        </div>
        <div v-if="order.totalPrice" class="flex-none mt-auto overflow-hidden p-3">
          <div class="text-center">
            <p class="text-xl px-6 text-bold-400">
              Total price : {{ order.totalPrice }}
            </p>
          </div>
        </div>
        <div v-if="order.status?.status === 'pending' && user.roles.includes('ROLE_DELIVERER')" class="flex-none mt-auto overflow-hidden p-3">
          <div class="text-center">
            <button @click="setDeliverer(order.id)" class="bg-white p-3 rounded">Deliver this command</button>
          </div>
        </div>
        <div v-if="order.status === 'accepted' && user.roles.includes('ROLE_DELIVERER')" class="flex-none mt-auto overflow-hidden p-3">
          <div class="text-center">
            <button @click="confirmDelivery(order.id)" class="bg-white p-3 rounded">Confirm delivery</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>