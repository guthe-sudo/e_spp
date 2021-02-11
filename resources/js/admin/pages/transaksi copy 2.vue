<template>
  <v-card class="pa-2 accent-4 black--text" color="basil">
    <v-banner single-line class="mb-5">
      <span class="headline">Proses Transaksi</span>
    </v-banner>

    <v-toolbar flat class="basil">
      <v-row>
        <v-col cols="6">List Role </v-col>
        <v-col cols="6">
          <v-select
            label="Pilih NIS"
            v-model="data.nis"
            for="nis"
            :items="siswas"
            item-value="nis"
            item-text="nama"
          >
          </v-select>
        </v-col>
      </v-row>
    </v-toolbar>
    <v-tabs v-model="tab" background-color="transparent" color="basil" grow>
      <v-tab class="pa-2 accent-4" v-for="item in items" :key="item.tab">
        {{ item.tab }}
      </v-tab>
    </v-tabs>

    <v-tabs-items :value="tab">
      <v-tab-item>
        <v-container>
          <v-row class="card1">
            <v-col cols="4"> Nama Transaksi </v-col>
            <v-col class="d-flex" cols="8">
              <v-select
                v-for="(n, i) in siswas"
                :value="n.nis"
                :key="i"
                v-model="data.nis"
                :items="n.nama"
                label="Pilih NIS"
                dense
                outlined
                for="nis"
              ></v-select>
            </v-col>
            <v-col cols="4"> Nama Transaksi </v-col>
            <v-col class="d-flex" cols="8">
              <v-select
                v-for="(n, i) in siswas"
                :value="n.nis"
                :key="i"
                v-model="data.nis"
                :items="n.nama"
                label="Pilih NIS"
                dense
                outlined
                for="nis"
              ></v-select>
            </v-col>
            <v-col cols="4"> Nama Transaksi </v-col>

            <v-col class="d-flex" cols="8">
              <v-select
                v-for="(n, i) in siswas"
                :value="n.nis"
                :key="i"
                v-model="data.nis"
                :items="n.nama"
                label="Pilih NIS"
                dense
                outlined
                for="nis"
              ></v-select>
            </v-col>
          </v-row>
        </v-container>
      </v-tab-item>
      <v-tab-item>
        <v-card-text> This is TWO!<br />2222222222 </v-card-text>
      </v-tab-item>
    </v-tabs-items>
  </v-card>
</template>

<script>
//import coba from './admin/pages/coba'
export default {
  data() {
    return {
      data: {
        nis: "",
      },
      isLoggedIn: false,
      isLogging: false,
      addTransaksi: false,
      siswas: [],
      tab: null,
      items: [{ tab: "Pembayaran SPP" }, { tab: "Pembayaran Iuran" }],
    };
  },
  methods: {
    async transaksi() {
      if (this.data.nis.trim() == "") return this.e("NIS tidak boleh kosong!!");
      this.isLoggedIn = true;
      const res = await this.callApi("post", "app/transaksi", this.data);
      if (res.status === 200) {
        this.s(res.data.msg);
        window.location = "/view_transaksi";
      } else {
        if (res.status === 401) {
          this.e(res.data.msg);
        } else if (res.status === 422) {
          for (let i in res.data.errors) {
            this.e(res.data.errors[i][0]);
          }
        } else {
          this.w();
        }
      }
      this.isLoggedIn = false;
    },
    changeAdmin() {
      let index = this.siswas.findIndex((siswa) => role.nis == this.data.nis);
      let permission = this.siswas[index].permission;
      if (!permission) {
        this.headers = this.defaultHeaders;
      } else {
        this.headers = JSON.parse(permission);
      }
    },
  },

  async created() {
    const res = await this.callApi("get", "app/get_siswa");
    if (res.status == 200) {
      this.siswas = res.data;
      if (res.data.length) {
        this.data.nis = res.data[0].nis;
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

