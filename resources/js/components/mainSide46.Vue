<template>
  <div class="side">
    <div class="side-tab">
      <p>目標達成率</p>
    </div>
    <div class="side-tab">
      <p>目標管理</p>
    </div>
    <div class="side-tab" @click="habitRegisterModalDisp">
      <p>習慣項目</p>
    </div>
    <div class="side-tab" @click="habitHistoryModalDisp">
      <p>習慣履歴</p>
    </div>
    <div class="side-tab">
      <p>プロフィールページ</p>
    </div>
    <div class="side-tab">
      <p>他ユーザーと繋がる</p>
    </div>
    <div class="side-tab">
      <p>サイトの使い方</p>
    </div>

    <div class="overlay" v-show="overlay">
      <div id="content" v-show="disp">
        <div class="overlay-content-title">
          <p><span ref="year"></span>年<span ref="month"></span>月の習慣項目登録</p>
          <span @click="habitRegisterModalHidden" class="modalClose">×</span>
        </div>
        <div class="overlay-content-wrapper" v-show="overlay">
          <div class="overlay-content-wrapper-register">
            <div class="overlay-content-wrapper-register-left">
              <input type="text" v-model="habitText">
            </div>
            <div class="overlay-content-wrapper-register-right">
              <button @click="addList">登録</button>
            </div>
          </div>
          <div class="overlay-content-wrapper-list">
            <table id="olElement">
              <tr v-for="(habitList, index) in habitLists">
                <td>{{index + 1}}</td>
                <td>{{habitList.item}}</td>
                <td @click="deleteList(habitList.id)"><i class="fas fa-trash-alt"></i></td>
                <td @click="editList(habitList.id,habitList.item)"><i class="fas fa-edit"></i></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="overlay" v-show="overlay2">
      <div id="content2" v-show="disp2">
        <div class="overlay-content-title">
          <p><span ref="year2"></span>年<span ref="month2"></span>月の習慣履歴</p>
          <span @click="habitRegisterModalHidden2" class="modalClose">×</span>
        </div>
        <div class="overlay-content-wrapper" v-show="overlay2">
          <div class="overlay-content-wrapper-list2">
            <table id="olElement2">
              <thead>
                <tr>
                  <td></td>
                  <td></td>
                  <td v-for="n in getCountCurrentMonthDate(getCurrentMonth())">{{n}}</td>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(habitList, index) in habitLists">
                  <td>{{index + 1}}</td>
                  <td>{{habitList.item}}</td>
                  <td v-for="n in getCountCurrentMonthDate(getCurrentMonth())" :data-id="habitList.id" :data-year="habitList.year" :data-month="habitList.month" :data-date="n">{{getDateResult(habitList.id,habitList.year,habitList.month,n)}}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        overlay: false,
        disp: false,
        overlay2: false,
        disp2: false,
        habitText: '',
        habitLists: null
      }
    },
    methods: {
      /* クッキーから特定の値を取得する */
      getCookie: function(name) {
        var cookies = document.cookie;
        cookies = cookies.replace(/\s+/g, "");
        var cookiesArray = cookies.split(';');
        var returnStr = '';
        cookiesArray.forEach(function(c) {
          var cArray = c.split('=');
          if( cArray[0] == name){
              returnStr = cArray[1];
          }
        });
        return returnStr;
      },

      getDateResult: function(id,year,month,date) {
        var response = '◎';
        const url = 'http://localhost:100/habitGetDateResult';
        var param = [id,year,month,date];
        const params = {
          result: param
        }
        axios.get(url, {params})
                  .then(function (response) {
                    response = response.data;
                  })
                  .catch(function (error) {
                    console.log(error);
                  })
        return response;
      },

      getCountCurrentMonthDate: function(num) {
        num = parseInt(num);
        var countMonthDate = {1:31,2:29,3:31,4:30,5:31,6:30,7:31,8:31,9:30,10:31,11:30,12:31};
        return countMonthDate[num];
      },

      getCurrentMonth: function() {
        return this.getCookie('month');
      },

      habitRegisterModalDisp: function() {
        this.$refs.year.textContent = this.getCookie('year');
        this.$refs.month.textContent = this.getCookie('month');
        this.overlay = true;
        this.disp = true;

        var year = this.getCookie('year');
        var month = this.getCookie('month');
        axios.get('http://localhost:100/habitGet?year=' + year + '&month=' + month)
          .then(response => this.habitLists = response.data)
          .catch(response => console.log(response))
      },

      habitHistoryModalDisp: function() {
        this.$refs.year2.textContent = this.getCookie('year');
        this.$refs.month2.textContent = this.getCookie('month');
        this.overlay2 = true;
        this.disp2 = true;

        var year = this.getCookie('year');
        var month = this.getCookie('month');
        axios.get('http://localhost:100/habitGet?year=' + year + '&month=' + month)
          .then(response => this.habitLists = response.data)
          .catch(response => console.log(response))
      },

      habitRegisterModalHidden: function() {
        this.overlay = false;
        this.disp = false;
      },

      habitRegisterModalHidden2: function() {
        this.overlay2 = false;
        this.disp2 = false;
      },

      addList: function() {
        var inputText = this.habitText;
        this.habitText = '';
        var now = new Date();
        var year = this.getCookie('year');
        var month = this.getCookie('month');
        if (inputText !== '') {
          axios.get('http://localhost:100/habitPost?habitText=' + inputText + '&year=' + year + '&month=' + month)
          .then(response => this.habitLists = response.data)
          .catch(response => console.log(response))
        }
      },

      deleteList: function(id) {
        if (window.confirm('選択した項目を削除しますか？')) {
          var year = this.getCookie('year');
          var month = this.getCookie('month');
          axios.get('http://localhost:100/habitDelete?id=' + id + '&year=' + year + '&month=' + month)
          .then(response => this.habitLists = response.data)
          .catch(response => console.log(response))
        }
      },

      editList: function(id,item) {
        var year = this.getCookie('year');
        var month = this.getCookie('month');
        axios.get('http://localhost:100/habitDelete?id=' + id + '&year=' + year + '&month=' + month)
          .then(response => this.habitLists = response.data)
          .catch(response => console.log(response))
        this.habitText = item;
      }
    },

    created : function(){
      
    }
  }
</script>