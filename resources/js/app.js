require('./bootstrap');

window.Vue = require('vue');
Vue.component('example-component', require('./components/ExampleComponent.vue').default);

import Vue from 'vue'
import mainHeader56 from './components/mainHeader56'
import mainSide262 from './components/mainSide262'
import mainContent211 from './components/mainContent211'

const app = new Vue({
  el: '#app',
  components: {
    mainHeader56,
    mainSide262,
    mainContent211,
  }
})