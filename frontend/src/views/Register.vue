<template>
  <div class="container">
      <div class="row py-5">
      <div class="col-lg-8 m-auto">
          <div class="card">
              <div class="card-header">
                  <h4>Register</h4>
              </div>
              <div class="card-body">
                  <div class="alert alert-danger text-center" v-if="error">
                      {{error}}
                  </div>
                  <form @submit.prevent="handleSubmit()">
                      <div class="form-group">
                      <label for="nama">Nama</label>
                      <input type="text" class="form-control" id="nama" v-model="nama" placeholder="Masukan Nama..."> 
                  </div>
                  <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" class="form-control" id="email" v-model="email" placeholder="Masukan Email...">
                  </div>
                  <div class="form-group">
                      <label for="Password">Password</label>
                      <input type="password" class="form-control" id="Password" v-model="password" placeholder="Masukan Password...">
                  </div>
                  <div class="form-group">
                      <label for="KonfirmasiPassword">Konfirmasi Password</label>
                      <input type="password" class="form-control" id="KonfirmasiPassword" v-model="konfirmasi_password" placeholder="Masukan Konfirmasi Password...">
                  </div>
                  <div class="form-group">
                      <input type="text" class="form-control jCaptcha my-2" id="captha" placeholder="verifikasi captha">
                  </div>
                  <div class="form-group">
                      <button class="btn Buttonapp" :disabled="loading">{{(loading)?'Loading...':'Daftar'}}</button>
                  </div>
                  </form>
              </div>
              <div class="card-footer text-center">
                <router-link to="/login"> <b-icon icon="arrow-right"></b-icon> Login</router-link>
              </div>
          </div>
      </div>
  </div>
  </div>
</template>

<script>
import Jscaptha from 'js-captcha';
import axios from 'axios';

export default {
    data(){
        return{
            nama:'',
            email:'',
            password:'',
            konfirmasi_password:'',
            error_konfirmasi:false,
            captha:'',
            loading:false,
            error:''
        }
    },
    mounted(){
        this.Captha();
         document.title = this.$route.meta.title
    },
    methods:{
        Captha(){
            var app = this;
            app.success = '';
            app.error = '';
             app.captha = new Jscaptha({
                el: '.jCaptcha',
                canvasClass: 'jCaptchaCanvas',
                canvasStyle: {
                    width: 100,
                    height: 20,
                    textBaseline: 'top',
                    font: '20px Arial',
                    textAlign: 'left',
                    fillStyle: '#000'
                },
                callback: ( response, $captchaInputElement, numberOfTries ) => {
                    if ( response == 'success' ) {
                        if(this.password === this.konfirmasi_password){
                            app.loading = true;
                            let formdata = new FormData();
                            formdata.append('nama',this.nama);
                            formdata.append('email',this.email);
                            formdata.append('password',this.password);
                            axios.post('http://localhost/shortner/backend/Register.php',formdata).then(res=>{
                                if(res.data.success){
                                    localStorage.setItem('register_sukses',res.data.success);
                                    setTimeout(()=>{
                                        this.$router.push('/login');
                                        app.loading = false;
                                    },1000)
                                    }else{
                                        app.error = res.data.error;
                                   app.loading = false;
                                    }
                            })
                        }else{
                            this.error = 'Password Tidak sama'
                        }   
                    }   
                    if ( response == 'error' ) {
                        app.error = 'Captha error'
                        if (numberOfTries === 3) {
                            // sectiobn
                        }
                    }
                }  
            })
        },
        handleSubmit(){
            this.captha.validate()
        }
    }
}
</script>

<style scoped>
.Buttonapp{
   background: rgb(58,218,197);
  border-radius: 0 20px 0 20px; 
  color: #fff;
  font-weight: bold;
  font-size: 15px;
}
</style>