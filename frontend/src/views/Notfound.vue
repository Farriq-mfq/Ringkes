<template>
  <div class="container">
      <div class="row">
          <div class="col-lg-12 py-5 my-5 text-center">
              <h1 class="text-white">
                  404 Notfound
              </h1>
                <router-link to="/" class="btn text-center "> <b-icon icon="arrow-left"></b-icon> Back</router-link>
          </div>
      </div>
  </div>
</template>

<script>
export default {
mounted(){
         document.title = this.$route.meta.title

}
}
</script>

<style>

</style>