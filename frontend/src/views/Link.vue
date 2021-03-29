<template>
  <div class="container">
      <div class="row py-5">
          <div class="col-lg-8 m-auto">
              <div class="card">
                  <div class="card-body py-3 text-center">
                      <h4 class="alert alert-success">
                          <b-icon icon="check"></b-icon> Link ini <b>Aman</b>
                      </h4>
                      <p v-if="!success_verif">Verifikasi Captha Di bawah <b-icon icon="arrow-down"></b-icon> </p>
                      <p v-else class="text-success">Verifikasi Captha success <b-icon icon="check"></b-icon> </p>
                      <p v-if="error_verif" class="text-danger">Verifikasi Captha Gagal</p>
                      <form @submit.prevent="VerifyCaptha()" class="mt-4 py-2" v-if="!success_verif">
                          <div class="form-group d-flex">
                                <input type="text" class="jCaptcha shadow form-control" placeholder="Ketikan Captha...">
                          </div>
                          <div class="form-group">
                              <button class="btn btn-sm btn-info mt-1" :disabled="disable">{{(disable)?'Reloading...':'Verfikasi'}}</button>
                          </div>
                      </form>
                    <button class="btn btn-sm btn-info mt-1" type="button" @click="getLink()" v-if="is_verif">Get Link <b-icon icon="arrow-right"></b-icon> </button>
                  </div>
              </div>
          </div>
      </div>
  </div>
  
</template>

<script>
import jsCaptha from 'js-captcha';
import axios from 'axios';
export default {
    data(){
        return{
            captha:'',
            is_verif:false,
            success_verif:false,
            error_verif:false,
            disable:false,
            link:''
        }
    },
    mounted(){
         document.title = this.$route.meta.title
        this.Captha()
        var app = this;
        axios({
            method:'GET',
            url:'http://localhost/shortner/backend/redirect.php',
            params:{
                code:this.$route.params.c
            }
        }).then(res=>{
            app.link = res.data.data.long_url;
        })
    },
    methods:{
        Captha(){
             this.captha = new jsCaptha({
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
                        this.error_verif = false;
                        this.is_verif = true;
                        this.success_verif = true;
                    }
                    if ( response == 'error' ) {
                        this.error_verif = true;

                        if (numberOfTries === 3) {
                            this.disable = true;
                            setTimeout(()=>{
                                location.reload(true);
                            },1000);
                        }
                    }
                }  
            })
        },
        VerifyCaptha(){
            this.captha.validate()
        },
        getLink(){
            window.location.href = this.link;
        }
    }
}
</script>

<style scoped>
</style>