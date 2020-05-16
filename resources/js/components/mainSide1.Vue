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
          <p>2020年6月の習慣項目登録</p>
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
            <ol id="olElement"></ol>
          </div>
          <p><button @click="habitRegisterModalHidden">close</button></p>
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
        var olNode = document.getElementById('olElement');
        var newElement = document.createElement('li');
        newElement.textContent = this.habitText;
        olNode.appendChild(newElement);
        this.habitText = '';
      }
    }
  }
</script>