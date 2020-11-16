<template>
  <div class="col-12 container-background rounded-full" style="padding-top: 0">
    <div class="col-12">
      <h2>{{ $t('crewAdd.title') }}</h2>
    </div>

    <div class="col-12 container-background rounded-full" style="margin-bottom: 10px">
      <button class="warning" v-on:click="$router.push({ name: 'crewList' })">{{ $t('crewDetails.back') }}</button>
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
                <label for="department_id" style="font-weight: bold;">{{ $t('editCrew.department') }}:</label>
              </td>
              <td>
                <select id="department_id"></select>
              </td>
            </tr>
            <tr>
              <td>
                <label for="strength" style="font-weight: bold;">{{ $t('editCrew.strength') }}:</label>
              </td>
              <td>
                <input id="strength" type="range" min="0" value="0" max="10" v-model="crewmate.strength"/>
              </td>
            </tr>
            <tr>
              <td>
                <label for="dexterity" style="font-weight: bold;">{{ $t('editCrew.dexterity') }}:</label>
              </td>
              <td>
                <input id="dexterity" type="range" min="0" value="0" max="10" v-model="crewmate.dexterity"/>
              </td>
            </tr>
            <tr>
              <td>
                <label for="intelligence" style="font-weight: bold;">{{ $t('editCrew.intelligence') }}:</label>
              </td>
              <td>
                <input id="intelligence" type="range" min="0" value="0" max="10" v-model="crewmate.intelligence"/>
              </td>
            </tr>
            <tr>
              <td>
                <label for="experience" style="font-weight: bold;">{{ $t('editCrew.experience') }}:</label>
              </td>
              <td>
                <input id="experience" type="range" min="0" value="0" max="10" v-model="crewmate.experience"/>
              </td>
            </tr>
            <tr>
              <td>
                <label for="condition" style="font-weight: bold;">{{ $t('editCrew.condition') }}:</label>
              </td>
              <td>
                <input id="condition" type="range" min="0" value="0" max="10" v-model="crewmate.condition"/>
              </td>
            </tr>
          </table>
        </form>
      </div>
      <div class="col-3">
        <StatsChart :chart-data="datacollection" :options="options"/>
      </div>
    </div>

  </div>
</template>

<script>
    import StatsChart from "./../utils/StatsChart";
    import chartOptions from "./../utils/chartOptions";
    export default {
        name: "AddCrewPage",
        components: {StatsChart},
        data() {
            return {
                crewmate: {
                    name: null,
                    lastName: null,
                    department: null,
                    strength: 0,
                    dexterity: 0,
                    intelligence: 0,
                    experience: 0,
                    condition: 0,
                },
                datacollection: null,
                options: chartOptions,
            }
        },
        watch: {
            crewmate: {
                handler() {
                    this.fillData()
                },
                deep: true,
            }
        },
        mounted() {
            this.fillData()
        },
        methods: {
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
                                parseInt(this.crewmate.strength || 0),
                                parseInt(this.crewmate.dexterity || 0),
                                parseInt(this.crewmate.intelligence || 0),
                                parseInt(this.crewmate.experience || 0),
                                parseInt(this.crewmate.condition || 0),
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
