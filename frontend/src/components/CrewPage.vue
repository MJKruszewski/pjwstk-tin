<template>
  <div class="col-12 container-background rounded-full">
    <table>
      <thead>
        <tr>
          <th>{{ $t('crewPage.crewName') }}</th>
          <th>{{ $t('crewPage.crewDepartment') }}</th>
          <th>{{ $t('crewPage.crewTasks') }}</th>
          <th>{{ $t('crewPage.crewActions') }}</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="mate in crew">
          <tr>
            <td>{{ mate.name }} {{ mate.lastName }}</td>
            <td>{{ $t("departments." + mate.department) }}</td>
            <td>todo</td>
            <td>
              <button class="info" v-on:click="detailsRedirect(mate.id)" >{{ $t('crewPage.details') }}</button>
              <button class="warning">{{ $t('crewPage.edit') }}</button>
              <button class="danger">{{ $t('crewPage.remove') }}</button>
            </td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</template>

<script>
    import crewList from "../mocks/crewList";

    export default {
        name: "CrewPage",
        data: () => {
            return {
                crew: crewList
            }
        },
        methods: {
            detailsRedirect(id) {
                this.$router.push({ name: 'crewDetails', params: { id: id } })
            }
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
</style>
