<template>
  <v-card class="d-block pa-2 accent-4 black--text basil">
    <v-breadcrumbs :items="items">
      <template v-slot:item="{ item }">
        <v-breadcrumbs-item :href="item.href" :disabled="item.disabled">{{
          item.text.toUpperCase()
        }}</v-breadcrumbs-item>
      </template>
    </v-breadcrumbs>

    <v-card-title class="ml-5 mr-5">
      <h3>List Iuran</h3>
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        hide-details
      ></v-text-field>
    </v-card-title>
    <v-card>
      <v-data-table
        :headers="headers"
        :items="iurans"
        :search="search"
        :items-per-page="5"
        sort-by="id"
        class="elevation-1"
      ></v-data-table>
    </v-card>
  </v-card>

</template>

<script>
export default {
  data() {
    return {
      data: {
        id_tahun_akademik: "",
        id_tingkat: "",
        jenis_tagihan: "",
        jumlah: "",
      },
      iurans: [],
      tahun_akademik: [],
      tingkat: [],
      items: [
        {
          text: "Home",
          disabled: false,
          href: "/",
        },
        {
          text: "Iuran",
          disabled: false,
          href: "/iuran",
        },
      ],
      search: "",
      headers: [
        {
          text: "ID",
          align: "center",
          class: "info--text",
          value: "id_iuran",
        },
        {
          text: "Tahun Akademik",
          align: "center",
          class: "info--text",
          value: "nama_tahun",
        },
        {
          text: "Tingkat",
          align: "center",
          class: "info--text",
          value: "tingkat",
        },
        {
          text: "Jenis Iuran",
          align: "center",
          sortable: false,
          class: "info--text",
          value: "jenis_tagihan",
        },
        {
          text: "Jumlah",
          align: "center",
          sortable: false,
          class: "info--text",
          value: "jumlah",
        },
      ],
    };
  },
  async created() {
    const [iuran, a, t] = await Promise.all([
      this.callApi("get", "app/get_iuran"),
      this.callApi("get", "app/get_tahun_akademik"),
      this.callApi("get", "app/get_tingkat"),
    ]);
    if (iuran.status == 200) {
      this.iurans = iuran.data;
      this.tahun_akademik = a.data;
      this.tingkat = t.data;
    } else {
      this.e();
    }
  },
};
</script>

