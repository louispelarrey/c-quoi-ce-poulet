<script setup>
import { ref } from 'vue'
import Cart from "./Cart/Cart.vue";
import Reports from "./Admin/Reports.vue";
import Commands from "./Commands.vue";
import CommandsUser from "./User/CommandsUser.vue";

function logout() {
  localStorage.removeItem("token");
  window.location.href = "/";
}

let userRoles = ref([]);
let isNotUser = ref(false);
const cartOpen = ref(false);
const user = ref(null);


const token = localStorage.getItem("token");
if (token){
  const user = JSON.parse(atob(token.split('.')[1]))
  userRoles.value = Object.values(user.roles);
  isNotUser.value = !(userRoles.value.includes('ROLE_USER') && userRoles.value.length === 1);
}

const closePopup = () => {
  cartOpen.value = false;
}
</script>

<template>
  <div @click="closePopup" id="overlay" class="overlay" v-if="cartOpen">
  </div>
  <div id="modal-edit-user" class="popup" v-if="cartOpen">
    <button @click="closePopup">close</button>
    <CommandsUser />
  </div>
  <div v-if="cartOpen">
    <div class="relative">
    </div>
  </div>
  <div id="header">
    <div>
      <div class="logo">
        <router-link to="/">
          C Quoi Ce Poulet
        </router-link>
      </div>
      <div id="nav-content">
        <ul>
          <li>
            <a>
              <router-link to="/">Home</router-link>
            </a>
          </li>
          <li>
            <div v-if="token">
              <a @click="logout">Logout</a>
            </div>
            <div v-else>
              <router-link to="/login">Login</router-link>
            </div>
          </li>
          <li>
            <router-link to="/profile" >Profile</router-link>
          </li>
          <li v-if="isNotUser">
            <router-link to="/orders" >Orders</router-link>
          </li>
        </ul>
        <button>
          <router-link to="/restaurants/new">
            Inscrire un restaurant
          </router-link>
        </button>
        <button
            @click="cartOpen = true">
          Panier
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.logo {
  font-size: 2rem;
  font-weight: bold;
}

.overlay {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 20;
  background-color: rgba(0, 0, 0, 0.47);
  width: 100%;
  height: 100%;
}

.popup{
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  z-index: 20;
  width: 80vw;
  background-color: white;
  border-radius: 10px;
  padding: 20px;
}

#header {
  background-color: lightblue;
  padding: 20px;
  text-align: center;
}

#header div {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

#header div #nav-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

#header div #nav-content ul {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

#header div #nav-content ul li {
  margin: 0 10px;
}

#header div #nav-content ul li a {
  text-decoration: none;
  color: #000;
}

#header div #nav-content ul li a:hover {
  color: #f2f2f2;
}

#header div #nav-content button {
  background-color: #000;
  color: #fff;
  border: none;
  padding: 10px 20px;
  border-radius: 5px;
  cursor: pointer;
}

#header div #nav-content button:hover {
  background-color: #f2f2f2;
  color: #000;
}

#header div #nav-content button a {
  text-decoration: none;
  color: #fff;
}

</style>