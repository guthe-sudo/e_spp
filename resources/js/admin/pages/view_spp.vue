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
      <h2>List SPP</h2>
      <v-spacer></v-spacer>
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        hide-details
      >
      </v-text-field>
    </v-card-title>
    <v-card>
      <v-data-table
        :headers="headers"
        :items="spps"
        :search="search"
        :items-per-page="5"
        sort-by="id"
        class="elevation-1"
      >
      </v-data-table>
    </v-card>
    
  </v-card>
</template>

<script>
export default {
  data() {
    return {
      data: {
        id_tahun_akademik: "",
        jumlah: "",
        id_tingkat: "",
      },
      spps: [],
      tingkat: [],
      tahun_akademik: [],
      items: [
        {
          text: "Home",
          disabled: false,
          href: "/",
        },
        {
          text: "Spp",
          disabled: false,
          href: "/spp",
        },
      ],
      search: "",
      headers: [
        {
          text: "ID",
          align: "center",
          class: "info--text",
          value: "id_spp",
        },
        {
          text: "Tahun Akademik",
          align: "center",
          class: "info--text",
          value: "nama_tahun",
        },
        {
          text: "Jumlah",
          align: "center",
          sortable: false,
          class: "info--text",
          value: "jumlah",
        },
        {
          text: "Tingkat",
          align: "center",
          class: "info--text",
          value: "tingkat",
        },
      ],
    };
  },
  async created() {
    const [spp, a, t] = await Promise.all([
      this.callApi("get", "app/get_spp"),
      this.callApi("get", "app/get_tahun_akademik"),
      this.callApi("get", "app/get_tingkat"),
    ]);
    if (spp.status == 200) {
      this.spps = spp.data;
      this.tahun_akademik = a.data;
      this.tingkat = t.data;
    } else {
      this.e();
    }
  },
};
</script>

