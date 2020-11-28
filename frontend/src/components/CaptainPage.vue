<template>
  <div class="col-12 container-background rounded-full" style="padding-top: 0">
    <div class="col-12">
      <h2>{{ $t('captainPage.title') }}</h2>
    </div>

    <div class="col-12">
      <Stations v-on:reload="loadAllStations()"  :stations="allStations" :captainView="true"></Stations>
    </div>
    <div class="col-12">
      <Departments v-on:reload="loadAllDepartments()" :departments="allDepartments" :captainView="true"></Departments>
    </div>


  </div>
</template>

<script>
    import Stations from "./stations/Stations";
    import Departments from "./departments/Departments";
    import {getAllDepartments} from "../api/departments";
    import {getAllStations} from "../api/stations";

    export default {
        name: "CaptainPage",
        components: {Departments, Stations},
        async beforeMount() {

            getAllDepartments().then((res) => {
                this.allDepartments = res.data.data
            });
            getAllStations().then((res) => {
                this.allStations = res.data.data
            });
        },
        methods: {
            loadAllStations() {
                this.allStations = [];
                getAllStations().then((res) => {
                    this.allStations = res.data.data
                });
            },
            loadAllDepartments() {
                this.allDepartments = [];
                getAllDepartments().then((res) => {
                    this.allDepartments = res.data.data
                });
            }
        },
        data() {
            return {
                allStations: [],
                allDepartments: [],
            }
        },
    }
</script>

<style scoped>

</style>
