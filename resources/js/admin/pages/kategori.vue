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
      <span> List Kategori </span>
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
            <span>Add Kategori</span>
          </v-btn>
        </template>

        <v-card v-model="addModal">
          <v-card-title>
            <span class="headline">Add Kategori</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    v-model="data.nama_kategori"
                    for="nama_kategori"
                    label="Nama Kategori*"
                    type="text"
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
                addKategori();
                getKategori();
              "
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Adding.." : "Add Kategori" }}</v-btn
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
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(kategori, i) in kategoris" :key="i">
            <td class="text-center">{{ i + 1 }}</td>
            <td class="text-center" hidden>{{ kategori.id_kategori }}</td>
            <td class="text-center">{{ kategori.nama_kategori }}</td>
            <td class="text-center">
              <v-btn
                depressed
                x-small
                class="light-blue white--text"
                @click="showEditModal(kategori, i)"
              >
                <v-icon small>mdi-pencil</v-icon>
                <span>Edit</span>
              </v-btn>
              <v-btn
                depressed
                x-small
                class="red white--text"
                @click="showDeletingModal(kategori, i)"
                :loading="kategori.isDeleting"
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
          <span class="headline">Edit kategori</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="editData.nama_kategori"
                  for="nama_kategori"
                  label="Nama Kategori*"
                  type="text"
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
              editKategori();
              getKategori();
            "
            :disabled="isAdding"
            :loading="isAdding"
            >{{ isAdding ? "Adding.." : "Edit Kategori" }}</v-btn
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
          @click="deleteKategori"
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
        nama_kategori: "",
      },
      row: null,
      addModal: false,
      editModal: false,
      isAdding: false,
      kategoris: [],
      editData: {
        nama_kategori: "",
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
          text: "Kategori",
          disabled: false,
          href: "/kategori",
        },
      ],
    };
  },
  methods: {
    async getKategori() {
      const res = await this.callApi("get", "app/get_kategori");
      if (res.status == 200) {
        this.kategoris = res.data;
      } else {
        this.e();
      }
    },
    async addKategori() {
      if (this.data.nama_kategori.trim() == "") {
        return this.swr("Kategori tidak boleh kosong!!");
      }
      const res = await this.callApi("post", "app/create_kategori", this.data);

      if (res.status === 201) {
        this.kategoris.unshift(res.data);
        this.s("kategori berhasil ditambahkan");
        this.addModal = false;
        this.data.nama_kategori = "";
      } else {
        if (res.status === 422) {
          if (res.data.errors.nama_kategori) {
            this.e(res.data.errors.nama_kategori[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    async editKategori() {
      if (this.editData.nama_kategori.trim() == "") {
        return this.swr("Kategori tidak boleh kosong!!");
      }
      const res = await this.callApi(
        "post",
        "app/edit_kategori",
        this.editData
      );

      if (res.status === 200) {
        this.kategoris[this.index].nama_kategori = this.editData.nama_kategori;
        this.s("kategori berhasil diubah");
        this.editModal = false;
      } else {
        if (res.status === 422) {
          if (res.data.errors.nama_kategori) {
            this.e(res.data.errors.nama_kategori[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(kategori, index) {
      let obj = {
        id_kategori: kategori.id_kategori,
        nama_kategori: kategori.nama_kategori,
      };
      this.editData = obj;
      this.editModal = true;
      this.index = index;
    },
    async deleteKategori() {
      this.isDeleting = true;
      const res = await this.callApi(
        "post",
        "app/delete_kategori",
        this.deleteItem
      );
      if (res.status === 200) {
        this.kategoris.splice(this.DeletingIndex, 1);
        this.s("Kategori berhasil dihapus");
      } else {
        this.swr();
      }
      this.isDeleting = false;
      this.showDeleteModal = false;
    },
    showDeletingModal(kategori, i) {
      this.deleteItem = kategori;
      this.DeletingIndex = i;
      this.showDeleteModal = true;
    },
  },
  async created() {
    const res = await this.callApi("get", "app/get_kategori");
    if (res.status == 200) {
      this.kategoris = res.data;
    } else {
      this.e();
    }
  },
};
</script>

