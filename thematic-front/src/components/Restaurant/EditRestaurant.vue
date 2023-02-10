<script setup>
import { ref } from 'vue'

const props = defineProps(
    {
      restaurantEdit: {
        type: Object
      }
    }
)

let restaurant = ref({});

if (props.restaurantEdit) {
  restaurant = props.restaurantEdit
}

const token = localStorage.getItem('token')
const actualUserId = JSON.parse(atob(token.split('.')[1])).user_id;

if(!props.userEdit || props.userEdit.id !== actualUserId) {
  fetch( import.meta.env.VITE_API_URL+'restaurants/' + restaurantEdit.id, {
    method: 'GET',
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
          restaurant.value = data
        }
      });
}

const submit = (user) => {
  fetch(import.meta.env.VITE_API_URL+"restaurants/"+restaurant.id, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      'Authorization': `Bearer ${token}`,
    },
    body: JSON.stringify(user)
  })
      .then((res) => res.json())
      .then((data) => {
        if (data.detail) {
          alert(data.violations[0].message);
        } else {
          alert('Your restaurant datas has been updated')
        }
      });
}


</script>
<template v-if="restaurant.value">
  <div class="border-b-2 block text-center">
    <div class="w-full p-4 sm:p-6 lg:p-8 bg-white shadow-md">
      <div class="flex justify-between">
        <span class="text-xl font-semibold block">Your Profile</span>
      </div>
      <span class="text-gray-600">Information on your account</span>
      <div class="w-full p-8 mx-2 flex justify-center">
        <img id="showImage" class="max-w-xs w-32 items-center border" src="https://images.unsplash.com/photo-1511367461989-f85a21fda167?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1631&q=80" alt="">
      </div>
    </div>
    <div class="w-full flex flex-column p-8 bg-white lg:ml-5 mt-1">
      <div class=" md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6">
        <div class="pb-4">
          <label for="about" class="font-semibold text-gray-700 block pb-1">Email</label>
          <input id="email" name="email" class="border rounded-r px-4 py-2 w-full" v-model="restaurant.email"/>
        </div>
      </div>
      <div class="md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6">
        <div class="pb-4">
          <label for="about" class="font-semibold text-gray-700 block pb-1">Firstname</label>
          <input id="firstname" name="firstname" class="border rounded-r px-4 py-2 w-full" v-model="user.firstname" />
        </div>
      </div>
      <div class="md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6">
        <div class="pb-4">
          <label for="about" class="font-semibold text-gray-700 block pb-1">Lastname</label>
          <input id="lastname" name="lastname" class="border rounded-r px-4 py-2 w-full" v-model="user.lastname" />
        </div>
      </div>
      <div class="md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6">
        <div class="pb-4">
          <label for="about" class="font-semibold text-gray-700 block pb-1">Phone number</label>
          <input id="numberPhone" name="numberPhone" class="border rounded-r px-4 py-2 w-full" v-model="user.numberPhone" />
        </div>
      </div>
      <div class="md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6">
        <div class="pb-4">
          <label for="about" class="font-semibold text-gray-700 block pb-1">Address</label>
          <input id="address" name="address" class=" border rounded-r px-4 py-2 w-full" v-model="user.address" />
        </div>
      </div>
      <div class="md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6" v-if="adminEdit">
        <div class="pb-4" >
          <label for="about" class="font-semibold text-gray-700 block pb-1">Roles</label>
          <select v-model="user.roles[0]" id="countries" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="ROLE_ADMIN">Admin</option>
            <option value="ROLE_USER">User</option>
            <option value="ROLE_USER">Restaurants</option>
            <option value="ROLE_USER">Deliverer</option>
          </select>
        </div>
      </div>
    </div>
    <div class="pt-4 cursor-pointer mb-2 mx-auto">
      <a @click="submit(user)" class="-mt-2 text-md font-bold text-white bg-gray-700 rounded-full px-5 py-2 hover:bg-gray-800">Edit</a>
    </div>
  </div>
</template>

<style scoped>
input{
  min-width: 150px;
}
</style>
