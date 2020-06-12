<template>
  <header>
    <div id="header">
      目標・習慣管理
    </div>
    <div id="header-link">
        <template v-if="loginFlag">
          <button class="header-link-button" @click="userLogout">ログアウト</button>
        </template>
    </div>

    <div class="user-register-modal" v-if="userRegisterModalDispFlag">
      <div class="user-register-form">
        <div class="user-register-form-title">
          アカウントを作成
        </div>
        <div class="user-register-form-body">
          <div class="user-register-input"><input type="text" placeholder="ユーザー名" ref="userRegisterName"></div>
          <div class="user-register-input"><input type="text" placeholder="メールアドレス" ref="userRegisterMail"></div>
          <div class="user-register-input"><input type="text" placeholder="パスワード" ref="userRegisterPassword"></div>
          <div class="user-register-button"><button @click="userRegister">新規登録</button></div>
          <div class="user-register-move-login">すでにアカウントをお持ちですか？ <span @click="closeRegisterModalAndOpenLoginModal">ログイン</span></div>
        </div>
      </div>
    </div>

    <div class="user-login-modal" v-if="userLoginModalDispFlag">
      <div class="user-login-form">
        <div class="user-login-form-title">
          ログイン
        </div>
        <div class="user-login-form-body">
          <div class="user-login-input"><input type="text" placeholder="メールアドレス" ref="userLoginMail"></div>
          <div class="user-login-input"><input type="text" placeholder="パスワード" ref="userLoginPassword"></div>
          <div class="user-login-button" @click="userLogin"><button>ログイン</button></div>
          <button @click="googleLogin">Google</button>
          <button @click="facebookLogin">Facebook</button>
          <button @click="twitterLogin">Twitter</button>
          <a href="https://accounts.google.com/o/oauth2/auth?client_id=13838527038-8f8mochnuj64db5fci9vpfvqe7vod0g0.apps.googleusercontent.com&redirect_uri=http%3A%2F%2Flocalhost%3A100%2Flogin%2Fgoogle%2Fcallback&scope=openid+profile+email&response_type=code&state=6nqJsHchSyZEnoCgcPi2cquETNZz7ynQER4NAEhm">google</a>
          <a href="https://www.facebook.com/v3.3/dialog/oauth?client_id=676523443187863&redirect_uri=http%3A%2F%2Flocalhost%3A100%2Fauth%2Ffacebook%2Fcallback&scope=email&response_type=code&state=qPwmflsORzmwoo63b37Ki8oGTUuJUg5zelfCUxNE">facebook</a>
          <a href="https://api.twitter.com/oauth/authenticate?oauth_token=bCsApAAAAAABFCeBAAABcp5j53s">twitter</a>
          <div class="user-login-move-register">アカウントをお持ちでないですか？ <span @click="closeLoginModalAndOpenRegisterModal">新規登録</span></div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
/* 共通関数ファイルのインポート */
import CF from "./../commonFunctions";
console.log(document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

export default {
  data: function() {
    return {
      userRegisterModalDispFlag: false,
      userLoginModalDispFlag: false,
      loginFlag: false,
    }
  },
  methods: {
    twitterLogin: function() {
      axios.get('http://localhost:100/auth/login/twitter')
        .then(function(response) {
          console.log(reponse);
        }).catch(function(error) {console.log(error)})
    },
    googleLogin: function() {
      axios.get('http://localhost:100/login/google')
        .then(function(response) {
          console.log(reponse);
        }).catch(function(error) {console.log(error)})
    },
    facebookLogin: function() {
      axios.get('http://localhost:100/auth/login/facebook')
        .then(function(response) {
          console.log(reponse);
        }).catch(function(error) {console.log(error)})
    },
    closeLoginModalAndOpenRegisterModal: function() {
      this.userLoginModalDispFlag = false;
      this.userRegisterModalDispFlag = true;
    },
    closeRegisterModalAndOpenLoginModal: function() {
      this.userRegisterModalDispFlag = false;
      this.userLoginModalDispFlag = true;
    },
    userRegisterModalDisp: function() {
      this.userRegisterModalDispFlag = true;
    },
    userLoginModalDisp: function() {
      this.userLoginModalDispFlag = true;
    },
    userRegisterModalClose: function() {
      this.userRegisterModalDispFlag = false;
    },
    userLoginModalClose: function() {
      this.userLoginModalDispFlag = false;
    },
    userRegister: function() {
      var vm = this;
      var name = this.$refs.userRegisterName.value;
      var mail = this.$refs.userRegisterMail.value;
      var password = this.$refs.userRegisterPassword.value;
      axios.defaults.headers.common = {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
      axios.post('http://localhost:100/register?name=' + name + '&email=' + mail + '&password=' + password)
        .then(function(response) {
          document.cookie = 'loginName=' + response.data.name;
          document.cookie = 'loginFlag=' + 1;
          document.cookie = 'loginId=' + response.data.id;
          vm.loginFlag = true;
        }).catch(function(error) {
          console.log(error);
        })
      this.userRegisterModalDispFlag = false;
    },
    userLogin: function() {
      var vm = this;
      var mail = this.$refs.userLoginMail.value;
      var password = this.$refs.userLoginPassword.value;
      axios.defaults.headers.common = {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
      axios.post('http://localhost:100/login?email=' + mail + '&password=' + password)
        .then(function(response) {
          document.cookie = 'loginName=' + response.data.name;
          document.cookie = 'loginFlag=' + 1;
          document.cookie = 'loginId=' + response.data.id;
          vm.loginFlag = true;
        }).catch(function(error) {
          console.log(error);
        })
      this.userLoginModalDispFlag = false;
    },
    userLogout: function() {
      if (!window.confirm('本当にログアウトしますか？')) {
        return;
      }
      var vm = this;
      axios.defaults.headers.common = {
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN' : document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      };
      axios.post('http://localhost:100/logout')
        .then(function(response) {
          document.cookie = 'loginName=' + null;
          document.cookie = 'loginFlag=' + 0;
          document.cookie = 'loginId=' + null;
          vm.loginFlag = false;
          vm.userLoginModalDispFlag = true;
        }).catch(function(error) {
          console.log(error);
        })
    }
  },
  mounted: function() {
    if (CF.getCookie('loginFlag') == 1) {
      this.loginFlag = true;
      this.userLoginModalDispFlag = false;
    } else {
      this.loginFlag = false;
      this.userLoginModalDispFlag = true;
    }
  }
}
</script>