<template>
  <v-card class="pa-2 accent-4 black--text" color="basil">
    <v-toolbar flat single-line class="mb-5 basil">
      <span class="headline">Proses Transaksi</span>
      <v-spacer></v-spacer>
      <v-autocomplete
        class="mt-8"
        label="Pilih NIS"
        v-model="data.nis"
        for="nis"
        :items="siswas"
        item-value="nis"
        item-text="nama"
        v-on:change="changeSiswa"
      >
      </v-autocomplete>
    </v-toolbar>
    <v-container>
      <v-card outlined v-model="listSiswa" v-show="listSiswa">
        <v-list-item three-line>
          <v-list-item-content>
            <v-list-item-title class="headline"> Data Siswa </v-list-item-title>
            <v-row>
              <v-col cols="12">
                <v-list-item>
                  <v-list-item-content>NIS :</v-list-item-content>
                  <v-list-item-content class="align-end">
                    {{ data.nis }}
                  </v-list-item-content>
                </v-list-item>
                <v-list-item>
                  <v-list-item-content>Nama Lengkap :</v-list-item-content>
                  <v-list-item-content class="align-end">
                    {{ data.nama }}
                  </v-list-item-content>
                </v-list-item>
                <v-list-item>
                  <v-list-item-content>Jurusan :</v-list-item-content>
                  <v-list-item-content class="align-end">
                    {{ data.nama_jurusan }}
                  </v-list-item-content>
                </v-list-item>
                <v-list-item>
                  <v-list-item-content>Kelas :</v-list-item-content>
                  <v-list-item-content class="align-end">
                    {{ data.tingkat }}
                  </v-list-item-content>
                </v-list-item>
                <v-list-item>
                  <v-list-item-content>TTL :</v-list-item-content>
                  <v-list-item-content class="align-end">
                    {{ data.tempat_lahir }} ,
                    {{ data.tgl_lahir | formatDate }}
                  </v-list-item-content>
                </v-list-item>
              </v-col>
            </v-row>
          </v-list-item-content>

          <v-list-item-avatar tile persistent color="grey">
            <img :src="`/uploads/${data.foto}`" />
          </v-list-item-avatar>
        </v-list-item>

        <v-card-actions class="ml-3 mb-3">
          <v-dialog v-model="addModal" persistent max-width="600px">
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                depressed
                x-small
                class="light-blue white--text"
                dark
                v-bind="attrs"
                v-on="on"
                @click="showEditModal();getTransaksi()"
              >
                <v-icon x-small>mdi-plus</v-icon>
                <span>Transaksi Spp</span>
              </v-btn>
            </template>
            <v-card v-model="addModal">
              <v-card-title>
                <span class="headline">Transaksi Pembayaran Spp</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12">
                      <v-text-field
                        readonly
                        v-model="dataSpp.nis"
                        for="nis"
                        label="NIS*"
                        type="text"
                        v-if="dataSpp.nis"
                      ></v-text-field>
                    </v-col>
                    <v-col cols="12">
                      <v-menu
                        v-model="menu2"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        min-width="auto"
                      >
                        <template v-slot:activator="{ on, attrs }">
                          <v-text-field
                            v-model="dataSpp.tgl_bayar"
                            label="Pilih Tanggal Pembayaran"
                            prepend-icon="mdi-calendar"
                            hint="YYYY/MM/DD format"
                            readonly
                            v-bind="attrs"
                            v-on="on"
                          ></v-text-field>
                        </template>
                        <v-date-picker
                          v-model="dataSpp.tgl_bayar"
                          @input="menu2 = false"
                        ></v-date-picker>
                      </v-menu>
                    </v-col>
                    <v-col cols="12">
                      <v-select
                        label="Pilih Tipe Pembayaran"
                        v-model="dataSpp.id_spp"
                        for="id_spp"
                        :items="spp"
                        item-value="id_spp"
                        item-text="jumlah"
                      >
                      </v-select>
                    </v-col>
                    <v-col cols="12">
                      <v-select
                        label="Pilih Tahun Akademik"
                        v-model="dataSpp.id_tahun_akademik"
                        for="id_tahun_akademik"
                        :items="tahun_akademik"
                        item-value="id_tahun_akademik"
                        :item-text="
                          (tahun_akademik) =>
                            tahun_akademik.nama_tahun +
                            '  --  ' +
                            tahun_akademik.keterangan
                        "
                      >
                      </v-select>
                    </v-col>
                    <v-col cols="12">
                      <v-select
                        label="Pilih Tingkat"
                        v-model="dataSpp.id_tingkat"
                        for="id_tingkat"
                        :items="tingkat"
                        item-value="id_tingkat"
                        item-text="tingkat"
                      >
                      </v-select>
                    </v-col>
                    <v-col cols="12">
                      <v-select
                        label="Pilih Jurusan"
                        v-model="dataSpp.id_jurusan"
                        for="id_jurusan"
                        :items="jurusan"
                        item-value="id_jurusan"
                        item-text="nama_jurusan"
                      >
                      </v-select>
                    </v-col>
                  </v-row>
                </v-container>
                <small>*haris diisi</small>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  type="default"
                  color="blue darken-1"
                  text
                  @click="addModal = false; listSiswa = false"
                  >Batal</v-btn
                >
                <v-btn
                  color="blue darken-2"
                  text
                  @click="transaksiSpp();listSiswa = false"
                  :disabled="isAdding"
                  :loading="isAdding"
                  >{{ isAdding ? "Adding.." : "Add Transaksi" }}</v-btn
                >
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-card-actions>
      </v-card>
    </v-container>
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      data: "null",
      dataSpp: {
        tgl_bayar: "",
        id_spp: "",
        id_tahun_akademik: "",
        id_tingkat: "",
        id_jurusan: "",
      },
      attrs: {
        class: "mb-6",
        boilerplate: true,
        elevation: 2,
      },
      // dataSpp: {
      //   nis:"",
      //   tgl_bayar: "",
      //   id_spp: "",
      //   id_tahun_akademik: "",
      //   id_tingkat: "",
      // },
      addModal: false,
      editModal: false,
      isAdding: false,
      siswas: [],
      transactions: [],
      spp: [],
      tahun_akademik: [],
      tingkat: [],
      jurusan: [],
      index: -1,
      listSiswa: false,
      date: new Date().toISOString().substr(0, 10),
      menu2: false,
    };
  },
  methods: {
    async getTransaksi() {
      const [spp, a, t, res, j] = await Promise.all([
        //this.callApi("get", "app/get_transaksi"),
        this.callApi("get", "app/get_spp"),
        this.callApi("get", "app/get_tahun_akademik"),
        this.callApi("get", "app/get_tingkat"),
        this.callApi("get", "app/get_siswa"),
        this.callApi("get", "app/get_jurusan"),
      ]);
      if (res.status == 200) {
        //this.transactions = tr.data;
        this.spp = spp.data;
        this.tahun_akademik = a.data;
        this.tingkat = t.data;
        this.jurusan = j.data;
        this.siswas = res.data;
        if (res.data.length) {
          this.data = res.data;
        }
      } else {
        this.e();
      }
    },
    changeSiswa() {
      let index = this.siswas.findIndex((siswa) => siswa.nis == this.data.nis);
      let murid = this.siswas[index];
      this.data = murid;
      this.listSiswa = true;
    
      console.log(murid);
    },
    showEditModal() {
      let index = this.siswas.findIndex((siswa) => siswa.nis == this.data.nis);
      let obj = {
        nis: this.data.nis,
        id_tahun_akademik: this.data.id_tahun_akademik,
        id_tingkat: this.data.id_tingkat,
        id_jurusan: this.data.id_jurusan,
      };
    //  this.data = murid;
    //  this.listSiswa = true;
      this.dataSpp = obj;
      this.addModal = true;
      console.log(obj);
    },
    async transaksiSpp() {
      if (this.dataSpp.tgl_bayar.trim() == "") {
        return this.swr("Tanggal tidak boleh kosong!!");
      }
      const res = await this.callApi("post", "app/create_transaksiSpp", this.dataSpp);
      if (res.status === 201) {
        this.transactions.unshift(res.dataSpp);
        //  this.siswas.findIndex((siswa) => siswa.nis == this.data.nis);
        this.dataSpp.nis = "";
        this.dataSpp.tgl_bayar = "";
        this.dataSpp.id_spp = "";
        this.dataSpp.id_tahun_akademik = "";
        this.dataSpp.id_tingkat = "";
        this.dataSpp.id_jurusan = "";
        //this.siswas[this.index].nis = this.dataSpp.nis;
        // this.transactions[this.index].tgl_bayar = this.dataSpp.tgl_bayar;
        // this.transactions[this.index].id_spp = this.dataSpp.id_spp;
        // this.transactions[this.index].id_tahun_akademik = this.dataSpp.id_tahun_akademik;
        // this.transactions[this.index].id_tingkat = this.dataSpp.id_tingkat;
        this.s("Transaksi SPP berhasil ditambahkan");
        this.addModal = false;
      } else {
        if (res.status === 422) {
          if (res.dataSpp.errors.nis) {
            this.e(res.dataSpp.errors.nis);
          } else if (res.dataSpp.errors.tgl_bayar) {
            this.e(res.dataSpp.errors.tgl_bayar);
          } else if (res.dataSpp.errors.id_spp) {
            this.e(res.dataSpp.errors.id_spp);
          } else if (res.dataSpp.errors.id_tahun_akademik) {
            this.e(res.dataSpp.errors.id_tahun_akademik);
          } else if (res.dataSpp.errors.id_tingkat) {
            this.e(res.dataSpp.errors.id_tingkat);
          }
        } else {
          this.swr();
        }
      }

    },
    // showEditModal() {
    //   let index = this.siswas.findIndex((siswa) => siswa.nis == this.data.nis);
    //   let obj = this.siswas[index];
    //   this.data = obj;
    //   this.addModal = true;
    //   this.index = index;
    //   this.isEditingItem = true;
    // },
  },
  async created() {
    const res = await this.callApi("get", "app/get_siswa");
    if (res.status == 200) {
      this.siswas = res.data;
      if (res.data.length) {
        this.data = res.data;
      }//this.tahun_akademiks = res.data;
    } else {
      this.e();
    }
  },
  // async created() {
  //   const [spp, a, t, res] = await Promise.all([
  //     this.callApi("get", "app/get_spp"),
  //     this.callApi("get", "app/get_tahun_akademik"),
  //     this.callApi("get", "app/get_tingkat"),
  //     this.callApi("get", "app/get_siswa"),
  //   ]);
  //   if (spp.status == 200) {
  //     this.spp = spp.data;
  //     this.tahun_akademik = a.data;
  //     this.tingkat = t.data;
  //     this.siswas = res.data;
  //     if (res.data.length) {
  //       this.data = res.data;
  //     }
  //   } else {
  //     this.e();
  //   }
  // },
};
</script>

