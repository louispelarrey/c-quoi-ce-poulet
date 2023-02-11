<script setup>
import {onMounted, ref} from 'vue'
import RestaurantForm from "../Restaurant/RestaurantForm.vue";
// check if the user is the admin via his token
// if not, redirect to home page

const token = localStorage.getItem('token')

const restaurants = ref([])

const restaurantToEdit = ref({})

const actualTags = ref([])

const modalOpen = ref(false)

const getRestaurants = () => {
  fetch(import.meta.env.VITE_API_URL+"restaurants", {
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
          fetch(import.meta.env.VITE_API_URL+"tag_restaurants?restaurant.id="+restaurantToEdit.id, {
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
                  actualTags.value = data

                }
              });
          restaurants.value = data

        }
      });

}

const deleteRestaurants = (id) => {
  fetch(import.meta.env.VITE_API_URL+"restaurants/"+id, {
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

const editRestaurant = (restaurantToEdit) => {
  restaurantToEdit.openingTime = [restaurantToEdit.openingTime, restaurantToEdit.closingTime]
  restaurantToEdit.owner = '/api/users/' + restaurantToEdit.owner.id
  fetch(import.meta.env.VITE_API_URL+"restaurants/"+restaurantToEdit.id, {
    method: "PUT",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      'Authorization': `Bearer ${token}`,
    },
    body: JSON.stringify(restaurantToEdit)
  })
      .then((res) => res.json())
      .then((data) => {
        restaurantToEdit.tags.map(tag => {
          // if the tag already exist in the base we don't need to create it
          console.log(restaurantToEdit.tags)
          if (restaurantToEdit.tags.value.contains(tag)) {
            return;
          }
          console.log(restaurantToEdit.tags)
          if (tag.id) {
            tag = tag.id
          }
          fetch(import.meta.env.VITE_API_URL+"tag_restaurants", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
              "Accept": "application/json",
              'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify({
              "restaurant": "/api/restaurants/"+restaurantToEdit.id,
              "tag": "/api/tags/"+tag
            })
          })
        })
        if (data.detail) {
          alert(data.violations[0].message);
        } else {
          alert('Your restaurant datas has been updated')
          restaurantToEdit.value = {}
          modalOpen.value = false
        }
      });
}

onMounted(() => {
  getRestaurants();
});

const showPopupEditRestaurant = (restaurantFromList) => {
  modalOpen.value = true;
  restaurantToEdit.value = restaurantFromList
}

const closePopup = () => {
  modalOpen.value = false;
}
</script>

<template>
  <div @click="closePopup" id="overlay" class="overlay" v-if="modalOpen">
  </div>
  <div id="modal-edit-user" class="popup" v-if="modalOpen">
    <button @click="closePopup">close</button>
    <RestaurantForm
        :restaurantEdit="restaurantToEdit"
        label="Edit restaurant"
        @submit-restaurant="editRestaurant(restaurantToEdit)"
        buttonLabel="Edit"
    />
  </div>
  <div class="flex flex-col">
    <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
      <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
        <div class="overflow-hidden">
          <table class="min-w-full">
            <thead class="border-b">
            <tr>
              <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                Name
              </th>
              <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                Owner
              </th>
              <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                State
              </th>
              <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                Tags
              </th>
              <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                Opening Date
              </th>
              <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                address
              </th>
              <th scope="col" class="text-sm font-medium text-gray-900 px-6 py-4 text-left">
                Actions
              </th>
            </tr>
            </thead>
            <tbody>
            <tr class="border-b" v-for="restaurant in restaurants">
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                {{ restaurant.name }}
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                {{ restaurant.owner.firstname }} {{ restaurant.owner.lastname }}
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                {{ restaurant.isActivated }}
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                <div>
                  <span v-for="tag in restaurant.tags" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-blue-800">
                    {{ tag.name }}
                  </span>
                </div>
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                <p>Ouverture : {{ restaurant.openingTime[0] }}</p>
                <p>Fermeture : {{ restaurant.openingTime[1] }}</p>
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                {{ restaurant.address }}
              </td>
              <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                <button @click="showPopupEditRestaurant(restaurant)" class="mx-auto lg:mx-0 hover:underline gradient text-black font-bold rounded-full my-6 py-4 px-8 shadow-lg focus:outline-none focus:shadow-outline transform transition hover:scale-105 duration-300 ease-in-out">
                  Edit</button>
                <button @click="deleteRestaurants(restaurant.id)"
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
  width: 80vw;
  background-color: white;
  border-radius: 10px;
  padding: 20px;
}
</style>
