<template>
  <div class="col-12 container-background rounded-full" style="padding-top: 0">
    <div class="col-12">
      <h2>{{ $t('editCrew.title', [this.crewmate.name + " " + this.crewmate.lastName]) }}</h2>
    </div>
    <div class="col-12 container-background rounded-full" style="margin-bottom: 10px">
      <button class="warning" v-on:click="$router.push({ name: 'crewList' })">{{ $t('crewDetails.back') }}</button>
      <div style="display: inline;float: right;">
        <button class="success" style="font-weight: bold" v-on:click="save()">{{ $t('editCrew.save') }}</button>
      </div>
    </div>
    <div style="margin-bottom: 10px" class="col-12 container-background rounded-full">
      <div class="col-6">
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
      </div>
      <div class="col-3">
        <StatsChart :chart-data="datacollection" :options="options"/>
      </div>
    </div>

    <Stations :stations="crewmate.stations" :user-view="true" :allow-set-station="true"></Stations>
  </div>
</template>

<script>
    import StatsChart from "./../utils/StatsChart";
    import chartOptions from "./../utils/chartOptions";
    import Stations from "../stations/Stations";
    import {getCrewmate} from "../../api/crewmates";

    export default {
        name: "EditCrewPage",
        components: {Stations, StatsChart},
        data() {
            return {
                datacollection: null,
                crewmate: {
                    name: null,
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
                options: chartOptions
            };
        },
        async beforeMount() {
            await getCrewmate(this.$route.params.id).then((res) => {
                this.crewmate = res.data.data;
            });

            this.fillData()
        },

        watch: {
            crewmate: {
                handler() {
                    this.fillData()
                },
                deep: true,
            }
        },
        methods: {
            save() {
                this.$router.push({ name: 'crewList' })
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
