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

    <div class="overlay" v-show="overlay" v-on:click="habitRegisterModalHidden">
      <div id="content" v-show="disp">
        <div class="overlay-content-title">
          <p>2020年6月の習慣項目登録</p>
        </div>
        <div class="overlay-content-wrapper" v-show="overlay">
          <div class="overlay-content-wrapper-register">
            <div class="overlay-content-wrapper-register-left">
              <input type="text">
            </div>
            <div class="overlay-content-wrapper-register-right">
              <button>登録</button>
            </div>
          </div>
          <div class="overlay-content-wrapper-list">
            <ol><li>毎朝6時に起きる</li></ol>
          </div>
        </div>
        <p><button @click="habitRegisterModalHidden">close</button></p>
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
      }
    }
  }
</script>