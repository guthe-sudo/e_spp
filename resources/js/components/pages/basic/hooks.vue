<template>
      <div>
            <h1 ref="myref">tutorial hooks</h1>
            <h2>namaku adalah:{{name}}</h2>
      </div>
</template>

<script>
export default {
      data() {
            return {
                  name: 'teguh'
            }
      },

      beforeCreate() {
            console.log(this.name)
      },
      created() {
            //call mostly initial data from it...
            console.log('data and other function is available but not html', this.name)
      },
      mounted() {
            console.log(this.$refs.myref)
      }
}
</script>