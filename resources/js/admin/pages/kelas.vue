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
      <span> List Kelas </span>
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
            <span>Add Kelas</span>
          </v-btn>
        </template>

        <v-card v-model="addModal">
          <v-card-title>
            <span class="headline">Add Kelas</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12">
                  <v-select
                    label="Pilih Program Keahlian"
                    v-model="data.id_jurusan"
                    for="id_jurusan"
                    :items="jurusan"
                    item-value="id_jurusan"
                    item-text="nama_jurusan"
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
                addKelas();
                getKelas();
              "
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Adding.." : "Add Kelas" }}</v-btn
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
            <th class="text-center">Tingkat</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(major, i) in majors" :key="i">
            <td class="text-center">{{ i + 1 }}</td>
            <td class="text-center" hidden>{{ major.id_major }}</td>
            <td class="text-center">{{ major.nama_jurusan }}</td>
            <td class="text-center">{{ major.tingkat }}</td>
            <td class="text-center">
              <v-btn
                depressed
                x-small
                class="light-blue white--text"
                @click="showEditModal(major, i)"
              >
                <v-icon small>mdi-pencil</v-icon>
                <span>Edit</span>
              </v-btn>
              <v-btn
                depressed
                x-small
                class="red white--text"
                @click="showDeletingModal(major, i)"
                :loading="major.isDeleting"
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
          <span class="headline">Edit Kelas</span>
        </v-card-title>
        <v-card-text>
          <v-container>
              <v-row>
                <v-col cols="12">
                  <v-select
                    label="Pilih Program Keahlian"
                    v-model="editData.id_jurusan"
                    for="id_jurusan"
                    :items="jurusan"
                    item-value="id_jurusan"
                    item-text="nama_jurusan"
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
              editKelas();
              getKelas();
            "
            :disabled="isAdding"
            :loading="isAdding"
            >{{ isAdding ? "Adding.." : "Edit Kelas" }}</v-btn
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
          @click="deleteKelas"
          >Delete</Button
        >
      </div>
    </Modal>
    <v-spacer></v-spacer>
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      data: {
        id_jurusan: "",
        id_tingkat: "",
      },
      row: null,
      addModal: false,
      editModal: false,
      isAdding: false,
      majors: [],
      jurusan: [],
      tingkat: [],
      editData: {
        id_jurusan: "",
        id_tingkat: "",
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
          text: "Kelas",
          disabled: false,
          href: "/kelas",
        },
      ],
    };
  },
  methods: {
    async getKelas() {
      const [major, j, t] = await Promise.all([
        this.callApi("get", "app/get_kelas"),
        this.callApi("get", "app/get_jurusan"),
        this.callApi("get", "app/get_tingkat"),
      ]);
      if (major.status == 200) {
        this.majors = major.data;
        this.jurusan = j.data;
        this.tingkat = t.data;
      } else {
        this.e();
      }
    },
    async addKelas() {
      const res = await this.callApi("post", "app/create_kelas", this.data);

      if (res.status === 201) {
        this.majors.unshift(res.data);
        this.s("Kelas berhasil ditambahkan");
        this.addModal = false;
        this.data.id_jurusan = "";
        this.data.id_tingkat = "";
      } else {
        if (res.status === 422) {
          if (res.data.errors.id_jurusan) {
            this.e(res.data.errors.id_jurusan[0]);
          } else if (res.data.errors.id_tingkat) {
            this.e(res.data.errors.id_tingkat[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    async editKelas() {
      const res = await this.callApi("post", "app/edit_kelas", this.editData);

      if (res.status === 200) {
        this.majors[this.index].id_jurusan = this.editData.id_jurusan;
        this.majors[this.index].id_tingkat = this.editData.id_tingkat;
        this.s(" Kelas berhasil diubah");
        this.editModal = false;
      } else {
        if (res.status === 422) {
          if (res.editData.errors.id_jurusan) {
            this.e(res.editData.errors.id_jurusan[0]);
          } else if (res.editData.errors.id_tingkat) {
            this.e(res.editData.errors.id_tingkat[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(major, index) {
      let obj = {
        id_kelas: major.id_kelas,
        id_jurusan: major.id_jurusan,
        id_tingkat: major.id_tingkat,
      };
      this.editData = obj;
      this.editModal = true;
      this.index = index;
    },
    async deleteKelas() {
      this.isDeleting = true;
      const res = await this.callApi(
        "post",
        "app/delete_kelas",
        this.deleteItem
      );
      if (res.status === 200) {
        this.majors.splice(this.DeletingIndex, 1);
        this.s("Data kelas berhasil dihapus");
      } else {
        this.swr();
      }
      this.isDeleting = false;
      this.showDeleteModal = false;
    },
    showDeletingModal(major, i) {
      this.deleteItem = major;
      this.DeletingIndex = i;
      this.showDeleteModal = true;
    },
  },
  async created() {
    const [major, j, t] = await Promise.all([
      this.callApi("get", "app/get_kelas"),
      this.callApi("get", "app/get_jurusan"),
      this.callApi("get", "app/get_tingkat"),
    ]);
    if (major.status == 200) {
      this.majors = major.data;
      this.jurusan = j.data;
      this.tingkat = t.data;
    } else {
      this.e();
    }
  },
};
</script>

