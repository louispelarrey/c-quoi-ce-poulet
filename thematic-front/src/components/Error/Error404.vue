<template>
    <div class="error">
      <h2>Cluck, cluck, 404!</h2>
      <p>It looks like the chicken you were looking for has flown the coop. But don't worry, we've got plenty of other delicious options on the menu!</p>
      <router-link to="/" class="error-link">Order some food now!</router-link>
    </div>
  </template>

<style>
  .error {
    text-align: center;
    padding: 50px;
  }
  h2 {
    font-size: 48px;
    margin-bottom: 20px;
  }
  p {
    font-size: 20px;
    margin-bottom: 20px;
  }
  .error-link {
    font-size: 20px;
    color: #ff7f50;
    text-decoration: underline;
  }
</style>
