<template>
  <div id="app">
    <nav class="navbar navbar-light bg-light" v-if="$route.name != 'Link'">
      <div class="container-fluid">
        <a class="navbar-brand">Ringkes</a>
       <div class="d-flex">
          <button class="btn btn-danger btn-sm" @click="routerLInk()" aria-current="page" v-if="route()">
            Logout
          </button>
       </div>
      </div>
    </nav>
    <router-view></router-view>
  </div>
</template>
<script>
import axios from 'axios'
export default {
  data(){
    return{
      link:''
    }
  },
  methods:{
    route(){
      if(localStorage.getItem('login')){
        return true
      }else{
        return false
      }
    },
    routerLInk(){
      if(this.route()){
        if(confirm('Apakah anda yakin ingin logout')){
          axios.post('http://localhost/shortner/backend/logout.php').then(res=>{
            if(res.data.success){
              localStorage.clear()
              this.$router.push('/login');
            }
          })
        }
      }else{
        this.$router.push('/login');
      }
    }
  },
}
</script>
<style>
body{
background: rgb(58,218,197);
background: linear-gradient(90deg, rgba(58,218,197,1) 14%, rgba(119,133,212,1) 100%);  
}
</style>
