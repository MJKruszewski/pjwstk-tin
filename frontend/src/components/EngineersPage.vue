<template>
  <div class="col-12 container-background rounded-full" style="padding-top: 0">
    <div class="col-12">
      <h2>{{ $t('taskPage.title') }}</h2>
    </div>
    <div class="col-12 container-background rounded-full" style="margin-bottom: 10px">
      <label for="ship">{{ $t('crewPage.ship')}}: <p style="display: inline" v-if="!showButtons()">{{ this.$store.state.user.ship.name }}</p></label>
      <select v-if="showButtons()" id="ship" v-model="ship">
        <template v-for="item in ships">
          <option :value="item">
            {{ item.name }}
          </option>
        </template>
      </select>

    </div>
    <div class="col-12 container-background rounded-full">
      {{ $t('hull') }}:
      <div class="progressbar">
        <div :style="{ width: ship.hull + '%' }"></div>
      </div>

      <br/>
      <br/>

      {{ $t('engines') }}:
      <div class="progressbar">
        <div :style="{ width: ship.engines + '%' }"></div>
      </div>

    </div>


    </div>
</template>

<script>
    import {getAllShips} from "../api/ships";
    import {isCaptain} from "../api/crewmates";

    export default {
        name: "EngineersPage",
        data() {
            return {
                ships: [],
                ship: {},
                lockButtons: false,
                currentShip: 0,
            };
        },
        mounted() {
            this.currentShip = this.$store.state.user.ship.id;
            this.ship = this.$store.state.user.ship;

            getAllShips().then((res) => {
                this.ships = res.data.data;
                this.currentShip = this.$store.state.user.ship.id;
            });
        },
        methods: {
            showButtons() {
                return isCaptain();
            },
        }
    }
</script>

<style scoped>
  .progressbar {
    background-color: grey;
    border-radius: 13px;
    padding: 1px;
  }

  .progressbar>div {
    background-color: limegreen;
    width: 0;
    height: 20px;
    border-radius: 10px;
  }
</style>
