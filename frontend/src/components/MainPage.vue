<template>
  <div class="col-12 container-background rounded-full" style="padding-top: 0">
    <div class="col-12">
      <h2>{{ $t('welcome') }}, {{ this.$store.state.user.name + " " + this.$store.state.user.lastName }}</h2>
    </div>
    <div class="col-3 container-background rounded-full">
      <h2 style="padding: 0;margin-top: 0">{{ $t('mainPage.stats') }}</h2>
      <StatsChart :chart-data="datacollection" :options="options"/>
    </div>
    <div class="col-9" style="padding-top: 0;">
      <TaskLog :lock-reload="lockButtons" v-on:reload="reload()" :tasks="$store.state.user.tasks" :showActions="true"/>
    </div>
    <div class="col-3" style="padding-top: 0;"></div>
    <div class="col-9" style="padding-top: 0;">
      <Stations :lock-reload="lockButtons" v-on:reload="reload()" :stations="stations" :user-view="true" />
    </div>

    <div class="col-12">
      <div class="col-3">
        <img style="max-width: 100%;display: block" src="../assets/ship-plan.png" />

      </div>
    </div>



  </div>
</template>

<script>
    import StatsChart from "./utils/StatsChart";
    import chartOptions from "./utils/chartOptions";
    import Stations from "./stations/Stations";
    import TaskLog from "./tasks/TaskLog";
    import {getMe} from "../api/login";

    export default {
        name: "MainPage",
        components: {TaskLog, Stations, StatsChart},
        data() {
            return {
                lockButtons: false,
                datacollection: null,
                stations: [],
                options: chartOptions,
            }
        },
        mounted() {
            this.fillData()

            this.stations = this.$store.state.user.stations;
        },
        methods: {
            reload() {
                this.lockButtons = true;
                getMe().then((res) => {
                    this.$store.dispatch('setCrewmate', res.data.data)
                }).finally(() => {
                    this.lockButtons = false;
                });
            },
            fillData() {
                this.datacollection = {
                    labels: [this.$t('mainPage.strength'), this.$t('mainPage.dexterity'), this.$t('mainPage.intelligence'), this.$t('mainPage.experience'), this.$t('mainPage.condition')],
                    datasets: [
                        {
                            label: this.$t('statistics'),
                            backgroundColor: '#f87979',
                            data: [
                                this.$store.state.user.stats.strength || 0,
                                this.$store.state.user.stats.dexterity || 0,
                                this.$store.state.user.stats.intelligence || 0,
                                this.$store.state.user.stats.experience || 0,
                                this.$store.state.user.stats.condition || 0,
                            ]
                        },
                    ]
                }
            },
        }
    }
</script>

<style scoped>
</style>
