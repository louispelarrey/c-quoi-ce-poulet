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
        data.filter((order) => {
          if (order.status === "opened") {
            data = order
          }
        })
        orders.value.push(data)

        data.meals.map((meal) => {
          if (meals.value.find((m) => m.id === meal.id)) {
            meals.value.find((m) => m.id === meal.id).quantity += 1
          } else {
            meal.quantity = 1
            meals.value.push(meal)
          }
        })
      }
    });

const sendCommand = (orderId) => {
  fetch(import.meta.env.VITE_API_URL + "orders", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      'Authorization': `Bearer ${token}`,
    }, body: JSON.stringify({
      "email": user.value.email,
      "orderId": orderId,
      "successUrl": "http://example.com/",
      "cancelUrl": "http://example.com/"
    })
  }).then((res) => res.json())
      .then((data) => {
        if (data.error) {
          alert(data.error);
        } else {

        }
      });
}

const removeProduct = (mealId) => {
  const mealOrdersToDelete = orders.value[0].mealOrders.filter((mealOrder) => mealOrder.meal.id === mealId)
  fetch(import.meta.env.VITE_API_URL + "meal_orders/"+mealOrdersToDelete[0].id, {
    method: "DELETE",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      'Authorization': `Bearer ${token}`,
    },
  })
      .then((data) => {
        if (data.error) {
          alert(data.error);
        } else {
          if (meals.value.find((m) => m.id === mealId).quantity > 1) {
            meals.value.find((m) => m.id === mealId).quantity -= 1
          } else {
            meals.value = meals.value.filter((m) => m.id !== mealId)
          }
          orders.value[0].mealOrders = orders.value[0].mealOrders.filter((mealOrder) => mealOrder !== mealOrdersToDelete[0])
        }
      });
}

</script>

<template>
  <section >
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
              <div class="text-center">
                <p class="text-sm px-6">
                  Prepared by : {{ order.restaurantUser.firstname }} {{ order.restaurantUser.lastname }}
                </p>
              </div>
          </div>
          <div v-if="order.deliverer?.id" class="flex-none mt-auto overflow-hidden p-3">
            <div class="text-center">
              <p class="text-sm px-6">
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
                  <button @click="removeProduct(meal.id)"
                          class="mx-5 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded ">
                    Remove product
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div v-if="order.totalPrice" class="flex-none mt-auto overflow-hidden p-3">
            <div class="text-center justify-center items-center flex flex-row">
              <p class="text-sm px-6 text-bold-400">
                Total price : {{ order.totalPrice }}
              </p>
              <button @click="sendCommand(order.id)"
                      class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ">
                Proceed to payment
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
