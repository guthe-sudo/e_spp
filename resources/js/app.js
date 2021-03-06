require('./bootstrap');
window.Vue = require('vue')
import router from './router'
import ViewUI from 'view-design';
import 'view-design/dist/styles/iview.css'
import common from './common'
import store from './store'
import locale from 'view-design/dist/locale/en-US';
import moment from 'moment';
import Vuetify from '../plugins/vuetify'
import VueCurrencyFilter from 'vue-currency-filter'

Vue.mixin(common);
Vue.use(ViewUI, {locale: locale});
Vue.use(VueCurrencyFilter, {
      symbol : 'Rp.',
      thousandsSeparator: '.',
      fractionCount: 0,
      fractionSeparator: '.',
      symbolPosition: 'front',
      symbolSpacing: true
});

Vue.filter('formatDate', function(value) {
      if(value) {
            return moment(String(value)).format('D MMMM YYYY')
      }
})
Vue.component ('mainapp', require('./components/mainapp.vue').default)

const app = new Vue ({
    vuetify: Vuetify,
      el: '#app',
      //el: '#member', 
      router,
      store,
});