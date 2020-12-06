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
                <input :disabled="lockButtons" type="text" id="name" v-model="crewmate.name" :class="{ 'validation-error': $v.crewmate.name.$error }"/>
                <div class="validation-error-text" v-if="!$v.crewmate.name.minLength">{{ $t('editCrew.validationMinLength', [$v.crewmate.name.$params.minLength.min]) }}</div>
              </td>
            </tr>
            <tr>
              <td>
                <label for="last_name" style="font-weight: bold;">{{ $t('editCrew.lastName') }}:</label>
              </td>
              <td>
                <input :disabled="lockButtons" type="text" id="last_name" v-model="crewmate.lastName" :class="{ 'validation-error': $v.crewmate.lastName.$error }"/>
                <div class="validation-error-text" v-if="!$v.crewmate.lastName.minLength">{{ $t('editCrew.validationMinLength', [$v.crewmate.lastName.$params.minLength.min]) }}</div>
              </td>
            </tr>
            <tr>
              <td>
                <label for="department_id" style="font-weight: bold;">{{ $t('editCrew.department') }}:</label>
              </td>
              <td>
                <select :disabled="lockButtons" id="department_id" v-model="crewmate.mainDepartment.id" :class="{ 'validation-error': $v.crewmate.mainDepartment.id.$error }">
                  <template v-for="item in departments">
                    <option :value="item.id">{{ $t('departments.' + item.code)  }}</option>
                  </template>

                </select>
              </td>
            </tr>
            <tr>
              <td>
                <label for="ship" style="font-weight: bold;">{{ $t('crewPage.ship')}}</label>
              </td>
              <td>
                <select :disabled="lockButtons" id="ship" v-model="crewmate.ship.id" :class="{ 'validation-error': $v.crewmate.ship.id.$error }">
                  <template v-for="item in this.ships">
                    <option :value="item.id">
                      {{ item.name }}
                    </option>
                  </template>
                </select>
              </td>
            </tr>
            <tr>
              <td>
                <label for="strength" style="font-weight: bold;">{{ $t('editCrew.strength') }}:</label>
              </td>
              <td>
                <input :disabled="lockButtons" id="strength" type="range" min="0" value="0" max="10" v-model="crewmate.stats.strength"/>
              </td>
            </tr>
            <tr>
              <td>
                <label for="dexterity" style="font-weight: bold;">{{ $t('editCrew.dexterity') }}:</label>
              </td>
              <td>
                <input :disabled="lockButtons" id="dexterity" type="range" min="0" value="0" max="10" v-model="crewmate.stats.dexterity"/>
              </td>
            </tr>
            <tr>
              <td>
                <label for="intelligence" style="font-weight: bold;">{{ $t('editCrew.intelligence') }}:</label>
              </td>
              <td>
                <input :disabled="lockButtons" id="intelligence" type="range" min="0" value="0" max="10" v-model="crewmate.stats.intelligence"/>
              </td>
            </tr>
            <tr>
              <td>
                <label for="experience" style="font-weight: bold;">{{ $t('editCrew.experience') }}:</label>
              </td>
              <td>
                <input :disabled="lockButtons" id="experience" type="range" min="0" value="0" max="10" v-model="crewmate.stats.experience"/>
              </td>
            </tr>
            <tr>
              <td>
                <label for="condition" style="font-weight: bold;">{{ $t('editCrew.condition') }}:</label>
              </td>
              <td>
                <input :disabled="lockButtons" id="condition" type="range" min="0" value="0" max="10" v-model="crewmate.stats.physicalCondition"/>
              </td>
            </tr>
          </table>
      </div>
      <div class="col-3">
        <StatsChart :chart-data="datacollection" :options="options"/>
      </div>
    </div>

    <Stations v-on:reload="reloadStations" :stations="crewmate.stations" :user-view="true" :allow-set-station="true"></Stations>
  </div>
</template>

<script>
    import StatsChart from "./../utils/StatsChart";
    import chartOptions from "./../utils/chartOptions";
    import Stations from "../stations/Stations";
    import {getCrewmate, putCrewmate} from "../../api/crewmates";
    import {getAllDepartments} from "../../api/departments";
    import {getAllShips} from "../../api/ships";
    import { required, minLength, between } from 'vuelidate/lib/validators'

    export default {
        name: "EditCrewPage",
        components: {Stations, StatsChart},
        data() {
            return {
                datacollection: null,
                lockButtons: false,
                departments: [],
                ships: [],
                crewmate: {
                    name: null,
                    mainDepartment: {
                      code: null,
                      id: null,
                    },
                    ship: {
                        id: null,
                    },
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
        validations: {
            crewmate: {
                name: {
                    required,
                    minLength: minLength(3)
                },
                lastName: {
                    required,
                    minLength: minLength(3)
                },
                login: {
                    required,
                    minLength: minLength(5)
                },
                password: {
                    required,
                    minLength: minLength(5)
                },
                mainDepartment: {
                    id: {
                        required,
                        minLength: minLength(1)
                    },
                },
                ship: {
                    id: {
                        required,
                        minLength: minLength(1)
                    },
                },
                stats: {
                    strength: {
                        required,
                        between: between(1, 10)
                    },
                    dexterity: {
                        required,
                        between: between(1, 10)
                    },
                    intelligence: {
                        required,
                        between: between(1, 10)
                    },
                    experience: {
                        required,
                        between: between(1, 10)
                    },
                    physicalCondition: {
                        required,
                        between: between(1, 10)
                    },
                },
            }
        },
        async beforeMount() {
            getCrewmate(this.$route.params.id).then((res) => {
                this.crewmate = res.data.data;
            });

            getAllShips().then((res) => {
                this.ships = res.data.data;
            });

            getAllDepartments().then((res) => {
                this.departments = res.data.data
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
                this.$v.$touch();

                if (this.$v.$invalid) {
                    return;
                }

                this.lockButtons = true;

                putCrewmate(this.$route.params.id, {
                    name: this.crewmate.name,
                    lastName: this.crewmate.lastName,
                    departmentId: Number.parseInt(this.crewmate.mainDepartment.id),
                    shipId: Number.parseInt(this.crewmate.ship.id),
                    strength: Number.parseInt(this.crewmate.stats.strength),
                    dexterity: Number.parseInt(this.crewmate.stats.dexterity),
                    intelligence: Number.parseInt(this.crewmate.stats.intelligence),
                    experience: Number.parseInt(this.crewmate.stats.experience),
                    physicalCondition: Number.parseInt(this.crewmate.stats.physicalCondition),
                }).then(() => {

                }).finally(()=>{
                    this.lockButtons = false;
                    this.$router.go();
                });

            },
            reloadStations() {
                getCrewmate(this.$route.params.id).then((res) => {
                    this.crewmate = res.data.data;
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
