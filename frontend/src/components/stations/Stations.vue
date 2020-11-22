<template>
  <div class="col-12 container-background rounded-full">
    <h2 style="padding: 0;margin-top: 0">
      {{ $t('mainPage.stations') }}
    </h2>

    <button class="info" v-on:click="reloadTasks">{{ $t('mainPage.reload') }}</button>
    <div style="display:inline;float: right">
      <button class="success" v-if="captainView" v-on:click="showCreate">{{ $t('mainPage.add') }}</button>
      <button class="success" v-if="allowSetStation" v-on:click="showAssign">{{ $t('mainPage.add') }}</button>
    </div>
    <br/>
    <br/>

    <table class="table-main">
      <thead>
      <tr>
        <th>
          {{ $t('mainPage.code') }}
        </th>
        <th>
          {{ $t('mainPage.department') }}
        </th>
        <th v-if="userView">
          {{ $t("mainPage.from") }}
        </th>
        <th v-if="userView">
          {{ $t("mainPage.to") }}
        </th>
        <th v-if="captainView || allowSetStation">
          {{ $t('mainPage.actions') }}
        </th>
      </tr>
      </thead>
      <tbody>
      <template v-for="item in stations">
        <tr>
          <td>
            {{ $t("mainPage." + item.code) }}
          </td>
          <td>
            {{ $t("mainPage." + item.department.code) }}
          </td>
          <td v-if="userView">
            {{ item.from }}
          </td>
          <td v-if="userView">
            {{ item.to }}
          </td>
          <td v-if="allowSetStation" width="15%">
            <button class="warning" v-if="item.to === null" v-on:click="terminate(item)">{{ $t('editCrew.checkOut') }}
            </button>
          </td>
          <td v-if="captainView" width="15%">
            <button class="warning" v-on:click="showEdit(item)">{{ $t('crewPage.edit') }}</button>
            <button class="danger" v-on:click="showPopupRemove(item)">{{ $t('departments.remove') }}</button>
          </td>
        </tr>
      </template>
      </tbody>
    </table>

    <PopupForm
      :title="popupForm.title"
      :display="popupForm.display"
      v-on:popup-confirm="saveConfirm"
      v-on:popup-cancel="saveCancel"
    >
      <table class="table-form">
        <tr>
          <td>
            <label for="code">{{ $t('mainPage.code') }}:</label>
          </td>
          <td>
            <input v-model="popupForm.code" type="text" id="code"/>
          </td>
        </tr>
        <tr>
          <td>
            <label for="department">{{ $t('editCrew.department') }}:</label>
          </td>
          <td>
            <select v-model="popupForm.department" id="department">
            </select>
          </td>
        </tr>
      </table>
    </PopupForm>

    <PopupForm
      :title="popupAssign.title"
      :display="popupAssign.display"
      v-on:popup-confirm="assignConfirm"
      v-on:popup-cancel="assignCancel"
    >
      <table class="table-form">
        <tr>
          <td>
            <label for="id">{{ $t('mainPage.code') }}:</label>
          </td>
          <td>
            <select id="id" v-model="popupAssign.id">
            </select>
          </td>
        </tr>
        <tr>
          <td>
            <label for="from">{{ $t('editCrew.from') }}:</label>
          </td>
          <td>
            <input type="date" v-model="popupAssign.from" id="from"/>
          </td>
        </tr>
      </table>
    </PopupForm>

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
    import Popup from "../utils/Popup";
    import PopupForm from "../utils/PopupForm";

    export default {
        name: 'Stations',
        components: {PopupForm, Popup},
        props: {
            captainView: false,
            allowSetStation: false,
            userView: false,
            stations: Array,
        },
        data() {
            return {
                popupForm: {
                    display: false,
                    title: null,
                    id: null,
                    code: null,
                    department: null,
                },
                popupAssign: {
                    display: false,
                    title: null,
                    id: null,
                    from: null,
                },
                popup: {
                    mate: '',
                    title: '',
                    description: '',
                    display: false,
                }
            }
        },
        methods: {
            terminate(item) {
                item.to = new Date();
            },
            popupCancel() {
                console.log(this.popup.mate)
                this.popup.display = false;
            },
            popupConfirm() {
                console.log(this.popup.mate)
            },
            assignCancel() {
                this.popupAssign.display = false;
            },
            assignConfirm() {
            },
            showCreate() {
                this.popupForm.title = this.$t('captainPage.addStation');
                this.popupForm.id = null;
                this.popupForm.code = null;
                this.popupForm.department = null;
                this.popupForm.display = true
            },

            showAssign() {
                this.popupAssign.title = this.$t('editPage.assign');
                this.popupAssign.id = null;
                this.popupAssign.from = null;
                this.popupAssign.display = true
            },

            showEdit(item) {
                this.popupForm.title = this.$t('captainPage.editStation');
                this.popupForm.id = item.id;
                this.popupForm.code = item.code;
                this.popupForm.department = item.department.id;
                this.popupForm.display = true
            },

            saveCancel() {
                console.log(this.popup.mate);
                this.popupForm.display = false;
            },
            saveConfirm() {
                console.log(this.popup.mate)
            },
            reloadTasks() {
                console.log('reloading')
            },
            showPopupRemove(item) {
                this.popup.title = this.$t("captainPage.removeStation");
                this.popup.display = true;
                this.popup.mate = mate;
            },
        }
    }
</script>
<style scoped>

  .table-form {
    margin-left: auto;
    margin-right: auto;
  }

  .table-main {
    border-collapse: collapse;
    width: 100%;
  }

  .table-main th, .table-main td {
    text-align: left;
    padding: 8px;
  }

  .table-main tr:nth-child(even) {
    background-color: #f2f2f2
  }

  .table-main th {
    background-color: #4a84af;
    color: white;
  }

  .table-main tr:hover {
    background-color: rgba(245, 245, 245, 0.21);
  }
</style>
