<template>
    <div class="col-12 container-background rounded-full">
        <h2 style="padding: 0;margin-top: 0">
            {{ $t('mainPage.tasks_log') }}
        </h2>

        <button class="info" v-on:click="reloadTasks">{{ $t('mainPage.reload') }}</button>
        <br/>
        <br/>

        <table>
            <thead>
            <tr>
                <th>
                    {{ $t('mainPage.task_code') }}
                </th>
                <th>
                    {{ $t('mainPage.task_status') }}
                </th>
                <th>
                    {{ $t('mainPage.task_priority') }}
                </th>
                <th>
                    {{ $t('mainPage.task_reporter') }}
                </th>
                <th v-if="showActions">
                    {{ $t('mainPage.task_actions') }}
                </th>
            </tr>
            </thead>
            <tbody>
            <template v-for="item in tasks">
                <tr>
                    <td>
                        {{ $t("mainPage." + item.code) }}
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
                    <td v-if="showActions">
                        <button class="info">Process</button>
                        <button>Cancel</button>
                    </td>
                </tr>
            </template>
            </tbody>
        </table>
    </div>
</template>
<script>
    export default {
        name: 'TaskLog',
        props: {
            showActions: false,
            tasks: Array,
        },
        methods: {
            reloadTasks() {
                console.log('reloading')
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
