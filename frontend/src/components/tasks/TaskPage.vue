<template>
  <div class="col-12 container-background rounded-full" style="padding-top: 0">
    <div class="col-12">
      <h2>{{ $t('taskPage.title') }}</h2>
    </div>
    <div class="col-12 container-background rounded-full" style="margin-bottom: 10px">
      <label for="ship">{{ $t('crewPage.ship')}}: <p style="display: inline" v-if="!showButtons()">{{ this.$store.state.user.ship.name }}</p></label>
      <select v-if="showButtons()" id="ship" v-model="currentShip" v-on:change="reloadList">
        <template v-for="item in this.ships">
          <option :value="item.id">
            {{ item.name }}
          </option>
        </template>
      </select>

      <div style="display: inline;float: right;">
        <button class="success" v-on:click="showPopup">{{ $t('crewPage.add') }}</button>
      </div>
    </div>
    <div class="col-12 container-background rounded-full">
      <table class="table-main">
        <thead>
        <tr>
          <th>{{ $t('taskPage.code') }}</th>
          <th>{{ $t('taskPage.assignee') }}</th>
          <th>{{ $t('taskPage.reporter') }}</th>
          <th>{{ $t('taskPage.status') }}</th>
          <th>{{ $t('taskPage.priority') }}</th>
          <th>{{ $t('taskPage.crewActions') }}</th>
        </tr>
        </thead>

        <transition-group name="list" tag="tbody">
          <tr :key="'loading'" v-if="tasks === null">
            <td>Loading ...</td>
            <td></td>
            <td></td>
            <td></td>
          </tr>

          <template v-for="task,key in tasks">
            <tr :key="key">
              <td>{{ $t("tasks." + task.code) }}</td>
              <td>
                <template v-if="task.assignee">
                  {{ task.assignee.name }} {{ task.assignee.lastName }}
                </template>
              </td>
              <td>
                <template v-if="task.reporter">
                  {{ task.reporter.name }} {{ task.reporter.lastName }}
                </template>
              </td>
              <td>{{ $t("tasks." + task.status) }}</td>
              <td>{{ $t("tasks." + task.priority) }}</td>
              <td>
                <form v-on:submit="(e) => e.preventDefault()">
                  <button :disabled="lockButtons" :class="{ 'disabled-button': lockButtons}" v-if="task.assignee === null" class="info" v-on:click="assign(task)">{{ $t('taskPage.assign') }}</button>
                  <button :disabled="lockButtons" :class="{ 'disabled-button': lockButtons}" v-if="canProcess(task)" class="warning" v-on:click="updateStatus(task, 'processing')">{{ $t('taskPage.process') }}</button>
                  <button :disabled="lockButtons" :class="{ 'disabled-button': lockButtons}" v-if="canFinish(task)" class="success" v-on:click="updateStatus(task, 'done')">{{ $t('taskPage.done') }}</button>
                </form>
              </td>
            </tr>
          </template>
        </transition-group>
      </table>

      <br/>
      <div style="text-align: center">
        <button style="float: left" v-on:click="firstPage" :disabled="pagination.page <= 0 || lockButtons" :class="{ 'disabled-button': pagination.page <= 0 || lockButtons}"><</button>
        <button style="float: left; margin-left: 4px" v-on:click="prevPage" :disabled="pagination.page <= 0 || lockButtons" :class="{ 'disabled-button': pagination.page <= 0 || lockButtons}">{{ $t('prev') }}</button>

        <div style="display: inline;text-align: center">
          {{ pagination.page + 1 }} / {{ pagination.totalPages + 1}}
        </div>

        <button v-on:click="lastPage" :class="{ 'disabled-button': pagination.page >= pagination.totalPages || lockButtons}" :disabled="pagination.page >= pagination.totalPages || lockButtons" style="float: right">></button>
        <button v-on:click="nextPage" :class="{ 'disabled-button': pagination.page >= pagination.totalPages || lockButtons}" :disabled="pagination.page >= pagination.totalPages || lockButtons" style="float: right;margin-right: 4px">{{ $t('next') }}</button>
      </div>

    </div>

    <PopupForm
      :title="popupForm.title"
      :display="popupForm.display"
      :lock-button="lockButtons"
      v-on:popup-confirm="saveConfirm"
      v-on:popup-cancel="saveCancel"
    >
      <table class="table-form">
        <tr>
          <td>
            <label for="code">{{ $t('taskPage.code') }}:</label>
          </td>
          <td>
            <select :disabled="lockButtons" v-model="popupForm.code" id="code" :class="{ 'validation-error': $v.popupForm.code.$error }">
              <template v-for="item in taskCodes">
                <option :value="item">{{ $t("tasks." + item) }}</option>
              </template>
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <label for="station">{{ $t('taskPage.station') }}:</label>
          </td>
          <td>
            <select :disabled="lockButtons" v-model="popupForm.station" id="station" :class="{ 'validation-error': $v.popupForm.station.$error }">
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
            <label for="priority">{{ $t('taskPage.priority') }}:</label>
          </td>
          <td>
            <select :disabled="lockButtons" v-model="popupForm.priority" id="priority" :class="{ 'validation-error': $v.popupForm.priority.$error }">
              <template v-for="item in priorities">
                <option :value="item">{{ $t("tasks." + item) }}</option>
              </template>
            </select>
          </td>
        </tr>
      </table>
    </PopupForm>

  </div>

</template>

<script>
    import {getAllShipTasks, patchTask, postTask} from "../../api/tasks";
    import {isCaptain} from "../../api/crewmates";
    import {getAllShips} from "../../api/ships";
    import {canFinish, canProcess} from "./logic";
    import PopupForm from "../utils/PopupForm";
    import {TASK_CODES, PRIORITIES} from "../../api/tasks";
    import {getAllStations} from "../../api/stations";
    import { required, minLength, between } from 'vuelidate/lib/validators'

    export default {
        name: "TaskPage",
        components: {PopupForm},
        data: () => {
            return {
                taskCodes:TASK_CODES,
                priorities: PRIORITIES,
                ships: [],
                lockButtons: false,
                currentShip: 0,
                tasks: null,
                pagination: {
                    itemsPerPage: 5,
                    page: 0,
                    totalPages: 0,
                },
                allStations: [],
                popupForm: {
                    code: null,
                    priority: null,
                    station: null,
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
                },
                priority: {
                    required,
                },
                station: {
                    required,
                },
            }
        },
        mounted() {
            this.currentShip = this.$store.state.user.ship.id;

            getAllStations().then((res) => {
                this.allStations = res.data.data
            });

            getAllShips().then((res) => {
                this.ships = res.data.data;
                this.currentShip = this.$store.state.user.ship.id;
            });

            getAllShipTasks(this.currentShip, this.pagination.page, this.pagination.itemsPerPage).then((res) => {
                this.pagination.totalPages = res.data.meta.totalPages;
                this.tasks = res.data.data;
            });
        },
        methods: {
            firstPage() {
                this.lockButtons = true;
                this.pagination.page = 0;
                this.tasks = [];
                getAllShipTasks(this.currentShip, this.pagination.page, this.pagination.itemsPerPage).then((res) => {
                    this.pagination.totalPages = res.data.meta.totalPages;
                    this.tasks = res.data.data;
                }).finally(() => {
                    this.lockButtons = false;
                });
            },
            lastPage() {
                this.lockButtons = true;
                this.pagination.page = this.pagination.totalPages;
                this.tasks = [];
                getAllShipTasks(this.currentShip, this.pagination.page, this.pagination.itemsPerPage).then((res) => {
                    this.pagination.totalPages = res.data.meta.totalPages;
                    this.tasks = res.data.data;
                }).finally(() => {
                    this.lockButtons = false;
                });
            },
            prevPage() {
                this.lockButtons = true;
                this.pagination.page--;
                this.tasks = [];
                getAllShipTasks(this.currentShip, this.pagination.page, this.pagination.itemsPerPage).then((res) => {
                    this.pagination.totalPages = res.data.meta.totalPages;
                    this.tasks = res.data.data;
                }).finally(() => {
                    this.lockButtons = false;
                });
            },
            nextPage() {
                this.lockButtons = true;
                this.pagination.page++;
                this.tasks = [];
                getAllShipTasks(this.currentShip, this.pagination.page, this.pagination.itemsPerPage).then((res) => {
                    this.pagination.totalPages = res.data.meta.totalPages;
                    this.tasks = res.data.data;
                }).finally(() => {
                    this.lockButtons = false;
                });
            },
            assign(task) {
                this.lockButtons = true;
                patchTask(task.id, {
                    assigneeId: this.$store.state.user.id,
                }).then((res) => {
                    this.tasks = [];
                    getAllShipTasks(this.currentShip, this.pagination.page, this.pagination.itemsPerPage).then((res) => {
                        this.pagination.totalPages = res.data.meta.totalPages;
                        this.tasks = res.data.data;
                    }).finally(() => {
                        this.lockButtons = false;
                    });
                })
            },
            updateStatus(task, status) {
                this.lockButtons = true;
                patchTask(task.id, {
                    status: status,
                }).then((res) => {
                    this.tasks = [];
                    getAllShipTasks(this.currentShip, this.pagination.page, this.pagination.itemsPerPage).then((res) => {
                        this.pagination.totalPages = res.data.meta.totalPages;
                        this.tasks = res.data.data;
                    }).finally(() => {
                        this.lockButtons = false;
                    });
                })
            },
            canProcess(task) {
                return canProcess(task)
            },
            canFinish(task) {
                return canFinish(task)
            },
            showButtons() {
                return isCaptain();
            },
            reloadList() {
                this.lockButtons = true;
                this.pagination.page = 0;
                this.tasks = [];
                getAllShipTasks(this.currentShip, this.pagination.page, this.pagination.itemsPerPage).then((res) => {
                    this.pagination.totalPages = res.data.meta.totalPages;
                    this.tasks = res.data.data;
                }).finally(() => {
                    this.lockButtons = false;
                });
            },
            showPopup() {
                this.popupForm.title = this.$t('taskPage.popupTitle');
                this.popupForm.display = true;
            },
            detailsRedirect(id) {
            },
            saveConfirm() {
                this.$v.$touch();

                if (this.$v.$invalid) {
                    return;
                }

                this.lockButtons = true;

                let body = {
                  code: this.popupForm.code,
                  priority: this.popupForm.priority,
                  stationId: this.popupForm.station,
                };

                if (isCaptain()) {
                    body.shipId = this.currentShip
                }

                postTask(body).then(() => {
                    this.tasks = [];
                    this.pagination.page = 0;
                    getAllShipTasks(this.currentShip, this.pagination.page, this.pagination.itemsPerPage).then((res) => {
                        this.pagination.totalPages = res.data.meta.totalPages;
                        this.tasks = res.data.data;
                    }).finally(() => {
                        this.lockButtons = false;
                    });
                }).finally(() => {
                    this.popupForm.code = null;
                    this.popupForm.priority = null;
                    this.popupForm.station = null;
                    this.popupForm.display = false;
                    this.lockButtons = false;
                });
            },
            saveCancel() {
                this.popupForm.code = null;
                this.popupForm.priority = null;
                this.popupForm.station = null;
                this.popupForm.display = false;
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
    background-color: rgba(165, 165, 165, 0.41)
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
