<template>
  <div class="col-12">
    <BackgroundImage/>

    <div style="margin-top: 150px"  class="col-12">
      <div class="col-4"></div>
      <div class="col-4 container-background rounded-full">
        <form>
          <label for="login">Login:</label>
          <input type="text" id="login">

          <label for="password">Password:</label>
          <input type="password" id="password">

        </form>
        <button>Register</button>
      </div>
    </div>
  </div>
</template>

<script>
    import BackgroundImage from "./../utils/BackgroundImage";
    export default {
        name: "RegisterPage",
        components: {BackgroundImage}
    }
</script>

<style scoped>

</style>
