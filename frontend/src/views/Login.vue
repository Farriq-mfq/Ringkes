<template>
  <div class="container">
      <div class="row py-5">
          <div class="col-lg-7 m-auto">
              <form @submit.prevent="handleSubmit()">
                <div class="card">
                    <div class="card-header">
                        <h4> Welcome Back</h4>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger text-center py-2" v-if="error">
                            <b>{{error}}</b>
                        </div>
                        <div class="alert alert-success text-center py-2" v-if="getmessage()">
                            <b>{{getmessage()}}</b>
                        </div>
                        <div class="form-group">
                            <label for="username">Email</label>
                            <input type="email" class="form-control" :class="[(error_email)?'is-invalid':'']" id="username" v-model="email" placeholder="Masukan email..."> 
                            <div class="invalid-feedback" v-if="error_email">
                                {{error_email}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="username">Password</label>
                            <input type="password" class="form-control" :class="[(error_password)?'is-invalid':'']" id="password" v-model="password" placeholder="Masukan password..."> 
                            <div class="invalid-feedback" v-if="error_password">
                                {{error_password}}
                            </div>
                        </div>
                        <div class="form-group mt-4">
                            <button class="btn Buttonapp" :disabled="loading">
                                {{(loading) ? 'Process...':'Login'}}
                            </button>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 text-center my-1">
                                <router-link to="/register"> <b-icon icon="arrow-right"></b-icon> Register</router-link>
                            </div>
                        </div>
                    </div>
                </div>
              </form>
          </div>
      </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
    data(){
        return{
            email:'',
            password:'',
            error_email:'',
            error_password:'',
            loading:false,
            error:'',
        }
    },
    methods:{
        handleSubmit(){
            var app = this;
            app.error = ''
            let formdata = new FormData();
            formdata.append('email',this.email)
            formdata.append('password',this.password)
           axios.post('http://localhost/shortner/backend/login.php',formdata).then(res=>{
               app.loading = true;
               if(res.data.success){
                   setTimeout(()=>{
                       app.loading = false;
                       localStorage.setItem('login',res.data.success);
                        localStorage.setItem('user',JSON.stringify(res.data.user));
                        this.$router.push('/dashboard')
                   },1000)
               }else{
                   app.loading = false;
                   if(res.data.error_email){
                      app.error_email = res.data.error_email
                   }else if(res.data.error_password){
                       app.error_password = res.data.error_password
                   }else{
                       app.error = res.data.error
                   }
               }
           }).catch(err=>{
               localStorage.setItem('login',false)
               localStorage.setItem('user',null)
               console.log(err);
           })
        },
        getmessage(){
            if(localStorage.getItem('register_sukses')){
                return localStorage.getItem('register_sukses');
            }else{
                 localStorage.removeItem('register_sukses');
            }
        }
    },
    mounted(){
        if(localStorage.getItem('login')){
            this.$router.push('/')
        }
         localStorage.removeItem('register_sukses');
          document.title = this.$route.meta.title
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