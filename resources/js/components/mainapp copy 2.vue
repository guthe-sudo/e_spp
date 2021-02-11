<template>
  <v-app id="inspire">
    <!--========== ADMIN SIDE MENU one ========-->
    <div class=".d-{breakpoint}-{value}">
      <div id="app" v-if="$store.state.user">
        <v-navigation-drawer v-model="drawer" app>
          <v-list shaped dense>
            <v-list-group
              v-for="(menuItem, i) in permission"
              :key="i"
              :to="menuItem.name"
              v-if="permission.length && menuItem.read"
            >
              <template v-slot:activator>
                <v-list-item-content>
                  <v-list-item-title v-text="menuItem.component"></v-list-item-title>
                </v-list-item-content>
              </template>
              <!-- <v-list-item
                v-for="(child, i) in item.permission"
                :key="i"
                :to="child.name"
              >
                <v-list-item-content>
                  <v-list-item-title v-text="child.headers"></v-list-item-title>
                </v-list-item-content>
              </v-list-item> -->
            </v-list-group>
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
        <v-app-bar
          app
          color="blue lighten-3"
          dark
          class=".d-{breakpoint}-{value}"
        >
          <v-app-bar-nav-icon
            @click.stop="drawer = !drawer"
          ></v-app-bar-nav-icon>
          <v-toolbar-title>APLIKASI E-SPP</v-toolbar-title>
        </v-app-bar>

        <!--========= FOOTER ==========-->
      </div>
      <v-main class="mt-10 ml-10 mr-10 mb-10 .d-{breakpoint}-{value}">
        <router-view />
      </v-main>
      <v-footer app class=".d-{breakpoint}-{value}" color="blue lighten-3">
        <span class="white--text">Guthe {{ new Date().getFullYear() }}</span>
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
      // items: [
      //   {
      //     action: "mdi-database",
      //     active: false,
      //     items: false,
      //     title: "Dashboard",
      //   },
      //   {
      //     action: "mdi-database",
      //     active: false,
      //     items: [
      //       { title: "Tahuh Akademik", link: "/tahun_akademik" },
      //       { title: "Tingkat", link: "/tingkat" },
      //       { title: "Kelas", link: "/kelas" },
      //       { title: "Program Kahlian", link: "/jurusan" },
      //       { title: "User Pengguna", link: "/adminUsers" },
      //     ],
      //     title: "Data Pendukung",
      //   },
      //   {
      //     action: "mdi-face",
      //     items: [
      //       { title: "Lihat Siswa", link: "/view_siswa" },
      //       { title: "Input Siswa", link: "/siswa" },
      //     ],
      //     title: "Data Siswa",
      //   },
      //   {
      //     action: "mdi-school",
      //     items: [
      //       { title: "Lihat SPP", link: "/view_spp" },
      //       { title: "Input SPP", link: "/spp" },
      //     ],
      //     title: "Master SPP",
      //   },
      //   {
      //     action: "mdi-cash-usd",
      //     items: [
      //       { title: "Lihat Iuran", link: "/view_iuran" },
      //       { title: "Input Iuran", link: "/iuran" },
      //     ],
      //     title: "Master Iuran",
      //   },
      //   {
      //     action: "mdi-grid",
      //     items: [
      //       { title: "Kategori", link: "/kategori" },
      //       { title: " Sub Kategori", link: "/subkategori" },
      //     ],
      //     title: "Data Kategori",
      //   },
      //   {
      //     action: "mdi-book-multiple",
      //     items: [
      //       { title: "Tunggakan", link: "/tunggakan" },
      //       { title: "Pemasukan", link: "/pemasukan" },
      //     ],
      //     title: "Laporan",
      //   },
      //   {
      //     action: "mdi-book-multiple",
      //     items: [
      //       { title: "Role", link: "/role" },
      //       { title: "Role manajemen", link: "/assignRole" },
      //     ],
      //     title: "Role",
      //   },
      // ],
    };
  },
  created() {
    this.$store.commit("updateUser", this.user);
    console.log(this.permission);
  },
};
</script>
