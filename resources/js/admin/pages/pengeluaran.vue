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
      <span> List Pengeluaran </span>
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
            <span>Add Pengeluaran</span>
          </v-btn>
        </template>

        <v-card v-model="addModal">
          <v-card-title>
            <span class="headline">Add Pengeluaran</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12">
                  <v-select
                    label="Pilih Kategori"
                    v-model="data.id_kategori"
                    for="id_kategori"
                    :items="kategori"
                    item-value="id_kategori"
                    item-text="nama_kategori"
                  >
                  </v-select>
                </v-col>
                <v-col cols="12">
                  <v-select
                    label="Pilih Sub-Kategori"
                    v-model="data.id_subkategori"
                    for="id_subkategori"
                    :items="subkategori"
                    item-value="id_subkategori"
                    item-text="jenis_subkategori"
                  >
                  </v-select>
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
                        v-model="data.tgl_pengeluaran"
                        label="Pilih Tanggal Pembayaran"
                        prepend-icon="mdi-calendar"
                        hint="YYYY/MM/DD format"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      v-model="data.tgl_pengeluaran"
                      @input="menu2 = false"
                    ></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    v-model="data.jumlah"
                    for="jumlah"
                    label="Jumlah*"
                    type="number"
                  ></v-text-field>
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
              @click="
                addPengeluaran();
                getPengeluaran();
              "
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Adding.." : "Add Pengeluaran" }}</v-btn
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
            <th class="text-center">Jenis Kategori</th>
            <th class="text-center">Jenis Sub kategori</th>
            <th class="text-center">Tanggal</th>
            <th class="text-center">Jumlah</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(pengeluaran, i) in pengeluarans" :key="i">
            <td class="text-center">{{ i + 1 }}</td>
            <td class="text-center" hidden>{{ pengeluaran.id_pengeluaran }}</td>
            <td class="text-center">{{ pengeluaran.nama_kategori }}</td>
            <td class="text-center">{{ pengeluaran.jenis_subkategori }}</td>
            <td class="text-center">
              {{ pengeluaran.tgl_pengeluaran | formatDate }}
            </td>
            <td class="text-center">{{ pengeluaran.jumlah | currency }}</td>
            <td class="text-center">
              <v-btn
                depressed
                x-small
                class="light-blue white--text"
                @click="showEditModal(pengeluaran, i)"
              >
                <v-icon small>mdi-pencil</v-icon>
                <span>Edit</span>
              </v-btn>
              <v-btn
                depressed
                x-small
                class="red white--text"
                @click="showDeletingModal(pengeluaran, i)"
                :loading="pengeluaran.isDeleting"
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
          <span class="headline">Edit Pengeluaran</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-select
                  label="Pilih Kategori"
                  v-model="editData.id_kategori"
                  for="id_kategori"
                  :items="kategori"
                  item-value="id_kategori"
                  item-text="nama_kategori"
                >
                </v-select>
              </v-col>
              <v-col cols="12">
                <v-select
                  label="Pilih Sub-Kategori"
                  v-model="editData.id_subkategori"
                  for="id_subkategori"
                  :items="subkategori"
                  item-value="id_subkategori"
                  item-text="jenis_subkategori"
                >
                </v-select>
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
                      v-model="editData.tgl_pengeluaran"
                      label="Pilih Tanggal Pembayaran"
                      prepend-icon="mdi-calendar"
                      hint="YYYY/MM/DD format"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker
                    v-model="editData.tgl_pengeluaran"
                    @input="menu2 = false"
                  ></v-date-picker>
                </v-menu>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="editData.jumlah"
                  for="jumlah"
                  label="Jumlah*"
                  type="number"
                ></v-text-field>
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
            @click="
              editPengeluaran();
              getPengeluaran();
            "
            :disabled="isAdding"
            :loading="isAdding"
            >{{ isAdding ? "Adding.." : "Edit Pengeluaran" }}</v-btn
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
          @click="deletePengeluaran"
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
        id_kategori: "",
        id_subkategori: "",
        tgl_pengeluaran: "",
        jumlah: "",
      },
      row: null,
      addModal: false,
      editModal: false,
      isAdding: false,
      pengeluarans: [],
      kategori: [],
      subkategori: [],
      editData: {
        id_kategori: "",
        id_subkategori: "",
        tgl_pengeluaran: "",
        jumlah: "",
      },
      index: -1,
      showDeleteModal: false,
      isDeleting: false,
      deleteItem: {},
      DeletingIndex: -1,
      date: new Date().toISOString().substr(0, 10),
      menu2: false,
      items: [
        {
          text: "Home",
          disabled: false,
          href: "/",
        },
        {
          text: "pengeluaran",
          disabled: false,
          href: "/pengeluaran",
        },
      ],
    };
  },
  methods: {
    async getPengeluaran() {
      const [p, sub, k] = await Promise.all([
        this.callApi("get", "app/get_pengeluaran"),
        this.callApi("get", "app/get_subkategori"),
        this.callApi("get", "app/get_kategori"),
      ]);
      if (p.status == 200) {
        this.pengeluarans = p.data;
        this.subkategori = sub.data;
        this.kategori = k.data;
      } else {
        this.e();
      }
    },
    async addPengeluaran() {
      if (this.data.tgl_pengeluaran.trim() == "") {
        return this.swr("Tanggal pengeluaran tidak boleh kosong!!");
      } else if (this.data.jumlah.trim() == "") {
        return this.swr("Jumlah tidak boleh kosong!!");
      }
      const res = await this.callApi(
        "post",
        "app/create_pengeluaran",
        this.data
      );

      if (res.status === 201) {
        this.pengeluarans.unshift(res.data);
        this.s("pengeluaran berhasil ditambahkan");
        this.addModal = false;
        this.data.id_kategori = "";
        this.data.id_subkategori = "";
        this.data.tgl_pengeluaran = "";
        this.data.jumlah = "";
      } else {
        if (res.status === 422) {
          if (res.data.errors.id_kategori) {
            this.e(res.data.errors.id_kategori[0]);
          } else if (res.data.errors.id_subkategori) {
            this.e(res.data.errors.id_subkategori[0]);
          } else if (res.data.errors.tgl_pengeluaran) {
            this.e(res.data.errors.tgl_pengeluaran[0]);
          } else if (res.data.errors.jumlah) {
            this.e(res.data.errors.jumlah[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    async editPengeluaran() {
      if (this.editData.tgl_pengeluaran.trim() == "") {
        return this.swr("Tanggal pengeluaran tidak boleh kosong!!");
      } else if (this.editData.jumlah.trim() == "") {
        return this.swr("Jumlah tidak boleh kosong!!");
      }
      const res = await this.callApi(
        "post",
        "app/edit_pengeluaran",
        this.editData
      );

      if (res.status === 200) {
        this.pengeluarans[this.index].id_kategori = this.editData.id_kategori;
        this.pengeluarans[this.index].id_subkategori = this.editData.id_subkategori;
        this.pengeluarans[this.index].tgl_pengeluaran = this.editData.tgl_pengeluaran;
        this.pengeluarans[this.index].jumlah = this.editData.jumlah;
        this.s("pengeluaran berhasil diubah");
        this.editModal = false;
      } else {
        if (res.status === 422) {
          if (res.editData.errors.id_kategori) {
            this.e(res.editData.errors.id_kategori[0]);
          } else if (res.editData.errors.id_subkategori) {
            this.e(res.editData.errors.id_subkategori[0]);
          } else if (res.editData.errors.tgl_pengeluaran) {
            this.e(res.editData.errors.tgl_pengeluaran[0]);
          } else if (res.editData.errors.jumlah) {
            this.e(res.editData.errors.jumlah[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(pengeluaran, index) {
      let obj = {
        id_pengeluaran: pengeluaran.id_pengeluaran,
        id_kategori: pengeluaran.id_kategori,
        id_subkategori: pengeluaran.id_subkategori,
        tgl_pengeluaran: pengeluaran.tgl_pengeluaran,
        jumlah: pengeluaran.jumlah,
      };
      this.editData = obj;
      this.editModal = true;
      this.index = index;
    },
    async deletePengeluaran() {
      this.isDeleting = true;
      const res = await this.callApi(
        "post",
        "app/delete_pengeluaran",
        this.deleteItem
      );
      if (res.status === 200) {
        this.pengeluarans.splice(this.DeletingIndex, 1);
        this.s("Pengeluaran berhasil dihapus");
      } else {
        this.swr();
      }
      this.isDeleting = false;
      this.showDeleteModal = false;
    },
    showDeletingModal(pengeluaran, i) {
      this.deleteItem = pengeluaran;
      this.DeletingIndex = i;
      this.showDeleteModal = true;
    },
  },
  async created() {
    const [p, sub, k] = await Promise.all([
      this.callApi("get", "app/get_pengeluaran"),
      this.callApi("get", "app/get_subkategori"),
      this.callApi("get", "app/get_kategori"),
    ]);
    if (p.status == 200) {
      this.pengeluarans = p.data;
      this.subkategori = sub.data;
      this.kategori = k.data;
    } else {
      this.e();
    }
  },
};
</script>

