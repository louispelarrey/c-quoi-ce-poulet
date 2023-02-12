<script setup>
import {ref} from 'vue'

const props = defineProps(
    {
      mealEdit: {
        type: Object
      },
      buttonLabel: {
        type: String
      },
      label: {
        type: String
      },
      restaurantId: {
        type: String
      }
    }
)

let meal = ref({
  name: '',
  description: '',
  price:'',
  picturePath: '',
  isAvailable: true,
  restaurant: props.restaurantId
});

if (props.mealEdit) {
  meal = props.mealEdit
}

const loadFile = (event) => {
  meal.picturePath = event.target.files[0]
}

</script>

<template >
  <form @submit.prevent="$emit('submitMeal',meal)">
    <div class="border-b-2 block text-center">
      <div class="w-full p-4 sm:p-6 lg:p-8 bg-white shadow-md">
        <div class="flex justify-between">
          <span class="text-xl font-semibold block">{{ props.label }}</span>
        </div>
      </div>
      <div class="w-full bg-white grid grid-cols-2">
        <div class="flex flex-row relative rounded shadow p-6">
          <div class="pb-4">
            <label for="about" class="font-semibold text-gray-700 block pb-1">Name</label>
            <input required id="name" name="name" class="border rounded-r px-4 py-2 w-full" v-model="meal.name"/>
          </div>
        </div>
        <div class="p-6 flex flex-row relative rounded  shadow p-6">
          <div class="pb-4">
            <label for="about" class="font-semibold text-gray-700 block pb-1">Description</label>
            <input id="description" name="description" class="border rounded-r px-4 py-2 w-full" v-model="meal.description" />
          </div>
        </div>
        <div class="p-6 flex flex-row relative rounded  shadow p-6">
          <div class="pb-4">
            <label for="about" class="font-semibold text-gray-700 block pb-1">Picture</label>
            <input id="firstname" type="file" name="firstname" class="border rounded-r px-4 py-2 w-full"
                   @change={loadFile} />
          </div>
        </div>
        <div class="p-6 flex flex-row relative rounded  shadow p-6">
          <div class="pb-4">
            <label for="about" type="number" class="font-semibold text-gray-700 block pb-1">Price</label>
            <input id="price" name="price" class="border rounded-r px-4 py-2 w-full"
                   v-model="meal.price" />
          </div>
        </div>
        <div class="p-6 flex flex-row relative rounded  shadow p-6">
          <div class="pb-4">
            <label class="relative inline-flex items-center cursor-pointer">
              <input type="checkbox" v-model="meal.isAvailable" class="sr-only peer">
              <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600"></div>
              <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Available</span>
            </label>
          </div>
        </div>
      </div>
      <button
          v-if="props.buttonLabel"
          type="submit"
          class="
        my-5
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