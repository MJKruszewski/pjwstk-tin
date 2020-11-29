<template>
  <div id="app">
    <transition name="fade">
      <router-view/>
    </transition>
  </div>
</template>

<script>
    export default {
        name: 'App'
    }
</script>

<style>
  @import url('https://fonts.googleapis.com/css2?family=Play:wght@400;700&display=swap');

  * {
    box-sizing: border-box;
    font-family: 'Play', sans-serif;
  }

  input[type=text],
  input[type=password],
  input[type=date],
  input[type=datetime-local],
  input[type=number] {
    font-size: 14px;
    height: 44px;
    padding: 0 42px 0 14px;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 3px;
    font-weight: 400;
    line-height: normal;
    margin: 10px 0;
    appearance: none;
    transition: border-color .2s,box-shadow .2s;
    min-width: 240px;
  }


  select {
    font-size: 14px;
    height: 44px;
    padding: 0 42px 0 14px;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 3px;
    font-weight: 400;
    line-height: normal;
    margin: 10px 0;
    transition: border-color .2s, box-shadow .2s;
    min-width: 240px;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    cursor: pointer;
  }

  select:hover,
  select:focus {
    border-color: #a6dbf3;
    box-shadow: 0 0 4px rgba(166, 219, 243, .87);
    outline: none;
  }

  select.small {
    height: 22px;
    min-width: 10px;
  }

  input[type=text]:focus,
  input[type=password]:focus,
  input[type=date]:focus,
  input[type=datetime-local]:focus,
  input[type=number]:focus {
    border-color: #a6dbf3;
    box-shadow: 0 0 4px rgba(166, 219, 243, .87);
    outline: none;
  }

  button {
    vertical-align: middle;
    height: 30px;
    background-color: rgba(93, 93, 93, 0.79);
    min-width: 90px;
    text-align: center;
    border-radius: 5px;
    text-decoration: none;
    border: none;
    cursor: pointer;
    color: #fff;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
  }
  button:hover {
    color: #fff;
    background-color: #5a6268;
    border-color: #545b62;
  }
  button.disabled-button {
    color: #fff;
    background-color: #949299;
  }
  button:focus {
    box-shadow: 0 0 0 .2rem rgba(108,117,125,.5);
  }

  button.success {
    color: #fff;
    background-color: #28a745;
    border-color: #28a745;
  }
  button.success.disabled-button {
    color: #fff;
    background-color: #218838;
    border-color: #1e7e34;
    cursor: no-drop;
  }
  button.success:hover {
    color: #fff;
    background-color: #218838;
    border-color: #1e7e34;
  }
  button.success:focus {
    box-shadow: 0 0 0 .2rem rgba(40, 167, 69, .5);
  }

  button.info {
    color: #fff;
    background-color: #17a2b8;
    border-color: #17a2b8;
  }
  button.info:hover {
    color: #fff;
    background-color: #138496;
    border-color: #117a8b;
  }
  button.info.disabled-button {
    color: #fff;
    background-color: #138496;
    border-color: #117a8b;
    cursor: no-drop;
  }
  button.info:focus {
    box-shadow: 0 0 0 .2rem rgba(23, 162, 184, .5);
  }

  button.warning {
    color: #212529;
    background-color: #ffc107;
    border-color: #ffc107;
  }
  button.warning:hover {
    color: #212529;
    background-color: #e0a800;
    border-color: #d39e00;
  }
  button.warning.disabled-button {
    color: #212529;
    background-color: #e0a800;
    border-color: #d39e00;
    cursor: no-drop;
  }
  button.warning:focus {
    box-shadow: 0 0 0 .2rem rgba(255,193,7,.5);
  }

  button.danger {
    color: #fff;
    background-color: #dc3545;
    border-color: #dc3545;
  }
  button.danger:hover {
    color: #fff;
    background-color: #c82333;
    border-color: #bd2130;
  }
  button.danger.disabled-button {
    color: #fff;
    background-color: #c82333;
    border-color: #bd2130;
    cursor: no-drop;
  }
  button.danger:focus {
    box-shadow: 0 0 0 .2rem rgba(220,53,69,.5);
  }

  .fade-enter-active {
    transition: opacity .8s;
  }
  .fade-leave-active {
    transition: opacity 0s;
  }
  .fade-enter, .fade-leave-to {
    opacity: 0;
  }

  .container-background {
    background-color: #c1c9ccc9;
  }

  .rounded-full {
    border-radius: 5px;
  }

  [class*="col-"] {
    float: left;
    padding: 15px;
    /*border: 1px solid red;*/
  }

  .col-1 {
    width: 8.33%;
  }

  .col-2 {
    width: 16.66%;
  }

  .col-3 {
    width: 25%;
  }

  .col-4 {
    width: 33.33%;
  }

  .col-5 {
    width: 41.66%;
  }

  .col-6 {
    width: 50%;
  }

  .col-7 {
    width: 58.33%;
  }

  .col-8 {
    width: 66.66%;
  }

  .col-9 {
    width: 75%;
  }

  .col-10 {
    width: 83.33%;
  }

  .col-11 {
    width: 91.66%;
  }

  .col-12 {
    width: 100%;
  }
</style>
