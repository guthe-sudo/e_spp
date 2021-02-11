import Vue from 'vue'
import Router from 'vue-router'
import firtsPage from './components/pages/myFirstPage.vue'
import SecondPage from './components/pages/newRoute.vue'
import hooks from './components/pages/basic/hooks.vue'
import capture from './admin/pages/capture'

// import project administrator
import home from './components/pages/home'
import adminusers from './admin/pages/adminusers'
import login from './admin/pages/login'

// import project administrator
import tahun_akademik from './admin/pages/tahun_akademik'
import tingkat from './admin/pages/tingkat'
import jurusan from './admin/pages/jurusan'
import kelas from './admin/pages/kelas'
import siswa from './admin/pages/siswa'
import view_siswa from './admin/pages/view_siswa'
import spp from './admin/pages/spp'
import view_spp from './admin/pages/view_spp'
import iuran from './admin/pages/iuran'
import view_iuran from './admin/pages/view_iuran'
import kategori from './admin/pages/kategori'
import subkategori from './admin/pages/subkategori'
import transaksi from './admin/pages/transaksi'
import pengeluaran from './admin/pages/pengeluaran'
import role from './admin/pages/role'
import assignRole from './admin/pages/assignRole'

Vue.use(Router)

const routes = [
      //route project
      {
            path: '/capture',
            component: capture,
            name: 'coba'
      },
      {
            path: '/',
            component: home,
            name: '/'
      },
      //project route
      {
            path: '/tahun_akademik',
            component: tahun_akademik,
            name: 'tahun_akademik'
      },
      {
            path: '/tingkat',
            component: tingkat,
            name: 'tingkat'
      },
      {
            path: '/jurusan',
            component: jurusan,
            name: 'jurusan'
      },
      {
            path: '/kelas',
            component: kelas,
            name: 'kelas'
      },
      {
            path: '/siswa',
            component: siswa,
            name: 'siswa'
      },
      {
            path: '/view_siswa',
            component: view_siswa,
            name: 'view_siswa'
      },
      {
            path: '/spp',
            component: spp,
            name: 'spp'
      },
      {
            path: '/view_spp',
            component: view_spp,
            name: 'view_spp'
      },
      {
            path: '/iuran',
            component: iuran,
            name: 'iuran'
      },
      {
            path: '/view_iuran',
            component: view_iuran,
            name: 'view_iuran'
      },
      {
            path: '/kategori',
            component: kategori,
            name: 'kategori'
      },
      {
            path: '/subkategori',
            component: subkategori,
            name: 'subkategori'
      },
      {
            path: '/transaksi',
            component: transaksi,
            name: 'transaksi'
      },
      {
            path: '/pengeluaran',
            component: pengeluaran,
            name: 'pengeluaran'
      },
      {
            path: '/role',
            component: role,
            name: 'role'
      },
      //End project route
      {
            path: '/assignRole',
            component: assignRole,
            name: 'assignRole'
      },
      {
            path: '/adminusers',
            component: adminusers,
            name: 'adminusers'
      },
      {
            path: '/login',
            component: login,
            name: 'login'
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