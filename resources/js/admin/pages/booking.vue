<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div
                    class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
                >
                    <p class="_title0">
                        Daftar Booking
                        <Button @click="addModal = true"
                            ><Icon type="md-add" /> Add Booking</Button
                        >
                    </p>
                    <div class="_overflow _table_div">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>No Booking</th>
                                <th>Nama</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                            <!-- ITEMS -->
                            <tr v-for="(member, i) in members" :key="i">
                                <td>{{ member.id }}</td>
                                <td class="_table_name">
                                    {{ member.nama }}
                                </td>
                                <td>{{ member.email }}</td>
                                <td>{{ member.no_hp }}</td>
                                <td>{{ member.alamat }}</td>
                                <td class="_table_name">
                                    {{ member.nm_club }}
                                </td>
                                <td>
                                    <Button
                                        type="info"
                                        size="small"
                                        @click="showEditModal(member, i)"
                                    >
                                        Edit
                                    </Button>
                                    <Button
                                        type="error"
                                        size="small"
                                        @click="showDeletingModal(member, i)"
                                        :loading="member.isDeleting"
                                    >
                                        Delete
                                    </Button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- modal untuk tambah member -->
                <Modal
                    v-model="addModal"
                    title="Add Member"
                    :styles="{ top: '20px' }"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Form v-model="addModal" class="col-14">
                        <div class="form-group row">
                            <label for="nama" class="col-3 col-form-label"
                                >Nama</label
                            >
                            <div class="col-9">
                                <Input v-model="data.nama" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-3 col-form-label"
                                >Email</label
                            >
                            <div class="col-9">
                                <Input v-model="data.email" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_hp" class="col-3 col-form-label"
                                >No Hp</label
                            >
                            <div class="col-9">
                                <Input v-model="data.no_hp" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="alamat" class="col-3 col-form-label"
                                >Alamat</label
                            >
                            <div class="col-9">
                                <Input v-model="data.alamat" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nm_club" class="col-3 col-form-label"
                                >Nama Club</label
                            >
                            <div class="col-9">
                                <Input v-model="data.nm_club" />
                            </div>
                        </div>
                    </Form>
                    <div slot="footer">
                        <Button type="default" @click="addModal = false"
                            >Close</Button
                        >
                        <Button
                            type="primary"
                            @click="addMember"
                            :disabled="isAdding"
                            :loading="isAdding"
                            >{{ isAdding ? "Adding.." : "Add Member" }}</Button
                        >
                    </div>
                </Modal>

                <!-- modal untuk edit member -->
                <Modal
                    v-model="editModal"
                    title="Edit Member"
                    :styles="{ top: '20px' }"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Form v-model="editModal" class="col-14">
                        <div class="form-group row">
                            <label for="nama" class="col-3 col-form-label"
                                >Nama</label
                            >
                            <div class="col-9">
                                <Input v-model="editData.nama" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-3 col-form-label"
                                >Email</label
                            >
                            <div class="col-9">
                                <Input v-model="editData.email" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="no_hp" class="col-3 col-form-label"
                                >No Hp</label
                            >
                            <div class="col-9">
                                <Input v-model="editData.no_hp" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="alamat" class="col-3 col-form-label"
                                >Alamat</label
                            >
                            <div class="col-9">
                                <Input v-model="editData.alamat" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nm_club" class="col-3 col-form-label"
                                >Nama Club</label
                            >
                            <div class="col-9">
                                <Input v-model="editData.nm_club" />
                            </div>
                        </div>
                    </Form>
                    <div slot="footer">
                        <Button type="default" @click="editModal = false"
                            >Close</Button
                        >
                        <Button
                            type="primary"
                            @click="editMember"
                            :disabled="isAdding"
                            :loading="isAdding"
                            >{{
                                isAdding ? "Editing.." : "Edit Member"
                            }}</Button
                        >
                    </div>
                </Modal>

                <!-- modal untuk delete member -->
                <Modal
                    v-model="showDeleteModal"
                    width="360"
                    :styles="{ top: '20px' }"
                >
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
                            size="large"
                            long
                            :loading="isDeleting"
                            :disabled="isDeleting"
                            @click="deleteMember"
                            >Delete</Button
                        >
                    </div>
                </Modal>
            </div>
        </div>
    </div>
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
                nm_club: ""
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
                nm_club: ""
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deleteItem: {},
            DeletingIndex: -1
        };
    },
    methods: {
        async addMember() {
            if (this.data.nama.trim() == "") {
                return this.swr("Tag name is required");
            } else if (this.data.email.trim() == "") {
                return this.swr("Tag email is required");
            } else if (this.data.no_hp.trim() == "") {
                return this.swr("Tag no hp is required");
            } else if (this.data.alamat.trim() == "") {
                return this.swr("Tag alamat is required");
            } else if (this.data.nm_club.trim() == "") {
                return this.swr("Tag nama club is required");
            }
            const res = await this.callApi(
                "post",
                "members/create_member",
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
                "members/edit_member",
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
                nm_club: member.nm_club
            };
            this.editData = obj;
            this.editModal = true;
            this.index = index;
        },
        async deleteMember() {
            this.isDeleting = true;
            const res = await this.callApi(
                "post",
                "members/delete_member",
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
        }
    },
    async created() {
        const res = await this.callApi("get", "members/get_members");
        if (res.status == 200) {
            this.members = res.data;
        } else {
            this.e();
        }
    }
};
</script>
