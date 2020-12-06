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
                <input :disabled="lockButtons" type="text" id="name" v-model="crewmate.name" :class="{ 'validation-error': $v.crewmate.name.$error }"/>
                <div class="validation-error-text" v-if="!$v.crewmate.name.minLength">{{ $t('editCrew.validationMinLength', [$v.crewmate.name.$params.minLength.min]) }}</div>
              </td>
            </tr>
            <tr>
              <td>
                <label for="last_name" style="font-weight: bold;">{{ $t('editCrew.lastName') }}:</label>
              </td>
              <td>
                <input :disabled="lockButtons" type="text" id="last_name" v-model="crewmate.lastName" :class="{ 'validation-error': $v.crewmate.lastName.$error }" />
                <div class="validation-error-text" v-if="!$v.crewmate.lastName.minLength">{{ $t('editCrew.validationMinLength', [$v.crewmate.lastName.$params.minLength.min]) }}</div>
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
                <label for="login" style="font-weight: bold;">{{ $t('editCrew.login') }}:</label>
              </td>
              <td>
                <input :disabled="lockButtons" type="text" id="login" v-model="crewmate.login" :class="{ 'validation-error': $v.crewmate.login.$error }"/>
                <div class="validation-error-text" v-if="!$v.crewmate.login.minLength">{{ $t('editCrew.validationMinLength', [$v.crewmate.login.$params.minLength.min]) }}</div>
                <div class="validation-error-text" v-if="loginTaken">{{ $t('editCrew.loginTaken') }}</div>
              </td>
            </tr>
            <tr>
              <td>
                <label for="password" style="font-weight: bold;">{{ $t('editCrew.password') }}:</label>
              </td>
              <td>
                <input :disabled="lockButtons" type="password" id="password" v-model="crewmate.password" :class="{ 'validation-error': $v.crewmate.password.$error }"/>
                <div class="validation-error-text" v-if="!$v.crewmate.password.minLength">{{ $t('editCrew.validationMinLength', [$v.crewmate.password.$params.minLength.min]) }}</div>
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
    import {postCrewmate} from "../../api/crewmates";
    import { required, minLength, between } from 'vuelidate/lib/validators'

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
                loginTaken: false,
                departments: [],
                lockButtons: false,
                crewmate: {
                    name: null,
                    login: null,
                    password: null,
                    mainDepartment: {
                        code: null,
                        id: null,
                    },
                    ship: {
                        id: null,
                    },
                    stats: {
                        strength: 1,
                        dexterity: 1,
                        intelligence: 1,
                        experience: 1,
                        physicalCondition: 1,
                    },
                    lastName: null,
                    stations: [],
                },
                datacollection: null,
                options: chartOptions,
            }
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
                this.loginTaken = false;
                this.$v.$touch();

                if (this.$v.$invalid) {
                    return;
                }

                this.lockButtons = true;

                postCrewmate({
                    name: this.crewmate.name,
                    lastName: this.crewmate.lastName,
                    login: this.crewmate.login,
                    password: this.crewmate.password,
                    departmentId: Number.parseInt(this.crewmate.mainDepartment.id),
                    shipId: Number.parseInt(this.crewmate.ship.id),
                    strength: Number.parseInt(this.crewmate.stats.strength),
                    dexterity: Number.parseInt(this.crewmate.stats.dexterity),
                    intelligence: Number.parseInt(this.crewmate.stats.intelligence),
                    experience: Number.parseInt(this.crewmate.stats.experience),
                    physicalCondition: Number.parseInt(this.crewmate.stats.physicalCondition),
                }).then((res) => {
                    if (this.backPage) {
                        this.$router.push({ name: this.backPage});
                    } else {
                        this.$router.push({ name: 'crewEdit', params: { id: res.data.data.id } })
                    }
                }).catch((res) => {
                    console.log(res)
                    if (res.response.status === 409){
                        this.loginTaken = true;
                    }
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
