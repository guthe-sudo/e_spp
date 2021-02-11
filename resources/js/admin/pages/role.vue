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
      <span> List Role </span>
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
            <span>Add Role</span>
          </v-btn>
        </template>

        <v-card v-model="addModal">
          <v-card-title>
            <span class="headline">Add Role</span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col cols="12">
                  <v-text-field
                    v-model="data.roleName"
                    for="roleName"
                    label="Role Type*"
                    type="text"
                  ></v-text-field>
                </v-col>
                <v-col cols="12">
                  <label>Pilih isAdmin</label>
                  <v-radio-group v-model="data.isAdmin" row>
                    <v-radio label="Ya" value="Ya"></v-radio>
                    <v-radio label="Tidak" value="Tidak"></v-radio>
                  </v-radio-group>
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
                addRole();
                getRoles();
              "
              :disabled="isAdding"
              :loading="isAdding"
              >{{ isAdding ? "Adding.." : "Add Role" }}</v-btn
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
            <th class="text-center">Role Type</th>
            <th class="text-center">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(role, i) in roles" :key="i">
            <td class="text-center">{{ i + 1 }}</td>
            <td class="text-center" hidden>{{ role.id_role }}</td>
            <td class="text-center">{{ role.roleName }}</td>
            <td class="text-center">
              <v-btn
                depressed
                x-small
                class="light-blue white--text"
                @click="showEditModal(role, i)"
              >
                <v-icon small>mdi-pencil</v-icon>
                <span>Edit</span>
              </v-btn>
              <v-btn
                depressed
                x-small
                class="red white--text"
                @click="showDeletingModal(role, i)"
                :loading="role.isDeleting"
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
          <span class="headline">Edit Role</span>
        </v-card-title>
        <v-card-text>
          <v-container>
            <v-row>
              <v-col cols="12">
                <v-text-field
                  v-model="editData.roleName"
                  for="roleName"
                  label="Role Type*"
                  type="text"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <label>Pilih isAdmin</label>
                <v-radio-group v-model="editData.isAdmin" row>
                  <v-radio label="Ya" value="Ya"></v-radio>
                  <v-radio label="Tidak" value="Tidak"></v-radio>
                </v-radio-group>
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
            @click="editRole"
            :disabled="isAdding"
            :loading="isAdding"
            >{{ isAdding ? "Adding.." : "Edit Role" }}</v-btn
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
          @click="deleteRole"
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
        roleName: "",
        isAdmin: "Ya",
      },
      row: null,
      addModal: false,
      editModal: false,
      isAdding: false,
      roles: [],
      editData: {
        roleName: "",
        isAdmin: "Ya",
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
          text: "role",
          disabled: false,
          href: "/role",
        },
      ],
    };
  },
  methods: {
    async getRoles() {
      const res = await this.callApi("get", "app/get_roles");
      if (res.status == 200) {
        this.roles = res.data;
      } else {
        this.e();
      }
    },
    async addRole() {
      if (this.data.roleName.trim() == "") {
        return this.swr("Role Name tidak boleh kosong!!");
      } else if (this.data.isAdmin.trim() == "") {
        return this.swr("isAdmin tidak boleh kosong!!");
      }
      const res = await this.callApi("post", "app/create_role", this.data);

      if (res.status === 201) {
        this.roles.unshift(res.data);
        this.s("role berhasil ditambahkan");
        this.addModal = false;
        this.data.roleName = "";
        this.data.isAdmin = "";
      } else {
        if (res.status === 422) {
          if (res.data.errors.roleName) {
            this.e(res.data.errors.roleName[0]);
          } else if (res.data.errors.isAdmin) {
            this.e(res.data.errors.isAdmin[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    async editRole() {
      if (this.editData.roleName.trim() == "") {
        return this.swr("Role Type tidak boleh kosong!!");
      } else if (this.editData.isAdmin.trim() == "") {
        return this.swr("isAdmin tidak boleh kosong!!");
      }
      const res = await this.callApi("post", "app/edit_role", this.editData);

      if (res.status === 200) {
        this.roles[this.index].roleName = this.editData.roleName;
        this.roles[this.index].isAdmin = this.editData.isAdmin;
        this.s("Role berhasil diubah");
        this.editModal = false;
      } else {
        if (res.status === 422) {
          if (res.data.errors.roleName) {
            this.e(res.data.errors.roleName[0]);
          } else if (res.data.errors.isAdmin) {
            this.e(res.data.errors.isAdmin[0]);
          }
        } else {
          this.swr();
        }
      }
    },
    showEditModal(role, index) {
      let obj = {
        id_role: role.id_role,
        roleName: role.roleName,
        isAdmin: role.isAdmin,
      };
      this.editData = obj;
      this.editModal = true;
      this.index = index;
    },
    async deleteRole() {
      this.isDeleting = true;
      const res = await this.callApi(
        "post",
        "app/delete_role",
        this.deleteItem
      );
      if (res.status === 200) {
        this.roles.splice(this.DeletingIndex, 1);
        this.s("Role berhasil dihapus");
      } else {
        this.swr();
      }
      this.isDeleting = false;
      this.showDeleteModal = false;
    },
    showDeletingModal(role, i) {
      this.deleteItem = role;
      this.DeletingIndex = i;
      this.showDeleteModal = true;
    },
  },
  async created() {
    const res = await this.callApi("get", "app/get_roles");
    if (res.status == 200) {
      this.roles = res.data;
    } else {
      this.e();
    }
  },
};
</script>

