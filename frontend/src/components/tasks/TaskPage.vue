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
        <button v-if="showButtons()" class="success" v-on:click="$router.push({ name: 'crewAdd', params: { showChart: true}})">{{ $t('crewPage.add') }}</button>
      </div>
    </div>
    <div class="col-12 container-background rounded-full">
      <table>
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
          <tr :key="'loading'" v-if="tasks.length === 0">
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
              <td>{{ task.reporter.name }} {{ task.reporter.lastName }}</td>
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
    </div>

  </div>

</template>

<script>
    import {getAllTasks, patchTask} from "../../api/tasks";
    import {isCaptain} from "../../api/crewmates";
    import {getAllShips} from "../../api/ships";
    import {canFinish, canProcess} from "./logic";

    export default {
        name: "TaskPage",
        data: () => {
            return {
                ships: [],
                lockButtons: false,
                currentShip: 0,
                tasks: [],
                popup: {
                    mate: '',
                    title: '',
                    description: '',
                    display: false,
                }
            }
        },
        mounted() {
            this.popup.description = this.$t('crewPage.popupRemove.description');

            getAllShips().then((res) => {
                this.ships = res.data.data;
                this.currentShip = this.$store.state.user.ship.id;
            });

            getAllTasks().then((res) => {
                this.tasks = res.data.data;
            });
        },
        methods: {
            assign(task) {
                this.lockButtons = true;
                patchTask(task.id, {
                    assigneeId: this.$store.state.user.id,
                }).then((res) => {
                    getAllTasks().then((res) => {
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
                    getAllTasks().then((res) => {
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

            },
            showPopup(mate) {
                this.popup.title = this.$t("crewPage.popupRemove.title", [mate.name + " " + mate.lastName]);
                this.popup.display = true;
                this.popup.mate = mate;
            },
            popupCancel() {
                console.log(this.popup.mate)
                this.popup.display = false;
            },
            popupConfirm() {
                console.log(this.popup.mate)
            },
            detailsRedirect(id) {

            },
            editRedirect(id) {

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
    background-color: rgba(165, 165, 165, 0.41)
  }

  th {
    background-color: #4a84af;
    color: white;
  }

  tr:hover {
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
