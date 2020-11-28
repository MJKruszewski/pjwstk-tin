<template>
  <div class="col-12 container-background rounded-full" style="padding-top: 0">
    <div class="col-12">
      <h2>{{ $t('crewAdd.title') }}</h2>
    </div>

    <div class="col-12 container-background rounded-full" style="margin-bottom: 10px">
      <button class="warning" v-on:click="$router.push({ name: backPage || 'crewList' })">{{ $t('crewDetails.back') }}</button>
      <div style="display: inline;float: right;">
        <button class="success" style="font-weight: bold" v-on:click="save()">{{ $t('editCrew.save') }}</button>
      </div>
    </div>

    <div class="col-12 container-background rounded-full">
      <div class="col-6">
        <form>
          <table>
            <tr>
              <td>
                <label for="name" style="font-weight: bold;">{{ $t('editCrew.name') }}:</label>
              </td>
              <td>
                <input type="text" id="name" v-model="this.crewmate.name"/>
              </td>
            </tr>
            <tr>
              <td>
                <label for="last_name" style="font-weight: bold;">{{ $t('editCrew.lastName') }}:</label>
              </td>
              <td>
                <input type="text" id="last_name" v-model="this.crewmate.lastName"/>
              </td>
            </tr>
            <tr>
              <td>
                <label for="ship">{{ $t('crewPage.ship')}}</label>
              </td>
              <td>
                <select id="ship">
                  <template v-for="item in this.ships">
                    <option :id="item.id">
                      {{ item.name }}
                    </option>
                  </template>
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <label for="login" style="font-weight: bold;">{{ $t('editCrew.login') }}:</label>
              </td>
              <td>
                <input type="text" id="login" v-model="crewmate.login"/>
              </td>
            </tr>
            <tr>
              <td>
                <label for="password" style="font-weight: bold;">{{ $t('editCrew.password') }}:</label>
              </td>
              <td>
                <input type="password" id="password" v-model="crewmate.password"/>
              </td>
            </tr>
            <tr>
              <td>
                <label for="department_id" style="font-weight: bold;">{{ $t('editCrew.department') }}:</label>
              </td>
              <td>
                <select id="department_id" v-model="crewmate.mainDepartment">
                  <template v-for="item in departments">
                    <option :value="item.id">{{ $t('departments.' + item.code)  }}</option>
                  </template>

                </select>
              </td>
            </tr>
            <tr>
              <td>
                <label for="strength" style="font-weight: bold;">{{ $t('editCrew.strength') }}:</label>
              </td>
              <td>
                <input id="strength" type="range" min="0" value="0" max="10" v-model="crewmate.stats.strength"/>
              </td>
            </tr>
            <tr>
              <td>
                <label for="dexterity" style="font-weight: bold;">{{ $t('editCrew.dexterity') }}:</label>
              </td>
              <td>
                <input id="dexterity" type="range" min="0" value="0" max="10" v-model="crewmate.stats.dexterity"/>
              </td>
            </tr>
            <tr>
              <td>
                <label for="intelligence" style="font-weight: bold;">{{ $t('editCrew.intelligence') }}:</label>
              </td>
              <td>
                <input id="intelligence" type="range" min="0" value="0" max="10" v-model="crewmate.stats.intelligence"/>
              </td>
            </tr>
            <tr>
              <td>
                <label for="experience" style="font-weight: bold;">{{ $t('editCrew.experience') }}:</label>
              </td>
              <td>
                <input id="experience" type="range" min="0" value="0" max="10" v-model="crewmate.stats.experience"/>
              </td>
            </tr>
            <tr>
              <td>
                <label for="condition" style="font-weight: bold;">{{ $t('editCrew.condition') }}:</label>
              </td>
              <td>
                <input id="condition" type="range" min="0" value="0" max="10" v-model="crewmate.stats.physicalCondition"/>
              </td>
            </tr>
          </table>
        </form>
      </div>
      <div class="col-3" v-if="getDisplayChart()">
        <StatsChart :chart-data="datacollection" :options="options"/>
      </div>
    </div>

  </div>
</template>

<script>
    import StatsChart from "./../utils/StatsChart";
    import chartOptions from "./../utils/chartOptions";
    import {getAllShips} from "../../api/ships";
    import {getAllDepartments} from "../../api/departments";
    export default {
        name: "AddCrewPage",
        components: {StatsChart},
        props: {
          displayChart: Boolean,
          backPage: null,
        },
        data() {
            return {
                ships: [],
                departments: [],
                crewmate: {
                    name: null,
                    login: null,
                    password: null,
                    mainDepartment: null,
                    stats: {
                        strength: null,
                        dexterity: null,
                        intelligence: null,
                        experience: null,
                        physicalCondition: null,
                    },
                    lastName: null,
                    stations: [],
                },
                datacollection: null,
                options: chartOptions,
            }
        },
        watch: {
            crewmate: {
                handler() {
                    if(this === null) {
                        return;
                    }

                    this.fillData()
                },
                deep: true,
            }
        },
        beforeMount() {
            this.fillData();
        },
        mounted() {
            getAllShips().then((res) => {
                this.ships = res.data.data;
            });

            getAllDepartments().then((res) => {
                this.departments = res.data.data
            })
        },
        methods: {
            getDisplayChart() {
              if (this.$route.params.showChart === true) {
                  return true;
              }

              return this.displayChart;
            },
            save() {

            },
            fillData() {
                this.datacollection = {
                    labels: [this.$t('mainPage.strength'), this.$t('mainPage.dexterity'), this.$t('mainPage.intelligence'), this.$t('mainPage.experience'), this.$t('mainPage.condition')],
                    datasets: [
                        {
                            label: this.$t('statistics'),
                            backgroundColor: '#f87979',
                            data: [
                                parseInt(this.crewmate.stats.strength || 0),
                                parseInt(this.crewmate.stats.dexterity || 0),
                                parseInt(this.crewmate.stats.intelligence || 0),
                                parseInt(this.crewmate.stats.experience || 0),
                                parseInt(this.crewmate.stats.physicalCondition || 0),
                            ]
                        },
                    ]
                }
            },
        },
    }
</script>

<style scoped>

</style>
