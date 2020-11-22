<template>
  <div class="col-12">
    <BackgroundImage/>

    <div style="margin-top: 150px"  class="col-12">
      <div class="col-4"></div>
      <div class="col-4 container-background rounded-full">
        <AddCrewPage :display-chart="false" :back-page="'LoginPage'"></AddCrewPage>
      </div>
    </div>
  </div>
</template>

<script>
    import BackgroundImage from "./../utils/BackgroundImage";
    import AddCrewPage from "../crew/AddCrewPage";
    export default {
        name: "RegisterPage",
        components: {AddCrewPage, BackgroundImage}
    }
</script>

<style scoped>

</style>
