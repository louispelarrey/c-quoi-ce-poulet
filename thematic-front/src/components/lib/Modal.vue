<script>

</script>
<template>
  <slot name="activator" :openModal="openModal">
    <button @click="open = true">Open Modal</button>
  </slot>
  <template v-if="open">
    <div @click="open = false" id="overlay" class="overlay" v-if="!!userToEdit">
    </div>
    <div id="modal-edit-user" class="popup" v-if="!!userToEdit">
      <slot name="modalContent">

      </slot>
      <EditUser :userEdit="userToEdit"/>
    </div>
  </template>
</template>

<script setup>
import { ref } from 'vue'

const open = ref(false)

const openModal = () => {
  open.value = true
}

const closeModal = () => {
  open.value = false
}

</script>

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