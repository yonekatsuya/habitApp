import Vue from 'vue'
import mainHeader from './components/mainHeader'
import mainSide from './components/mainSide'
import mainContent from './components/mainContent'

const app = new Vue({
  el: '#app',
  components: {
    mainHeader,
    mainSide,
    mainContent,
  }
})