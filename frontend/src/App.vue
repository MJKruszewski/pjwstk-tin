<template>
  <div id="app">
    <router-view/>
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
