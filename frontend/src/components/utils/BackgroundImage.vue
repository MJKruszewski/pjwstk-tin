<template>
  <div class="bg-image"></div>
</template>

<script>
    export default {
        name: "BackgroundImage"
    }
</script>

<style scoped>
  .bg-image {
    position: fixed;
    background-image: url("../../assets/bg.jpg");
    height: 100%;
    width: 100%;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    background-attachment: fixed;
    top: 0;
    left: 0;
    z-index: -100;
  }
</style>
