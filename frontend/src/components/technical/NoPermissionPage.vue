<template>
  <div class="col-12">
    <BackgroundImage/>

    <div style="margin-top: 150px"  class="col-12">
      <div class="col-2"></div>
      <div class="col-8 container-background rounded-full">
        <div style="text-align: center;">
          <h1><b>401</b> - Unauthorized</h1>
          <br/>
          <br/>
          <button class="info">{{ $t('notFoundPage.goBack') }}</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    import BackgroundImage from "./../utils/BackgroundImage";
    export default {
        name: "NoPermissionPage",
        components: {BackgroundImage}
    }
</script>

<style scoped>

</style>
