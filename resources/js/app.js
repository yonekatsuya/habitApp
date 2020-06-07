require('./bootstrap');

window.Vue = require('vue');
Vue.component('example-component', require('./components/ExampleComponent.vue').default);

import Vue from 'vue'
import mainHeader44 from './components/mainHeader44'
import mainSide248 from './components/mainSide248'
import mainContent208 from './components/mainContent208'

const app = new Vue({
  el: '#app',
  components: {
    mainHeader44,
    mainSide248,
    mainContent208,
  }
})