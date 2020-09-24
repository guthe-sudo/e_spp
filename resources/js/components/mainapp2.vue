<template>
  <v-app id="inspire">
    <Layout>
      <div v-if="$store.state.user">
        <!--========== ADMIN SIDE MENU one ========-->
        <div class="_1side_menu">
          <!--~~~~~~~~ MENU CONTENT ~~~~~~~~-->
          <div class="_1side_menu_content">
            <Layout>
              <Sider breakpoint="md" collapsible :collapsed-width="0" v-model="isCollapsed">
            <div class="_1side_menu_logo">
              <h3>Logo</h3>
              <!--<img src="/img/logo.jpg" style="width: 108px;margin-left: 68px;"/>-->
            </div>

            <!--~~~ MENU LIST ~~~~~~-->
                <div :class="menuitemClasses">
                  <div class="_1side_menu_list">
                    <ul class="_1side_menu_list_ul">
                      <li>
                        <router-link to="/">
                          <Icon type="ios-speedometer-outline" />
                          <span>Dashboard</span>
                        </router-link>
                      </li>
                      <li>
                        <router-link to="/members">
                          <Icon type="ios-contact-outline" />
                          <span>Members</span>
                        </router-link>
                      </li>
                      <li>
                        <router-link to="/fare">
                          <Icon type="ios-calculator-outline" />
                          <span>Tarif</span>
                        </router-link>
                      </li>
                      <li>
                        <router-link to="/booking">
                          <Icon type="ios-clipboard-outline" />
                          <span>Booking</span>
                        </router-link>
                      </li>
                      <li>
                        <router-link to="/upload">
                          <Icon type="ios-cloud-upload-outline" />
                          <span>Uploads</span>
                        </router-link>
                      </li>
                      <li>
                        <router-link to="/adminusers">
                          <Icon type="ios-people-outline" />
                          <span>Admin Users</span>
                        </router-link>
                      </li>
                      <li>
                        <a href="/logout">
                          <Icon type="ios-exit-outline" />
                          <span>Logout</span>
                        </a>
                      </li>
                    </ul>
                  </div>
                </div>
              </Sider>
            </Layout>
          </div>
        </div>
        <!--========== ADMIN SIDE MENU ========-->

        <!--========= HEADER ==========-->
          <div class="layout-header-bar">
            <div class="_2menu _box_shadow">
              <div class="_2menu_logo">
                <ul class="open_button">
                  <li>
                    <Icon refs="" type="md-menu" size="20" @click="isCollapsed = false"/>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        <!--========= HEADER ==========-->
      </div>
    </Layout>    
    
    <Content class="layout">
      <router-view />
    </Content>
  </v-app>
</template>

<script>
export default {
  props: ["user"],
  data() {
    return {
      isLoggedIn: false,
      isCollapsed: false,
    };
  },
  created() {
    this.$store.commit("updateUser", this.user);
  },
};
</script>
