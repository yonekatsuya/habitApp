import Vue from 'vue'
import mainHeader from './components/mainHeader'
import mainSide from './components/mainSide'

const app = new Vue({
  el: '#app',
  components: {
    mainHeader,
    mainSide,
  }
})