require('./bootstrap');

window.Vue = require('vue');
Vue.component('example-component', require('./components/ExampleComponent.vue').default);

import Vue from 'vue'
import mainHeader79 from './components/mainHeader79'
import mainSide262 from './components/mainSide262'
import mainContent211 from './components/mainContent211'

const app = new Vue({
  el: '#app',
  components: {
    mainHeader79,
    mainSide262,
    mainContent211,
  }
})