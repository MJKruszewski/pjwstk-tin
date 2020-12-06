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
              <input v-model="username" type="text" id="login" placeholder="username" :class="{ 'validation-error': $v.username.$error }">
              <br/>
              <label for="password" style="font-weight: bold">Password:</label>
              <br/>
              <input v-model="password" type="password" id="password" placeholder="password" :class="{ 'validation-error': $v.password.$error }">
            </div>

          </form>

          <div class="col-12" style="text-align: right">
            <button v-on:click="$router.push({name: 'registerPage'})">Register</button>
            <button class="success" v-on:click="login">Login</button>
          </div>
        </div>
        <div class="col-4"></div>
      </div>
    </div>
</template>

<script>
    import BackgroundImage from "./../utils/BackgroundImage";
    import {postLogin} from "../../api/login";
    import { required, minLength, between } from 'vuelidate/lib/validators'

    export default {
        name: "LoginPage",
        components: {BackgroundImage},
        data: () => {
            return {
                username: null,
                password: null,
            }
        },
        validations: {
            username: {
                required,
            },
            password: {
                required,
            },
        },
        methods: {
            login() {
                this.$v.$touch();

                if (this.$v.$invalid) {
                    return;
                }

                postLogin(this.username, this.password).then((res) => {
                    this.$store.dispatch('setCrewmate', res.data.data.crewmate)
                    this.$store.dispatch('setSession', {
                        token: res.data.data.token,
                        expireAt: res.data.data.expireAt
                    })

                    this.$router.push({name: 'summary'})
                });
            }
        }
    }
</script>

<style scoped>

</style>
