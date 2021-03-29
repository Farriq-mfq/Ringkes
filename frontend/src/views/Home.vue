<template>
  <div class="home">
    <div class="container">
      <div class="row py-5" v-if="!$route.params.c">
        <div class="col-lg-12">
          <h1 class="text-center text-white TitleApp mb-3">
            Ringkes - Pemendek Url   
          </h1>
          <p class="lead text-center text-white textApp">
            Pemendek Url created 
          </p>
        </div>
        <div class="col-lg-12 py-2">
          <form method="post" @submit.prevent="handleSubmit()">
            <div class="formCreate">
                <input type="text" class="form-control inputApp" id="url" placeholder="Paste Your Url" v-model="link" autofocus>
                <button class="btn Buttonapp shadow" :disabled="loading" v-if="!done"><b-icon icon="circle-fill" animation="throb" font-scale="1" v-if="loading"></b-icon> {{(loading) ? 'Loading...':'Ringkes'}}</button>
                <button class="btn Buttonapp shadow" type="button" @click="copy()" v-if="done">Copy</button>
            </div>
            <span class="text-danger ml-3" v-if="error">{{error}}</span>
          </form>
        </div>
        <div class="col-lg-12 text-center py-3">
          <router-link to="/dashboard" class="btn Buttonapps mx-2">Dashboard</router-link>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from 'axios';
export default {
  data(){
    return{
      link:'',
      loading:false,
      done:false,
      error:'',
    }
  },
  methods:{
    handleSubmit(){
      var app = this;
      app.error = '';
      app.loading = true;
      app.done = false;
      let formdata = new FormData();
      if(localStorage.getItem('user')){
        const uid = JSON.parse(localStorage.getItem('user')).id;
        formdata.append('uid',uid);
      }
      formdata.append('url',this.link);
      axios.post('http://localhost/shortner/backend/shortlink.php',formdata).then(response=>{
        app.loading = false;
        if(response.data.success){
          app.getdataurl(response.data.insert_id)
          app.done = true;
        }else{
          app.error = response.data.error;
        }
      })
    },
    getdataurl(id){
      var app = this;
      axios({
        method:'get',
        url:'http://localhost/shortner/backend/shortlink.php',
        params:{
          id:id
        }
      }).then(res=>{
       app.link = window.location.origin +'/c/'+ res.data.data.short_url;
      })
    },
    copy(){
      const input = document.getElementById('url');
      input.select();
      input.setSelectionRange(0,99999);
      document.execCommand('copy')
      this.done = false;
      this.link = ''
    }
  },
  mounted(){
     document.title = this.$route.meta.title
  }
}
</script>





<style scoped>
  .TitleApp{
  font-weight: bold;
}
.textApp{
  letter-spacing: 2px;
}
.formCreate{
  display: flex;
  justify-content: center;
  align-items: center;
}
.inputApp{
  border-radius: 0 15px 0 15px;
  margin-right: 5px;
}
.Buttonapp{
   background: rgb(58,218,197);
  border-radius: 0 20px 0 20px; 
  color: #fff;
  font-weight: bold;
  font-size: 15px;
}
.Buttonapps{
   background: rgba(119,133,212,1);
  border-radius: 0 20px 0 20px; 
  color: #fff;
  font-weight: bold;
  font-size: 15px;
  letter-spacing: 2px;
}
.Buttonapp:hover{
  transition: 0.5s;
}
</style>
