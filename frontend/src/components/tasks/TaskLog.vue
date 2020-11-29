<template>
    <div class="col-12 container-background rounded-full">
        <h2 style="padding: 0;margin-top: 0">
            {{ $t('mainPage.tasks_log') }}
        </h2>

        <button :disabled="lockReload" :class="{ 'disabled-button': lockReload}" class="info" v-on:click="$emit('reload')">{{ $t('mainPage.reload') }}</button>
        <br/>
        <br/>

      <table>
        <thead>
        <tr>
          <th>{{ $t('taskPage.code') }}</th>
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
</template>
<script>
    import {canFinish, canProcess} from "./logic";
    import {patchTask} from "../../api/tasks";
    import {getMe} from "../../api/login";

    export default {
        name: 'TaskLog',
        data: () => {
            return {
                lockButtons: false,
            }
        },
        props: {
            lockReload: false,
            showActions: false,
            tasks: null,
        },
        methods: {
            assign(task) {
                this.lockButtons = true;
                patchTask(task.id, {
                    assigneeId: this.$store.state.user.id,
                }).then((res) => {
                    getMe().then((res) => {
                        this.$store.dispatch('setCrewmate', res.data.data)
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
                    getMe().then((res) => {
                        this.$store.dispatch('setCrewmate', res.data.data)
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
