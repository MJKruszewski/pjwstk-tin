<template>
  <div class="col-12 container-background rounded-full">
    <h2 style="padding: 0;margin-top: 0">
      {{ $t('mainPage.stations') }}
    </h2>

    <button :disabled="lockReload" :class="{ 'disabled-button': lockReload}" class="info" v-on:click="$emit('reload')">{{ $t('mainPage.reload') }}</button>
    <div style="display:inline;float: right">
      <button class="success" v-if="captainView" v-on:click="showCreate">{{ $t('mainPage.add') }}</button>
      <button class="success" v-if="allowSetStation" v-on:click="showAssign">{{ $t('mainPage.add') }}</button>
    </div>
    <br/>
    <br/>

    <table class="table-main">
      <thead>
      <tr>
        <th>
          {{ $t('mainPage.name') }}
        </th>
        <th v-if="captainView">
          {{ $t('mainPage.code') }}
        </th>
        <th>
          {{ $t('mainPage.department') }}
        </th>
        <th v-if="userView">
          {{ $t("mainPage.from") }}
        </th>
        <th v-if="userView">
          {{ $t("mainPage.to") }}
        </th>
        <th v-if="captainView || allowSetStation">
          {{ $t('mainPage.actions') }}
        </th>
      </tr>
      </thead>
      <transition-group name="list" tag="tbody">
        <tr :key="'loading'" v-if="stations === null">
          <td>Loading ...</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
          <template v-for="item,key in stations">
          <tr :key="key">
            <td>
              {{ $t("stations." + item.code) }}
            </td>
            <td v-if="captainView">
              {{ item.code }}
            </td>
            <td>
              {{ $t("departments." + item.department.code) }}
            </td>
            <td v-if="userView">
              {{ item.from }}
            </td>
            <td v-if="userView">
              {{ item.to }}
            </td>
            <td v-if="allowSetStation" width="15%">
              <button class="warning" v-if="item.to === null" v-on:click="terminate(item)">{{ $t('editCrew.checkOut') }}
              </button>
            </td>
            <td v-if="captainView" width="15%">
              <button class="warning" v-on:click="showEdit(item)">{{ $t('crewPage.edit') }}</button>
              <button class="danger" v-on:click="showPopupRemove(item)">{{ $t('departments.remove') }}</button>
            </td>
          </tr>
        </template>
      </transition-group>
    </table>

    <br/>
    <div v-if="showPagination" style="text-align: center">
      <button  style="float: left"  v-on:click="$emit('prev')" :disabled="pagination.page <= 0" :class="{ 'disabled-button': pagination.page <= 0}">{{ $t('prev') }}</button>

      <div style="display: inline;text-align: center">
        {{ pagination.page + 1 }} / {{ pagination.totalPages + 1}}
      </div>

      <button v-on:click="$emit('next')" :class="{ 'disabled-button': pagination.page >= pagination.totalPages}" :disabled="pagination.page >= pagination.totalPages" style="float: right">{{ $t('next') }}</button>
    </div>

    <PopupForm
      :title="popupForm.title"
      :display="popupForm.display"
      v-on:popup-confirm="saveConfirm"
      v-on:popup-cancel="saveCancel"
    >
      <table class="table-form">
        <tr>
          <td>
            <label for="code">{{ $t('mainPage.code') }}:</label>
          </td>
          <td>
            <input :disabled="this.lockButtons" v-model="popupForm.code" type="text" id="code" :class="{ 'validation-error': $v.popupForm.code.$error }"/>
            <div class="validation-error-text" v-if="!$v.popupForm.code.minLength">{{ $t('editCrew.validationMinLength', [$v.popupForm.code.$params.minLength.min]) }}</div>
          </td>
        </tr>
        <tr>
          <td>
            <label for="department">{{ $t('editCrew.department') }}:</label>
          </td>
          <td>
            <select :disabled="this.lockButtons" v-model="popupForm.department" id="department" :class="{ 'validation-error': $v.popupForm.department.$error }">
              <template v-for="item in allDepartments">
                <option :value="item.id">
                  {{ $t('departments.' + item.code) }}
                </option>
              </template>
            </select>
          </td>
        </tr>
      </table>
    </PopupForm>

    <PopupForm
      :title="popupAssign.title"
      :display="popupAssign.display"
      v-on:popup-confirm="assignConfirm"
      v-on:popup-cancel="assignCancel"
    >
      <table class="table-form">
        <tr>
          <td>
            <label for="id">{{ $t('mainPage.code') }}:</label>
          </td>
          <td>
            <select :disabled="this.lockButtons" id="id" v-model="popupAssign.id"  :class="{ 'validation-error': $v.popupAssign.id.$error }">
              <template v-for="item in allStations">
                <option :value="item.id">
                  {{ $t('stations.' + item.code) }}
                </option>
              </template>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <label for="from">{{ $t('editCrew.from') }}:</label>
          </td>
          <td>
            <input :disabled="this.lockButtons" type="date" v-model="popupAssign.from" id="from" :class="{ 'validation-error': $v.popupAssign.from.$error }"/>
          </td>
        </tr>
      </table>
    </PopupForm>

    <Popup
      :title="popup.title"
      :description="popup.description"
      :display="popup.display"
      v-on:popup-confirm="popupConfirm"
      v-on:popup-cancel="popupCancel"
    ></Popup>
  </div>
</template>
<script>
    import Popup from "../utils/Popup";
    import PopupForm from "../utils/PopupForm";
    import {
        deleteStation,
        getAllStations,
        patchStation,
        postCrewmateStation,
        postResign,
        postStation
    } from "../../api/stations";
    import {getAllDepartments} from "../../api/departments";
    import { required, minLength, between } from 'vuelidate/lib/validators'

    export default {
        name: 'Stations',
        components: {PopupForm, Popup},
        props: {
            captainView: false,
            lockReload: false,
            showPagination: false,
            allowSetStation: false,
            userView: false,
            stations: null,
            pagination: Object,
        },
        data() {
            return {
                allStations: [],
                lockButtons: false,
                allDepartments: [],
                new: {
                    station: null,
                },
                popupForm: {
                    display: false,
                    createNew: false,
                    title: null,
                    id: null,
                    code: null,
                    department: null,
                },
                popupAssign: {
                    display: false,
                    title: null,
                    id: null,
                    from: null,
                },
                popup: {
                    mate: '',
                    title: '',
                    description: '',
                    display: false,
                }
            }
        },
        validations: {
            popupForm: {
                code: {
                    required,
                    minLength: minLength(3),
                },
                department: {
                    required,
                },
            },
            popupAssign: {
                from: {
                    required,
                },
                id: {
                    required,
                },
            }
        },
        async beforeMount() {
            getAllDepartments().then((res) => {
                this.allDepartments = res.data.data
            });

            if (this.allowSetStation) {
                getAllStations().then((res) => {
                    this.allStations = res.data.data
                });
            }
        },
        methods: {
            terminate(item) {
                postResign(this.$route.params.id, item.id).then(()=>{
                    this.$emit('reload')
                });
            },
            popupCancel() {
                this.popup.display = false;
            },
            popupConfirm() {
                this.lockButtons=true;
                deleteStation(this.popup.mate.id).then(() => {
                    this.$emit('reload')
                }).finally(()=>{
                    this.lockButtons=false;
                    this.popup.display = false;
                });
            },
            assignCancel() {
                this.popupAssign.display = false;
            },
            assignConfirm() {
                this.$v.popupAssign.$touch();

                if (this.$v.popupAssign.$invalid) {
                    return;
                }

                this.lockButtons=true;
                postCrewmateStation(this.$route.params.id, this.popupAssign.id, this.popupAssign.from).then((res) => {
                    this.popupAssign.display = false;
                }).then(() => {
                    this.$emit('reload')
                }).finally(()=>{
                    this.lockButtons=false;
                    this.popupAssign.display = false;
                });
            },
            showCreate() {
                this.popupForm.title = this.$t('captainPage.addStation');
                this.popupForm.id = null;
                this.popupForm.code = null;
                this.popupForm.department = null;
                this.popupForm.display = true
                this.popupForm.createNew = true
            },

            showAssign() {
                this.popupAssign.title = this.$t('editCrew.assign');
                this.popupAssign.id = null;
                this.popupAssign.from = null;
                this.popupAssign.display = true
            },

            showEdit(item) {
                this.popupForm.title = this.$t('captainPage.editStation');
                this.popupForm.id = item.id;
                this.popupForm.code = item.code;
                this.popupForm.department = item.department.id;
                this.popupForm.display = true
                this.popupForm.createNew = false
            },

            saveCancel() {
                this.popupForm.display = false;
            },
            saveConfirm() {
                this.$v.popupForm.$touch();

                if (this.$v.popupForm.$invalid) {
                    return;
                }

                this.lockButtons=true;

                if (this.popupForm.createNew) {
                    postStation({
                        code: this.popupForm.code,
                        departmentId: this.popupForm.department,
                    }).then(() => {
                        this.$emit('reload')
                    }).finally(()=>{
                        this.lockButtons=false;
                        this.popupForm.display = false;
                    });
                } else {
                    patchStation(this.popupForm.id, {
                        code: this.popupForm.code,
                        departmentId: this.popupForm.department,
                    }).then(() => {
                        this.$emit('reload')
                    }).finally(()=>{
                        this.lockButtons=false;
                        this.popupForm.display = false;
                    });
                }
            },
            reloadTasks() {
                this.$emit('reload')
            },
            showPopupRemove(item) {
                this.popup.title = this.$t("captainPage.removeStation");
                this.popup.display = true;
                this.popup.mate = item;
            },
        }
    }
</script>
<style scoped>

  .table-form {
    margin-left: auto;
    margin-right: auto;
  }

  .table-main {
    border-collapse: collapse;
    width: 100%;
  }

  .table-main th, .table-main td {
    text-align: left;
    padding: 8px;
  }

  .table-main tr:nth-child(even) {
    background-color: #f2f2f2
  }

  .table-main th {
    background-color: #4a84af;
    color: white;
  }

  .table-main tr:hover {
    background-color: rgba(245, 245, 245, 0.21);
  }
  .list-enter-active, .list-leave-active {
    transition: all 1s;
  }
  .list-enter, .list-leave-to{
    opacity: 0;
    transform: translateX(30px);
  }
  .list-move {
    transition: transform 1s;
  }
</style>
