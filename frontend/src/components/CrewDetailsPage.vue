<template>
  <div class="col-12 container-background rounded-full" style="padding-top: 0">
    <div class="col-12">
      <button class="warning" v-on:click="$router.push({ name: 'crewList' })">{{ $t('crewDetails.back') }}</button>
    </div>


    <div class="col-3 container-background rounded-full">
      <h2 style="padding: 0;margin-top: 0">{{ $t('mainPage.stats') }}</h2>
      <StatsChart :chart-data="datacollection" :options="options"/>
    </div>
    <div class="col-9" style="padding-top: 0;">
      <TaskLog :tasks="tasks"/>
    </div>

    <div class="col-12">
      <div class="col-3">
        <img style="max-width: 100%;display: block" src="../assets/ship-plan.png"/>
      </div>
    </div>



  </div>
</template>

<script>
    import StatsChart from "./utils/StatsChart";
    import crewmateTasks from "../mocks/crewmateTasks";
    import RefreshIcon from 'vue-material-design-icons/Refresh.vue';
    import TaskLog from "./TaskLog";

    export default {
        name: "CrewDetailsPage",
        components: {TaskLog, StatsChart, RefreshIcon},
        data() {
            return {
                datacollection: null,
                tasks: crewmateTasks,
                options: {
                    legend: {
                        display: false
                    },
                    scale: {
                        angleLines: {
                            display: false
                        },
                        ticks: {
                            callback: function () {
                                return ""
                            },
                            backdropColor: "rgba(0, 0, 0, 0)",
                            suggestedMin: 0,
                            suggestedMax: 10
                        }
                    }
                }
            }
        },
        mounted() {
            this.fillData()
        },
        methods: {
            reloadTasks() {
                console.log('reloading')
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
  table {
    border-collapse: collapse;
    width: 100%;
  }

  th, td {
    text-align: left;
    padding: 8px;
  }

  tr:nth-child(even) {
    background-color: #f2f2f2
  }

  th {
    background-color: #4a84af;
    color: white;
  }

  tr:hover {
    background-color: rgba(245, 245, 245, 0.21);
  }
</style>
