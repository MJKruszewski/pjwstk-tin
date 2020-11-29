<template>
  <div class="nav" style="display: table">
      <div style="text-align: left;padding-top: 5px;padding-left: 10px;display: table-cell">
        <select class="small" v-model="$i18n.locale" id="language">
          <option value="pl">PL</option>
          <option value="en">ENG</option>
        </select>
      </div>

      <div style="text-align: right;padding-top: 5px;padding-right: 10px;display: table-cell">
        <button class="warning" v-on:click="logout">
          {{ $t("navBar.logout") }}
        </button>
      </div>
  </div>
</template>

<script>
    export default {
        name: "NavBar",
        methods: {
            logout() {

                this.$store.commit('setCrewmate', {
                    id: null,
                    name: "Guest",
                    lastName: "Guest",
                    role: 'captain',
                    ship: {
                        id:	null,
                        name:	null,
                        hull:	null,
                        engines:	null,
                    },
                    stats: {
                        strength: 1,
                        dexterity: 3,
                        intelligence: 6,
                        experience: 9,
                        condition: 2,
                    }
                });

                this.$router.push({name: 'LoginPage'})
            }
        }
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
