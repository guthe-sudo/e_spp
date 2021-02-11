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
      <span> List Program Keahlian</span>
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
            <span>Add Jurusan</span>
          </v-btn>
        </template>

        <v-card v-model="addModal">
          <v-card-title>
            <span class="headline">Add Program Keahlian</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    v-model="data.nama_jurusan"
                    for="nama_jurusan"
                    label="Jurusan*"
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
              @click="addJurusan(); getJurusan()"
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Adding.." : "Add Program Keahlian" }}</v-btn
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
            <th class="text-center">Program Keahlian</th>
            <th class="text-center">Aktif</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(jurusan, i) in jurusans" :key="i">
            <td class="text-center">{{ i + 1 }}</td>
            <td class="text-center" hidden>{{jurusan.id_jurusan}}</td>
            <td class="text-center">{{ jurusan.nama_jurusan }}</td>
            <td class="text-center">
              {{ jurusan.aktif }}
            </td>
            <td class="text-center">
              <v-btn
                depressed
                x-small
                class="light-blue white--text"
                @click="showEditModal(jurusan, i)"
              >
                <v-icon small>mdi-pencil</v-icon>
                <span>Edit</span>
              </v-btn>
              <v-btn
                depressed
                x-small
                class="red white--text"
                @click="showDeletingModal(jurusan, i)"
                :loading="jurusan.isDeleting"
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
          <span class="headline">Edit Jurusan</span>
        </v-card-title>
        <v-card-text>
          <v-container>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    v-model="editData.nama_jurusan"
                    for="nama_jurusan"
                    label="Jurusan*"
                    type="text"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <label>Pilih Status</label>
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
            @click="editJurusan"
            :disabled="isAdding"
            :loading="isAdding"
            >{{ isAdding ? "Adding.." : "Edit Program Keahlian" }}</v-btn
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
        <p>Apakah anda yakin akan menghapus data ini?</p>
      </div>
      <div slot="footer">
        <Button
          type="error"
          size="small"
          long
          :loading="isDeleting"
          :disabled="isDeleting"
          @click="deleteJurusan"
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
        nama_jurusan: "",
        aktif: "Ya",
      },
      row: null,
      addModal: false,
      editModal: false,
      isAdding: false,
      jurusans: [],
      editData: {
        nama_jurusan: "",
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
          text: "Program Keahlian",
          disabled: false,
          href: "/jurusan",
        },
      ],
    };
  },
  methods: {
    async getJurusan() {
    const res = await this.callApi("get", "app/get_jurusan");
    if (res.status == 200) {
      this.jurusans = res.data;
    } else {
      this.e();
    }
    },
    async addJurusan() {
      if (this.data.nama_jurusan.trim() == "") {
        return this.swr("Jurusan tidak boleh kosong!!");
      } else if (this.data.aktif.trim() == "") {
        return this.swr("Aktif tidak boleh kosong!!");
      }
      const res = await this.callApi(
        "post",
        "app/create_jurusan",
        this.data
      );

      if (res.status === 201) {
        this.jurusans.unshift(res.data);
        this.s("Jurusan berhasil ditambahkan");
        this.addModal = false;
        this.data.nama_jurusan = "";
        this.data.aktif = "";
      } else {
        if (res.status === 422) {
          if (res.data.errors.nama_jurusan) {
            this.e(res.data.errors.nama_jurusan[0]);
          } else if (res.data.errors.aktif) {
            this.e(res.data.errors.aktif[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    async editJurusan() {
      if (this.editData.nama_jurusan.trim() == "") {
        return this.swr("Nama Jurusan tidak boleh kosong!!");
      } else if (this.editData.aktif.trim() == "") {
        return this.swr("Aktif tidak boleh kosong!!");
      }
      const res = await this.callApi(
        "post",
        "app/edit_jurusan",
        this.editData
      );

      if (res.status === 200) {
        this.jurusans[this.index].nama_jurusan = this.editData.nama_jurusan;
        this.jurusans[this.index].aktif = this.editData.aktif;
        this.s("Jurusan berhasil diubah");
        this.editModal = false;
      } else {
        if (res.status === 422) {
          if (res.data.errors.nama_jurusan) {
            this.e(res.data.errors.nama_jurusan[0]);
          } else if (res.data.errors.aktif) {
            this.e(res.data.errors.aktif[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(jurusan, index) {
      let obj = {
        nama_jurusan: jurusan.nama_jurusan,
        aktif: jurusan.aktif,
      };
      this.editData = obj;
      this.editModal = true;
      this.index = index;
    },
    async deleteJurusan() {
      this.isDeleting = true;
      const res = await this.callApi(
        "post",
        "app/delete_jurusan",
        this.deleteItem
      );
      if (res.status === 200) {
        this.jurusans.splice(this.DeletingIndex, 1);
        this.s("Jurusan berhasil dihapus");
      } else {
        this.swr();
      }
      this.isDeleting = false;
      this.showDeleteModal = false;
    },
    showDeletingModal(jurusan, i) {
      this.deleteItem = jurusan;
      this.DeletingIndex = i;
      this.showDeleteModal = true;
    },
  },
  async created() {
    const res = await this.callApi("get", "app/get_jurusan");
    if (res.status == 200) {
      this.jurusans = res.data;
    } else {
      this.e();
    }
  },
};
</script>

