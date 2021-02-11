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
      <span> Data Siswa </span>
      <v-spacer></v-spacer>
      <v-dialog v-model="addModal" persistent max-width="1200px">
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
            <span>Add Siswa</span>
          </v-btn>
        </template>
        <v-card v-model="addModal">
          <v-card-title>
            <span class="headline">Add Siswa</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="6">
                  <v-text-field
                    v-model="data.nis"
                    for="nis"
                    label="NIS*"
                    type="text"
                  ></v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    v-model="data.nama"
                    for="nama"
                    label="Nama Siswa*"
                    type="text"
                  ></v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    v-model="data.tempat_lahir"
                    for="tempat_lahir"
                    label="Tempat Lahir*"
                    type="text"
                  ></v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-menu
                    ref="menu"
                    v-model="menu"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="data.tgl_lahir"
                        for="tgl_lahir"
                        label="Tanggal Lahir"
                        prepend-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      ref="picker"
                      v-model="data.tgl_lahir"
                      :max="new Date().toISOString().substr(0, 10)"
                      min="1950-01-01"
                      @change="save"
                    ></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="6">
                  <v-select
                    v-model="data.jk"
                    for="jk"
                    label="Jenis Kelamin*"
                    type="text"
                    :items="jk"
                    item-value="value"
                    item-text="label"
                  ></v-select>
                </v-col>
                <v-col cols="6">
                  <v-select
                    label="Pilih Tahun Akademik"
                    v-model="data.id_tahun_akademik"
                    for="id_tahun_akademik"
                    :items="tahun_akademik"
                    item-value="id_tahun_akademik"
                    :item-text="
                      (tahun_akademik) =>
                        tahun_akademik.nama_tahun +
                        '  --  ' +
                        tahun_akademik.keterangan
                    "
                  >
                  </v-select>
                </v-col>
                <v-col cols="6">
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
                <v-col cols="6">
                  <v-select
                    label="Pilih Jurusan"
                    v-model="data.id_jurusan"
                    for="id_jurusan"
                    :items="jurusan"
                    item-value="id_jurusan"
                    item-text="nama_jurusan"
                  >
                  </v-select>
                </v-col>
                <v-col cols="6">
                  <label for="alamat">Alamat</label>
                  <Input v-model="data.alamat" type="textarea" size="large" />
                </v-col>
                <v-col cols="6">
                  <label for="foto">Foto</label>
                  <Input v-model="data.foto" hidden />
                  <v-spacer></v-spacer>
                  <Upload
                    ref="siswa"
                    type="drag"
                    :headers="{
                      'x-csrf-token': token,
                      'X-Requested-with': 'XMLHttpRequest',
                    }"
                    :on-success="handleSuccess"
                    :on-error="handleError"
                    :format="['jpg', 'jpeg', 'png']"
                    :max-size="2048"
                    :on-format-error="handleFormatError"
                    :on-exceeded-size="handleMaxSize"
                    action="app/siswa"
                  >
                    <Icon
                      type="ios-cloud-upload"
                      size="small"
                      style="color: #3399ff"
                    ></Icon>
                    <p>Click or drag files here to upload</p>
                  </Upload>
                  <div class="demo-upload-list" v-if="data.foto">
                    <img :src="`/uploads/${data.foto}`" />
                    <div class="demo-upload-list-cover">
                      <Icon type="ios-eye-outline" @click="deleteImage"></Icon>
                    </div>
                  </div>
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
                addSiswa();
                getSiswa();
              "
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Adding.." : "Add Siswa" }}</v-btn
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
            <th class="text-center">NIS</th>
            <th class="text-center">Nama Siswa</th>
            <th class="text-center">Tempat Lahir</th>
            <th class="text-center">Tanggal Lahir</th>
            <th class="text-center">Jenis Kelamin</th>
            <th class="text-center">Tahun Akademik</th>
            <th class="text-center">Tingkat</th>
            <th class="text-center">Jurusan</th>
            <th class="text-center">Alamat</th>
            <th class="text-center">Foto</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(siswa, i) in siswas" :key="i">
            <td class="text-center">{{ i + 1 }}</td>
            <td class="text-center">{{ siswa.nis }}</td>
            <td class="text-center">{{ siswa.nama }}</td>
            <td class="text-center">{{ siswa.tempat_lahir }}</td>
            <td class="text-center">{{ siswa.tgl_lahir }}</td>
            <td class="text-center">{{ siswa.jk }}</td>
            <td class="text-center">{{ siswa.nama_tahun }}</td>
            <td class="text-center">{{ siswa.tingkat }}</td>
            <td class="text-center">{{ siswa.nama_jurusan }}</td>
            <td class="text-center">{{ siswa.alamat }}</td>
            <td class="table_image">
              <img :src="`/uploads/` + siswa.foto" />
            </td>
            <td class="text-center">
              <v-btn
                depressed
                x-small
                class="light-blue white--text"
                @click="showEditModal(siswa, i)"
              >
                <v-icon small>mdi-pencil</v-icon>
                <span>Edit</span>
              </v-btn>
              <v-btn
                depressed
                x-small
                class="red white--text"
                @click="showDeletingModal(siswa, i)"
                :loading="siswa.isDeleting"
              >
                <v-icon small>mdi-trash-can-outline</v-icon>
                <span>Delete</span>
              </v-btn>
            </td>
          </tr>
        </tbody>
      </v-simple-table>
    </v-card>
    <v-dialog v-model="editModal" persistent max-width="1200px">
      <v-card v-model="editModal">
        <v-card-title>
          <span class="headline">Edit Siswa</span>
        </v-card-title>
        <v-card-text>
          <v-container>
              <v-row>
                <v-col cols="6">
                  <v-text-field
                    v-model="editData.nis"
                    for="nis"
                    label="NIS*"
                    type="text"
                  ></v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    v-model="editData.nama"
                    for="nama"
                    label="Nama Siswa*"
                    type="text"
                  ></v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    v-model="editData.tempat_lahir"
                    for="tempat_lahir"
                    label="Tempat Lahir*"
                    type="text"
                  ></v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-menu
                    ref="menu"
                    v-model="menu"
                    :close-on-content-click="false"
                    transition="scale-transition"
                    offset-y
                    min-width="290px"
                  >
                    <template v-slot:activator="{ on, attrs }">
                      <v-text-field
                        v-model="editData.tgl_lahir"
                        for="tgl_lahir"
                        label="Tanggal Lahir"
                        prepend-icon="mdi-calendar"
                        readonly
                        v-bind="attrs"
                        v-on="on"
                      ></v-text-field>
                    </template>
                    <v-date-picker
                      ref="picker"
                      v-model="editData.tgl_lahir"
                      :max="new Date().toISOString().substr(0, 10)"
                      min="1950-01-01"
                      @change="save"
                    ></v-date-picker>
                  </v-menu>
                </v-col>
                <v-col cols="6">
                  <v-select
                    v-model="editData.jk"
                    for="jk"
                    label="Jenis Kelamin*"
                    type="text"
                    :items="jk"
                    item-value="value"
                    item-text="label"
                  ></v-select>
                </v-col>
                <v-col cols="6">
                  <v-select
                    label="Pilih Tahun Akademik"
                    v-model="editData.id_tahun_akademik"
                    for="id_tahun_akademik"
                    :items="tahun_akademik"
                    item-value="id_tahun_akademik"
                    :item-text="
                      (tahun_akademik) =>
                        tahun_akademik.nama_tahun +
                        '  --  ' +
                        tahun_akademik.keterangan
                    "
                  >
                  </v-select>
                </v-col>
                <v-col cols="6">
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
                <v-col cols="6">
                  <v-select
                    label="Pilih Jurusan"
                    v-model="editData.id_jurusan"
                    for="id_jurusan"
                    :items="jurusan"
                    item-value="id_jurusan"
                    item-text="nama_jurusan"
                  >
                  </v-select>
                </v-col>
                <v-col cols="6">
                  <label for="alamat">Alamat</label>
                  <Input v-model="editData.alamat" type="textarea" size="large" />
                </v-col>
                <v-col cols="6">
                <label for="foto">Foto</label>
                <Input v-model="editData.foto" hidden />
                <v-spacer></v-spacer>
                <Upload
                  v-show="isIconImageNew"
                  ref="siswa"
                  type="drag"
                  :headers="{
                    'x-csrf-token': token,
                    'X-Requested-with': 'XMLHttpRequest',
                  }"
                  :on-success="handleSuccess"
                  :on-error="handleError"
                  :format="['jpg', 'jpeg', 'png']"
                  :max-size="2048"
                  :on-format-error="handleFormatError"
                  :on-exceeded-size="handleMaxSize"
                  action="app/siswa"
                >
                  <Icon
                    type="ios-cloud-upload"
                    size="small"
                    style="color: #3399ff"
                  ></Icon>
                  <p>Click or drag files here to upload</p>
                </Upload>
                <div class="demo-upload-list" v-if="editData.foto">
                  <img :src="`/uploads/${editData.foto}`" />
                  <div class="demo-upload-list-cover">
                    <Icon
                      type="ios-eye-outline"
                      @click="deleteEditImage"
                    ></Icon>
                  </div>
                </div>
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
              editSiswa();
              getSiswa();
            "
            :disabled="isAdding"
            :loading="isAdding"
            >{{ isAdding ? "Adding.." : "Edit Siswa" }}</v-btn
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
          @click="deleteSiswa"
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
        nis: "",
        nama: "",
        tempat_lahir: "",
        tgl_lahir: "",
        jk: "",
        id_tahun_akademik: "",
        id_tingkat: "",
        id_jurusan: "",
        alamat: "",
        foto: "",
      },
      jk: [
        {
          value: "Laki-laki",
          label: "Laki-laki",
        },
        {
          value: "Perempuan",
          label: "Perempuan",
        },
      ],
      date: null,
      menu: false,
      row: null,
      addModal: false,
      editModal: false,
      isAdding: false,
      siswas: [],
      tahun_akademik: [],
      tingkat: [],
      jurusan: [],
      editData: {
        nis: "",
        nama: "",
        tempat_lahir: "",
        tgl_lahir: "",
        jk: "",
        id_tahun_akademik: "",
        id_tingkat: "",
        id_jurusan: "",
        alamat: "",
        foto: "",
      },
      index: -1,
      showDeleteModal: false,
      isDeleting: false,
      deleteItem: {},
      DeletingIndex: -1,
      token: "",
      isIconImageNew: false,
      isEditingItem: false,
      items: [
        {
          text: "Home",
          disabled: false,
          href: "/",
        },
        {
          text: "Siswa",
          disabled: false,
          href: "/siswa",
        },
      ],
    };
  },
  watch: {
    menu(val) {
      val && setTimeout(() => (this.$refs.picker.activePicker = "YEAR"));
    },
  },
  methods: {
    save(tgl_lahir) {
      this.$refs.menu.save(tgl_lahir);
    },
    async getSiswa() {
      this.token = window.Laravel.csrfToken;
      const [siswa, a, t, j] = await Promise.all([
        this.callApi("get", "app/get_siswa"),
        this.callApi("get", "app/get_tahun_akademik"),
        this.callApi("get", "app/get_tingkat"),
        this.callApi("get", "app/get_jurusan"),
      ]);
      if (siswa.status == 200) {
        this.siswas = siswa.data;
        this.tahun_akademik = a.data;
        this.tingkat = t.data;
        this.jurusan = j.data;
      } else {
        this.e();
      }
    },
    async addSiswa() {
      if (this.data.nis.trim() == "") {
        return this.swr("NIS tidak boleh kosong!!");
      } else if (this.data.nama.trim() == "") {
        return this.swr("Nama tidak boleh kosong!!");
      } else if (this.data.tempat_lahir.trim() == "") {
        return this.swr("Tempat Lahir tidak boleh kosong!!");
      } else if (this.data.tgl_lahir.trim() == "") {
        return this.swr("Tanggal Lahir tidak boleh kosong!!");
      } else if (this.data.jk.trim() == "") {
        return this.swr("Jenis Kelamin tidak boleh kosong!!");
      } else if (this.data.alamat.trim() == "") {
        return this.swr("Alamat tidak boleh kosong!!");
      } else if (this.data.foto.trim() == "") {
        return this.swr("Foto tidak boleh kosong!!");
      }
      const res = await this.callApi("post", "app/create_siswa", this.data);

      if (res.status === 201) {
        this.siswas.unshift(res.data);
        this.s("Data Siswa berhasil ditambahkan");
        this.addModal = false;
        this.data.nis = "";
        this.data.nama = "";
        this.data.tempat_lahir = "";
        this.data.tgl_lahir = "";
        this.data.jk = "";
        this.data.id_tahun_akademik = "";
        this.data.id_tingkat = "";
        this.data.id_jurusan = "";
        this.data.alamat = "";
        this.data.foto = "";
      } else {
        if (res.status === 422) {
          if (res.data.errors.nis) {
            this.e(res.data.errors.nis[0]);
          } else if (res.data.errors.nama) {
            this.e(res.data.errors.nama[0]);
          } else if (res.data.errors.tempat_lahir) {
            this.e(res.data.errors.tempat_lahir[0]);
          } else if (res.data.errors.tgl_lahir) {
            this.e(res.data.errors.tgl_lahir[0]);
          } else if (res.data.errors.jk) {
            this.e(res.data.errors.jk[0]);
          } else if (res.data.errors.id_tahun_akademik) {
            this.e(res.data.errors.id_tahun_akademik[0]);
          } else if (res.data.errors.id_tingkat) {
            this.e(res.data.errors.id_tingkat[0]);
          } else if (res.data.errors.id_jurusan) {
            this.e(res.data.errors.id_jurusan[0]);
          } else if (res.data.errors.alamat) {
            this.e(res.data.errors.alamat[0]);
          } else if (res.data.errors.foto) {
            this.e(res.data.errors.foto[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    async editSiswa() {
      if (this.editData.nama.trim() == "") {
        return this.swr("Nama tidak boleh kosong!!");
      } else if (this.editData.tempat_lahir.trim() == "") {
        return this.swr("Tempat Lahir tidak boleh kosong!!");
      } else if (this.editData.tgl_lahir.trim() == "") {
        return this.swr("Tanggal Lahir tidak boleh kosong!!");
      } else if (this.editData.jk.trim() == "") {
        return this.swr("Jenis Kelamin tidak boleh kosong!!");
      } else if (this.editData.alamat.trim() == "") {
        return this.swr("Alamat tidak boleh kosong!!");
      } else if (this.editData.foto.trim() == "") {
        return this.swr("Foto tidak boleh kosong!!");
      }
      const res = await this.callApi("post", "app/edit_siswa", this.editData);

      if (res.status === 200) {
        this.siswas[this.index].nis = this.editData.nis;
        this.siswas[this.index].nama = this.editData.nama;
        this.siswas[this.index].tempat_lahir = this.editData.tempat_lahir;
        this.siswas[this.index].tgl_lahir = this.editData.tgl_lahir;
        this.siswas[this.index].jk = this.editData.jk;
        this.siswas[
          this.index
        ].id_tahun_akademik = this.editData.id_tahun_akademik;
        this.siswas[this.index].id_tingkat = this.editData.id_tingkat;
        this.siswas[this.index].id_jurusan = this.editData.id_jurusan;
        this.siswas[this.index].alamat = this.editData.alamat;
        this.siswas[this.index].foto = this.editData.foto;
        this.s(" data siswa berhasil diubah");
        this.editModal = false;
      } else {
        if (res.status === 422) {
          if (res.editData.errors.nis) {
            this.e(res.editData.errors.nis[0]);
          } else if (res.editData.errors.nama) {
            this.e(res.editData.errors.nama[0]);
          } else if (res.editData.errors.tempat_lahir) {
            this.e(res.editData.errors.tempat_lahir[0]);
          } else if (res.editData.errors.tgl_lahir) {
            this.e(res.editData.errors.tgl_lahir[0]);
          } else if (res.editData.errors.jk) {
            this.e(res.editData.errors.jk[0]);
          } else if (res.editData.errors.id_tahun_akademik) {
            this.e(res.editData.errors.id_tahun_akademik[0]);
          } else if (res.editData.errors.id_tingkat) {
            this.e(res.editData.errors.id_tingkat[0]);
          } else if (res.editData.errors.id_jurusan) {
            this.e(res.editData.errors.id_jurusan[0]);
          } else if (res.editData.errors.alamat) {
            this.e(res.editData.errors.alamat[0]);
          } else if (res.editData.errors.foto) {
            this.e(res.editData.errors.foto[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(siswa, index) {
      let obj = {
        nis: siswa.nis,
        nama: siswa.nama,
        tempat_lahir: siswa.tempat_lahir,
        tgl_lahir: siswa.tgl_lahir,
        jk: siswa.jk,
        id_tahun_akademik: siswa.id_tahun_akademik,
        id_tingkat: siswa.id_tingkat,
        id_jurusan: siswa.id_jurusan,
        alamat: siswa.alamat,
        foto: siswa.foto,
      };
      this.editData = obj;
      this.editModal = true;
      this.index = index;
      this.isEditingItem = true;
    },
    async deleteSiswa() {
      this.isDeleting = true;
      const res = await this.callApi(
        "post",
        "app/delete_siswa",
        this.deleteItem
      );
      if (res.status === 200) {
        this.siswas.splice(this.DeletingIndex, 1);
        this.s("Data Program Keahlian berhasil dihapus");
      } else {
        this.swr();
      }
      this.isDeleting = false;
      this.showDeleteModal = false;
    },
    showDeletingModal(siswa, i) {
      this.deleteItem = siswa;
      this.DeletingIndex = i;
      this.showDeleteModal = true;
    },
    handleSuccess(res, file) {
      if (this.isEditingItem) {
        return (this.editData.foto = res);
      }
      this.data.foto = res;
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
      let image = this.data.foto;
      this.data.foto = "";
      this.$refs.siswa.clearFiles();
      const res = await this.callApi("post", "app/delete_image", {
        imageName: image,
      });
      if (res.status != 200) {
        this.data.foto = image;
        this.swr();
      }
    },
    async deleteEditImage() {
      let image = this.editData.foto;
      this.isIconImageNew = true;
      this.editData.foto = "";
      this.$refs.siswa.clearFiles();
      const res = await this.callApi("post", "app/delete_image_edit", {
        imageName: image,
      });
      if (res.status != 200) {
        this.editData.foto = image;
        this.swr();
      }
    },
  },
  async created() {
    this.token = window.Laravel.csrfToken;
    const [res, a, t, j] = await Promise.all([
      this.callApi("get", "app/get_siswa"),
      this.callApi("get", "app/get_tahun_akademik"),
      this.callApi("get", "app/get_tingkat"),
      this.callApi("get", "app/get_jurusan"),
    ]);
    if (res.status == 200) {
      this.siswas = res.data;
      this.tahun_akademik = a.data;
      this.tingkat = t.data;
      this.jurusan = j.data;
    } else {
      this.e();
    }
  },
};
</script>

