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
      <span> List Iuran </span>
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
            <span>Add Iuran</span>
          </v-btn>
        </template>

        <v-card v-model="addModal">
          <v-card-title>
            <span class="headline">Add Iuran</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12">
                  <v-select
                    label="Pilih Tahun Akademik"
                    v-model="data.id_tahun_akademik"
                    for="id_tahun_akademik"
                    :items="tahun_akademik"
                    item-value="id_tahun_akademik"
                    :item-text="tahun_akademik => tahun_akademik.nama_tahun + '  --  ' + tahun_akademik.keterangan"
                  >
                  </v-select>
                </v-col>
                <v-col cols="12">
                <v-select
                    label="Pilih Tingkat"
                    v-model="data.id_tingkat"
                    for="id_tingkat"
                    :items="tingkat"
                    item-value="id_tingkat"
                    item-text="tingkat"
                  >
                  </v-select>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    v-model="data.jenis_tagihan"
                    for="jenis_tagihan"
                    label="Jenis Tagihan*"
                    type="text"
                  ></v-text-field>
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
                addIuran();
                getIuran();
              "
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Adding.." : "Add Iuran" }}</v-btn
            >
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-toolbar>

    <v-card class="mx-auto" max-width="auto" outlined>
      <v-simple-table dense class="d-flex flex-column-reverse col-3 col-s-12">
        <thead>
          <tr class="_table col-3 col-s-12">
            <th class="text-center">NO</th>
            <th class="text-center">Tahun Akademik</th>
            <th class="text-center">Tingkat</th>
            <th class="text-center">Jenis Iuran</th>
            <th class="text-center">Jumlah</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(iuran, i) in iurans" :key="i">
            <td class="text-center">{{ i + 1 }}</td>
            <td class="text-center" hidden>{{ iuran.id_iuran }}</td>
            <td class="text-center">{{ iuran.nama_tahun }}</td>
            <td class="text-center">{{ iuran.tingkat }}</td>
            <td class="text-center">{{ iuran.jenis_tagihan }}</td>
            <td class="text-center">{{ iuran.jumlah | currency}}</td>
            <td class="text-center">
              <v-btn
                depressed
                x-small
                class="light-blue white--text"
                @click="showEditModal(iuran, i)"
              >
                <v-icon small>mdi-pencil</v-icon>
                <span>Edit</span>
              </v-btn>
              <v-btn
                depressed
                x-small
                class="red white--text"
                @click="showDeletingModal(iuran, i)"
                :loading="iuran.isDeleting"
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
          <span class="headline">Edit Iuran</span>
        </v-card-title>
        <v-card-text>
          <v-container>
              <v-row>
                <v-col cols="12">
                  <v-select
                    label="Pilih Tahun Akademik"
                    v-model="editData.id_tahun_akademik"
                    for="id_tahun_akademik"
                    :items="tahun_akademik"
                    item-value="id_tahun_akademik"
                    :item-text="tahun_akademik => tahun_akademik.nama_tahun + '  --  ' + tahun_akademik.keterangan"
                  >
                  </v-select>
                </v-col>
                <v-col cols="12">
                <v-select
                    label="Pilih Tingkat"
                    v-model="editData.id_tingkat"
                    for="id_tingkat"
                    :items="tingkat"
                    item-value="id_tingkat"
                    item-text="tingkat"
                  >
                  </v-select>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    v-model="editData.jenis_tagihan"
                    for="jenis_tagihan"
                    label="Jenis Tagihan*"
                    type="text"
                  ></v-text-field>
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
              editIuran();
              getIuran();
            "
            :disabled="isAdding"
            :loading="isAdding"
            >{{ isAdding ? "Adding.." : "Edit Iuran" }}</v-btn
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
          @click="deleteIuran"
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
        id_tahun_akademik: "",
        id_tingkat: "",
        jenis_tagihan: "",
        jumlah: "",
      },
      row: null,
      addModal: false,
      editModal: false,
      isAdding: false,
      iurans: [],
      tahun_akademik: [],
      tingkat: [],
      editData: {
        id_tahun_akademik: "",
        id_tingkat: "",
        jenis_tagihan: "",
        jumlah: "",
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
          text: "Iuran",
          disabled: false,
          href: "/iuran",
        },
      ],
    };
  },
  methods: {
    async getIuran() {
      const [iuran, a, t] = await Promise.all([
        this.callApi("get", "app/get_iuran"),
        this.callApi("get", "app/get_tahun_akademik"),
        this.callApi("get", "app/get_tingkat"),
      ]);
      if (iuran.status == 200) {
        this.iurans = iuran.data;
        this.tahun_akademik = a.data;
        this.tingkat = t.data;
      } else {
        this.e();
      }
    },
    async addIuran() {
      if (this.data.jenis_tagihan.trim() == "") {
        return this.swr("Jenis Tagihan tidak boleh kosong!!");
      } else if (this.data.jumlah.trim() == "") {
        return this.swr("Jumlah tidak boleh kosong!!");
      }
      const res = await this.callApi("post", "app/create_iuran", this.data);

      if (res.status === 201) {
        this.iurans.unshift(res.data);
        this.s("Iuran berhasil ditambahkan");
        this.addModal = false;
        this.data.id_tahun_akademik = "";
        this.data.id_tingkat = "";
        this.data.jenis_tagihan = "";
        this.data.jumlah = "";
      } else {
        if (res.status === 422) {
          if (res.data.errors.id_tahun_akademik) {
            this.e(res.data.errors.id_tahun_akademik[0]);
          } else if (res.data.errors.id_tingkat) {
            this.e(res.data.errors.id_tingkat[0]);
          } else if (res.data.errors.jenis_tagihan) {
            this.e(res.data.errors.jenis_tagihan[0]);
          } else if (res.data.errors.jumlah) {
            this.e(res.data.errors.jumlah[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    async editIuran() {
      if (this.editData.jenis_tagihan.trim() == "") {
        return this.swr("Jenis Tagihan tidak boleh kosong!!");
      } else if (this.editData.jumlah.trim() == "") {
        return this.swr("Jumlah tidak boleh kosong!!");
      }
      const res = await this.callApi("post", "app/edit_iuran", this.editData);

      if (res.status === 200) {
        this.iurans[
          this.index
        ].id_tahun_akademik = this.editData.id_tahun_akademik;
        this.iurans[this.index].id_tingkat = this.editData.id_tingkat;
        this.iurans[this.index].jenis_tagihan = this.editData.jenis_tagihan;
        this.iurans[this.index].jumlah = this.editData.jumlah;
        this.s("Iuran berhasil diubah");
        this.editModal = false;
      } else {
        if (res.status === 422) {
          if (res.editData.errors.id_tahun_akademik) {
            this.e(res.editData.errors.id_tahun_akademik[0]);
          } else if (res.editData.errors.id_tingkat) {
            this.e(res.editData.errors.id_tingkat[0]);
          } else if (res.editData.errors.jenis_tagihan) {
            this.e(res.editData.errors.jenis_tagihan[0]);
          } else if (res.editData.errors.jumlah) {
            this.e(res.editData.errors.jumlah[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(iuran, index) {
      let obj = {
        id_iuran: iuran.id_iuran,
        id_tahun_akademik: iuran.id_tahun_akademik,
        id_tingkat: iuran.id_tingkat,
        jenis_tagihan: iuran.jenis_tagihan,
        jumlah: iuran.jumlah,
      };
      this.editData = obj;
      this.editModal = true;
      this.index = index;
    },
    async deleteIuran() {
      this.isDeleting = true;
      const res = await this.callApi(
        "post",
        "app/delete_iuran",
        this.deleteItem
      );
      if (res.status === 200) {
        this.iurans.splice(this.DeletingIndex, 1);
        this.s("Iuran berhasil dihapus");
      } else {
        this.swr();
      }
      this.isDeleting = false;
      this.showDeleteModal = false;
    },
    showDeletingModal(iuran, i) {
      this.deleteItem = iuran;
      this.DeletingIndex = i;
      this.showDeleteModal = true;
    },
  },
  async created() {
    const [iuran, a, t] = await Promise.all([
      this.callApi("get", "app/get_iuran"),
      this.callApi("get", "app/get_tahun_akademik"),
      this.callApi("get", "app/get_tingkat"),
    ]);
    if (iuran.status == 200) {
      this.iurans = iuran.data;
      this.tahun_akademik = a.data;
      this.tingkat = t.data;
    } else {
      this.e();
    }
  },
};
</script>

