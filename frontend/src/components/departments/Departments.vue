<template>
  <div class="col-12 container-background rounded-full">
    <h2 style="padding: 0;margin-top: 0">
      {{ $t('mainPage.departments') }}
    </h2>

    <button class="info" v-on:click="reloadTasks">{{ $t('mainPage.reload') }}</button>
    <div style="display:inline;float: right">
      <button class="success" v-if="captainView" v-on:click="showCreate">{{ $t('mainPage.add') }}</button>
    </div>
    <br/>
    <br/>

    <table class="table-main">
      <thead>
        <tr>
          <th>
            {{ $t('mainPage.code') }}
          </th>
          <th v-if="captainView" width="15%">
            {{ $t('mainPage.actions') }}
          </th>
        </tr>
      </thead>
      <tbody>
      <template v-for="item in departments">
        <tr>
          <td >
            {{ $t("mainPage." + item.code) }}
          </td>
          <td v-if="captainView">
            <button class="warning" v-on:click="showEdit(item)">{{ $t('crewPage.edit') }}</button>
            <button class="danger" v-on:click="showPopup(item)">{{ $t('departments.remove') }}</button>
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
        name: 'Departments',
        components: {PopupForm, Popup},
        props: {
            showActions: false,
            captainView: false,
            departments: Array,
        },
        data() {
            return {
                popupForm: {
                    display: false,
                    title: null,
                    id: null,
                    code: null,
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
            popupCancel() {
                console.log(this.popup.mate)
                this.popup.display = false;
            },
            popupConfirm() {
                console.log(this.popup.mate)
            },
            showCreate() {
                this.popupForm.title = this.$t('captainPage.addDepartment');
                this.popupForm.id = null;
                this.popupForm.code = null;
                this.popupForm.department = null;
                this.popupForm.display = true
            },
            showEdit(item) {
                this.popupForm.title = this.$t('captainPage.editDepartment');
                this.popupForm.id = item.id;
                this.popupForm.code = item.code;
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
            showPopup(item) {
                this.popup.title = this.$t("captainPage.removeDepartment");
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
