<template>
  <v-card class="d-block pa-2 accent-4 black--text">
    <v-breadcrumbs :items="items">
      <template v-slot:item="{ item }">
        <v-breadcrumbs-item
          :href="item.href"
          :disabled="item.disabled"
        >{{ item.text.toUpperCase() }}</v-breadcrumbs-item>
      </template>
    </v-breadcrumbs>
    <v-banner>
      List Tarif
      <v-dialog v-model="addModal" persistent max-width="600px">
        <template v-slot:activator="{ on, attrs }">
          <v-btn depressed x-small class="blue white--text" dark v-bind="attrs" v-on="on">
                <v-icon x-small>mdi-plus</v-icon>
                <span>Add Tarif</span> 
                </v-btn>
        </template>
        <v-card v-model="addModal">
          <v-card-title>
            <span class="headline">Add Tarif</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12">
                    <v-select v-model="data.waktu" :items="item" label="Pilih Waktu*"></v-select>
                  </v-col>
                <v-col cols="12">
                  <v-text-field v-model="data.harga" for="harga" label="Harga*" type="int"></v-text-field>
                </v-col>
                </v-row>
            </v-container>
            <small>*haris diisi</small>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn type="default" color="blue darken-1" text @click="addModal = false">Batal</v-btn>
            <v-btn
              color="blue darken-2"
              text
              @click="addFare"
              :disabled="isAdding"
              :loading="isAdding"
            >{{ isAdding ? "Adding.." : "Add Tarif" }}</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-banner>
    <v-card class="mx-auto" max-width="auto" outlined>
      <v-simple-table dense class="d-flex flex-column-reverse">
        <thead>
          <tr class="_table">
            <th class="text-left">ID</th>
            <th class="text-left">Waktu</th>
            <th class="text-left">Harga</th>
            <th class="text-left">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(fare, i) in fares" :key="i">
            <td>{{ fare.id }}</td>
            <td>{{ fare.waktu }}</td>
            <td>{{ fare.harga }}</td>
            <td>
            <v-btn depressed x-small class="light-blue white--text" @click="showEditModal(fare, i)">
                  <v-icon small>mdi-pencil</v-icon>
                  <span>Edit</span>        
            </v-btn>
              <v-btn depressed x-small class="red white--text"
                @click="showDeletingModal(fare, i)"
                :loading="fare.isDeleting">
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
          <span class="headline">Edit Tarif</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                    <v-select v-model="editData.waktu" :items="item" label="Pilih Waktu"></v-select>
                </v-col>
              <v-col cols="12">
                <v-text-field v-model="editData.harga" for="harga" label="Harga*" type="int"></v-text-field>
              </v-col>
              </v-row>
          </v-container>
          <small>*haris diisi</small>
        </v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn type="default" color="blue darken-1" text @click="editModal = false">Batal</v-btn>
          <v-btn
            color="blue darken-2"
            text
            @click="editFare"
            :disabled="isAdding"
            :loading="isAdding"
          >{{ isAdding ? "Adding.." : "Edit Tarif" }}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <Modal v-model="showDeleteModal" width="360" :styles="{ top: '20px' }">
            <p slot="header" style="color:#f60;text-align:center">
              <Icon type="ios-information-circle"></Icon>
              <span>Delete confirmation</span>
            </p>
            <div style="text-align:center">
              <p>Apakah anda yakin akan menghapus fare ini?</p>
            </div>
            <div slot="footer">
              <Button
                type="error"
                size="small"
                long
                :loading="isDeleting"
                :disabled="isDeleting"
                @click="deleteFare"
              >Delete</Button>
            </div>
          </Modal>
          <v-spacer></v-spacer>
          <v-banner class="text-center">
            <v-pagination
              v-model="page"
              :length="4"
              prev-icon="mdi-menu-left"
              next-icon="mdi-menu-right"
            ></v-pagination>
          </v-banner>
  </v-card>
</template>



<script>
export default {
    data() {
        return {
            data: {
                waktu: "",
                harga: ""
            },
            select: '',
            item: [
                'Pagi-Siang (06.00-12.00)',
                'Siang-Sore (12.00-18.00)',
                'Sore-Malam (18.00-24.00)',
            ],
            addModal: false,
            editModal: false,
            isAdding: false,
            fares: [],
            editData: {
                waktu: "",
                harga: ""
            },
            select: '',
            item: [
                'Pagi-Siang (06.00-12.00)',
                'Siang-Sore (12.00-18.00)',
                'Sore-Malam (18.00-24.00)',
            ],
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deleteItem: {},
            DeletingIndex: -1,
            page: 1,
            items: [
        {
          text: 'Home',
          disabled: false,
          href: '/',
        },
        {
          text: 'Tarif',
          disabled: false,
          href: '/fare',
        },
      ],
        };
    },
    methods: {
        async addFare() {
            if (this.data.waktu.trim() == "") {
                return this.swr("Tag waktu is required");
            } else if (this.data.harga.trim() == "") {
                return this.swr("Tag harga is required");
            }
            const res = await this.callApi(
                "post",
                "app/create_fare",
                this.data
            );

            if (res.status === 201) {
                this.fares.unshift(res.data);
                this.s("fare berhasil ditambahkan");
                this.addModal = false;
                this.data.waktu = "";
                this.data.harga = "";
            } else {
                if (res.status === 422) {
                    if (res.data.errors.waktu) {
                        this.e(res.data.errors.waktu[0]);
                    } else if (res.data.errors.harga) {
                        this.e(res.data.errors.harga[0]);
                    }
                } else {
                    this.swr();
                }
            }
        },
        async editFare() {
            if (this.editData.waktu.trim() == "") {
                return this.swr("Tag waktu is required");
            } else if (this.editData.harga.trim() == "") {
                return this.swr("Tag harga is required");
            }
            const res = await this.callApi(
                "post",
                "app/edit_fare",
                this.editData
            );

            if (res.status === 200) {
                this.fares[this.index].waktu = this.editData.waktu;
                this.fares[this.index].harga = this.editData.harga;
                this.s("fare berhasil diubah");
                this.editModal = false;
            } else {
                if (res.status === 422) {
                    if (res.data.errors.waktu) {
                        this.e(res.data.errors.waktu[0]);
                    } else if (res.data.errors.harga) {
                        this.e(res.data.errors.harga[0]);
                    }
                } else {
                    this.swr();
                }
            }
        },
        showEditModal(fare, index) {
            let obj = {
                id: fare.id,
                waktu: fare.waktu,
                harga: fare.harga
            };
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },
        async deleteFare() {
            this.isDeleting = true;
            const res = await this.callApi(
                "post",
                "app/delete_fare",
                this.deleteItem
            );
            if (res.status === 200) {
                this.fares.splice(this.DeletingIndex, 1);
                this.s("fare berhasil dihapus");
            } else {
                this.swr();
            }
            this.isDeleting = false;
            this.showDeleteModal = false;
        },
        showDeletingModal(fare, i) {
            this.deleteItem = fare;
            this.DeletingIndex = i;
            this.showDeleteModal = true;
        }
    },
    async created() {
        const res = await this.callApi("get", "app/get_fares");
        if (res.status == 200) {
            this.fares = res.data;
        } else {
            this.e();
        }
    }
};
</script>
