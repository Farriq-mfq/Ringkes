<template>
  <div class="container">
      <div class="row py-5">
          <div class="col-lg-12">
              <ul class="nav justify-content-center">
                  <li class="nav-item">
                      <router-link to="/dashboard" class="nav-link TabsLInk">
                       <b-icon icon="bounding-box"></b-icon> Dashboard
                      </router-link>
                  </li>
                  <li class="nav-item">
                      <router-link to="/dashboard/settings" class="nav-link TabsLInk">
                            <b-icon icon="gear"></b-icon> Settings
                        </router-link>
                  </li>
                  <li class="nav-item">
                      <router-link to="/" class="nav-link TabsLInk">
                           <b-icon icon="arrow-left"></b-icon> Create ShortLink
                          </router-link>
                  </li>
              </ul>
          </div>
      </div>
      <div class="row">
          <div class="col-lg-12">
              <router-view></router-view>
          </div>
          <div class="col-lg-12" v-if="$route.name == 'dashboard'">
             <div class="row">
      <div class="col-lg-12">
          <div class="card shadow">
              <div class="card-body">
                    <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th>No</th>
                    <th>Link</th>
                    <th>View</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                    <tr class="text-center" v-for="(link,index) in data" :key="index">
                        <td>{{index+1}}</td>
                        <td>
                            <a :href="`${geturl()}/c/${link.short_url}`">
                            {{`${geturl()}/c/${link.short_url}`}}
                            </a>
                        </td>
                        <td>{{(link.view == null)?'0':link.view}}</td>
                        <td>
                            <button class="btn btn-sm btn-danger" type="button" @click="hapus(link.id)">
                                Hapus
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
              </div>
          </div>
      </div>
    </div>
          </div>
      </div>
  </div>
</template>

<script>
import axios from 'axios'
export default {
    data(){
        return{
            data:[],
        }
    },
    methods:{
        geturl(){
            return window.location.origin
        },
        getdata(){
           var app = this;
            const id = JSON.parse(localStorage.getItem('user')).id;
            axios({
                method:'GET',
                url:'http://localhost/shortner/backend/Shorlink2.php',
                params:{
                    id:id
                }
            }).then(res=>{
                app.data = res.data.data;
            }) 
        },
        hapus(id){
            if(confirm('Apakah anda Yakin ?')){
                var app = this;
                axios({
                    method:'DELETE',
                    url:'http://localhost/shortner/backend/shortlink.php',
                    params:{
                        id:id
                    }
                }).then(res=>{
                    if(res.data.success){
                        app.getdata();
                    }
                })
            }
            
        }
    },
    mounted(){
         document.title = this.$route.meta.title
        var app = this;
        const id = JSON.parse(localStorage.getItem('user')).id;
        axios({
            method:'GET',
            url:'http://localhost/shortner/backend/Shorlink2.php',
            params:{
                id:id
            }
        }).then(res=>{
            app.data = res.data.data;
        })  
    }
}
</script>

<style scoped>
.TabsLInk{
    color: #fff;
    font-weight: 600;
    letter-spacing: 1px;
}
</style>