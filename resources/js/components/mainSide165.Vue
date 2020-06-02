<template>
  <div class="side">
    <div class="side-tab">
      <p>
        <span><span class="achiveRateMonth" ref="achiveRateMonth"></span>月の目標達成率</span>
        <span class="achiveGraph">
          <span ref="monthAchiveRateGraph"></span>
        </span>
        <span ref="monthAchiveRate"></span>
      </p>
    </div>
    <div class="side-tab" @click="habitManageAchiveModalDisp">
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
                  <td>達成指標</td>
                  <td v-for="n in getCountCurrentMonthDate(getCurrentMonth())">{{n}}</td>
                </tr>
              </thead>
              <tbody ref="tbody">
                <tr v-for="(habitList, index) in habitLists" ref="tr">
                  <td>{{index + 1}}</td>
                  <td>{{habitList.item}}</td>
                  <td>{{achiveRateNum[index]}}/{{getCountCurrentMonthDate(getCurrentMonth())}}</td>
                  <td v-for="n in getCountCurrentMonthDate(getCurrentMonth())" :data-id="habitList.id" :data-year="habitList.year" :data-month="habitList.month" :data-date="n" ref="td">{{((countHabitLists - 1) == index && n == getCountCurrentMonthDate(getCurrentMonth())) ? habitGetDateResult() : ''}}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <div class="overlay" v-show="overlay3">
      <div id="content2" v-show="disp3">
        <div class="overlay-content-title">
          <p><span ref="year3"></span>年<span ref="month3"></span>月度の目標管理</p>
          <span @click="habitRegisterModalHidden3" class="modalClose">×</span>
        </div>
        <div class="overlay-content-wrapper" v-show="overlay3">
          <div class="overlay-content-wrapper-list2">
            <div class="manage-achive-wrapper">

              <div class="purpose-area">
                <div class="manage-achive-area-title">目的</div>
                <div class="manage-achive-area-text" ref="purposeText"></div>
                <div class="manage-achive-button" @click="purposeRegister" ref="purposeButton"></div>
              </div>

              <div class="achive-area">
                <div class="manage-achive-area-title">目標</div>
                <div class="manage-achive-area-text"  ref="achiveText"></div>
                <div class="manage-achive-area-button" @click="achiveRegister" ref="achiveButton"></div>
              </div>

              <div class="goal-image-wrapper">
                <div class="manage-achive-area-title">ゴールイメージ</div>
                <div class="manage-achive-area-text" ref="goalImageText"></div>
                <div class="manage-achive-area-button" @click="goalImageRegister" ref="goalImageButton"></div>
              </div>

              <div class="passion-area">
                <div class="manage-achive-area-title">意気込み</div>
                <div class="manage-achive-area-text" ref="passionText"></div>
                <div class="manage-achive-area-button" @click="passionRegister" ref="passionButton"></div>
              </div>

            </div>
            <table id="olElement2">
              <thead>
                <tr>
                  <td>No</td>
                  <td>項目</td>
                  <td>達成指標</td>
                  <td>目標達成率</td>
                </tr>
              </thead>
              <tbody ref="tbody2">
                <tr v-for="(item,index) in habitManageAchiveLists" ref="tr2">
                  <td>{{index + 1}}</td>
                  <td>{{item.item}}</td>
                  <td><span ref="achiveNum" @keyup.enter="registerAchiveRate($event,item.id,index)">{{achiveRates[index]}}</span>/{{getCountCurrentMonthDate(getCurrentMonth())}} <span class="editAchiveNumButton" @click="editAchiveNum(index)">編集</span></td>
                  <td><span ref="achiveRate">{{achiveRate(index)}}</span>%</td>
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
        overlay3: false,
        disp3: false,
        changeFlg: 0,
        habitText: '',
        habitLists: null,
        habitManageAchiveLists: null,
        achiveRates: [],
        achiveRateNum: [],
      }
    },
    computed: {
      countHabitLists: function() {
        return this.habitLists.length;
      },
    },
    watch: {
      /* 習慣履歴テーブルの一番最後（右下）のtdが生成されたタイミングで実行される */
      changeFlg: function() {
        var vm = this;
        var tbody = this.$refs.tbody;
        var year = this.getCookie('year');
        var month = this.getCookie('month');
        const url = 'http://localhost:100/habitGetDateResult?year=' + year + '&month=' + month;
          axios.get(url)
              .then(function(response) {
                for (var i = 0;i < vm.$refs.td.length;i++) {
                  for (var j = 0;j < response.data.length;j++) {
                    if (response.data[j].habit_post_id == vm.$refs.td[i].getAttribute('data-id') && response.data[j].date == vm.$refs.td[i].getAttribute('data-date')) {
                      vm.$refs.td[i].textContent = response.data[j].result;
                    }
                  }
                }
              })
              .catch(error => console.error(error))
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

      purposeRegister: function() {
        var vm = this;
        var year = this.getCookie('year');
        var month = this.getCookie('month');
        /* inputタグに入力されているデータを取得する。 */
        var purposeText = vm.$refs.purposeText.children[0].value;

        axios.get('http://localhost:100/purposeRegister?year=' + year + '&month=' + month + '&purposeText=' + purposeText)
          .then(function(response) {
            var purposeText = vm.$refs.purposeText.children[0].value;
            vm.$refs.purposeText.textContent = null;
            vm.$refs.purposeText.textContent = purposeText;
          })
          .catch(response => console.log(response))
      },

      achiveRegister: function() {
        var vm = this;
        var year = this.getCookie('year');
        var month = this.getCookie('month');
        var achiveText = vm.$refs.achiveText.children[0].value;

        axios.get('http://localhost:100/achiveRegister?year=' + year + '&month=' + month + '&achiveText=' + achiveText)
          .then(function(response) {
            var achiveText = vm.$refs.achiveText.children[0].value;
            vm.$refs.achiveText.textContent = null;
            vm.$refs.achiveText.textContent = achiveText;
          })
          .catch(response => console.log(response))
      },

      goalImageRegister: function() {
        var vm = this;
        var year = this.getCookie('year');
        var month = this.getCookie('month');
        var goalImageText = vm.$refs.goalImageText.children[0].value;

        axios.get('http://localhost:100/goalImageRegister?year=' + year + '&month=' + month + '&goalImageText=' + goalImageText)
          .then(function(response) {
            var goalImageText = vm.$refs.goalImageText.children[0].value;
            vm.$refs.goalImageText.textContent = null;
            vm.$refs.goalImageText.textContent = goalImageText;
          })
          .catch(response => console.log(response))
      },

      passionRegister: function() {
        var vm = this;
        var year = this.getCookie('year');
        var month = this.getCookie('month');
        var passionText = vm.$refs.passionText.children[0].value;

        axios.get('http://localhost:100/passionRegister?year=' + year + '&month=' + month + '&passionText=' + passionText)
          .then(function(response) {
            var passionText = vm.$refs.passionText.children[0].value;
            vm.$refs.passionText.textContent = null;
            vm.$refs.passionText.textContent = passionText;
          })
          .catch(response => console.log(response))
      },

      registerAchiveRate: function(event,id,index) {
          var vm = this;
          axios.get('http://localhost:100/registerAchiveRate?id=' + id + '&value=' + event.srcElement.value)
              .then(function(response) {
                var value = vm.$refs.achiveNum[index].children[0].value;
                vm.$refs.achiveNum[index].textContent = null;
                vm.$refs.achiveNum[index].textContent = value;
                var newRate = Math.floor((value / vm.getCountCurrentMonthDate(vm.getCurrentMonth())) * 100);
                console.log(newRate);
                vm.$refs.achiveRate[index].textContent = newRate;
              })
              .catch(function(response) {
                console.log(response);
              })
      },

      achiveRate: function(index) {
        return Math.floor((this.achiveRates[index] / this.getCountCurrentMonthDate(this.getCurrentMonth())) * 100);
      },

      editAchiveNum: function(index) {
        /* 押下した編集ボタンに対応する要素を取得する */
        var objElem = this.$refs.tr2[index].children[2].children[0];
        var objElemText = objElem.textContent;
        objElem.textContent = null;
        var input = document.createElement('input');
        input.classList.add('achiveNumInput');
        /* inputタグのvalue値に元々表示されていたテキストを突っ込む */
        input.value = objElemText;
        /* inputタグを挿入する */
        objElem.appendChild(input);


      },

      habitGetDateResult: function() {
        this.changeFlg = 1;
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
        var vm = this;
        this.$refs.year2.textContent = this.getCookie('year');
        this.$refs.month2.textContent = this.getCookie('month');
        this.overlay2 = true;
        this.disp2 = true;

        var year = this.getCookie('year');
        var month = this.getCookie('month');
        axios.get('http://localhost:100/habitGetItemAndAchiveRate?year=' + year + '&month=' + month)
          .then(function(response) {
            vm.habitLists = response.data[0];
            vm.achiveRateNum = response.data[1];
          })
          .catch(response => console.log(response))
      },

      habitManageAchiveModalDisp: function() {
        var vm = this;

        this.$refs.year3.textContent = this.getCookie('year');
        this.$refs.month3.textContent = this.getCookie('month');

        this.overlay3 = true;
        this.disp3 = true;

        var year = this.getCookie('year');
        var month = this.getCookie('month');
        axios.get('http://localhost:100/habitGet?year=' + year + '&month=' + month)
          .then(function(response) {
            vm.habitManageAchiveLists = response.data;

            var param = response.data;
            var array = [];
            param.forEach(function(e) {
              array.push(e.id);
            });

            const url = 'http://localhost:100/getHabitAchiveRate';
            const params = {
              result: array
            }
            axios.get(url, {params})
                      .then(function (response) {
                        vm.achiveRates = response.data;
                      })
                      .catch(function (error) {
                        console.log(error);
                      })
          })
          .catch(response => console.log(response))

        /* 目的・目標・ゴールイメージ・意気込みのデータを取得する。 */
        axios.get('http://localhost:100/confirmExistsAchiveData?year=' + year + '&month=' + month)
          .then(function(response) {
            console.log(response);

            /* 目的エリアへのデータ挿入 */
            if (response.data[0] == null) {
              vm.$refs.purposeButton.textContent = '登録';
              var input = document.createElement('input');
              console.log(input);
              vm.$refs.purposeText.appendChild(input);
            } else {
              var purposeText = response.data[0].purpose;
              vm.$refs.purposeButton.textContent = '編集';
              vm.$refs.purposeText.textContent = purposeText;
            }

            /* 目標エリアへのデータ挿入 */
            if (response.data[1] == null) {
              vm.$refs.achiveButton.textContent = '登録';
              var input = document.createElement('input');
              vm.$refs.achiveText.appendChild(input);
            } else {
              var achiveText = response.data[1].achive;
              vm.$refs.achiveButton.textContent = '編集';
              vm.$refs.achiveText.textContent = achiveText;
            }

            /* ゴールイメージエリアへのデータ挿入 */
            if (response.data[2] == null) {
              vm.$refs.goalImageButton.textContent = '登録';
              var input = document.createElement('input');
              vm.$refs.goalImageText.appendChild(input);
            } else {
              var goalImageText = response.data[2].goal_image;
              vm.$refs.goalImageButton.textContent = '編集';
              vm.$refs.goalImageText.textContent = goalImageText;
            }

            /* 意気込みエリアへのデータ挿入 */
            if (response.data[3] == null) {
              vm.$refs.passionButton.textContent = '登録';
              var input = document.createElement('input');
              vm.$refs.passionText.appendChild(input);
            } else {
              var passionText = response.data[3].passion;
              vm.$refs.passionButton.textContent = '編集';
              vm.$refs.passionText.textContent = passionText;
            }
          }).catch(error => console.log(error));
      },

      habitRegisterModalHidden: function() {
        this.overlay = false;
        this.disp = false;
      },

      habitRegisterModalHidden2: function() {
        this.overlay2 = false;
        this.disp2 = false;
      },

      habitRegisterModalHidden3: function() {
        this.overlay3 = false;
        this.disp3 = false;
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
      },

    },

    mounted : function(){
      var vm = this;
      var now = new Date();
      var year = now.getFullYear();
      var month = (now.getMonth() + 1);
      axios.get('http://localhost:100/habitGetMonthAchiveRate?year=' + year + '&month=' + month)
        .then(function(response) {
          var result = parseInt(response.data);
          vm.$refs.monthAchiveRate.textContent = result + '%';
          vm.$refs.monthAchiveRateGraph.style.width = result + '%';
        })
        .catch(response => console.log(response))
      this.$refs.achiveRateMonth.textContent = month;
    }
  }
</script>