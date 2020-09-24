import Vue from 'vue'
import Router from 'vue-router'
import firtsPage from './components/pages/myFirstPage.vue'
import SecondPage from './components/pages/newRoute.vue'
import hooks from './components/pages/basic/hooks.vue'

// import project
import capture from './admin/pages/capture'
import utama from './components/pages/utama'
import home from './components/pages/home'
import booking from './admin/pages/booking'
import members from './admin/pages/members'
import fare from './admin/pages/fare'
import upload from './admin/pages/upload'
import adminusers from './admin/pages/adminusers'
import login from './admin/pages/login'

Vue.use(Router)

const routes = [
      //route project
      {
            path: '/capture',
            component: capture,
      },
      {
            path: '/futsal',
            component: utama,
      },
      {
            path: '/',
            component: home,
      },
      {
            path: '/members',
            component: members,
      },
      {
            path: '/fare',
            component: fare,
      },
      {
            path: '/booking',
            component: booking,
      },
      {
            path: '/upload',
            component: upload,
      },
      {
            path: '/adminusers',
            component: adminusers,
      },
      {
            path: '/login',
            component: login,
      },
      

      //route basic
      {
            path: '/my-new-vue-route',
            component: firtsPage
      },
      {
            path: '/newroute',
            component: SecondPage
      },

      //route hooks
      {
            path: '/hooks',
            component: hooks
      },
]

export default new Router({
      mode: 'history',
      routes
})