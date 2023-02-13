<script setup>
import {ref} from 'vue'

const token = localStorage.getItem('token')
const user = JSON.parse(atob(token.split('.')[1]))

const props = defineProps(
    {
      userToReport: {
        type: Number
      },
    }
)

console.log(user)

let reportReason = ref('')
console.log(props.userToReport)
const submitReport = () => {
  fetch(import.meta.env.VITE_API_URL+"reports", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
      "Accept": "application/json",
      "Authorization": `Bearer ${localStorage.getItem('token')}`
    },body: JSON.stringify({
      "reportBy": '/api/users/'+user.user_id,
      "reportedUser": '/api/users/'+props.userToReport,
      "reason": reportReason.value,
      "status": ["opened"]
    })
  })
      .then((res) => res.json())
      .then((data) => {
        if (data.error) {
          alert(data.error);
        } else {
          alert("Deliverer reported")
        }
      });
}

</script>

<template >
  <form @submit.prevent="submitReport">
    <div class="border-b-2 block text-center">
      <div class="w-full p-4 sm:p-6 lg:p-8 bg-white shadow-md">
          <span class="text-xl font-semibold block">Report a user</span>
      </div>
      <div class="w-full flex p-8 bg-white mt-1">
        <div class="p-6 flex flex-col relative rounded w-full  shadow p-6">
            <label for="about" class="font-semibold text-gray-700 block pb-1">Reason of report</label>
            <input required id="email" name="email" class="border rounded-r px-4 py-2 w-full" v-model="reportReason"/>
        </div>
      </div>
      <div class="mb-5">
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
        Report
      </button>
    </div>
    </div>
  </form>
</template>