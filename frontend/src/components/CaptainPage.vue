<template>
  <div class="col-12 container-background rounded-full" style="padding-top: 0">
    <div class="col-12">
      <h2>{{ $t('captainPage.title') }}</h2>
    </div>

    <div class="col-12">
      <Stations :stations="allStations" :captainView="true"></Stations>
    </div>
    <div class="col-12">
      <Departments :departments="allDepartments" :captainView="true"></Departments>
    </div>


  </div>
</template>

<script>
    import stationsList from "../mocks/stationsList";
    import Stations from "./stations/Stations";
    import Departments from "./departments/Departments";
    import departmentsList from "../mocks/departmentsList";

    export default {
        name: "CaptainPage",
        components: {Departments, Stations},
        data() {
            return {
                allStations: stationsList,
                allDepartments: departmentsList,
            }
        },
    }
</script>

<style scoped>

</style>
