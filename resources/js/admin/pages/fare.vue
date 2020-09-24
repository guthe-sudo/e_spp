<template>
    <div>
        <div class="content">
            <div class="container-fluid">
                <!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
                <div
                    class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20"
                >
                    <p class="_title0">
                        Daftar Tarif
                        <Button @click="addModal = true"
                            ><Icon type="md-add" /> add Tarif</Button
                        >
                    </p>
                    <div class="container-fluid">
                        <table class="_table">
                            <!-- TABLE TITLE -->
                            <tr>
                                <th>ID</th>
                                <th>Waktu</th>
                                <th>Harga</th>
                                <th>Action</th>
                            </tr>
                            <!-- ITEMS -->
                            <tr v-for="(fare, i) in fares" :key="i">
                                <td>{{ fare.id }}</td>
                                <td class="_table_name">
                                    {{ fare.waktu }}
                                </td>
                                <td>{{ fare.harga }}</td>
                                <td>
                                    <Button
                                        type="info"
                                        size="small"
                                        @click="showEditModal(fare, i)"
                                    >
                                        Edit
                                    </Button>
                                    <Button
                                        type="error"
                                        size="small"
                                        @click="showDeletingModal(fare, i)"
                                        :loading="fare.isDeleting"
                                    >
                                        Delete
                                    </Button>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- modal untuk tambah fare -->
                <Modal
                    v-model="addModal"
                    title="Add fare"
                    :styles="{ top: '20px' }"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Form v-model="addModal" class="col-14">
                        <div class="form-group row">
                            <label for="waktu" class="col-3 col-form-label"
                                >Waktu</label
                            >
                            <div class="col-9">
                                <TimePicker
                                    type="time"
                                    placeholder="Select time"
                                    style="width: 168px"
                                    v-model="data.waktu"
                                ></TimePicker>
                                <Input v-model="data.waktu" hidden />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="harga" class="col-3 col-form-label"
                                >Harga</label
                            >
                            <div class="col-9">
                                <Input v-model="data.harga" />
                            </div>
                        </div>
                    </Form>
                    <div slot="footer">
                        <Button type="default" @click="addModal = false"
                            >Close</Button
                        >
                        <Button
                            type="primary"
                            @click="addFare"
                            :disabled="isAdding"
                            :loading="isAdding"
                            >{{ isAdding ? "Adding.." : "Add fare" }}</Button
                        >
                    </div>
                </Modal>

                <!-- modal untuk edit fare -->
                <Modal
                    v-model="editModal"
                    title="Edit Fare"
                    :styles="{ top: '20px' }"
                    :mask-closable="false"
                    :closable="false"
                >
                    <Form v-model="editModal" class="col-14">
                        <div class="form-group row">
                            <label for="waktu" class="col-3 col-form-label"
                                >waktu</label
                            >
                            <div class="col-9">
                                <TimePicker
                                    type="time"
                                    placeholder="Select time"
                                    style="width: 168px"
                                    v-model="editData.waktu"
                                ></TimePicker>
                                <Input v-model="editData.waktu" hidden />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="harga" class="col-3 col-form-label"
                                >Harga</label
                            >
                            <div class="col-9">
                                <Input v-model="editData.harga" />
                            </div>
                        </div>
                    </Form>
                    <div slot="footer">
                        <Button type="default" @click="editModal = false"
                            >Close</Button
                        >
                        <Button
                            type="primary"
                            @click="editFare"
                            :disabled="isAdding"
                            :loading="isAdding"
                            >{{ isAdding ? "Editing.." : "Edit fare" }}</Button
                        >
                    </div>
                </Modal>

                <!-- modal untuk delete fare -->
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
                        <p>Apakah anda yakin akan menghapus fare ini?</p>
                    </div>
                    <div slot="footer">
                        <Button
                            type="error"
                            size="large"
                            long
                            :loading="isDeleting"
                            :disabled="isDeleting"
                            @click="deleteFare"
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
                waktu: "time",
                harga: ""
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            fares: [],
            editData: {
                waktu: "",
                harga: ""
            },
            index: -1,
            showDeleteModal: false,
            isDeleting: false,
            deleteItem: {},
            DeletingIndex: -1
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
