<template>
  <div class="side">
    <div class="side-tab">
      <p>
        <span>今月の目標達成率</span>
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
              <table class="olElement3">
                <thead>
                  <tr>
                    <td class="habit-history-table-no-td">No</td>
                    <td class="habit-history-table-item-td">習慣項目</td>
                    <td class="habit-history-table-index-td">達成指標</td>
                    <td class="habit-history-table-date-td" v-for="n in getCountCurrentMonthDate(getCurrentMonth())">{{n}}</td>
                  </tr>
                </thead>
                <tbody ref="tbody">
                  <tr v-for="(habitList, index) in habitLists" ref="tr">
                    <td class="habit-history-table-no-td">{{index + 1}}</td>
                    <td class="habit-history-table-item-td">{{habitList.item}}</td>
                    <td class="habit-history-table-index-td">{{achiveRateNum[index]}}/{{getCountCurrentMonthDate(getCurrentMonth())}}</td>
                    <td class="habit-history-table-date-td" v-for="n in getCountCurrentMonthDate(getCurrentMonth())" :data-id="habitList.id" :data-year="habitList.year" :data-month="habitList.month" :data-date="n" ref="td">{{((countHabitLists - 1) == index && n == getCountCurrentMonthDate(getCurrentMonth())) ? habitGetDateResult() : ''}}</td>
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
                <div class="manage-achive-area-title"><span>目的</span></div>
                <div class="manage-achive-area-text" ref="purposeText"></div>
                <div class="manage-achive-area-button"><button ref="purposeButton" @click="purposeRegister"></button></div>
              </div>

              <div class="achive-area">
                <div class="manage-achive-area-title"><span>目標</span></div>
                <div class="manage-achive-area-text"  ref="achiveText"></div>
                <div class="manage-achive-area-button"><button ref="achiveButton" @click="achiveRegister"></button></div>
              </div>

              <div class="goal-image-wrapper">
                <div class="manage-achive-area-title"><span>ゴールイメージ</span></div>
                <div class="manage-achive-area-text" ref="goalImageText"></div>
                <div class="manage-achive-area-button"><button ref="goalImageButton" @click="goalImageRegister"></button></div>
              </div>

              <div class="passion-area">
                <div class="manage-achive-area-title"><span>意気込み</span></div>
                <div class="manage-achive-area-text" ref="passionText"></div>
                <div class="manage-achive-area-button"><button ref="passionButton" @click="passionRegister"></button></div>
              </div>
            </div>

            <div class="achive-manage-index-title">
              <div>達成指標</div>
            </div>

            <table class="olElement2">
              <thead>
                <tr>
                  <td class="achive-manage-no-td">No</td>
                  <td class="">項目</td>
                  <td class="achive-manage-index-td">達成指標</td>
                  <td class="achive-manage-rate-td">目標達成率</td>
                </tr>
              </thead>
              <tbody ref="tbody2">
                <tr v-for="(item,index) in habitManageAchiveLists" ref="tr2">
                  <td class="achive-manage-no-td olElement2-base-td">{{index + 1}}</td>
                  <td class="olElement2-base-td">{{item.item}}</td>
                  <td class="achive-manage-index-td olElement2-base-td"><span ref="achiveNum" @keyup.enter="registerAchiveRate($event,item.id,index)">{{achiveRates[index]}}</span>/{{getCountCurrentMonthDate(getCurrentMonth())}} <span class="editAchiveNumButton" @click="editAchiveNum($event,item.id,index)">編集</span></td>
                  <td class="achive-manage-rate-td olElement2-base-td"><span ref="achiveRate">{{achiveRate(index)}}</span>%</td>
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
  function escape_html (string) {
    if(typeof string !== 'string') {
      return string;
    }
    return string.replace(/[&'`"<>]/g, function(match) {
      return {
        '&': '&amp;',
        "'": '&#x27;',
        '`': '&#x60;',
        '"': '&quot;',
        '<': '&lt;',
        '>': '&gt;',
      }[match]
    });
  }

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
        if (vm.$refs.purposeButton.textContent == '登録')  {
          var purposeText = vm.$refs.purposeText.children[0].value;
          escape_html(purposeText);
          axios.get('http://localhost:100/purposeRegister?year=' + year + '&month=' + month + '&purposeText=' + purposeText)
            .then(function(response) {
              var purposeText = vm.$refs.purposeText.children[0].value;
              vm.$refs.purposeText.textContent = null;
              vm.$refs.purposeText.textContent = purposeText;
              vm.$refs.purposeButton.textContent = '編集';
            })
            .catch(response => console.log(response))
        } else if (vm.$refs.purposeButton.textContent == '編集') {
          if (vm.$refs.purposeText.children[0] == undefined) {
            var purposeText = vm.$refs.purposeText.textContent;
            escape_html(purposeText);
            vm.$refs.purposeText.textContent = null;
            var input = document.createElement('input');
            vm.$refs.purposeText.appendChild(input);
            vm.$refs.purposeText.children[0].value = purposeText;
          } else {
            var purposeText = vm.$refs.purposeText.children[0].value;
            escape_html(purposeText);
            axios.get('http://localhost:100/purposeEdit?purposeText=' + purposeText + '&year=' + year + '&month=' + month)
              .then(response => console.log(response))
              .catch(error => console.log(error))
            vm.$refs.purposeText.textContent = null;
            vm.$refs.purposeText.textContent = purposeText;
          }
        }
      },

      achiveRegister: function() {
        var vm = this;
        var year = this.getCookie('year');
        var month = this.getCookie('month');

        if (vm.$refs.achiveButton.textContent == '登録')  {
          var achiveText = vm.$refs.achiveText.children[0].value;
          escape_html(achiveText);
          axios.get('http://localhost:100/achiveRegister?year=' + year + '&month=' + month + '&achiveText=' + achiveText)
            .then(function(response) {
              var achiveText = vm.$refs.achiveText.children[0].value;
              vm.$refs.achiveText.textContent = null;
              vm.$refs.achiveText.textContent = achiveText;
              vm.$refs.achiveButton.textContent = '編集';
            })
            .catch(response => console.log(response))
        } else if (vm.$refs.achiveButton.textContent == '編集') {
          if (vm.$refs.achiveText.children[0] == undefined) {
            var achiveText = vm.$refs.achiveText.textContent;
            escape_html(achiveText);
            vm.$refs.achiveText.textContent = null;
            var input = document.createElement('input');
            vm.$refs.achiveText.appendChild(input);
            vm.$refs.achiveText.children[0].value = achiveText;
          } else {
            var achiveText = vm.$refs.achiveText.children[0].value;
            escape_html(achiveText);
            axios.get('http://localhost:100/achiveEdit?achiveText=' + achiveText + '&year=' + year + '&month=' + month)
              .then(response => console.log(response))
              .catch(error => console.log(error))
            vm.$refs.achiveText.textContent = null;
            vm.$refs.achiveText.textContent = achiveText;
          }
        }
      },

      goalImageRegister: function() {
        var vm = this;
        var year = this.getCookie('year');
        var month = this.getCookie('month');

        if (vm.$refs.goalImageButton.textContent == '登録') {
          var goalImageText = vm.$refs.goalImageText.children[0].value;
          escape_html(goalImageText);
          axios.get('http://localhost:100/goalImageRegister?year=' + year + '&month=' + month + '&goalImageText=' + goalImageText)
          .then(function(response) {
            var goalImageText = vm.$refs.goalImageText.children[0].value;
            vm.$refs.goalImageText.textContent = null;
            vm.$refs.goalImageText.textContent = goalImageText;
            vm.$refs.goalImageButton.textContent = '編集';
          })
          .catch(response => console.log(response))
        } else if (vm.$refs.goalImageButton.textContent == '編集') {
          if (vm.$refs.goalImageText.children[0] == undefined) {
            var goalImageText = vm.$refs.goalImageText.textContent;
            escape_html(goalImageText);
            vm.$refs.goalImageText.textContent = null;
            var input = document.createElement('input');
            vm.$refs.goalImageText.appendChild(input);
            vm.$refs.goalImageText.children[0].value = goalImageText;
          } else {
            var goalImageText = vm.$refs.goalImageText.children[0].value;
            escape_html(goalImageText);
            axios.get('http://localhost:100/goalImageEdit?goalImageText=' + goalImageText + '&year=' + year + '&month=' + month)
              .then(response => console.log(response))
              .catch(error => console.log(error))
            vm.$refs.goalImageText.textContent = null;
            vm.$refs.goalImageText.textContent = goalImageText;
          }
        }
      },

      passionRegister: function() {
        var vm = this;
        var year = this.getCookie('year');
        var month = this.getCookie('month');

        if (vm.$refs.passionButton.textContent == '登録') {
          var passionText = vm.$refs.passionText.children[0].value;
          escape_html(passionText);
          axios.get('http://localhost:100/passionRegister?year=' + year + '&month=' + month + '&passionText=' + passionText)
            .then(function(response) {
              var passionText = vm.$refs.passionText.children[0].value;
              vm.$refs.passionText.textContent = null;
              vm.$refs.passionText.textContent = passionText;
              vm.$refs.passionButton.textContent = '編集';
            })
            .catch(response => console.log(response))
        } else if (vm.$refs.passionButton.textContent == '編集') {
          if (vm.$refs.passionText.children[0] == undefined) {
            var passionText = vm.$refs.passionText.textContent;
            escape_html(passionText);
            vm.$refs.passionText.textContent = null;
            var input = document.createElement('input');
            vm.$refs.passionText.appendChild(input);
            vm.$refs.passionText.children[0].value = passionText;
          } else {
            var passionText = vm.$refs.passionText.children[0].value;
            escape_html(passionText);
            axios.get('http://localhost:100/passionEdit?passionText=' + passionText + '&year=' + year + '&month=' + month)
              .then(response => console.log(response))
              .catch(error => console.log(error))
            vm.$refs.passionText.textContent = null;
            vm.$refs.passionText.textContent = passionText;
          }
        }

      },

      registerAchiveRate: function(event,id,index) {
          var vm = this;
          var registerValue = event.srcElement.value;
          escape_html(registerValue);
          var pattern = /^[-]?([1-9]\d*|0)(\.\d+)?$/;
          if (pattern.test(registerValue) && (vm.getCountCurrentMonthDate(vm.getCurrentMonth()) >= registerValue) && registerValue >= 0) {
            axios.get('http://localhost:100/registerAchiveRate?id=' + id + '&value=' + registerValue)
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
          } else {
            alert('正しい数値を入力してください。');
            event.srcElement.value = null;
          }
      },

      achiveRate: function(index) {
        return Math.floor((this.achiveRates[index] / this.getCountCurrentMonthDate(this.getCurrentMonth())) * 100);
      },

      editAchiveNum: function(event,id,index) {
        /* 押下した編集ボタンに対応する要素を取得する */
        if (this.$refs.tr2[index].children[2].children[0].children[0] == null) {
          var objElem = this.$refs.tr2[index].children[2].children[0];
          var objElemText = objElem.textContent;
          objElem.textContent = null;
          var input = document.createElement('input');
          input.classList.add('achiveNumInput');
          /* inputタグのvalue値に元々表示されていたテキストを突っ込む */
          input.value = objElemText;
          /* inputタグを挿入する */
          objElem.appendChild(input);
        } else {
          var vm = this;
          var inputText = vm.$refs.achiveNum[index].children[0].value;
          escape_html(inputText);
          var pattern = /^[-]?([1-9]\d*|0)(\.\d+)?$/;
          if (pattern.test(inputText) && (vm.getCountCurrentMonthDate(vm.getCurrentMonth()) >= inputText) && inputText >= 0) {
            axios.get('http://localhost:100/registerAchiveRate?id=' + id + '&value=' + inputText)
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
          } else {
            alert('正しい数値を入力してください。');
            vm.$refs.achiveNum[index].children[0].value = null;
          }
        }
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
              vm.$refs.purposeText.textContent = null;
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
              vm.$refs.achiveText.textContent = null;
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
              vm.$refs.goalImageText.textContent = null;
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
              vm.$refs.passionText.textContent = null;
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
        escape_html(inputText);
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
    }
  }
</script>