<script setup>
import {ref} from 'vue'
import {useRoute, useRouter} from "vue-router";

const router = useRouter();
const route = useRoute();

const token = localStorage.getItem('token')

const orders = ref([])
const user = ref({})
const meals = ref([])

user.value = JSON.parse(atob(token.split('.')[1]))

fetch(import.meta.env.VITE_API_URL + "orders", {
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
          if (order.status !== 'accepted' && order.status !== 'opened') {
            order.status = JSON.parse(order.status)
          }
          orders.value.push(order)

          // add all order meals in an array and if it already exist add quantity
          order.meals.map((meal) => {
            if (meals.value.find((m) => m.id === meal.id)) {
              meals.value.find((m) => m.id === meal.id).quantity += 1
            } else {
              meal.quantity = 1
              meals.value.push(meal)
            }
          })
        })
      }
    });

const setDeliverer = (order) => {
  fetch(import.meta.env.VITE_API_URL + "orders/" + order + "/accepted", {
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
        Your Orders
      </h1>
      <div v-for="order in orders" class="w-full flex flex-col relative">
        <div class="rounded bg-white border  py-8 m-6 flex flex-col relative">
          <div class="flex-none mt-auto overflow-hidden p-3">
            <div class="text-center">
              <p class="text-md">
                Status : {{ order.status }}
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
            <div class="w-full bg-white grid grid-cols-2">
              <div v-for="meal in meals" class="p-2 w-full">
                <div>
                  <p class="text-md">
                    {{ meal.name }} : {{ meal.price }}€
                  </p>
                  <span class="text-sm">
                      Quantity :  {{ meal.quantity }}
                  </span>
                </div>
              </div>
            </div>
          </div>
          <div class="flex-none mt-auto overflow-hidden p-3">
            <div class="text-center">
              <p class="text-xl px-6">
                Prepared by : {{ order.restaurantUser.firstname }} {{ order.restaurantUser.lastname }}
              </p>
            </div>
          </div>

        </div>
        <div v-if="order.totalPrice" class="flex-none mt-auto overflow-hidden p-3">
          <div class="text-center">
            <p class="text-xl px-6 text-bold-400">
              Total price : {{ order.totalPrice }}
            </p>
          </div>
        </div>
        <button @click="sendCommand(order.id)"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ">
          Send order
        </button>
      </div>
      <div>
      </div>
    </div>
  </section>
</template>
