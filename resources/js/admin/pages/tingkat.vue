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
      <span> List Tingkat </span>
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
            <span>Add Tingkat</span>
          </v-btn>
        </template>

        <v-card v-model="addModal">
          <v-card-title>
            <span class="headline">Add Tingkat</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12">
                    <v-select v-model="data.tingkat" :items="item" label="Pilih Tingkat" placeholder="--Pilih Tingkat--"></v-select>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    v-model="data.keterangan"
                    for="keterangan"
                    label="Keterangan*"
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
              @click="addTingkat(); getTingkat()"
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Adding.." : "Add Tingkat" }}</v-btn
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
            <th class="text-center">Tingkat</th>
            <th class="text-center">Keterangan</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(tingkat, i) in tingkats" :key="i">
            <td class="text-center">{{ i + 1 }}</td>
            <td class="text-center" hidden>{{tingkat.id_tingkat}}</td>
            <td class="text-center">{{ tingkat.tingkat }}</td>
            <td class="text-center">{{ tingkat.keterangan }}</td>
            <td class="text-center">
              <v-btn
                depressed
                x-small
                class="light-blue white--text"
                @click="showEditModal(tingkat, i)"
              >
                <v-icon small>mdi-pencil</v-icon>
                <span>Edit</span>
              </v-btn>
              <v-btn
                depressed
                x-small
                class="red white--text"
                @click="showDeletingModal(tingkat, i)"
                :loading="tingkat.isDeleting"
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
          <span class="headline">Edit tingkat</span>
        </v-card-title>
        <v-card-text>
          <v-container>
              <v-row>
                <v-col cols="12">
                    <v-select v-model="editData.tingkat" :items="item" label="Pilih Tingkat" placeholder="--Pilih Tingkat--"></v-select>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    v-model="editData.keterangan"
                    for="keterangan"
                    label="Keterangan*"
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
            @click="editTingkat"
            :disabled="isAdding"
            :loading="isAdding"
            >{{ isAdding ? "Adding.." : "Edit Tingkat" }}</v-btn
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
          @click="deleteTingkat"
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
        tingkat: "",
        keterangan: "",
      },
      select: '',
      item: [
        'X',
        'XI',
        'XII',
      ],
      row: null,
      addModal: false,
      editModal: false,
      isAdding: false,
      tingkats: [],
      editData: {
        tingkat: "",
        keterangan: "",
      },
      select: '',
      item: [
        'X',
        'XI',
        'XII',
      ],
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
          text: "Tingkat",
          disabled: false,
          href: "/tingkat",
        },
      ],
    };
  },
  methods: {
    async getTingkat() {
    const res = await this.callApi("get", "app/get_tingkat");
    if (res.status == 200) {
      this.tingkats = res.data;
    } else {
      this.e();
    }
  },
    async addTingkat() {
      if (this.data.tingkat.trim() == "") {
        return this.swr("Tingkat tidak boleh kosong!!");
      } else if (this.data.keterangan.trim() == "") {
        return this.swr("Keterangan tidak boleh kosong!!");
      }
      const res = await this.callApi(
        "post",
        "app/create_tingkat",
        this.data
      );

      if (res.status === 201) {
        this.tingkats.unshift(res.data);
        this.s("tingkat berhasil ditambahkan");
        this.addModal = false;
        this.data.tingkat = "";
        this.data.keterangan = "";
      } else {
        if (res.status === 422) {
          if (res.data.errors.tingkat) {
            this.e(res.data.errors.tingkat[0]);
          } else if (res.data.errors.keterangan) {
            this.e(res.data.errors.keterangan[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    async editTingkat() {
      if (this.editData.tingkat.trim() == "") {
        return this.swr("Tingkat tidak boleh kosong!!");
      } else if (this.editData.keterangan.trim() == "") {
        return this.swr("Keterangan tidak boleh kosong!!");
      }
      const res = await this.callApi(
        "post",
        "app/edit_tingkat",
        this.editData
      );

      if (res.status === 200) {
        this.tingkats[this.index].tingkat = this.editData.tingkat;
        this.tingkats[this.index].keterangan = this.editData.keterangan;
        this.s("tingkat berhasil diubah");
        this.editModal = false;
      } else {
        if (res.status === 422) {
          if (res.data.errors.tingkat) {
            this.e(res.data.errors.tingkat[0]);
          } else if (res.data.errors.keterangan) {
            this.e(res.data.errors.keterangan[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(tingkat, index) {
      let obj = {
        id_tingkat: tingkat.id_tingkat,
        tingkat: tingkat.tingkat,
        keterangan: tingkat.keterangan,
      };
      this.editData = obj;
      this.editModal = true;
      this.index = index;
    },
    async deleteTingkat() {
      this.isDeleting = true;
      const res = await this.callApi(
        "post",
        "app/delete_tingkat",
        this.deleteItem
      );
      if (res.status === 200) {
        this.tingkats.splice(this.DeletingIndex, 1);
        this.s("Tahun akademik berhasil dihapus");
      } else {
        this.swr();
      }
      this.isDeleting = false;
      this.showDeleteModal = false;
    },
    showDeletingModal(tingkat, i) {
      this.deleteItem = tingkat;
      this.DeletingIndex = i;
      this.showDeleteModal = true;
    },
  },
  async created() {
    const res = await this.callApi("get", "app/get_tingkat");
    if (res.status == 200) {
      this.tingkats = res.data;
    } else {
      this.e();
    }
  },
};
</script>

