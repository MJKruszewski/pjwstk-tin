<template>
  <div class="col-12 container-background rounded-full">
    <h2 style="padding: 0;margin-top: 0">
      {{ $t('mainPage.departments') }}
    </h2>

    <button class="info" v-on:click="$emit('reload')">{{ $t('mainPage.reload') }}</button>
    <div style="display:inline;float: right">
      <button class="success" v-if="captainView" v-on:click="showCreate">{{ $t('mainPage.add') }}</button>
    </div>
    <br/>
    <br/>

    <table class="table-main">
      <thead>
        <tr>
          <th>
            {{ $t('mainPage.name') }}
          </th>
          <th>
            {{ $t('mainPage.code') }}
          </th>
          <th v-if="captainView" width="15%">
            {{ $t('mainPage.actions') }}
          </th>
        </tr>
      </thead>
      <transition-group name="list" tag="tbody">
        <tr :key="'loading'" v-if="departments === null">
          <td>Loading ...</td>
          <td></td>
          <td></td>
          <td></td>
        </tr>
      <template v-for="item,key in departments">
        <tr :key="key">
          <td >
            {{ $t("departments." + item.code) }}
          </td>
          <td >
            {{ item.code }}
          </td>
          <td v-if="captainView">
            <button class="warning" v-on:click="showEdit(item)">{{ $t('crewPage.edit') }}</button>
            <button class="danger" v-on:click="showPopup(item)">{{ $t('departments.remove') }}</button>
          </td>
        </tr>
      </template>
      </transition-group>
    </table>

    <br/>
    <div style="text-align: center">
      <button style="float: left" v-on:click="$emit('prev')" :disabled="pagination.page <= 0" :class="{ 'disabled-button': pagination.page <= 0}">{{ $t('prev') }}</button>

      <div style="display: inline;text-align: center">
        {{ pagination.page + 1 }} / {{ pagination.totalPages + 1}}
      </div>

      <button v-on:click="$emit('next')" :class="{ 'disabled-button': pagination.page >= pagination.totalPages}" :disabled="pagination.page >= pagination.totalPages" style="float: right">{{ $t('next') }}</button>
    </div>

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
            <input v-model="popupForm.code" type="text" id="code" :class="{ 'validation-error': $v.popupForm.code.$error }"/>
            <div class="validation-error-text" v-if="!$v.popupForm.code.minLength">{{ $t('editCrew.validationMinLength', [$v.popupForm.code.$params.minLength.min]) }}</div>
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
    import {deleteDepartment, patchDepartment, postDepartment} from "../../api/departments";
    import { required, minLength, between } from 'vuelidate/lib/validators'

    export default {
        name: 'Departments',
        components: {PopupForm, Popup},
        props: {
            showActions: false,
            captainView: false,
            departments: Array,
            pagination: Object,
        },
        data() {
            return {
                lockButtons: false,
                popupForm: {
                    display: false,
                    createNew: false,
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
        validations: {
            popupForm: {
                code: {
                    required,
                    minLength: minLength(3),
                },
            },
        },
        methods: {
            prevPage() {
                this.$emit('prev')
            },
            nextPage() {
              this.$emit('next')
            },
            popupCancel() {
                console.log(this.popup.mate)
                this.popup.display = false;
            },
            popupConfirm() {
                this.lockButtons=true;
                deleteDepartment(this.popup.mate.id).then(() => {
                    this.$emit('reload')
                }).finally(()=>{
                    this.lockButtons=false;
                    this.popup.display = false;
                });
            },
            showCreate() {
                this.popupForm.title = this.$t('captainPage.addDepartment');
                this.popupForm.id = null;
                this.popupForm.code = null;
                this.popupForm.department = null;
                this.popupForm.display = true
                this.popupForm.createNew = true
            },
            showEdit(item) {
                this.popupForm.title = this.$t('captainPage.editDepartment');
                this.popupForm.id = item.id;
                this.popupForm.code = item.code;
                this.popupForm.display = true
                this.popupForm.createNew = false
            },
            saveCancel() {
                this.popupForm.display = false;
            },
            saveConfirm() {
                this.$v.popupForm.$touch();

                if (this.$v.popupForm.$invalid) {
                    return;
                }

                this.lockButtons=true;

                if (this.popupForm.createNew) {
                    postDepartment({
                        code: this.popupForm.code,
                    }).then(() => {
                        this.$emit('reload')
                    }).finally(()=>{
                        this.lockButtons=false;
                        this.popupForm.display = false;
                    });
                } else {
                    patchDepartment(this.popupForm.id, {
                        code: this.popupForm.code,
                    }).then(() => {
                        this.$emit('reload')
                    }).finally(()=>{
                        this.lockButtons=false;
                        this.popupForm.display = false;
                    });
                }
            },
            reloadTasks() {
                this.$emit('reload')
            },
            showPopup(item) {
                this.popup.title = this.$t("captainPage.removeDepartment");
                this.popup.display = true;
                this.popup.mate = item;
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
