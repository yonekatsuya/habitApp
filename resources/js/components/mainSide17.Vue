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
          <p>2020年5月の習慣項目登録</p>
          <span @click="habitRegisterModalHidden">×</span>
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
  </div>
</template>

<script>
  export default {
    data() {
      return {
        overlay: false,
        disp: false,
        habitText: '',
        habitLists: null
      }
    },
    methods: {
      habitRegisterModalDisp: function() {
        this.overlay = true;
        this.disp = true;
      },
      habitRegisterModalHidden: function() {
        this.overlay = false;
        this.disp = false;
      },
      addList: function() {
        var inputText = this.habitText;
        this.habitText = '';
        var now = new Date();
        var year = now.getFullYear();
        var month = now.getMonth() + 1;
        axios.get('http://localhost:100/habitPost?habitText=' + inputText + '&year=' + year + '&month=' + month)
        .then(response => this.habitLists = response.data)
        .catch(response => console.log(response))
      },
      deleteList: function(id) {
        if (window.confirm('選択した項目を削除しますか？')) {
          axios.get('http://localhost:100/habitDelete?id=' + id)
          .then(response => this.habitLists = response.data)
          .catch(response => console.log(response))
        }
      },
      editList: function(id,item) {
        axios.get('http://localhost:100/habitDelete?id=' + id)
          .then(response => this.habitLists = response.data)
          .catch(response => console.log(response))
        this.habitText = item;
      }
    },
    created : function(){
      axios.get('http://localhost:100/habitGet')
          .then(response => this.habitLists = response.data)
          .catch(response => console.log(response))
    }
  }
</script>