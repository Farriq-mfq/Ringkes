<template>
  <form class="py-3" v-if="show" @submit.prevent="changePass()">
        <div class="form-group">
            <div class="alert" :class="[(error)?'alert-danger':'alert-success']" v-if="error || success">{{(error)?error:success}}</div>
            <input type="text"  class="form-control" placeholder="Ketikan password Lama" v-model="pass_lama">
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-10">
                    <input :type="`${(eye)?'text':'password'}`"  class="form-control" placeholder="Ketikan password Baru" v-model="pass_baru">
                </div>
                <div class="col-2">
                    <button class="btn btn-sm " @click="showpass()" type="button"><b-icon icon = 'eye'></b-icon></button>
                </div>
            </div>
        </div>
        <div class="form-group">
            <button class="btn btn-info btn-sm" :disabled = loading>Ubah Password</button>
        </div>
    </form>
</template>

<script>
import axios from 'axios'
export default {
    name:'Reset',
    props:{
        show:Boolean
    },
    data(){
        return{
            pass_lama:'',
            pass_baru:'',
            loading:false,
            error:'',
            success:'',
            eye:false
        }
    },
    methods:{
        changePass(){
            var app  = this;
            app.loading = true;
            app.error = '';
            app.success = '';
            const id = JSON.parse(localStorage.getItem('user')).id;
            let formdata= new FormData();
            formdata.append('pass_lama',this.pass_lama)
            formdata.append('pass_baru',this.pass_baru)
            formdata.append('id',id)
           axios.post('http://localhost/shortner/backend/changePass.php',formdata).then(res=>{
               console.log(res);
               if(res.data.success){
                   app.success = res.data.success
                   app.pass_lama = ''
                   app.pass_baru = ''
               }else{
                   app.error = res.data.error
                   
               }
                   app.loading = false;
           })
        },
        showpass(){
            if(this.eye){
                this.eye = false;
            }else{
                this.eye = true
            }
        }
    }
}
</script>

<style>

</style>