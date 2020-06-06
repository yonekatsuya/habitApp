<template>
  <header>
    <div id="header">
      目標・習慣管理
    </div>
    <div id="header-link">
      <button class="header-link-button" @click="userRegister">新規登録</button>
      <button class="header-link-button" @click="userLogin">ログイン</button>
    </div>

    <div class="user-register-modal" v-if="userRegisterModalDisp">
      <div class="user-register-form">
        <div class="user-register-form-title">
          アカウントを作成
          <span class="user-register-modal-close" @click="userRegisterModalClose">×</span>
        </div>
        <div class="user-register-form-body">
          <div class="user-register-input"><input type="text" placeholder="ユーザー名"></div>
          <div class="user-register-input"><input type="text" placeholder="メールアドレス"></div>
          <div class="user-register-input"><input type="text" placeholder="パスワード"></div>
          <div class="user-register-button"><button>新規登録</button></div>
        </div>
      </div>
    </div>

    <div class="user-login-modal" v-if="userLoginModalDisp">
      <div class="user-login-form">
        <div class="user-login-form-title">
          ログイン
          <span class="user-login-modal-close" @click="userLoginModalClose">×</span>
        </div>
        <div class="user-login-form-body">
          <div class="user-login-input"><input type="text" placeholder="メールアドレス"></div>
          <div class="user-login-input"><input type="text" placeholder="パスワード"></div>
          <div class="user-login-button"><button>ログイン</button></div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
export default {
  data: function() {
    return {
      userRegisterModalDisp: false,
      userLoginModalDisp: false,
    }
  },
  methods: {
    userRegister: function() {
      this.userRegisterModalDisp = true;
    },
    userLogin: function() {
      this.userLoginModalDisp = true;
    },
    userRegisterModalClose: function() {
      this.userRegisterModalDisp = false;
    },
    userLoginModalClose: function() {
      this.userLoginModalDisp = false;
    },
  }
}
</script>