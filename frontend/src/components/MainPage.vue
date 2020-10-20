<template>
  <div class="col-12 container-background rounded-full">
    <div class="col-12">
      <h2>{{ $t('welcome') }}, {{ this.$store.state.user.name + " " + this.$store.state.user.lastName }}</h2>
    </div>
    <div class="col-3">
      <StatsChart :chart-data="datacollection" :options="options"/>
    </div>
    <div class="col-9">
      <div class="col-12 container-background rounded-full">
        <h2 style="padding: 0;margin-top: 0">{{ $t('tasks_log') }}</h2>
        <table>
          <thead>
          <tr>
            <th>
              {{ $t('task_code') }}
            </th>
            <th>
              {{ $t('task_status') }}
            </th>
            <th>
              {{ $t('task_priority') }}
            </th>
            <th>
              {{ $t('task_reporter') }}
            </th>
            <th>
              {{ $t('task_actions') }}
            </th>
          </tr>
          </thead>
          <tbody>
          <template v-for="item in tasks">
            <tr>
              <td>
                {{ item.code }}
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
                <button>Process</button>
                <button>Cancel</button>
              </td>
            </tr>
          </template>
          </tbody>
        </table>
      </div>
      <div class="col-12">
        <img src="../assets/ship-plan.png"/>
      </div>
    </div>



  </div>
</template>

<script>
    import StatsChart from "./utils/StatsChart";

    export default {
        name: "MainPage",
        components: {StatsChart},
        data() {
            return {
                datacollection: null,
                tasks: [
                    {
                        id: "testid",
                        code: 'engineer_task_1',
                        status: 'todo',
                        priority: 'high',
                        reporter: {
                            id: "id",
                            name: "Adolf",
                            lastName: "Bitler"
                        },
                    }
                ],
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
                    labels: [this.$t('strength'), this.$t('dexterity'), this.$t('intelligence'), this.$t('experience'), this.$t('condition')],
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
