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
        :items="siswas"
        :search="search"
        :items-per-page="5"
        sort-by="id"
        class="elevation-1"
      ></v-data-table>
    </v-card>

    <!-- <v-card class="mx-auto" max-width="auto" outlined>
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
          </tr>
        </tbody>
      </v-simple-table>
    </v-card> -->
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
      row: null,
      siswas: [],
      tahun_akademik: [],
      tingkat: [],
      jurusan: [],
      items: [
        {
          text: "Home",
          disabled: false,
          href: "/",
        },
        {
          text: " list Siswa",
          disabled: false,
          href: "/siswa",
        },
      ],
      search: "",
      headers: [
        {
          text: "NIS",
          align: "start",
          class: "info--text",
          value: "nis",
        },
        {
          text: "Nama",
          align: "start",
          class: "info--text",
          value: "nama",
        },
        {
          text: "Tempat Lahir",
          align: "start",
          class: "info--text",
          value: "tempat_lahir",
        },
        {
          text: "Tanggal Lahir",
          align: "start",
          class: "info--text",
          value: "tgl_lahir",
        },
        {
          text: "Jenis Kelamin",
          align: "start",
          class: "info--text",
          value: "jk",
        },
        {
          text: "Tahun Akademik",
          align: "start",
          class: "info--text",
          value: "nama_tahun",
        },
        {
          text: "Tingkat",
          align: "start",
          class: "info--text",
          value: "tingkat",
        },
        {
          text: "Jurusan",
          align: "start",
          class: "info--text",
          value: "nama_jurusan",
        },
        {
          text: "Alamat",
          align: "start",
          class: "info--text",
          value: "alamat",
        },
      ],
    };
  },
  async created() {
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

