<template>
  <div v-if="display" class="popup-background">
    <div class="popup-box rounded-full">
      <div >
        <b>{{ title }}</b>
      </div>
      <div style="font-size: 12px;margin-top: 30px">
        {{ description }}
      </div>
      <div style="padding-top: 14%">
        <button class="success" v-on:click="$emit('popup-confirm')">{{ $t("popup.confirm") }}</button>
        <button class="danger" v-on:click="$emit('popup-cancel')">{{ $t("popup.cancel") }}</button>
      </div>
    </div>
  </div>
</template>

<script>
    export default {
        name: "Popup",
        props: {
            title: String,
            description: String,
            display: Boolean,
        }
    }
</script>

<style scoped>
  .popup-box {
    background-color: #c1c9ccc9;
    width: 400px;
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
