import Vue from 'vue'
import mainHeader from './components/mainHeader'
import mainSide1 from './components/mainSide1'

const app = new Vue({
  el: '#app',
  components: {
    mainHeader,
    mainSide1
  }
})