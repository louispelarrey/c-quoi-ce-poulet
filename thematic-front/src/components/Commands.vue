<script setup>
import {ref} from 'vue'
import {useRoute, useRouter} from "vue-router";
import ReportForm from "./User/ReportForm.vue";

const router = useRouter();
const route = useRoute();

const token = localStorage.getItem('token')
const actualUserId = JSON.parse(atob(token.split('.')[1])).user_id;

const orders = ref([])
const meals = ref([])
const user = ref('')
const userReport = ref({})
const cartOpen = ref(false);

const closePopup = () => {
  cartOpen.value = false;
}

user.value = JSON.parse(atob(token.split('.')[1]))

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
      if (order.status !== "delivered") {
        orders.value.push(order)
      }
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
    }
  });
}

const confirmPreparation = (order) => {
  fetch(import.meta.env.VITE_API_URL+"orders/"+order+"/prepared", {
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
    }
  });
}

const reportDeliverer = (userId) => {
  console.log(userId)
  cartOpen.value = true;
  userReport.value = userId;
}
</script>

<template>
  <div @click="closePopup" id="overlay" class="overlay" v-if="cartOpen">
  </div>
  <div id="modal-edit-user" class="popup" v-if="cartOpen">
    <button @click="closePopup">close</button>
    <ReportForm
      :userToReport="userReport"
    />
  </div>
  <section class="bg-white border-b py-8 w-full">
    <div class="container mx-auto flex flex-wrap pt-4 pb-12">
      <h1 class="w-full my-2 text-5xl text-center font-bold leading-tight text-center text-gray-800">
        Orders List
      </h1>
      <div v-for="order in orders" class="rounded bg-white md:w-1/3 m-6 flex flex-col relative"
           :style="order.status?.status !== 'pending' && actualUserId === order.deliverer?.id ? 'background-color:rgba(27, 159, 45, 0.4)' : 'background-color:rgba(226, 122, 38, 0.4)'"
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
        <div class="flex-none mt-auto overflow-hidden p-3" v-if="actualUserId !== order.client.id">
          <div class="text-center">
            <p class="text-xl px-6">
              order from : {{ order.client.firstname }} {{ order.client.lastname }}
            </p>
          </div>
        </div>
        <div v-if="order.deliverer?.id" class="flex-none mt-auto overflow-hidden p-3">
          <div class="text-center">
            <p class="text-xl px-6">
              Delivered from : {{ order.deliverer?.firstname }} {{ order.deliverer?.lastname }}
            </p>
            <button @click="reportDeliverer(order.deliverer.id)" class="bg-white p-3 rounded">
              Report Deliverer
            </button>
          </div>
        </div>
        <div>
          <div v-for="meal in meals" class="p-2 w-full">
            <div>
              <p class="text-md">
                {{ meal.name }} : {{ meal.price }} €
              </p>
              <span class="text-sm">
                      Quantity :  {{ meal.quantity }}
                  </span>
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
        <div v-if="order.status === 'prepared' && user.roles.includes('ROLE_DELIVERER')" class="flex-none mt-auto overflow-hidden p-3">
          <div class="text-center">
            <button @click="setDeliverer(order.id)" class="bg-white p-3 rounded">Deliver this command</button>
          </div>
        </div>
        <div v-if="order.status === 'accepted' && user.roles.includes('ROLE_DELIVERER')" class="flex-none mt-auto overflow-hidden p-3">
          <div class="text-center">
            <button @click="confirmDelivery(order.id)" class="bg-white p-3 rounded">Confirm delivery</button>
          </div>
        </div>
        <div v-if="order.status === 'paid' && user.roles.includes('ROLE_RESTAURANT')" class="flex-none mt-auto overflow-hidden p-3">
          <div class="text-center">
            <button @click="confirmPreparation(order.id)" class="bg-white p-3 rounded">Confirm preparation</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>

.overlay {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 99;
  overflow-y: hidden;
  background-color: rgba(0, 0, 0, 0.47);
  width: 100%;
  height: 100%;
}

.popup{
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 100;
  width: 80vw;
  height: 80vh;
  background-color: white;
  border-radius: 10px;
  padding: 20px;
}
</style>
