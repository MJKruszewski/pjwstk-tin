<template>
  <div class="col-12 container-background rounded-full" style="padding-top: 0">
    <div class="col-12">
      <h2>{{ $t('crewPage.title') }}</h2>
    </div>
    <div class="col-12 container-background rounded-full" style="margin-bottom: 10px">
      <div style="display: inline;float: right;">
        <button class="success" v-on:click="$router.push({ name: 'crewAdd' })">{{ $t('crewPage.add') }}</button>
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
        <tbody>
        <template v-for="mate in crew">
          <tr>
            <td>{{ mate.name }} {{ mate.lastName }}</td>
            <td>{{ $t("departments." + mate.department) }}</td>
            <td>todo</td>
            <td>
              <button class="info" v-on:click="detailsRedirect(mate.id)">{{ $t('crewPage.details') }}</button>
              <button class="warning" v-on:click="editRedirect(mate.id)">{{ $t('crewPage.edit') }}</button>
              <button class="danger" v-on:click="showPopup(mate)">{{ $t('crewPage.remove') }}</button>
            </td>
          </tr>
        </template>
        </tbody>
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
    import crewList from "../../mocks/crewList";
    import Popup from "./../utils/Popup";

    export default {
        name: "CrewPage",
        components: {Popup},
        data: () => {
            return {
                crew: crewList,
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
        },
        methods: {
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
</style>
