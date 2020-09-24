<template>
  <div>
    <div class="container-fluid">
      <div class="_login _box_shadow _border_radious _mar_b30 _p20 col-md-4">
      <div>
        <h1 class="_text_center">Login ke Dashboard</h1>
      </div>
        <div class="space">
          <label for="email" class="form-label">Email address</label>
          <Input type="email" v-model="data.email" placeholder="Email" />
        </div>
        <div class="space">
          <label for="password" class="form-label">Password</label>
          <Input type="password" v-model="data.password" placeholder="Password" />
        </div>
        <div class="form-group row">
          <div class="col-6 col-form-label">
            <input
              id="user_remember_me"
              name="user[remember_me]"
              type="checkbox"
              class="spec-sign-in-remember-me"
              value="1"
            />
            <label for="user_remember_me">Remember me</label>
          </div>
          <div class="col-6 col-form-label">
            <a href="/users/password/new" class="float-right">Lupa password?</a>
          </div>
        </div>

        <div class="login_footer">
          <Button type="primary" @click="login" :disabled="isLogging" :loading="isLogging">
              {{
              isLogging ? "Logging.." : "Login"
              }}
            </Button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      data : {
        email : '',
        password : ''
      },
      isLoggedIn: false,
      isLogging: false,
    }
  },
  methods : {
    async login() {
      if(this.data.email.trim() == "") return this.e("Email tidak boleh kosong!!")
      if(this.data.password.trim() == "") return this.e("Password tidak boleh kosong!!")
      if(this.data.password.length < 8) return this.e("Password harus 8 Karakter")
      this.isLoggedIn = true
      const res = await this.callApi('post', 'app/admin_login', this.data)
      if(res.status===200){
        this.s(res.data.msg)
        window.location = '/'
      }else{
        if(res.status===401){
          this.e(res.data.msg)
        }else if(res.status === 422) {
          for(let i in res.data.errors){
            this.e(res.data.errors[i][0])
          }

        }
        else{
          this.w()
        }
      }
      this.isLoggedIn = false
    }
  }
}
</script>




