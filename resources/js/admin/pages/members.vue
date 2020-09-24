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
      List Admin members
      <v-dialog v-model="addModal" persistent max-width="600px">
        <template v-slot:activator="{ on, attrs }">
          <v-btn depressed x-small class="blue white--text" dark v-bind="attrs" v-on="on">
                <v-icon x-small>mdi-plus</v-icon>
                <span>Add Member</span> 
                </v-btn>
        </template>
        <v-card v-model="addModal">
          <v-card-title>
            <span class="headline">Add Member</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12">
                  <v-text-field v-model="data.nama" for="nama" label="Nama Lengkap*" type="text"></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field v-model="data.email" for="email" label="Email*" type="email"></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field v-model="data.no_hp" for="no_hp" label="No_Hp*" type="int"></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field v-model="data.alamat" for="alamat" label="Alamat*" type="text"></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field v-model="data.nm_club" for="nm_club" label="Nama Club*" type="text"></v-text-field>
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
              @click="addMember"
              :disabled="isAdding"
              :loading="isAdding"
            >{{ isAdding ? "Adding.." : "Add Member" }}</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-banner>
    <v-card class="mx-auto" max-width="auto" outlined>
      <v-simple-table dense class="d-flex flex-column-reverse">
        <thead>
          <tr class="_table">
            <th class="text-left">ID</th>
            <th class="text-left">Nama Lengkap</th>
            <th class="text-left">Email</th>
            <th class="text-left">No Hp</th>
            <th class="text-left">Alamat</th>
            <th class="text-left">Nama Club</th>
            <th class="text-left">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(member, i) in members" :key="i">
            <td>{{ member.id }}</td>
            <td>{{ member.nama }}</td>
            <td>{{ member.email }}</td>
            <td>{{ member.no_hp }}</td>
            <td>{{ member.alamat }}</td>
            <td>{{ member.nm_club }}</td>
            <td>
            <v-btn depressed x-small class="light-blue white--text" @click="showEditModal(member, i)">
                  <v-icon small>mdi-pencil</v-icon>
                  <span>Edit</span>        
            </v-btn>
              <v-btn depressed x-small class="red white--text"
                @click="showDeletingModal(member, i)"
                :loading="member.isDeleting">
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
          <span class="headline">Edit Member</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field v-model="editData.nama" for="nama" label="Nama Lengkap*" type="text"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field v-model="editData.email" for="email" label="Email*" type="email"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field v-model="editData.no_hp" for="no_hp" label="No_Hp*" type="int"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field v-model="editData.alamat" for="alamat" label="Alamat*" type="text"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                  v-model="editData.nm_club"
                  for="nm_club"
                  label="Nama Club*"
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
            @click="editMember"
            :disabled="isAdding"
            :loading="isAdding"
          >{{ isAdding ? "Adding.." : "Edit Member" }}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <Modal v-model="showDeleteModal" width="360" :styles="{ top: '20px' }">
            <p slot="header" style="color:#f60;text-align:center">
              <Icon type="ios-information-circle"></Icon>
              <span>Delete confirmation</span>
            </p>
            <div style="text-align:center">
              <p>Apakah anda yakin akan menghapus member ini?</p>
            </div>
            <div slot="footer">
              <Button
                type="error"
                size="small"
                long
                :loading="isDeleting"
                :disabled="isDeleting"
                @click="deleteMember"
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
        nama: "",
        email: "",
        no_hp: "",
        alamat: "",
        nm_club: "",
      },
      addModal: false,
      editModal: false,
      isAdding: false,
      members: [],
      editData: {
        nama: "",
        email: "",
        no_hp: "",
        alamat: "",
        nm_club: "",
      },
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
          text: 'Members',
          disabled: false,
          href: '/members',
        },
      ],
    };
  },
  methods: {
    async addMember() {
      if (this.data.nama.trim() == "") {
        return this.swr("Nama tidak boleh kosong!!");
      } else if (this.data.email.trim() == "") {
        return this.swr("Email tidak boleh kosong!!");
      } else if (this.data.no_hp.trim() == "") {
        return this.swr("No Hp tidak boleh kosong!!");
      } else if (this.data.alamat.trim() == "") {
        return this.swr("Alamat tidak boleh kosong!!");
      } else if (this.data.nm_club.trim() == "") {
        return this.swr("Nama Club tidak boleh kosong!!");
      }
      const res = await this.callApi(
        "post",
        "app/create_member",
        this.data
      );

      if (res.status === 201) {
        this.members.unshift(res.data);
        this.s("Member berhasil ditambahkan");
        this.addModal = false;
        this.data.nama = "";
        this.data.email = "";
        this.data.no_hp = "";
        this.data.alamat = "";
        this.data.nm_club = "";
      } else {
        if (res.status === 422) {
          if (res.data.errors.nama) {
            this.e(res.data.errors.nama[0]);
          } else if (res.data.errors.email) {
            this.e(res.data.errors.email[0]);
          } else if (res.data.errors.no_hp) {
            this.e(res.data.errors.no_hp[0]);
          } else if (res.data.errors.alamat) {
            this.e(res.data.errors.alamat[0]);
          } else if (res.data.errors.nm_club) {
            this.e(res.data.errors.nm_club[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    async editMember() {
      if (this.editData.nama.trim() == "") {
        return this.swr("Tag name is required");
      } else if (this.editData.email.trim() == "") {
        return this.swr("Tag email is required");
      } else if (this.editData.no_hp.trim() == "") {
        return this.swr("Tag no hp is required");
      } else if (this.editData.alamat.trim() == "") {
        return this.swr("Tag alamat is required");
      } else if (this.editData.nm_club.trim() == "") {
        return this.swr("Tag nama club is required");
      }
      const res = await this.callApi(
        "post",
        "app/edit_member",
        this.editData
      );

      if (res.status === 200) {
        this.members[this.index].nama = this.editData.nama;
        this.members[this.index].email = this.editData.email;
        this.members[this.index].no_hp = this.editData.no_hp;
        this.members[this.index].alamat = this.editData.alamat;
        this.members[this.index].nm_club = this.editData.nm_club;
        this.s("Member berhasil diubah");
        this.editModal = false;
      } else {
        if (res.status === 422) {
          if (res.data.errors.nama) {
            this.e(res.data.errors.nama[0]);
          } else if (res.data.errors.email) {
            this.e(res.data.errors.email[0]);
          } else if (res.data.errors.no_hp) {
            this.e(res.data.errors.no_hp[0]);
          } else if (res.data.errors.alamat) {
            this.e(res.data.errors.alamat[0]);
          } else if (res.data.errors.nm_club) {
            this.e(res.data.errors.nm_club[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(member, index) {
      let obj = {
        id: member.id,
        nama: member.nama,
        email: member.email,
        no_hp: member.no_hp,
        alamat: member.alamat,
        nm_club: member.nm_club,
      };
      this.editData = obj;
      this.editModal = true;
      this.index = index;
    },
    async deleteMember() {
      this.isDeleting = true;
      const res = await this.callApi(
        "post",
        "app/delete_member",
        this.deleteItem
      );
      if (res.status === 200) {
        this.members.splice(this.DeletingIndex, 1);
        this.s("Member berhasil dihapus");
      } else {
        this.swr();
      }
      this.isDeleting = false;
      this.showDeleteModal = false;
    },
    showDeletingModal(member, i) {
      this.deleteItem = member;
      this.DeletingIndex = i;
      this.showDeleteModal = true;
    },
  },
  async created() {
    const res = await this.callApi("get", "app/get_members");
    if (res.status == 200) {
      this.members = res.data;
    } else {
      this.e();
    }
  },
};
</script>

