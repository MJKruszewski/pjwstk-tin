<template>
  <div class="col-12 ">
    <BackgroundImage/>
    <div class="col-12">
      <NavBar/>
    </div>

    <div class="col-12">
      <div class="col-2">
        <SideMenu/>
      </div>
      <div class="col-10">
        <transition name="fade">
          <router-view/>
        </transition>
      </div>
    </div>

  </div>
</template>

<script>
    import NavBar from "./utils/NavBar";
    import SideMenu from "./utils/SideMenu";
    import BackgroundImage from "./utils/BackgroundImage";
    export default {
        name: "SpaceshipPage",
        components: {BackgroundImage, SideMenu, NavBar}
    }
</script>

<style scoped>

</style>
