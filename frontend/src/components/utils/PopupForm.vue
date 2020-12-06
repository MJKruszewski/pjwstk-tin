<template>
  <div v-if="display" class="popup-background">
    <div class="popup-box rounded-full">
      <div >
        <b>{{ title }}</b>
      </div>
      <div style="font-size: 12px;margin-top: 30px;">
        <slot></slot>
      </div>
      <div style="padding-top: 14%">
        <button :disabled="lockButton" :class="{ 'disabled-button': lockButton}"  class="success" v-on:click="$emit('popup-confirm')">{{ $t("popup.confirm") }}</button>
        <button :disabled="lockButton" :class="{ 'disabled-button': lockButton}"  class="danger" v-on:click="$emit('popup-cancel')">{{ $t("popup.cancel") }}</button>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        name: "PopupForm",
        props: {
            title: String,
            display: Boolean,
            lockButton: Boolean,
        }
    }
</script>

<style scoped>
  .popup-box {
    background-color: #c1c9ccc9;
    width: 600px;
    height: auto;
    text-align: center;

    margin: 10% auto auto;
    padding: 10px;
  }
  .popup-background {
    top: 0;
    left: 0;
    position: fixed;
    background-color: rgba(43, 49, 55, 0.61);
    width: 100%;
    height: 100%;
    text-align: center;
  }
</style>
