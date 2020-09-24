import { DatePicker } from "view-design";

export default {
      data() {
            DatePicker
            return {

            }
      },
      methods: {
            async callApi(method, url, dataObj) {
                  try {
                        return await axios({
                              method: method,
                              url: url,
                              data: dataObj
                        });
                  } catch (e) {
                        return e.response
                  }
            },

            i(desc, title="hey") {
                  this.$Notice.info({
                      title: title,
                      desc: desc
                  });
              },
              s(desc, title="Berhasil") {
                  this.$Notice.success({
                        title: title,
                        desc: desc
                  });
              },
              w(desc="Username / Password Salah", title="Oops!!") {
                  this.$Notice.warning({
                        title: title,
                        desc: desc
                  });
              },
              e(desc, title="Oops!!") {
                  this.$Notice.error({
                        title: title,
                        desc: desc
                  });
              },
              swr(desc="Ada yang salah....!!! Silahkan coba lagi", title="Oops!!") {
                  this.$Notice.error({
                        title: title,
                        desc: desc
                  });
              }
      }
}