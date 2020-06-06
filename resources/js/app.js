require('./bootstrap');

window.Vue = require('vue');
Vue.component('example-component', require('./components/ExampleComponent.vue').default);

import Vue from 'vue'
import mainHeader10 from './components/mainHeader10'
import mainSide247 from './components/mainSide247'
import mainContent208 from './components/mainContent208'

const app = new Vue({
  el: '#app',
  components: {
    mainHeader10,
    mainSide247,
    mainContent208,
  }
})