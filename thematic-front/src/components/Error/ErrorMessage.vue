<script setup>
import { computed } from 'vue';

const props = defineProps({
    code: {
        type: Number,
        required: true,
    },
});

const errorMessage = computed(() => {
    let error = "An unknown error has occurred";

    switch (props.code) {
        case 401:
            error = "Incorrect email or password";
            break;
        case 500:
            error = "Internal Server Error";
            break;
        default:
            break;
    }

    return error;
});

</script>

<template>
    <div class="error-message">{{ errorMessage }}</div>
</template>

<style scoped>
.error-message {
    color: red;
    font-weight: bold;
}

</style>