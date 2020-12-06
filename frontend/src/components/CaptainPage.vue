<template>
  <div class="col-12 container-background rounded-full" style="padding-top: 0">
    <div class="col-12">
      <h2>{{ $t('captainPage.title') }}</h2>
    </div>

    <div class="col-12">
      <Stations
        v-on:reload="loadAllStations()"
        v-on:next="statNextPage"
        v-on:prev="statPrevPage"
        :show-pagination="true"
        :pagination="paginationStations"
        :stations="allStations" :captainView="true"></Stations>
    </div>
    <div class="col-12">
      <Departments
        v-on:reload="loadAllDepartments()"
        v-on:next="depNextPage"
        v-on:prev="depPrevPage"
        :pagination="paginationDepartment"
        :departments="allDepartments"
        :captainView="true"></Departments>
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
            getAllDepartments(this.paginationDepartment.page, this.paginationDepartment.itemsPerPage).then((res) => {
                this.allDepartments = res.data.data
                this.paginationDepartment.totalPages = res.data.meta.totalPages;
            });
            getAllStations(this.paginationStations.page, this.paginationStations.itemsPerPage).then((res) => {
                this.allStations = res.data.data
                this.paginationStations.totalPages = res.data.meta.totalPages;
            });
        },
        methods: {
            depPrevPage() {
                this.paginationDepartment.page--;
                this.allDepartments = [];
                getAllDepartments(this.paginationDepartment.page, this.paginationDepartment.itemsPerPage).then((res) => {
                    this.allDepartments = res.data.data
                    this.paginationDepartment.totalPages = res.data.meta.totalPages;
                });
            },
            depNextPage() {
                this.paginationDepartment.page++;
                this.allDepartments = [];
                getAllDepartments(this.paginationDepartment.page, this.paginationDepartment.itemsPerPage).then((res) => {
                    this.allDepartments = res.data.data
                    this.paginationDepartment.totalPages = res.data.meta.totalPages;
                });
            },
            statPrevPage() {
                this.paginationStations.page--;
                this.allStations = [];
                getAllStations(this.paginationStations.page, this.paginationStations.itemsPerPage).then((res) => {
                    this.allStations = res.data.data
                    this.paginationStations.totalPages = res.data.meta.totalPages;
                });
            },
            statNextPage() {
                this.paginationStations.page++;
                this.allStations = [];
                getAllStations(this.paginationStations.page, this.paginationStations.itemsPerPage).then((res) => {
                    this.allStations = res.data.data
                    this.paginationStations.totalPages = res.data.meta.totalPages;
                });
            },
            loadAllStations() {
                this.paginationStations.page = 0;
                this.allStations = [];
                getAllStations(this.paginationStations.page, this.paginationStations.itemsPerPage).then((res) => {
                    this.allStations = res.data.data
                    this.paginationStations.totalPages = res.data.meta.totalPages;
                });
            },
            loadAllDepartments() {
                this.paginationDepartment.page = 0;
                this.allDepartments = [];
                getAllDepartments(this.paginationDepartment.page, this.paginationDepartment.itemsPerPage).then((res) => {
                    this.allDepartments = res.data.data
                    this.paginationDepartment.totalPages = res.data.meta.totalPages;
                });
            }
        },
        data() {
            return {
                allStations: [],
                allDepartments: [],
                paginationDepartment: {
                    itemsPerPage: 5,
                    page: 0,
                    totalPages: 0,
                },
                paginationStations: {
                    itemsPerPage: 5,
                    page: 0,
                    totalPages: 0,
                },
            }
        },
    }
</script>

<style scoped>

</style>
