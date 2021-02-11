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
      <span> List Sub-Kategori </span>
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
            <span>Add Sub-Kategori</span>
          </v-btn>
        </template>

        <v-card v-model="addModal">
          <v-card-title>
            <span class="headline">Add Sub-Kategori</span>
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
                  <v-text-field
                    v-model="data.jenis_subkategori"
                    for="jenis_subkategori"
                    label="Jenis Sub Kategori*"
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
              @click="addSubkategori(); getSubkategori()"
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Adding.." : "Add Sub-Kategori" }}</v-btn
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
            <th class="text-center">Jenis Sub-Kategori</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(subkategori, i) in subkategoris" :key="i">
            <td class="text-center">{{ i + 1 }}</td>
            <td class="text-center" hidden>{{subkategori.id_subkategori}}</td>
            <td class="text-center">{{ subkategori.nama_kategori }}</td>
            <td class="text-center">{{ subkategori.jenis_subkategori }}</td>
            <td class="text-center">
              <v-btn
                depressed
                x-small
                class="light-blue white--text"
                @click="showEditModal(subkategori, i)"
              >
                <v-icon small>mdi-pencil</v-icon>
                <span>Edit</span>
              </v-btn>
              <v-btn
                depressed
                x-small
                class="red white--text"
                @click="showDeletingModal(subkategori, i)"
                :loading="subkategori.isDeleting"
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
          <span class="headline">Edit Sub-Kategori</span>
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
                  <v-text-field
                    v-model="editData.jenis_subkategori"
                    for="jenis_subkategori"
                    label="Jenis Sub Kategori*"
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
            @click="editSubkategori(); getSubkategori()"
            :disabled="isAdding"
            :loading="isAdding"
            >{{ isAdding ? "Adding.." : "Edit Sub-Kategori" }}</v-btn
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
          @click="deleteSubkategori"
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
        jenis_subkategori: "",
      },
      row: null,
      addModal: false,
      editModal: false,
      isAdding: false,
      subkategoris: [],
      kategori: [],
      editData: {
        id_kategori: "",
        jenis_subkategori: "",
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
          text: "sub kategori",
          disabled: false,
          href: "/subkategori",
        },
      ],
    };
  },
  methods: {
    async getSubkategori() {
      const [sub, k] = await Promise.all([
        this.callApi("get", "app/get_subkategori"),
        this.callApi("get", "app/get_kategori"),
      ]);
      if (sub.status == 200) {
        this.subkategoris = sub.data;
        this.kategori = k.data;
      } else {
        this.e();
      }
    },
    async addSubkategori() {
      if (this.data.jenis_subkategori.trim() == "") {
        return this.swr("Subkategori tidak boleh kosong!!");
      }
      const res = await this.callApi(
        "post",
        "app/create_subkategori",
        this.data
      );

      if (res.status === 201) {
        this.subkategoris.unshift(res.data);
        this.s("Subkategori berhasil ditambahkan");
        this.addModal = false;
        this.data.id_kategori = "";
        this.data.jenis_subkategori = "";
      } else {
        if (res.status === 422) {
          if (res.data.errors.id_kategori) {
            this.e(res.data.errors.id_kategori[0]);
          }else if (res.data.errors.jenis_subkategori) {
            this.e(res.data.errors.jenis_subkategori[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    async editSubkategori() {
      if (this.editData.jenis_subkategori.trim() == "") {
        return this.swr("Subkategori tidak boleh kosong!!");
      }
      const res = await this.callApi(
        "post",
        "app/edit_subkategori",
        this.editData
      );

      if (res.status === 200) {
        this.subkategoris[this.index].id_kategori = this.editData.id_kategori;
        this.subkategoris[this.index].jenis_subkategori = this.editData.jenis_subkategori;
        this.s("subkategori berhasil diubah");
        this.editModal = false;
      } else {
        if (res.status === 422) {
          if (res.editData.errors.id_kategori) {
            this.e(res.editData.errors.id_kategori[0]);
          }else if (res.editData.errors.jenis_subkategori) {
            this.e(res.editData.errors.jenis_subkategori[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(subkategori, index) {
      let obj = {
        id_subkategori: subkategori.id_subkategori,
        id_kategori: subkategori.id_kategori,
        jenis_subkategori: subkategori.jenis_subkategori,
      };
      this.editData = obj;
      this.editModal = true;
      this.index = index;
    },
    async deleteSubkategori() {
      this.isDeleting = true;
      const res = await this.callApi(
        "post",
        "app/delete_subkategori",
        this.deleteItem
      );
      if (res.status === 200) {
        this.subkategoris.splice(this.DeletingIndex, 1);
        this.s("Subkategori berhasil dihapus");
      } else {
        this.swr();
      }
      this.isDeleting = false;
      this.showDeleteModal = false;
    },
    showDeletingModal(subkategori, i) {
      this.deleteItem = subkategori;
      this.DeletingIndex = i;
      this.showDeleteModal = true;
    },
  },
  async created() {
    const [sub, k] = await Promise.all([
        this.callApi("get", "app/get_subkategori"),
        this.callApi("get", "app/get_kategori"),
      ]);
      if (sub.status == 200) {
        this.subkategoris = sub.data;
        this.kategori = k.data;
      } else {
        this.e();
      }
  },
};
</script>

