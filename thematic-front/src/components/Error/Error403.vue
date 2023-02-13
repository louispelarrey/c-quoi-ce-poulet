<template>
    <div class="error">
      <h2>Oh no, it's a 403!</h2>
      <p>Unfortunately, you don't have the necessary permissions to access this page. But don't worry, you can still enjoy our delicious chicken dishes!</p>
      <router-link to="/" class="error-link">Check out our menu!</router-link>
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