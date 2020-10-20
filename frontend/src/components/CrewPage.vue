<template>
  <div class="col-12 container-background rounded-full">
    <table>
      <thead>
        <tr>
          <th>{{ $t('crew_name') }}</th>
          <th>{{ $t('crew_department') }}</th>
          <th>{{ $t('crew_tasks') }}</th>
          <th>{{ $t('crew_actions') }}</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="mate in crew">
          <tr>
            <td>

            </td>
          </tr>
        </template>
      </tbody>
    </table>
  </div>
</template>

<script>
    export default {
        name: "CrewPage",
        data: () => {
            return {
                crew: [
                    {

                    }
                ]
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
