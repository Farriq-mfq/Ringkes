import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import Login from '../views/Login.vue'
import Register from '../views/Register.vue'
import Dashboard from '../views/Dashboard.vue'
import Settings from '../views/Settings.vue'
import Link from '../views/Link.vue'
import Notfound from '../views/Notfound.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home,
    meta:{
      title:'Ringkes - Home'
    }
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta:{
      title:'Ringkes - Login'
    }
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta:{
      title:'Ringkes - Register'
    }
  },
  {
    path: '/c/:c',
    name: 'Link',
    component: Link,
    meta:{
      title:'Ringkes - Get Link'
    }
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    meta:{
      auth:true,
      title:'Ringkes - Dashboard'
    },
    children:[
      {
        path:'settings',
        component:Settings,
        meta:{
          auth:true,
          title:'Ringkes - Settings'
        }
      }
    ]
  },
  {
    path:'*',
    component:Notfound,
    meta:{
      title:'Ringkes - Notfound'
    }

  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})
import Nprogress from 'nprogress';
import 'nprogress/nprogress.css'
router.beforeEach((to,from,next)=>{
  if(to.meta.auth){
    if(localStorage.getItem('login')){
      Nprogress.start()
      next()
    }else{
      Nprogress.start()
      next('/login')
    }
  }else{
    Nprogress.start()
    next()
  }
})
router.afterEach(()=>{
  Nprogress.done()
})
export default router
