<script setup>
import { ref } from 'vue'
import EditUser from "../User/editProfile.vue";
import Modal from "../lib/Modal.vue";
// check if the user is the admin via his token
// if not, redirect to home page

const token = localStorage.getItem('token')

const users = ref([])
const userToEdit = ref({})

const modalOpen = ref(false)

fetch(import.meta.env.VITE_API_URL+"users", {
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
      users.value = data
    }
  });

const deleteUser = (id) => {
  fetch(import.meta.env.VITE_API_URL+"users/"+id, {
    method: "DELETE",
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
      alert('User deleted successfully')
      location.reload()
    }
  });
}

const showPopupEditUser = (userfromList) => {
  userToEdit.value = userfromList
  console.log(userToEdit.value)
}

</script>

<template>
  <div class="flex flex-col">
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
        <div class="overflow-hidden">
          <Modal v-if="!!userToEdit">
            <template #modalContent="{ userToEdit }" :openModal="userToEdit">
              <EditUser :userEdit="userToEdit"/>
            </template>
          </Modal>
<!--          <div id="overlay" class="overlay" v-if="!!userToEdit">-->
<!--          </div>-->
<!--          <div id="modal-edit-user" class="popup" v-if="!!userToEdit">-->
<!--            <EditUser :userEdit="userToEdit"/>-->
<!--          </div>-->
          <table class="min-w-full">
            <thead class="border-b">
            <tr>
              <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                Username
              </th>
              <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                Actions
              </th>
            </tr>
            </thead>
            <tbody>
            <tr class="border-b" v-for="(userfromList,index) in users" :key="index">
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                {{ userfromList.email }}
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                <button @click="showPopupEditUser(userfromList)" class="mx-auto lg:mx-0 hover:underline gradient text-black font-bold rounded-full my-6 py-4 px-8 shadow-lg focus:outline-none focus:shadow-outline transform transition hover:scale-105 duration-300 ease-in-out">
                  Edit</button>
                <button @click="deleteUser(userfromList.id)"
                  class="mx-auto lg:mx-0 hover:underline gradient text-red font-bold rounded-full my-6 py-4 px-8 shadow-lg focus:outline-none focus:shadow-outline transform transition hover:scale-105 duration-300 ease-in-out">
                  Delete</button>
              </td>
            </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

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
  background-color: white;
  border-radius: 10px;
  padding: 20px;
}
</style>
