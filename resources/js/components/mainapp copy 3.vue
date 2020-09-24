<template>
    <div class="layout">
  <Layout>
      <div v-if="$store.state.user">
        <!--========== ADMIN SIDE MENU one ========-->
        <div class="_1side_menu">
          <!--~~~~~~~~ MENU CONTENT ~~~~~~~~-->
          <div class="_1side_menu_content">
            <div class="_1side_menu_logo">
              <h3 style="text-align:center;">Logo</h3>
              <!--<img src="/img/logo.jpg" style="width: 108px;margin-left: 68px;"/>-->
            </div>

            <!--~~~ MENU LIST ~~~~~~-->
            <Layout>
            <Sider breakpoint="md" collapsible :collapsed-width="78" v-model="isCollapsed">
              <div theme="light" :class="menuitemClasses">
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
        <Header :style="{padding: 0}" class="layout-header-bar">
        <div class="header">
          <div class="_2menu _box_shadow">
            <div class="_2menu_logo">
              <ul class="open_button">
                <li>
                  <Icon type="md-menu" size="24"></Icon>
                </li>
              </ul>
            </div>
          </div>
        </div>
        </Header>
        <!--========= HEADER ==========-->
      </div>
      <Content :style="{margin: '20px', background: '#fff', minHeight: '260px'}">
      <router-view />
      </Content>
  </Layout>
    </div>
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
  computed: {
    menuitemClasses: function () {
      return ["menu-item", this.isCollapsed ? "collapsed-menu" : ""];
    },
  },
};
</script>
