<template>
  <v-card class="d-block pa-2 accent-4 black--text">
    <!-- <v-breadcrumbs :items="items">
      <template v-slot:item="{ item }">
        <v-breadcrumbs-item :href="item.href" :disabled="item.disabled">{{
          item.text.toUpperCase()
        }}</v-breadcrumbs-item>
      </template>
    </v-breadcrumbs> -->

    <v-card class="mx-auto" max-width="auto" outlined>
      <v-toolbar flat class="mt-6">
        <v-row>
          <v-col cols="6">
            <v-label>List Role</v-label>
          </v-col>
          <v-col cols="6">
            <v-select
              label="Pilih Hak Akses"
              v-model="data.id_role"
              for="id_role"
              :items="roles"
              item-value="id_role"
              item-text="roleName"
              v-on:change="changeAdmin"
            >
            </v-select>
          </v-col>
        </v-row>
      </v-toolbar>
      <v-simple-table dense class="d-flex flex-column-reverse">
        <thead>
          <tr class="_table">
            <th>Part</th>
            <th>Read</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="item in headers">
            <tr v-for="child in item.title" :key="child.part" class="dense">
              <td>{{ child.part }}</td>
              <td>
                <Checkbox v-model="child.read" color="primary"></Checkbox>
              </td>
            </tr>
          </template>
        </tbody>
        <div class="center_button">
          <Button
            type="primary"
            :loading="isSending"
            :disabled="isSending"
            @click="assignRole"
            >Assign</Button
          >
        </div>
      </v-simple-table>
      <v-simple-table dense class="d-flex flex-column-reverse">
        <thead>
          <tr class="_table">
            <th>Components</th>
            <th>Create</th>
            <th>Read</th>
            <th>Update</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="item in headers">
            <tr
              v-for="child in item.items"
              :key="child.component"
              class="dense"
            >
              <td>{{ child.component }}</td>
              <td>
                <Checkbox v-model="child.create" color="primary"></Checkbox>
              </td>
              <td>
                <Checkbox v-model="child.read" color="primary"></Checkbox>
              </td>
              <td>
                <Checkbox v-model="child.update" color="primary"></Checkbox>
              </td>
              <td>
                <Checkbox v-model="child.delete" color="primary"></Checkbox>
              </td>
            </tr>
          </template>
        </tbody>
        <div class="center_button">
          <Button
            type="primary"
            :loading="isSending"
            :disabled="isSending"
            @click="assignRole"
            >Assign</Button
          >
        </div>
      </v-simple-table>
    </v-card>
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      data: {
        id_role: "",
        permission: "",
      },
      isSending: false,
      row: null,
      roles: [],
      headers: [
        {
          items: [
            {
              component: "Home",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "/",
            },
          ],
          title: [{ part: "Dashboard", read: false, action: "mdi-home" }],
        },
        {
          items: [
            {
              component: "Transaksi",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "transaksi",
            },
            {
              component: "Pengeluaran",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "pengeluaran",
            },
          ],
          title: [
            { part: "Transaksi", read: false, action: "mdi-shuffle-variant" },
          ],
        },
        {
          items: [
            {
              component: "Tahun Akademik",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "tahun_akademik",
            },
            {
              component: "Tingkat",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "tingkat",
            },
            {
              component: "Jurusan",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "jurusan",
            },
            {
              component: "Kelas",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "kelas",
            },
            {
              component: "Pengguna",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "adminusers",
            },
          ],
          title: [
            { part: "Data Pendukung", read: false, action: "mdi-database" },
          ],
        },
        {
          items: [
            {
              component: "Siswa",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "siswa",
            },
            {
              component: "View Siswa",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "view_siswa",
            },
          ],
          title: [{ part: "Data Siswa", read: false, action: "mdi-face" }],
        },
        {
          items: [
            {
              component: "SPP",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "spp",
            },
            {
              component: "View SPP",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "view_spp",
            },
          ],
          title: [{ part: "Data Spp", read: false, action: "mdi-school" }],
        },
        {
          items: [
            {
              component: "Iuran",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "iuran",
            },
            {
              component: "View Iuran",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "view_iuran",
            },
          ],
          title: [{ part: "Data Iuran", read: false, action: "mdi-cash-usd" }],
        },
        {
          items: [
            {
              component: "Kategori",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "kategori",
            },
            {
              component: "Sub-Kategori",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "subkategori",
            },
          ],
          title: [{ part: "Data Kategori", read: false, action: "mdi-grid" }],
        },
        {
          items: [
            {
              component: "Role",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "role",
            },
            {
              component: "Role Permission",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "assignRole",
            },
          ],
          title: [
            { part: "Assign Role", read: false, action: "mdi-account-key" },
          ],
        },
        {
          items: [
            {
              component: "Laporan",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "laporan",
            },
          ],
          title: [
            { part: "Data Laporan", read: false, action: "mdi-book-multiple" },
          ],
        },
      ],
      defaultHeaders: [
        {
          items: [
            {
              component: "Home",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "/",
            },
          ],
          title: [{ part: "Dashboard", read: false, action: "mdi-home" }],
        },
        {
          items: [
            {
              component: "Transaksi",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "transaksi",
            },
            {
              component: "Pengeluaran",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "Pengeluaran",
            },
          ],
          title: [
            { part: "Transaksi", read: false, action: "mdi-shuffle-variant" },
          ],
        },
        {
          items: [
            {
              component: "Tahun Akademik",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "tahun_akademik",
            },
            {
              component: "Tingkat",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "tingkat",
            },
            {
              component: "Jurusan",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "jurusan",
            },
            {
              component: "Kelas",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "kelas",
            },
            {
              component: "Pengguna",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "adminusers",
            },
          ],
          title: [
            { part: "Data Pendukung", read: false, action: "mdi-database" },
          ],
        },
        {
          items: [
            {
              component: "Siswa",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "siswa",
            },
            {
              component: "View Siswa",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "view_siswa",
            },
          ],
          title: [{ part: "Data Siswa", read: false, action: "mdi-face" }],
        },
        {
          items: [
            {
              component: "SPP",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "spp",
            },
            {
              component: "View SPP",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "view_spp",
            },
          ],
          title: [{ part: "Data Spp", read: false, action: "mdi-school" }],
        },
        {
          items: [
            {
              component: "Iuran",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "iuran",
            },
            {
              component: "View Iuran",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "view_iuran",
            },
          ],
          title: [{ part: "Data Iuran", read: false, action: "mdi-cash-usd" }],
        },
        {
          items: [
            {
              component: "Kategori",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "kategori",
            },
            {
              component: "Sub-Kategori",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "subkategori",
            },
          ],
          title: [{ part: "Data Kategori", read: false, action: "mdi-grid" }],
        },
        {
          items: [
            {
              component: "Role",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "role",
            },
            {
              component: "Role Permission",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "assignRole",
            },
          ],
          title: [
            { part: "Assign Role", read: false, action: "mdi-account-key" },
          ],
        },
        {
          items: [
            {
              component: "Laporan",
              create: false,
              read: false,
              update: false,
              delete: false,
              name: "laporan",
            },
          ],
          title: [
            { part: "Data Laporan", read: false, action: "mdi-book-multiple" },
          ],
        },
      ],
      items: [
        {
          text: "Home",
          disabled: false,
          href: "/",
        },
        {
          text: "Assign role",
          disabled: false,
          href: "/assignRole",
        },
      ],
    };
  },
  methods: {
    // async getRoles() {
    // const res = await this.callApi("get", "app/get_roles");
    // if (res.status == 200) {
    //   this.roles = res.data;
    //   if (res.data.length) {
    //     this.data.id_role = res.data[0].id_role;
    //     if (res.data[0].permission) {
    //       this.headers = JSON.parse(res.data[0].permission);
    //     }
    //   }
    // } else {
    //   this.e();
    // }
    // },
    async assignRole() {
      let data = JSON.stringify(this.headers);
      const res = await this.callApi("post", "app/assign_roles", {
        permission: data,
        id_role: this.data.id_role,
      });
      if (res.status == 200) {
        this.s("Component Role berhasil diupdate");
        let index = this.roles.findIndex(
          (role) => role.id_role == this.data.id_role
        );
        this.roles[index].permission = data;
      } else {
        this.e();
      }
    },
    changeAdmin() {
      let index = this.roles.findIndex(
        (role) => role.id_role == this.data.id_role
      );
      let permission = this.roles[index].permission;
      if (!permission) {
        this.headers = this.defaultHeaders;
      } else {
        this.headers = JSON.parse(permission);
      }
    },
  },
  async created() {
    const res = await this.callApi("get", "app/get_roles");
    if (res.status == 200) {
      this.roles = res.data;
      if (res.data.length) {
        this.data.id_role = res.data[0].id_role;
        if (res.data[0].permission) {
          this.headers = JSON.parse(res.data[0].permission);
        }
      }
    } else {
      this.e();
    }
    // const [user, r] = await Promise.all([
    //     this.callApi("get", "app/get_users"),
    //     this.callApi("get", "app/get_roles"),
    //   ]);
    //   if (user.status == 200) {
    //     this.users = user.data;
    //     this.role = r.data;
    //   } else {
    //     this.e();
    //   }
  },
};
</script>
