<template>
  <div class="nav">

    <p style="text-align: right;margin-top: 5px;margin-right: 10px">
      <button class="warning">
        {{ $t("navBar.logout") }}
      </button>
    </p>
  </div>
</template>

<script>
    export default {
        name: "NavBar"
    }
</script>

<style scoped>
  .nav {
    width: calc(100% - 20px);
    margin-left: 10px;
    height: 40px;
    background-color: #2b3137;
    border-bottom-left-radius: 5px;
    border-bottom-right-radius: 5px;
    overflow: hidden;
    position: fixed;
    top: 0;
    left: 0;
  }

</style>
