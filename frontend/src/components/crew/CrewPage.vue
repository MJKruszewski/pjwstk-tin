<template>
  <div class="col-12 container-background rounded-full" style="padding-top: 0">
    <div class="col-12">
      <h2>{{ $t('crewPage.title') }}</h2>
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
          <th>{{ $t('crewPage.crewName') }}</th>
          <th>{{ $t('crewPage.crewDepartment') }}</th>
          <th>{{ $t('crewPage.crewTasks') }}</th>
          <th>{{ $t('crewPage.crewActions') }}</th>
        </tr>
        </thead>

        <transition-group name="list" tag="tbody">
          <tr :key="'loading'" v-if="crew === null">
            <td>Loading ...</td>
            <td></td>
            <td></td>
            <td></td>
          </tr>

          <template v-for="mate,key in crew">
            <tr :key="key">
              <td>{{ mate.name }} {{ mate.lastName }}</td>
              <td>{{ $t("departments." + mate.mainDepartment.code) }}</td>
              <td>todo</td>
              <td>
                <button class="info" v-on:click="detailsRedirect(mate.id)">{{ $t('crewPage.details') }}</button>
                <button class="warning" v-if="showButtons()" v-on:click="editRedirect(mate.id)">{{ $t('crewPage.edit') }}</button>
                <button class="danger" v-if="showButtons()" v-on:click="showPopup(mate)">{{ $t('crewPage.remove') }}</button>
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

        <button v-on:click="lastPage" :class="{ 'disabled-button': pagination.page >= pagination.totalPages || lockButtons}" :disabled="pagination.page >= pagination.totalPages  || lockButtons" style="float: right">></button>
        <button v-on:click="nextPage" :class="{ 'disabled-button': pagination.page >= pagination.totalPages || lockButtons}" :disabled="pagination.page >= pagination.totalPages || lockButtons" style="float: right;margin-right: 4px">{{ $t('next') }}</button>
      </div>

    </div>
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
    import Popup from "./../utils/Popup";
    import {getAllShips} from "../../api/ships";
    import {deleteCrewmate, getShipCrewmates, isCaptain} from "../../api/crewmates";

    export default {
        name: "CrewPage",
        components: {Popup},
        data: () => {
            return {
                ships: [],
                currentShip: 0,
                lockButtons: false,
                crew: null,
                pagination: {
                    itemsPerPage: 5,
                    page: 0,
                    totalPages: 0,
                },
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
            this.currentShip = this.$store.state.user.ship.id;

            getAllShips().then((res) => {
                this.ships = res.data.data;
                this.currentShip = this.$store.state.user.ship.id;
            });

            getShipCrewmates(this.currentShip, this.pagination.page, this.pagination.itemsPerPage).then((res) => {
                this.crew = res.data.data;
                this.pagination.totalPages = res.data.meta.totalPages;
            });
        },
        methods: {
            firstPage() {
                this.lockButtons = true;
                this.pagination.page = 0;
                this.crew = [];
                getShipCrewmates(this.currentShip, this.pagination.page, this.pagination.itemsPerPage).then((res) => {
                    this.crew = res.data.data;
                    this.pagination.totalPages = res.data.meta.totalPages;
                }).finally(() => {
                    this.lockButtons = false;
                });
            },
            lastPage() {
                this.lockButtons = true;
                this.pagination.page = this.pagination.totalPages;
                this.crew = [];
                getShipCrewmates(this.currentShip, this.pagination.page, this.pagination.itemsPerPage).then((res) => {
                    this.crew = res.data.data;
                    this.pagination.totalPages = res.data.meta.totalPages;
                }).finally(() => {
                    this.lockButtons = false;
                });
            },
            prevPage() {
                this.lockButtons = true;
                this.pagination.page--;
                this.crew = [];
                getShipCrewmates(this.currentShip, this.pagination.page, this.pagination.itemsPerPage).then((res) => {
                    this.crew = res.data.data;
                    this.pagination.totalPages = res.data.meta.totalPages;
                }).finally(() => {
                    this.lockButtons = false;
                });
            },
            nextPage() {
                this.lockButtons = true;
                this.pagination.page++;
                this.crew = [];
                getShipCrewmates(this.currentShip, this.pagination.page, this.pagination.itemsPerPage).then((res) => {
                    this.crew = res.data.data;
                    this.pagination.totalPages = res.data.meta.totalPages;
                }).finally(() => {
                    this.lockButtons = false;
                });
            },
            showButtons() {
                return isCaptain();
            },
            reloadList() {
                this.pagination.page = 0;
                this.crew = [];
                getShipCrewmates(this.currentShip, this.pagination.page, this.pagination.itemsPerPage).then((res) => {
                    this.crew = res.data.data;
                    this.pagination.totalPages = res.data.meta.totalPages;
                });
            },
            showPopup(mate) {
              this.popup.title = this.$t("crewPage.popupRemove.title", [mate.name + " " + mate.lastName]);
              this.popup.display = true;
              this.popup.mate = mate;
            },
            popupCancel() {
                this.popup.display = false;
            },
            popupConfirm() {
                deleteCrewmate(this.popup.mate.id)
                    .then(() => {
                        this.pagination.page = 0;
                        getShipCrewmates(this.currentShip, this.pagination.page, this.pagination.itemsPerPage).then((res) => {
                            this.crew = res.data.data;
                            this.pagination.totalPages = res.data.meta.totalPages;
                        });
                    })
                    .finally(() => {
                        this.popup.display = false;
                    })
            },
            detailsRedirect(id) {
                this.$router.push({ name: 'crewDetails', params: { id: id } })
            },
            editRedirect(id) {
                this.$router.push({ name: 'crewEdit', params: { id: id } })
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
