<script setup>
import {ref} from 'vue'

const props = defineProps(
    {
      restaurantEdit: {
        type: Object
      },
      label: {
        type: String
      },
      buttonLabel: {
        type: String
      }
    }
)

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

let tags = ref([])

let restaurant = ref({
  name: '',
  picturePath: '',
  address:'',
  openingTime: '',
  closingTime: '',
  tags: []
});

let openHour = ref('');
let closeHour = ref('');

if (props.restaurantEdit) {
  restaurant = props.restaurantEdit
}


if (restaurant.name) {
  openHour = props.restaurantEdit.openingTime[0]
  closeHour = props.restaurantEdit.openingTime[1]

  restaurant.openingTime = openHour
  restaurant.closingTime = closeHour

}

const loadFile = (event) => {
  restaurant.picturePath = event.target.files[0]
}

</script>

<template >
  <form @submit.prevent="$emit('submitRestaurant',restaurant)">
    <div class="border-b-2 block text-center">
      <div class="w-full p-4 sm:p-6 lg:p-8 bg-white shadow-md">
        <div class="flex justify-between">
          <span class="text-xl font-semibold block">{{ props.label }}</span>
        </div>
      </div>
      <div class="w-full flex flex-column p-8 bg-white lg:ml-5 mt-1">
        <div class=" md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6">
          <div class="pb-4">
            <label for="about" class="font-semibold text-gray-700 block pb-1">Name</label>
            <input required id="email" name="email" class="border rounded-r px-4 py-2 w-full" v-model="restaurant.name"/>
          </div>
        </div>
        <div class="md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6">
          <div class="pb-4">
            <label for="about" class="font-semibold text-gray-700 block pb-1">Picture</label>
            <input id="firstname" type="file" name="firstname" class="border rounded-r px-4 py-2 w-full"
                   @change={loadFile} />
          </div>
        </div>
        <div class="md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6">
          <div class="pb-4">
            <label for="about" class="font-semibold text-gray-700 block pb-1">Address</label>
            <input required id="lastname" name="lastname" class="border rounded-r px-4 py-2 w-full" v-model="restaurant.address"/>
          </div>
        </div>
        <div class="md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6">
          <div class="pb-4">
            <label for="about" class="font-semibold text-gray-700 block pb-1">Opening time</label>
            <input required type="time" id="address" name="address" class=" border rounded-r px-4 py-2 w-full"
                   v-model="restaurant.openingTime"/>
          </div>
        </div>
        <div class="md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6">
          <div class="pb-4">
            <label for="about" class="font-semibold text-gray-700 block pb-1">Closing time</label>
            <input required type="time" id="address" name="address" class=" border rounded-r px-4 py-2 w-full"
                   v-model="restaurant.closingTime"/>
          </div>
        </div>
        <div v-if="restaurant.tags.length > 1" class="md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6">
          <div class="pb-4">
            <p>Actual tags : </p>
            <span v-for="tag in restaurant.tags" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-blue-800">
              {{ tag.name }}
            </span>
          </div>
        </div>
        <div class="md:w-1/3 p-6 flex flex-col relative rounded  shadow p-6">
          <div class="pb-4">
            <select multiple aria-expanded="true" v-model="restaurant.tags">
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
        {{ props.buttonLabel }}
      </button>
    </div>
  </form>
</template>