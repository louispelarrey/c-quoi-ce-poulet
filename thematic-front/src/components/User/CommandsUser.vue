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
  <section class="cart-container">
    <div>
      <h1>
        Your Orders
      </h1>
      <div v-for="order in orders" class="order-container">
        <div>
          <div>
            <div>
              <p class="status">
                Status : {{ order.status }}
              </p>
            </div>
              <div>
                <p class="prepared-by">
                  Prepared by : {{ order.restaurantUser.firstname }} {{ order.restaurantUser.lastname }}
                </p>
              </div>
          </div>
          <div v-if="order.deliverer?.id" class="">
            <div>
              <p class="delivered-from">
                Delivered from : {{ order.deliverer.firstname }} {{ order.deliverer.lastname }}
              </p>
            </div>
          </div>
          <div>
            <div>
              <div v-for="meal in meals" class="">
                <div class="meal-item">
                  <p class="meal-name">
                    {{ meal.name }}
                  </p>
                  <span class="meal-price">
                      {{ meal.price }}€
                  </span>
                  <span class="meal-quantity">
                      Quantity :  {{ meal.quantity }}
                  </span>
                  <button @click="removeProduct(meal.id)"
                          class="remove-product-button">
                    Remove product
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div v-if="order.totalPrice" class="">
            <div>
              <p class="total-price">
                Total price : {{ order.totalPrice }}€
              </p>
              <button @click="sendCommand(order.id)"
                      class="proceed-to-payment-button">
                Proceed to payment
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
  .cart-container {
    width: 80%;
    margin: 0 auto;
    text-align: center;
    padding: 20px;
  }
  .order-container {
    background-color: #f2f2f2;
    padding: 20px;
    margin-bottom: 20px;
  }
  .status {
    font-weight: bold;
    margin-bottom: 10px;
  }
  .prepared-by {
    font-style: italic;
    margin-bottom: 10px;
  }
  .delivered-from {
    font-style: italic;
    margin-bottom: 10px;
  }
  .meal-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
  }
  .meal-name {
    font-weight: bold;
  }
  .meal-price {
    font-weight: bold;
    margin-right: 10px;
  }
  .meal-quantity {
    margin-right: 10px;
  }
  .remove-product-button {
    background-color: #ff0000;
    color: #fff;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
  }
  .total-price {
    font-weight: bold;
    margin-top: 20px;
  }
  .proceed-to-payment-button {
    background-color: #0000ff;
    color: #fff;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 20px;
  }
</style>