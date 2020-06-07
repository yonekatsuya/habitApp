require('./bootstrap');

window.Vue = require('vue');
Vue.component('example-component', require('./components/ExampleComponent.vue').default);

import Vue from 'vue'
import mainHeader45 from './components/mainHeader45'
import mainSide262 from './components/mainSide262'
import mainContent208 from './components/mainContent208'

const app = new Vue({
  el: '#app',
  components: {
    mainHeader45,
    mainSide262,
    mainContent208,
  }
})