<template>
    <div class="_login _border_radious _mar_b30 _p20 col-md-4">
      <div>
        <h1 class="_text_center">Aplikasi E-SPP</h1>
        <p class="_text_center">Login</p>
      </div>
      <v-text-field
        label="Email"
        outlined
        dense
        v-model="data.email"
        required
      ></v-text-field>
      <v-text-field
        label="Password"
        outlined
        dense
        v-model="data.password"
        required
        type="password"
      ></v-text-field>
      <div class="login_footer">
        <Button
          type="primary"
          @click="adminLogin"
          :disabled="isLogging"
          :loading="isLogging"
        >
          {{ isLogging ? "Logging.." : "Login" }}
        </Button>
      </div>
    </div>
</template>

<script>
export default {
  data() {
    return {
      data: {
        email: "",
        password: "",
      },
      isLoggedIn: false,
      isLogging: false,
    };
  },
  methods: {
    async adminLogin() {
      if (this.data.email.trim() == "")
        return this.e("Email tidak boleh kosong!!");
      if (this.data.password.trim() == "")
        return this.e("Password tidak boleh kosong!!");
      if (this.data.password.length < 8)
        return this.e("Password harus 8 Karakter");
      this.isLoggedIn = true;
      const res = await this.callApi("post", "app/admin_login", this.data);
      if (res.status === 200) {
        this.s(res.data.msg);
        window.location.href = "/";
      } else {
        if (res.status === 401) {
          this.e(res.data.msg);
        } else if (res.status === 422) {
          for (let i in res.data.errors) {
            this.e(res.data.errors[i][0]);
          }
        } else {
          this.w();
        }
      }
    },
  },
};
</script>