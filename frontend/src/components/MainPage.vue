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
      <TaskLog :tasks="tasks" :showActions="true"/>
    </div>
    <div class="col-3" style="padding-top: 0;"></div>
    <div class="col-9" style="padding-top: 0;">
      <Stations :stations="stations" :user-view="true" />
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
    import crewmateTasks from "../mocks/crewmateTasks";
    import chartOptions from "./utils/chartOptions";
    import Stations from "./stations/Stations";
    import TaskLog from "./tasks/TaskLog";

    export default {
        name: "MainPage",
        components: {TaskLog, Stations, StatsChart},
        data() {
            return {
                datacollection: null,
                tasks: crewmateTasks,
                stations: [],
                options: chartOptions,
            }
        },
        mounted() {
            this.fillData()

            this.stations = this.$store.state.user.stations;
        },
        methods: {
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
