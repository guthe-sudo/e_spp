<template>
  <v-card class="d-block pa-2 accent-4 black--text basil">
    <v-breadcrumbs :items="items">
      <template v-slot:item="{ item }">
        <v-breadcrumbs-item :href="item.href" :disabled="item.disabled">{{
          item.text.toUpperCase()
        }}</v-breadcrumbs-item>
      </template>
    </v-breadcrumbs>
    <v-toolbar flat class="basil">
      <span> List Tahun Akademik </span>
      <v-spacer></v-spacer>
      <v-dialog v-model="addModal" persistent max-width="600px">
        <template v-slot:activator="{ on, attrs }">
          <v-btn
            depressed
            x-small
            class="green white--text"
            dark
            v-bind="attrs"
            v-on="on"
          >
            <v-icon x-small>mdi-plus</v-icon>
            <span>Add</span>
          </v-btn>
        </template>

        <v-card v-model="addModal">
          <v-card-title>
            <span class="headline">Add Tahun Akademik</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    v-model="data.nama_tahun"
                    for="nama_tahun"
                    label="Tahun Akademik*"
                    type="text"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    v-model="data.keterangan"
                    for="keterangan"
                    label="Keterangan*"
                    type="text"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <label>Pilih Status</label>
                  <v-radio-group v-model="data.aktif" row>
                    <v-radio label="Ya" value="Ya"></v-radio>
                    <v-radio label="Tidak" value="Tidak"></v-radio>
                  </v-radio-group>
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
              @click="addModal = false"
              >Batal</v-btn
            >
            <v-btn
              color="blue darken-2"
              text
              @click="addTahun_akademik(); getTahun_akademik()"
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Adding.." : "Add tahun_akademik" }}</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-toolbar>
    
    <v-card class="mx-auto" max-width="auto" outlined>
      <v-simple-table dense class="d-flex flex-column-reverse">
        <thead>
          <tr class="_table">
            <th class="text-center">NO</th>
            <th class="text-center">Nama Tahun Akademik</th>
            <th class="text-center">Keterangan</th>
            <th class="text-center">Aktif</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(tahun_akademik, i) in tahun_akademiks" :key="i">
            <td class="text-center">{{ i + 1 }}</td>
            <td class="text-center" hidden>{{tahun_akademik.id_tahun_akademik}}</td>
            <td class="text-center">{{ tahun_akademik.nama_tahun }}</td>
            <td class="text-center">{{ tahun_akademik.keterangan }}</td>
            <td class="text-center">
              {{ tahun_akademik.aktif }}
            </td>
            <td class="text-center">
              <v-btn
                depressed
                x-small
                class="light-blue white--text"
                @click="showEditModal(tahun_akademik, i)"
              >
                <v-icon small>mdi-pencil</v-icon>
                <span>Edit</span>
              </v-btn>
              <v-btn
                depressed
                x-small
                class="red white--text"
                @click="showDeletingModal(tahun_akademik, i)"
                :loading="tahun_akademik.isDeleting"
              >
                <v-icon small>mdi-trash-can-outline</v-icon>
                <span>Delete</span>
              </v-btn>
            </td>
          </tr>
        </tbody>
      </v-simple-table>
    </v-card>
    
    <v-dialog v-model="editModal" persistent max-width="550px">
      <v-card v-model="editModal">
        <v-card-title>
          <span class="headline">Edit tahun_akademik</span>
        </v-card-title>
        <v-card-text>
          <v-container>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    v-model="editData.nama_tahun"
                    for="nama_tahun"
                    label="Tahun Akademik*"
                    type="text"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    v-model="editData.keterangan"
                    for="keterangan"
                    label="Keterangan*"
                    type="text"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <label>Pilih Status Aktif</label>
                  <v-radio-group v-model="editData.aktif" row>
                    <v-radio label="Ya" value="Ya"></v-radio>
                    <v-radio label="Tidak" value="Tidak"></v-radio>
                  </v-radio-group>
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
            @click="editModal = false"
            >Batal</v-btn
          >
          <v-btn
            color="blue darken-2"
            text
            @click="editTahun_akademik"
            :disabled="isAdding"
            :loading="isAdding"
            >{{ isAdding ? "Adding.." : "Edit Tahun Akademik" }}</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
    <Modal v-model="showDeleteModal" width="360" :styles="{ top: '20px' }">
      <p slot="header" style="color: #f60; text-align: center">
        <Icon type="ios-information-circle"></Icon>
        <span>Delete confirmation</span>
      </p>
      <div style="text-align: center">
        <p>Apakah anda yakin akan menghapus tahun akademik ini?</p>
      </div>
      <div slot="footer">
        <Button
          type="error"
          size="small"
          long
          :loading="isDeleting"
          :disabled="isDeleting"
          @click="deleteTahun_akademik"
          >Delete</Button
        >
      </div>
    </Modal>
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      data: {
        nama_tahun: "",
        keterangan: "",
        aktif: "Ya",
      },
      row: null,
      addModal: false,
      editModal: false,
      isAdding: false,
      tahun_akademiks: [],
      editData: {
        nama_tahun: "",
        keterangan: "",
        aktif: "Ya",
      },
      index: -1,
      showDeleteModal: false,
      isDeleting: false,
      deleteItem: {},
      DeletingIndex: -1,
      items: [
        {
          text: "Home",
          disabled: false,
          href: "/",
        },
        {
          text: "Tahun Akademik",
          disabled: false,
          href: "/tahun_akademik",
        },
      ],
    };
  },
  methods: {
    async getTahun_akademik() {
    const res = await this.callApi("get", "app/get_tahun_akademik");
    if (res.status == 200) {
      this.tahun_akademiks = res.data;
    } else {
      this.e();
    }
    },
    async addTahun_akademik() {
      if (this.data.nama_tahun.trim() == "") {
        return this.swr("Tahun akademik tidak boleh kosong!!");
      } else if (this.data.keterangan.trim() == "") {
        return this.swr("Keterangan tidak boleh kosong!!");
      } else if (this.data.aktif.trim() == "") {
        return this.swr("Aktif tidak boleh kosong!!");
      }
      const res = await this.callApi(
        "post",
        "app/create_tahun_akademik",
        this.data
      );

      if (res.status === 201) {
        this.tahun_akademiks.unshift(res.data);
        this.s("tahun_akademik berhasil ditambahkan");
        this.addModal = false;
        this.data.nama_tahun = "";
        this.data.keterangan = "";
        this.data.aktif = "";
      } else {
        if (res.status === 422) {
          if (res.data.errors.nama_tahun) {
            this.e(res.data.errors.nama_tahun[0]);
          } else if (res.data.errors.keterangan) {
            this.e(res.data.errors.keterangan[0]);
          } else if (res.data.errors.aktif) {
            this.e(res.data.errors.aktif[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    async editTahun_akademik() {
      if (this.editData.nama_tahun.trim() == "") {
        return this.swr("Tahun akademik tidak boleh kosong!!");
      } else if (this.editData.keterangan.trim() == "") {
        return this.swr("Keterangan tidak boleh kosong!!");
      } else if (this.editData.aktif.trim() == "") {
        return this.swr("Aktif tidak boleh kosong!!");
      }
      const res = await this.callApi(
        "post",
        "app/edit_tahun_akademik",
        this.editData
      );

      if (res.status === 200) {
        this.tahun_akademiks[this.index].nama_tahun = this.editData.nama_tahun;
        this.tahun_akademiks[this.index].keterangan = this.editData.keterangan;
        this.tahun_akademiks[this.index].aktif = this.editData.aktif;
        this.s("tahun_akademik berhasil diubah");
        this.editModal = false;
      } else {
        if (res.status === 422) {
          if (res.data.errors.nama_tahun) {
            this.e(res.data.errors.nama_tahun[0]);
          } else if (res.data.errors.keterangan) {
            this.e(res.data.errors.keterangan[0]);
          } else if (res.data.errors.aktif) {
            this.e(res.data.errors.aktif[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(tahun_akademik, index) {
      let obj = {
        nama_tahun: tahun_akademik.nama_tahun,
        keterangan: tahun_akademik.keterangan,
        aktif: tahun_akademik.aktif,
      };
      this.editData = obj;
      this.editModal = true;
      this.index = index;
    },
    async deleteTahun_akademik() {
      this.isDeleting = true;
      const res = await this.callApi(
        "post",
        "app/delete_tahun_akademik",
        this.deleteItem
      );
      if (res.status === 200) {
        this.tahun_akademiks.splice(this.DeletingIndex, 1);
        this.s("Tahun akademik berhasil dihapus");
      } else {
        this.swr();
      }
      this.isDeleting = false;
      this.showDeleteModal = false;
    },
    showDeletingModal(tahun_akademik, i) {
      this.deleteItem = tahun_akademik;
      this.DeletingIndex = i;
      this.showDeleteModal = true;
    },
  },
   async created() {
    const res = await this.callApi("get", "app/get_tahun_akademik");
    if (res.status == 200) {
      this.tahun_akademiks = res.data;
    } else {
      this.e();
    }
  },
};
</script>

