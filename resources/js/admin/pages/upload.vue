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
      List Upload bukti
      <v-dialog v-model="addModal" persistent max-width="600px">
        <template v-slot:activator="{ on, attrs }">
          <v-btn depressed x-small class="blue white--text" dark v-bind="attrs" v-on="on">
            <v-icon x-small>mdi-plus</v-icon>
            <span>Add Upload</span>
          </v-btn>
        </template>
        <v-card v-model="addModal">
          <v-card-title>
            <span class="headline">Add Upload</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12">
                  <label for="tgl">Tanggal</label>
                  <Input v-model="data.tgl" type="date" />
                </v-col>
                <v-col cols="12">
                  <label for="bukti" label="Bukti*">Bukti</label>
                  <v-spacer></v-spacer>
                  <Input v-model="data.bukti" hidden />
                  <Upload
                    ref="upload"
                    type="drag"
                    :headers="{
                                        'x-csrf-token': token,
                                        'X-Requested-with': 'XMLHttpRequest'
                                    }"
                    :on-success="handleSuccess"
                    :on-error="handleError"
                    :format="['jpg', 'jpeg', 'png']"
                    :max-size="2048"
                    :on-format-error="handleFormatError"
                    :on-exceeded-size="handleMaxSize"
                    action="app/upload"
                  >
                    <div cols="12">
                      <Icon type="ios-cloud-upload" size="small" style="color: #3399ff"></Icon>
                      <p>Click or drag files here to upload</p>
                    </div>
                  </Upload>

                  <div class="demo-upload-list" v-if="data.bukti">
                    <img :src="`/uploads/${data.bukti}`" />
                    <div class="demo-upload-list-cover">
                      <Icon type="ios-eye-outline" @click="deleteImage"></Icon>
                    </div>
                  </div>
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
            <v-btn type="default" color="blue darken-1" text @click="addModal = false">Batal</v-btn>
            <v-btn
              color="blue darken-2"
              text
              @click="addUpload"
              :disabled="isAdding"
              :loading="isAdding"
            >{{ isAdding ? "Adding.." : "Add upload" }}</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-banner>
    <v-card class="mx-auto" max-width="auto" outlined>
      <v-simple-table dense class="d-flex flex-column-reverse">
        <thead>
          <tr class="_table">
            <th class="text-left">Bukti</th>
            <th class="text-left">Keterangan</th>
            <th class="text-left">Tanggal</th>
            <th class="text-left">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(upload, i) in uploads" :key="i">
            <td class="table_image">
              <img :src="`/uploads/` + upload.bukti" />
            </td>
            <td>{{ upload.keterangan }}</td>
            <td>{{ upload.tgl | formatDate }}</td>
            <td>
              <v-btn
                depressed
                x-small
                class="light-blue white--text"
                @click="showEditModal(upload, i)"
              >
                <v-icon small>mdi-pencil</v-icon>
                <span>Edit</span>
              </v-btn>
              <v-btn
                depressed
                x-small
                class="red white--text"
                @click="showDeletingModal(upload, i)"
                :loading="upload.isDeleting"
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
          <span class="headline">Edit Upload</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <label for="tgl">Tanggal</label>
                <Input v-model="editData.tgl" type="date" />
              </v-col>
              <v-col cols="12">
                <label for="bukti">Bukti</label>
                <Input v-model="editData.bukti" for="bukti" hidden />
                <Upload
                  v-show="isIconImageNew"
                  ref="upload"
                  type="drag"
                  :headers="{
                                        'x-csrf-token': token,
                                        'X-Requested-with': 'XMLHttpRequest'
                                    }"
                  :on-success="handleSuccess"
                  :on-error="handleError"
                  :format="['jpg', 'jpeg', 'png']"
                  :max-size="2048"
                  :on-format-error="handleFormatError"
                  :on-exceeded-size="handleMaxSize"
                  action="app/upload"
                >
                  <div style="padding: 20px 0">
                    <Icon type="ios-cloud-upload" size="small" style="color: #3399ff"></Icon>
                    <p>Click or drag files here to upload</p>
                  </div>
                </Upload>

                <div class="demo-upload-list" v-if="editData.bukti">
                  <img :src="`/uploads/${editData.bukti}`" />
                  <div class="demo-upload-list-cover">
                    <Icon type="ios-eye-outline" @click="deleteEditImage"></Icon>
                  </div>
                </div>
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
          <v-btn type="default" color="blue darken-1" text @click="editModal = false">Batal</v-btn>
          <v-btn
            color="blue darken-2"
            text
            @click="editUpload"
            :disabled="isAdding"
            :loading="isAdding"
          >{{ isAdding ? "Adding.." : "Edit upload" }}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <Modal v-model="showDeleteModal" width="360" :styles="{ top: '20px' }">
      <p slot="header" style="color:#f60;text-align:center">
        <Icon type="ios-information-circle"></Icon>
        <span>Delete confirmation</span>
      </p>
      <div style="text-align:center">
        <p>Apakah anda yakin akan menghapus upload ini?</p>
      </div>
      <div slot="footer">
        <Button
          type="error"
          size="small"
          long
          :loading="isDeleting"
          :disabled="isDeleting"
          @click="deleteUpload"
        >Delete</Button>
      </div>
    </Modal>
    <v-spacer></v-spacer>
    <v-banner class="text-center">
      <v-pagination v-model="page" :length="4" prev-icon="mdi-menu-left" next-icon="mdi-menu-right"></v-pagination>
    </v-banner>
  </v-card>
</template>


<script>
export default {
  data() {
    return {
      data: {
        tgl: "",
        bukti: "",
        keterangan: "",
      },
      addModal: false,
      editModal: false,
      isAdding: false,
      uploads: [],
      editData: {
        tgl: "",
        bukti: "",
        keterangan: "",
      },
      index: -1,
      showDeleteModal: false,
      isDeleting: false,
      deleteItem: {},
      DeletingIndex: -1,
      token: "",
      isIconImageNew: false,
      isEditingItem: false,
      page: 1,
      items: [
        {
          text: "Home",
          disabled: false,
          href: "/",
        },
        {
          text: "Upload",
          disabled: false,
          href: "/upload",
        },
      ],
    };
  },
  methods: {
    async addUpload() {
      if (this.data.tgl.trim() == "") {
        return this.swr("Tag tanggal is required");
      } else if (this.data.bukti.trim() == "") {
        return this.swr("Tag bukti is required");
      } else if (this.data.keterangan.trim() == "") {
        return this.swr("Tag keterangan is required");
      }
      const res = await this.callApi("post", "app/create_upload", this.data);

      if (res.status === 201) {
        this.uploads.unshift(res.data);
        this.s("upload berhasil ditambahkan");
        this.addModal = false;
        this.data.tgl = "";
        this.data.bukti = "";
        this.data.keterangan = "";
      } else {
        if (res.status === 422) {
          if (res.data.errors.tgl) {
            this.e(res.data.errors.tgl[0]);
          } else if (res.data.errors.bukti) {
            this.e(res.data.errors.bukti[0]);
          } else if (res.data.errors.keterangan) {
            this.e(res.data.errors.keterangan[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    async editUpload() {
      if (this.editData.tgl.trim() == "") {
        return this.swr("Tag tgl is required");
      } else if (this.editData.bukti.trim() == "") {
        return this.swr("Tag bukti is required");
      } else if (this.editData.keterangan.trim() == "") {
        return this.swr("Tag keterangan is required");
      }
      const res = await this.callApi("post", "app/edit_upload", this.editData);

      if (res.status === 200) {
        this.uploads[this.index].tgl = this.editData.tgl;
        this.uploads[this.index].bukti = this.editData.bukti;
        this.uploads[this.index].keterangan = this.editData.keterangan;
        this.s("upload berhasil diubah");
        this.editModal = false;
      } else {
        if (res.status === 422) {
          if (res.data.errors.tgl) {
            this.e(res.data.errors.tgl[0]);
          } else if (res.data.errors.bukti) {
            this.e(res.data.errors.bukti[0]);
          } else if (res.data.errors.keterangan) {
            this.e(res.data.errors.keterangan[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(upload, index) {
      let obj = {
        id: upload.id,
        tgl: upload.tgl,
        bukti: upload.bukti,
        keterangan: upload.keterangan,
      };
      this.editData = obj;
      this.editModal = true;
      this.index = index;
      this.isEditingItem = true;
    },
    async deleteUpload() {
      //let image = this.data.bukti;
      //this.data.bukti = "";
      this.isDeleting = true;
      const res = await this.callApi(
        "post",
        "app/delete_upload",
        this.deleteItem
      );
      if (res.status === 200) {
        this.uploads.splice(this.DeletingIndex, 1);
        this.s("upload berhasil dihapus");
      } else {
        this.swr();
      }
      this.isDeleting = false;
      this.showDeleteModal = false;
    },
    showDeletingModal(upload, i) {
      this.deleteItem = upload;
      this.DeletingIndex = i;
      this.showDeleteModal = true;
    },
    handleSuccess(res, file) {
      if (this.isEditingItem) {
        return (this.editData.bukti = res);
      }
      this.data.bukti = res;
    },
    handleError(res, file) {
      this.$Notice.warning({
        title: "The file format is incorrect",
        desc: `${
          file.error.file.length ? file.error.file[0] : "Ada sesuatu yang salah"
        }`,
      });
    },
    handleFormatError(file) {
      this.$Notice.warning({
        title: "The file format is incorrect",
        desc:
          "File format of " +
          file.name +
          " is incorrect, please select jpg or png.",
      });
    },
    handleMaxSize(file) {
      this.$Notice.warning({
        title: "Exceeding file size limit",
        desc: "File  " + file.name + " is too large, no more than 2M.",
      });
    },
    async deleteImage() {
      let image = this.data.bukti;
      this.data.bukti = "";
      this.$refs.upload.clearFiles();
      const res = await this.callApi("post", "app/delete_image", {
        imageName: image,
      });
      if (res.status != 200) {
        this.data.bukti = image;
        this.swr();
      }
    },
    async deleteEditImage() {
      let image = this.editData.bukti;
      this.isIconImageNew = true;
      this.editData.bukti = "";
      this.$refs.upload.clearFiles();
      const res = await this.callApi("post", "app/delete_image_edit", {
        imageName: image,
      });
      if (res.status != 200) {
        this.editData.bukti = image;
        this.swr();
      }
    },
  },
  async created() {
    this.token = window.Laravel.csrfToken;
    const res = await this.callApi("get", "app/get_uploads");
    if (res.status == 200) {
      this.uploads = res.data;
    } else {
      this.e();
    }
  },
};
</script>
