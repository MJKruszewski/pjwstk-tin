<template>
  <div class="col-12 container-background rounded-full">
    <div class="col-12" style="padding: 0;font-weight: bold">
      Menu
    </div>
    <div class="col-12" style="padding-top: 0">
      <ul style="padding-left: 0;line-height: 40px;">
        <template v-for="item in menuPositions">
          <li v-if="display(item)" v-on:click="$router.push({name: item.title})" class="rounded-full disable-default" :class="{ hovered: $route.meta.sideMenuName === item.title }">{{ $t("sideMenu."+item.title) }}</li>
        </template>
      </ul>
    </div>
  </div>
</template>

<script>
    import {isRolePresent} from "../../api/crewmates";

    export default {
        name: "SideMenu",
        methods: {
            display(item) {
                if (0 >= item.roles.length) {
                    return true;
                }

                return isRolePresent(item.roles);
            }
        },
        data: () => {
            return {
                menuPositions: [
                    {
                        title: 'summary',
                        roles: []
                    },
                    {
                        title: 'captainPanel',
                        roles: [
                            'captain',
                            'secretary',
                        ]
                    },
                    {
                        title: 'crewList',
                        roles: []
                    },
                    {
                        title: 'tasks',
                        roles: []
                    },
                    {
                        title: 'engineersRoom',
                        roles: [
                            'captain',
                            'engineer',
                            'programmer',
                            'calibrator',
                        ]
                    },
                ],
            }
        }
    }
</script>

<style scoped>
  .disable-default {
    vertical-align: middle;
    list-style-type: none;
    margin-top: 5px;
    height: 40px;
    background-color: #c1c9ccc9;
    text-align: center;
  }
  .disable-default:hover {
    cursor: pointer;
    background-color: #e4e5e6c9;
    -webkit-box-shadow: 9px 10px 3px -7px rgba(0,0,0,0.75);
    -moz-box-shadow: 9px 10px 3px -7px rgba(0,0,0,0.75);
    box-shadow: 9px 10px 3px -7px rgba(0,0,0,0.75);
  }
  .hovered {
    background-color: #e4e5e6c9;
    -webkit-box-shadow: 9px 10px 3px -7px rgba(0,0,0,0.75);
    -moz-box-shadow: 9px 10px 3px -7px rgba(0,0,0,0.75);
    box-shadow: 9px 10px 3px -7px rgba(0,0,0,0.75);
  }
</style>
