<script setup>
import { ref } from 'vue'

let restaurant = ref({});

const name = ref('')
const address = ref('')
const openingTime = ref('')
const closingTime = ref('')
const picturePath = ref('')
const tags = ref([])
const tagsValues = ref([])

const token = localStorage.getItem('token')
const actualUserId = JSON.parse(atob(token.split('.')[1])).user_id;

fetch(import.meta.env.VITE_API_URL+"tags", {
  method: "GET",
  headers: {
    "Content-Type": "application/json",
    "Accept": "application/json",
    "Authorization": `Bearer ${localStorage.getItem('token')}`
  }
})
    .then((res) => res.json())
    .then((data) => {
      if (data.error) {
        alert(data.error);
      } else {
        const dataFormated = data.map((tag) => {
          return {
            name: tag.name,
            id: tag.id
          }
        })
        tags.value = dataFormated
      }
    });

const submit = () => {
  fetch(import.meta.env.VITE_API_URL+"restaurants", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      'Authorization': `Bearer ${token}`,
    },
    body: JSON.stringify({
      name: name.value,
      address: address.value,
      openingTime: [openingTime.value,closingTime.value],
      picturePath: picturePath.value,
      owner: 'api/users/'+actualUserId,
      tags: tagsValues.value
    }),
  })
      .then((res) => res.json())
      .then((data) => {
        if (data.detail) {
          alert(data.violations[0].message);
        } else {
          alert('The resquest to create a restaurant has been sent')
        }
      });
}

const loadFile = (event) => {
  const file = event.target.files[0];
  const reader = new FileReader();
  reader.readAsDataURL(file);
  reader.onload = () => {
    restaurant.picture = reader.result;
  };
};


</script>
<template v-if="restaurant.value">
  <form @submit.prevent="submit">
    <div class="border-b-2 block text-center">
      <div class="w-full p-4 sm:p-6 lg:p-8 bg-white shadow-md">
        <div class="flex justify-between">
          <span class="text-xl font-semibold block">Créer votre restaurant</span>
        </div>
      </div>
      <div class="w-full flex flex-column p-8 bg-white lg:ml-5 mt-1">
        <div class=" md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6">
          <div class="pb-4">
            <label for="about" class="font-semibold text-gray-700 block pb-1">Name</label>
            <input required id="email" name="email" class="border rounded-r px-4 py-2 w-full" v-model="name"/>
          </div>
        </div>
        <div class="md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6">
          <div class="pb-4">
            <label for="about" class="font-semibold text-gray-700 block pb-1">Picture</label>
            <input id="firstname" type="file" name="firstname" class="border rounded-r px-4 py-2 w-full" @change={loadFile} />
          </div>
        </div>
        <div class="md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6">
          <div class="pb-4">
            <label for="about" class="font-semibold text-gray-700 block pb-1">Address</label>
            <input required id="lastname" name="lastname" class="border rounded-r px-4 py-2 w-full" v-model="address" />
          </div>
        </div>
        <div class="md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6">
          <div class="pb-4">
            <label for="about" class="font-semibold text-gray-700 block pb-1">Opening time</label>
            <input required type="time" id="address" name="address" class=" border rounded-r px-4 py-2 w-full" v-model="openingTime" />
          </div>
        </div>
        <div class="md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6">
          <div class="pb-4">
            <label for="about" class="font-semibold text-gray-700 block pb-1">Closing time</label>
            <input required type="time" id="address" name="address" class=" border rounded-r px-4 py-2 w-full" v-model="closingTime" />
          </div>
        </div>
        <div class="md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6">
          <div class="pb-4">
            <select multiple aria-expanded="true" v-model="tagsValues">
              <template v-for="tag in tags">
                <option :value="tag.id">
                  {{ tag.name }}
                </option>
              </template>
            </select>
          </div>
        </div>
      </div>
      <button type="submit"
              class="

        px-6
        py-2.5
        bg-blue-600
        text-white
        font-medium
        text-xs
        leading-tight
        uppercase
        rounded
        shadow-md
        hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">
        Create demand
      </button>
    </div>
  </form>
</template>

<style scoped>
input{
  min-width: 150px;
}
</style>
