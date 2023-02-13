<script>
import { ref } from "vue";
import { useRouter } from "vue-router";

const password = ref("");
const passwordConfirm = ref("");
const router = useRouter();

const submit = () => {
    if (password.value !== passwordConfirm.value) {
        alert("Passwords do not match!");
        return;
    }

    fetch(import.meta.env.VITE_API_URL + "reset-password/", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: JSON.stringify({
            password: password.value,
            reset_token: this.$route.params.reset_token,
        }),
    })
        .then((res) => res.json())
        .then((data) => {
            if (data.error) {
                alert(data.message);
            } else {
                router.push("/login");
            }
        });
};

</script>
<template>
  <div class="p-6 rounded-lg shadow-lg bg-white max-w-md">
    <h2 class="text-lg font-medium mb-6 text-center">Reset Password</h2>
    <form @submit.prevent="submit">
      <div class="form-group mb-6">
        <label for="password" class="form-label inline-block mb-2 text-gray-700">New Password</label>
        <input
          type="password"
          name="password"
          v-model="password"
          required
          class="form-control
            block
            w-full
            px-3
            py-1.5
            text-base
            font-normal
            text-gray-700
            bg-white
            bg-clip-padding
            border
            border-solid
            border-gray-300
            rounded
            transition
            ease-in-out
            m-0
            focus:text-gray-700
            focus:bg-white
            focus:border-blue-600
            focus:outline-none"
          id="password"
          placeholder="Enter your new password"
        />
      </div>
      <div class="form-group mb-6">
        <label for="passwordConfirm" class="form-label inline-block mb-2 text-gray-700">Confirm Password</label>
        <input
          type="password"
          name="passwordConfirm"
          v-model="passwordConfirm"
          required
          class="form-control
            block
            w-full
            px-3
            py-1.5
            text-base
            font-normal
            text-gray-700
            bg-white
            bg-clip-padding
            border
            border-solid
            border-gray-300
            rounded
            transition
            ease-in-out
            m-0
            focus:text-gray-700
            focus:bg-white
            focus:border-blue-600
            focus:outline-none"
            id="passwordConfirm"
            placeholder="Confirm your new password"
        />
      </div>
        <button
            type="submit"
            class="
            w-full
            px-6
            py-2.5
            bg-blue-600
            text-white
            rounded
            font-medium
            text-sm
            transition
            ease-in-out
            duration-150
            hover:bg-blue-700
            focus:outline-none
            focus:shadow-outline
            focus:border-blue-700
            active:bg-blue-700
            disabled:opacity-50
            disabled:cursor-not-allowed
            "
        >
            Reset Password
        </button>
    </form>
    </div>
</template>
