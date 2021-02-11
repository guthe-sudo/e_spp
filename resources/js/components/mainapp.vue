<template>
  <v-app id="inspire">
    <!--========== ADMIN SIDE MENU one ========-->
    <div class=".d-{breakpoint}-{value}">
      <div id="app" v-if="$store.state.user">
        <v-navigation-drawer v-model="drawer" app class="black-text">
          <v-sheet color="grey lighten-4" class="pa-4 upnav">
            <v-avatar color="blue lighten-3">
              <v-icon dark> mdi-account-circle </v-icon>
            </v-avatar>
            <div class="email">{{ user.email }}</div>
          </v-sheet>

          <v-divider></v-divider>
          <v-list shaped dense>
            <template v-for="item in permission">
            <v-list-group
              v-for="child in item.title"
              :key="child.part"
              :prepend-icon="child.action"
              no-action
              v-if="permission.length && child.read"
            >
              <template v-slot:activator>
                <v-list-item-title v-text="child.part"></v-list-item-title>
              </template>

              <v-list-item
                v-for="child in item.items"
                :key="child.component"
                :to="child.name"
                v-if="permission.length && child.read"
              >
                <v-list-item-content>
                  <v-list-item-title
                    v-text="child.component"
                  ></v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </v-list-group>
            </template>
          </v-list>
        </v-navigation-drawer>
        <!--========== ADMIN SIDE MENU ========-->

        <!--========= HEADER ==========-->
        <v-app-bar
          app
          color="blue lighten-3"
          dark
          class=".d-{breakpoint}-{value} black--text"
        >
          <v-app-bar-nav-icon
            @click.stop="drawer = !drawer"
          ></v-app-bar-nav-icon>
          <v-toolbar-title>APLIKASI E-SPP</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn icon a href="/logout" value="Logout">
            <v-icon>mdi-logout</v-icon>
          </v-btn>
        </v-app-bar>

        <!--========= FOOTER ==========-->
      </div>
      <v-main class=".d-{breakpoint}-{value}" color="red">
        <router-view />
      </v-main>
      <v-footer app class=".d-{breakpoint}-{value}" color="blue lighten-3">
        <v-spacer></v-spacer>
        <span>teguhPrayitno@{{ new Date().getFullYear() }}</span>
      </v-footer>
    </div>
  </v-app>
</template>


<script>
export default {
  props: ["user", "permission"],
  source: String,
  data() {
    return {
      isLoggedIn: false,
      drawer: null,
      items: [
        {
          action: "mdi-shuffle-variant",
          active: false,
          title: "Transaksi",
        },
        {
          action: "mdi-database",
          active: false,
          title: "Data Pendukung",
        },
        {
          action: "mdi-face",
          title: "Data Siswa",
        },
        {
          action: "mdi-school",
          title: "Master SPP",
        },
        {
          action: "mdi-cash-usd",
          title: "Master Iuran",
        },
        {
          action: "mdi-grid",
          title: "Data Kategori",
        },
        {
          action: "mdi-book-multiple",
          title: "Laporan",
        },
        {
          action: "mdi-account-key",
          title: "Assign Role",
        },
      ],
    };
  },
  created() {
    this.$store.commit("updateUser", this.user);
    //console.log(this.permission);
  },
};
</script>
