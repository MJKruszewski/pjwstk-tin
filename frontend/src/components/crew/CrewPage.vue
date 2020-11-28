<template>
  <div class="col-12 container-background rounded-full" style="padding-top: 0">
    <div class="col-12">
      <h2>{{ $t('crewPage.title') }}</h2>
    </div>
    <div class="col-12 container-background rounded-full" style="margin-bottom: 10px">
      <label for="ship">{{ $t('crewPage.ship')}}</label>
      <select id="ship" v-model="currentShip" v-on:change="reloadList">
        <template v-for="item in this.ships">
          <option :value="item.id">
            {{ item.name }}
          </option>
        </template>
      </select>

      <div style="display: inline;float: right;">
        <button class="success" v-on:click="$router.push({ name: 'crewAdd', params: { showChart: true}})">{{ $t('crewPage.add') }}</button>
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
          <tr :key="'loading'" v-if="crew.length === 0">
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
                <button class="warning" v-on:click="editRedirect(mate.id)">{{ $t('crewPage.edit') }}</button>
                <button class="danger" v-on:click="showPopup(mate)">{{ $t('crewPage.remove') }}</button>
              </td>
            </tr>
          </template>
        </transition-group>
      </table>
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
    import {getShipCrewmates} from "../../api/crewmates";

    export default {
        name: "CrewPage",
        components: {Popup},
        data: () => {
            return {
                ships: [],
                currentShip: 0,
                crew: [],
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

            getShipCrewmates(50).then((res) => {
                this.crew = res.data.data
            })
        },
        methods: {
            reloadList() {
                getShipCrewmates(this.currentShip).then((res) => {
                    this.crew = res.data.data
                })
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
