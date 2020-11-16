<template>
    <div class="col-12">
      <BackgroundImage/>

      <div style="margin-top: 150px"  class="col-12">
        <div class="col-4"></div>
        <div class="col-4 container-background rounded-full" style="align-content: center">
          <form>
            <div class="col-12" style="text-align: center">
              <label for="login" style="font-weight: bold;">Login:</label>
              <br/>
              <input type="text" id="login" placeholder="username">
              <br/>
              <label for="password" style="font-weight: bold">Password:</label>
              <br/>
              <input type="password" id="password" placeholder="password">
            </div>

          </form>

          <div class="col-12" style="text-align: right">
            <button>Register</button>
            <button class="success" v-on:click="login">Login</button>
          </div>
        </div>
        <div class="col-4"></div>
      </div>
    </div>
</template>

<script>
    import BackgroundImage from "./../utils/BackgroundImage";
    export default {
        name: "LoginPage",
        components: {BackgroundImage},
        methods: {
            login() {
                this.$router.push({name: 'summary'})
            }
        }
    }
</script>

<style scoped>

</style>
