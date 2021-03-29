<template>
    <div class="container pb-3">
        <div class="row">
        <div class="col-lg-8 m-auto">
            <div class="card shadow">
                <div class="card-header text-center">
                   <h4> Profile Settings</h4>
                </div>
                <div class="card-body my-2">
                    <div class="alert text-center" v-if="error || success" :class="[(error)?'alert-danger':'alert-success']">
                        {{(error)?error:success}}
                    </div>
                    <table class="table table-borderless text-center">
                        <tr>
                            <th>Nama</th>
                            <td id="nama" :contenteditable="!ubahbtnPassword">Farriq muwaffaq </td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td :contenteditable="!ubahbtnPassword" id="email">Farriqmuwaffaq@mail.com </td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <button class="btn btn-sm btn-info" @click.prevent="settings()" :disabled="ubahbtnPassword" type="button">Ubah <b-icon icon="gear"></b-icon></button>
                    <button class="btn btn-sm mx-4" :class="[(ubahbtnPassword)?'btn-danger':'btn-info']" @click.prevent="clickUbahPassword()">{{(ubahbtnPassword)?'Batal ':'Ubah password '}}<b-icon :icon="`${(ubahbtnPassword)?'x':'gear'}`"></b-icon></button>
                    <Reset :show = ubahbtnPassword />
                </div>
            </div>
        </div>
    </div>
    </div>
</template>

<script>
import axios from 'axios';
import Reset from '@/components/Reset.vue';
export default {
    data(){
        return{
            data:[],
            error:'',
            success:'',
            ubahbtnPassword:false,
            
        }
    },
    components:{
        Reset
    },
    methods:{
        settings(){
            var app = this;
            app.success ='';
            app.error = '';
            let formdata = new FormData();
            const id = JSON.parse(localStorage.getItem('user')).id;
            const nama = document.querySelector("#nama").innerText;
            const email = document.querySelector("#email").innerText;
            formdata.append('nama',nama);
            formdata.append('id',id);
            formdata.append('email',email);
            axios.post('http://localhost/shortner/backend/Settings.php',formdata).then(res=>{
                if(res.data.success){
                    app.reload()
                    app.success = res.data.success;
                }else{
                    app.error = res.data.error;
                }
            })
        },
        reload(){
           var app = this;
            const id = JSON.parse(localStorage.getItem('user')).id;
            axios({
                method:'GET',
                url:'http://localhost/shortner/backend/Settings.php',
                params:{
                    id:id
                }
            }).then(res=>{
                app.data = res.data.data;
            })  
        },
        clickUbahPassword(){
           if(this.ubahbtnPassword){
               this.ubahbtnPassword = false;
           }else{
               this.ubahbtnPassword = true;
           }
        },
    },
    mounted(){
         document.title = this.$route.meta.title
        var app = this;
     const id = JSON.parse(localStorage.getItem('user')).id;
     axios({
         method:'GET',
         url:'http://localhost/shortner/backend/Settings.php',
         params:{
             id:id
         }
     }).then(res=>{
        app.data = res.data.data;
     }) 
    }
}
</script>

<style>
</style>