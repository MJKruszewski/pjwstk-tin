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
      <div class="col-12 container-background rounded-full">
        <h2 style="padding: 0;margin-top: 0">
          {{ $t('mainPage.tasks_log') }}
        </h2>

        <button class="info">{{ $t('mainPage.reload') }}</button>
        <br/>
        <br/>

        <table>
          <thead>
          <tr>
            <th>
              {{ $t('mainPage.task_code') }}
            </th>
            <th>
              {{ $t('mainPage.task_status') }}
            </th>
            <th>
              {{ $t('mainPage.task_priority') }}
            </th>
            <th>
              {{ $t('mainPage.task_reporter') }}
            </th>
            <th>
              {{ $t('mainPage.task_actions') }}
            </th>
          </tr>
          </thead>
          <tbody>
          <template v-for="item in tasks">
            <tr>
              <td>
                {{ $t("mainPage." + item.code) }}
              </td>
              <td>
                {{ item.status }}
              </td>
              <td>
                {{ item.priority }}
              </td>
              <td>
                {{ item.reporter.name }} {{ item.reporter.lastName }}
              </td>
              <td>
                <button class="info">Process</button>
                <button>Cancel</button>
              </td>
            </tr>
          </template>
          </tbody>
        </table>
      </div>
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

    export default {
        name: "MainPage",
        components: {StatsChart, RefreshIcon},
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
