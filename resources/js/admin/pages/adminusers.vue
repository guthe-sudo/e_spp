<template>
  <v-card class="d-block pa-2 accent-4 black--text basil">
    <v-breadcrumbs :items="items">
      <template v-slot:item="{ item }">
        <v-breadcrumbs-item
          :href="item.href"
          :disabled="item.disabled"
        >{{ item.text.toUpperCase() }}</v-breadcrumbs-item>
      </template>
    </v-breadcrumbs>
    <v-toolbar flat>
      <span> List Admin </span>
      <v-spacer></v-spacer>
      <v-dialog v-model="addModal" persistent max-width="600px">
        <template v-slot:activator="{ on, attrs }">
          <v-btn depressed x-small class="green white--text" dark v-bind="attrs" v-on="on">
                <v-icon x-small>mdi-plus</v-icon>
                <span>Add user</span> 
                </v-btn>
        </template>
        <v-card v-model="addModal">
          <v-card-title>
            <span class="headline">Add Admin</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    v-model="data.fullName"
                    for="fullName"
                    label="Nama Lengkap*"
                    type="text"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    v-model="data.email"
                    for="email"
                    label="Email*"
                    type="email"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    v-model="data.password"
                    for="password"
                    label="Password*"
                    type="password"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-select
                    label="Pilih Hak Akses"
                    v-model="data.id_role"
                    for="id_role"
                    :items="role"
                    item-value="id_role"
                    item-text="roleName"
                  >
                  </v-select>
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
              @click="addAdmin(); getAdmin()"
              :disabled="isAdding"
              :loading="isAdding"
            >{{ isAdding ? "Adding.." : "Add Admin" }}</v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-toolbar>
    <v-card class="mx-auto" max-width="auto" outlined>
      <v-simple-table dense class="d-flex flex-column-reverse">
        <thead>
          <tr  class="_table">
            <th class="text-center">No</th>
            <th class="text-center">Nama Lengkap</th>
            <th class="text-center">Email</th>
            <th class="text-center">Hak Akses</th>
            <th class="text-center">Created At</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(user, i) in users" :key="i">
            <td class="text-center">{{ i+1 }}</td>
            <td class="text-center">{{ user.fullName }}</td>
            <td class="text-center">{{ user.email }}</td>
            <td class="text-center">{{ user.roleName }}</td>
            <td class="text-center">{{ user.created_at | formatDate}}</td>
            <td class="text-center">
            <v-btn depressed x-small class="light-blue white--text" @click="showEditModal(user, i)">
                  <v-icon small>mdi-pencil</v-icon>
                  <span>Edit</span>        
            </v-btn>
              <v-btn depressed x-small class="red white--text"
                @click="showDeletingModal(user, i)"
                :loading="user.isDeleting">
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
          <span class="headline">Edit Admin</span>
        </v-card-title>
        <v-card-text>
          <v-container>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    v-model="editData.fullName"
                    for="fullName"
                    label="Nama Lengkap*"
                    type="text"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    v-model="editData.email"
                    for="email"
                    label="Email*"
                    type="email"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-text-field
                    v-model="editData.password"
                    for="password"
                    label="Password*"
                    type="password"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <v-select
                    label="Pilih Hak Akses"
                    v-model="editData.id_role"
                    for="id_role"
                    :items="role"
                    item-value="id_role"
                    item-text="roleName"
                  >
                  </v-select>
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
            @click="editAdmin(); getAdmin()"
            :disabled="isAdding"
            :loading="isAdding"
          >{{ isAdding ? "Adding.." : "Edit Admin" }}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
    <Modal v-model="showDeleteModal" width="360" :styles="{ top: '20px' }">
            <p slot="header" style="color:#f60;text-align:center">
              <Icon type="ios-information-circle"></Icon>
              <span>Delete confirmation</span>
            </p>
            <div style="text-align:center">
              <p>Apakah anda yakin akan menghapus data ini?</p>
            </div>
            <div slot="footer">
              <Button
                type="error"
                size="small"
                long
                :loading="isDeleting"
                :disabled="isDeleting"
                @click="deleteAdmin"
              >Delete</Button>
            </div>
          </Modal>
  </v-card>
</template>



<script>
export default {
  data() {
    return {
      data: {
        fullName: "",
        email: "",
        password: "",
        id_role: "",
      },
      addModal: false,
      editModal: false,
      isAdding: false,
      users: [],
      role: [],
      editData: {
        fullName: "",
        email: "",
        password: "",
        id_role: ""
      },
      index: -1,
      showDeleteModal: false,
      isDeleting: false,
      deleteItem: {},
      DeletingIndex: -1,
      items: [
        {
          text: 'Home',
          disabled: false,
          href: '/',
        },
        {
          text: 'Admin',
          disabled: false,
          href: '/adminusers',
        },
      ],
      pagination: {
        page: 1,
        total: 0,
        perPage: 0,
        visible: 7
      }
    };
  },
  methods: {
    async getAdmin() {
      const [user, r] = await Promise.all([
        this.callApi("get", "app/get_users"),
        this.callApi("get", "app/get_roles"),
      ]);
      if (user.status == 200) {
        this.users = user.data;
        this.role = r.data;
      } else {
        this.e();
      }
    },
    async addAdmin() {
      if (this.data.fullName.trim() == "") {
        return this.swr("Nama Lengkap tidak boleh kosong!!");
      } else if (this.data.email.trim() == "") {
        return this.swr("Email tidak boleh kosong!!");
      } else if (this.data.password.trim() == "") {
        return this.swr("Password tidak boleh kosong!!");
      }
      const res = await this.callApi("post", "app/create_user", this.data);

      if (res.status === 201) {
        this.users.unshift(res.data);
        this.s("admin berhasil ditambahkan");
        this.addModal = false;
        this.data.fullName = "";
        this.data.email = "";
        this.data.password = "";
        this.data.id_role= "";
      } else {
        if (res.status === 422) {
          for(let i in res.data.errors){
            this.e(res.data.errors[i][0])
          }
        } else {
          this.swr();
        }
      }
    },
    async editAdmin() {
      if (this.editData.fullName.trim() == "") {
        return this.swr("Nama Lengkap tidak boleh kosong!!");
      } else if (this.editData.email.trim() == "") {
        return this.swr("Email tidak boleh kosong!!");
      }
      const res = await this.callApi(
        "post",
        "app/edit_user",
        this.editData
      );

      if (res.status === 200) {
        this.users[this.index].fullName = this.editData.fullName;
        this.users[this.index].email = this.editData.email;
        this.users[this.index].id_role = this.editData.id_role;
        this.s("user berhasil diubah");
        this.editModal = false;
      } else {
        if (res.status === 422) {
          for(let i in res.data.errors){
            this.e(res.data.errors[i][0])
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(user, index) {
      let obj = {
        id: user.id,
        fullName: user.fullName,
        email: user.email,
        id_role: user.id_role
      };
      this.editData = obj;
      this.editModal = true;
      this.index = index;
    },
    async deleteAdmin() {
      this.isDeleting = true;
      const res = await this.callApi(
        "post",
        "app/delete_user",
        this.deleteItem
      );
      if (res.status === 200) {
        this.users.splice(this.DeletingIndex, 1);
        this.s("admin berhasil dihapus");
      } else {
        this.swr();
      }
      this.isDeleting = false;
      this.showDeleteModal = false;
    },
    showDeletingModal(user, i) {
      this.deleteItem = user;
      this.DeletingIndex = i;
      this.showDeleteModal = true;
    }
  },
  async created() {
    const [user, r] = await Promise.all([
        this.callApi("get", "app/get_users"),
        this.callApi("get", "app/get_roles"),
      ]);
      if (user.status == 200) {
        this.users = user.data;
        this.role = r.data;
      } else {
        this.e();
      }
  }
};

</script>
