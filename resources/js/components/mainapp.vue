<template>
  <v-app id="inspire">
        <!--========== ADMIN SIDE MENU one ========-->
        <Layout>
          <div id="app" v-if="$store.state.user">
        <v-navigation-drawer v-model="drawer" app>
          <v-list dense>
            <v-list-item link to="/">
              <v-list-item-action>
                <v-icon>mdi-home-circle</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>Home</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item link to="/adminusers">
              <v-list-item-action>
                <v-icon>mdi-account-multiple</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>Admin Users</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item link to="/fare">
              <v-list-item-action>
                <v-icon>mdi-cash-usd</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>Tarif</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item link to="/upload">
              <v-list-item-action>
                <v-icon>mdi-cloud-upload</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>Upload</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item link to="/members">
              <v-list-item-action>
                <v-icon>mdi-wallet-membership</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>List Member</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item link to="/booking">
              <v-list-item-action>
                <v-icon>mdi-book-open</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>Booking</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
            <v-list-item a href="/logout">
              <v-list-item-action>
                <v-icon>mdi-logout</v-icon>
              </v-list-item-action>
              <v-list-item-content>
                <v-list-item-title>Logout</v-list-item-title>
              </v-list-item-content>
            </v-list-item>
          </v-list>
        </v-navigation-drawer>
        <!--========== ADMIN SIDE MENU ========-->

        <!--========= HEADER ==========-->
        <v-app-bar app color="blue lighten-3" dark>
          <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
          <v-toolbar-title>Application</v-toolbar-title>
        </v-app-bar>
        
        <!--========= FOOTER ==========-->
        <v-footer color="blue lighten-3" app>
          <span class="white--text">Guthe {{ new Date().getFullYear() }}</span>
        </v-footer>
          </div>
        <v-content class="mt-10 ml-10 mr-10 mb-auto d-flex">
        <router-view/>
        </v-content>
        </Layout>
  </v-app>
</template>


<script>
export default {
  props: 
    ["user"],
    source: String,
  data() {
    return {
      isLoggedIn: false,
      drawer: null,
    };
  },
  created() {
    this.$store.commit("updateUser", this.user);
  },
};
</script>
