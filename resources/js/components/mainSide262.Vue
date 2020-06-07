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
                <div class="manage-achive-area-button"><button ref="purposeButton" @click="purposeAchiveRegister('purpose')"></button></div>
              </div>

              <div class="achive-area">
                <div class="manage-achive-area-title"><span>目標</span></div>
                <div class="manage-achive-area-text"  ref="achiveText"></div>
                <div class="manage-achive-area-button"><button ref="achiveButton" @click="purposeAchiveRegister('achive')"></button></div>
              </div>

              <div class="goal-image-wrapper">
                <div class="manage-achive-area-title"><span>ゴールイメージ</span></div>
                <div class="manage-achive-area-text" ref="goalImageText"></div>
                <div class="manage-achive-area-button"><button ref="goalImageButton" @click="purposeAchiveRegister('goalImage')"></button></div>
              </div>

              <div class="passion-area">
                <div class="manage-achive-area-title"><span>意気込み</span></div>
                <div class="manage-achive-area-text" ref="passionText"></div>
                <div class="manage-achive-area-button"><button ref="passionButton" @click="purposeAchiveRegister('passion')"></button></div>
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
                  <td class="achive-manage-index-td olElement2-base-td"><span ref="achiveNum" @keyup.enter="editAchiveNum($event,item.id,index)">{{achiveRates[index]}}</span>/{{getCountCurrentMonthDate(getCurrentMonth())}} <span class="editAchiveNumButton" @click="editAchiveNum($event,item.id,index)">編集</span></td>
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
  /* 共通関数ファイルのインポート */
  import CF from "./../commonFunctions";
  export default {
    data() {
      return {
        /* モーダルの表示・非表示切り替えフラグ */
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
      /* 指定月の習慣項目数を取得 */
      countHabitLists: function() {
        return this.habitLists.length;
      },
    },
    watch: {
      /* 習慣履歴テーブルの一番最後（右下）のtdが生成されたタイミングで実行される */
      changeFlg: function() {
        var vm = this;
        var tbody = this.$refs.tbody;
        var year = CF.getCookie('year');
        var month = CF.getCookie('month');
        var loginId = CF.getCookie('loginId');
        const url = 'http://localhost:100/habitGetDateResult?year=' + year + '&month=' + month + '&loginId=' + loginId;
          axios.get(url)
              .then(function(response) {
                /* テーブルのセル数分処理を回し、習慣項目と年月日のセットに対応する場合、該当する習慣評価をセル内に挿入する */
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
      /* 目的・目標・ゴールイメージ・意気込みの登録・編集 */
      purposeAchiveRegister: function(place) {
        var vm = this;
        var year = CF.getCookie('year');
        var month = CF.getCookie('month');
        var loginId = CF.getCookie('loginId');

        /* 引数に応じて操作対象を変える */
        if (place == 'purpose') {
          var text = vm.$refs.purposeText;
          var button = vm.$refs.purposeButton;
          var register = 'purposeRegister';
          var edit = 'purposeEdit';
          var paramText = 'purposeText';
        } else if (place == 'achive') {
          var text = vm.$refs.achiveText;
          var button = vm.$refs.achiveButton;
          var register = 'achiveRegister';
          var edit = 'achiveEdit';
          var paramText = 'achiveText';
        } else if (place == 'goalImage') {
          var text = vm.$refs.goalImageText;
          var button = vm.$refs.goalImageButton;
          var register = 'goalImageRegister';
          var edit = 'goalImageEdit';
          var paramText = 'goalImageText';
        } else if (place == 'passion') {
          var text = vm.$refs.passionText;
          var button = vm.$refs.passionButton;
          var register = 'passionRegister';
          var edit = 'passionEdit';
          var paramText = 'passionText';
        }

        /* 「登録」ボタン押下時（該当データがまだ保存されていない場合）の処理 */
        if (button.textContent == '登録')  {
          /* 入力値の取得 */
          var registerText = text.children[0].value;
          CF.escape_html(registerText);
          /* 入力値の登録 */
          axios.get('http://localhost:100/' + register + '?year=' + year + '&month=' + month + '&' + paramText + '=' + registerText + '&loginId=' + loginId)
            .then(function(response) {
              /* 最新の入力値を表示し、ボタンの名称を「編集」に切り替える */
              var dispText = text.children[0].value;
              text.textContent = null;
              text.textContent = dispText;
              button.textContent = '編集';
            })
            .catch(response => console.log(response))
          /* 「編集」ボタン押下時（該当データが既に保存されている場合）の処理 */
        } else if (button.textContent == '編集') {
          /* まずテキストエリアに切り替える処理 */
          if (text.children[0] == undefined) {
            /* 入力値の取得 */
            var editText = text.textContent;
            CF.escape_html(editText);
            text.textContent = null;
            var input = document.createElement('input');
            text.appendChild(input);
            /* 切り替えたテキストエリア内に元々表示されていた値を挿入 */
            text.children[0].value = editText;
          } else {
            /* 入力値の取得 */
            var editText = text.children[0].value;
            CF.escape_html(editText);
            /* 入力値の登録 */
            axios.get('http://localhost:100/' + edit + '?' + paramText + '=' + editText + '&year=' + year + '&month=' + month + '&loginId=' + loginId)
              .then(response => console.log(response))
              .catch(error => console.log(error))
            /* inputタグから入力した最新のテキストに表示を切り替える */
            text.textContent = null;
            text.textContent = editText;
          }
        }
      },

      /* 目標管理モーダルに表示する目標達成率の計算・取得 */
      achiveRate: function(index) {
        return Math.floor((this.achiveRates[index] / this.getCountCurrentMonthDate(this.getCurrentMonth())) * 100);
      },

      /* 目標管理モーダルの目標達成指標編集時の処理 */
      editAchiveNum: function(event,id,index) {
        /* 目標達成指標の編集モードでない場合の処理 */
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
          /* 目標達成指標の編集モードの場合の処理 */
          var vm = this;
          /* 入力値の取得 */
          var inputText = vm.$refs.achiveNum[index].children[0].value;
          CF.escape_html(inputText);
          var pattern = /^[-]?([1-9]\d*|0)(\.\d+)?$/;
          /* 入力値が半角数字かつ月の日数を超えてなく正の数である場合のみ処理を実行 */
          if (pattern.test(inputText) && (vm.getCountCurrentMonthDate(vm.getCurrentMonth()) >= inputText) && inputText >= 0) {
            axios.get('http://localhost:100/registerAchiveRate?id=' + id + '&value=' + inputText)
              .then(function(response) {
                var value = vm.$refs.achiveNum[index].children[0].value;
                vm.$refs.achiveNum[index].textContent = null;
                /* 目標達成指標の表示を最新の入力値に切り替える */
                vm.$refs.achiveNum[index].textContent = value;
                /* 目標達成率の計算 */
                var newRate = Math.floor((value / vm.getCountCurrentMonthDate(vm.getCurrentMonth())) * 100);
                /* 計算した目標達成率を表示する */
                vm.$refs.achiveRate[index].textContent = newRate;
              })
              .catch(function(response) {
                console.log(response);
              })
          } else {
            alert('正しい数値を入力してください。');
            /* 入力値をリセットする */
            vm.$refs.achiveNum[index].children[0].value = null;
          }
        }
      },

      /* 習慣履歴テーブルの右下tdが生成されたタイミングでフラグを立てる（ウォッチャ実行） */
      habitGetDateResult: function() {
        this.changeFlg = 1;
      },

      /* 指定月の日数を取得 */
      getCountCurrentMonthDate: function(num) {
        num = parseInt(num);
        var countMonthDate = {1:31,2:29,3:31,4:30,5:31,6:30,7:31,8:31,9:30,10:31,11:30,12:31};
        return countMonthDate[num];
      },

      /* 指定月を取得 */
      getCurrentMonth: function() {
        return CF.getCookie('month');
      },

      /* 習慣項目登録モーダル表示時の処理 */
      habitRegisterModalDisp: function() {
        /* モーダルタイトルに年月の表示 */
        this.$refs.year.textContent = CF.getCookie('year');
        this.$refs.month.textContent = CF.getCookie('month');
        /* 習慣項目登録モーダルの表示処理 */
        this.overlay = true;
        this.disp = true;

        var year = CF.getCookie('year');
        var month = CF.getCookie('month');
        var loginId = CF.getCookie('loginId');
        /* 指定月に対応する習慣項目の取得 */
        axios.get('http://localhost:100/habitGet?year=' + year + '&month=' + month + '&loginId=' + loginId)
          .then(response => this.habitLists = response.data)
          .catch(response => console.log(response))
      },

      /* 習慣履歴モーダル表示時の処理 */
      habitHistoryModalDisp: function() {
        var vm = this;
        /* モーダルタイトルに年月の表示 */
        this.$refs.year2.textContent = CF.getCookie('year');
        this.$refs.month2.textContent = CF.getCookie('month');
        /* 習慣履歴モーダルの表示処理 */
        this.overlay2 = true;
        this.disp2 = true;

        var year = CF.getCookie('year');
        var month = CF.getCookie('month');
        var loginId = CF.getCookie('loginId');
        axios.get('http://localhost:100/habitGetItemAndAchiveRate?year=' + year + '&month=' + month + '&loginId=' + loginId)
          .then(function(response) {
            /* 習慣項目一覧の取得 */
            vm.habitLists = response.data[0];
            /* 習慣項目に対応する目標達成指標の取得 */
            vm.achiveRateNum = response.data[1];
          })
          .catch(response => console.log(response))
      },

      /* 目標管理モーダル表示時の処理 */
      habitManageAchiveModalDisp: function() {
        var vm = this;
        /* モーダルタイトルに年月の表示 */
        this.$refs.year3.textContent = CF.getCookie('year');
        this.$refs.month3.textContent = CF.getCookie('month');
        /* 目標管理モーダルの表示処理 */
        this.overlay3 = true;
        this.disp3 = true;
        var year = CF.getCookie('year');
        var month = CF.getCookie('month');
        var loginId = CF.getCookie('loginId');
        axios.get('http://localhost:100/habitGet?year=' + year + '&month=' + month + '&loginId=' + loginId)
          .then(function(response) {
            vm.habitManageAchiveLists = response.data;

            /* 指定月の習慣項目に対応する目標達成指標を取得して、それぞれ表示する */
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
        axios.get('http://localhost:100/confirmExistsAchiveData?year=' + year + '&month=' + month + '&loginId=' + loginId)
          .then(function(response) {

            /* 目的エリアへのデータ挿入 */
            if (response.data[0] == null) {
              /* 指定月の目的が登録されていない場合の処理 */
              vm.$refs.purposeButton.textContent = '登録';
              var input = document.createElement('input');
              vm.$refs.purposeText.textContent = null;
              /* 登録されていない場合、空のテキストエリアを表示 */
              vm.$refs.purposeText.appendChild(input);
            } else {
              /* 指定月の目的が登録されている場合の処理 */
              var purposeText = response.data[0].purpose;
              vm.$refs.purposeButton.textContent = '編集';
              /* テキストをそのまま表示 */
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

      /* 習慣項目登録モーダルを閉じる処理 */
      habitRegisterModalHidden: function() {
        this.overlay = false;
        this.disp = false;
      },

      /* 習慣履歴モーダルを閉じる処理 */
      habitRegisterModalHidden2: function() {
        this.overlay2 = false;
        this.disp2 = false;
      },

      /* 目標管理モーダルを閉じる処理 */
      habitRegisterModalHidden3: function() {
        this.overlay3 = false;
        this.disp3 = false;
      },

      /* 習慣項目登録処理 */
      addList: function() {
        /* 入力テキストの取得 */
        var inputText = this.habitText;
        CF.escape_html(inputText);
        /* テキストエリア内のリセット */
        this.habitText = '';
        var now = new Date();
        var year = CF.getCookie('year');
        var month = CF.getCookie('month');
        var id = CF.getCookie('loginId');
        /* テキストエリアに文字が入力されている場合のみ実行 */
        if (inputText !== '') {
          axios.get('http://localhost:100/habitPost?habitText=' + inputText + '&year=' + year + '&month=' + month + '&id=' + id)
          /* 習慣項目登録後のリスト作成 */
          .then(response => this.habitLists = response.data)
          .catch(response => console.log(response))
        }
      },

      /* 習慣項目削除処理 */
      deleteList: function(id) {
        if (window.confirm('選択した項目を削除しますか？')) {
          var year = CF.getCookie('year');
          var month = CF.getCookie('month');
          var loginId = CF.getCookie('loginId');
          axios.get('http://localhost:100/habitDelete?id=' + id + '&year=' + year + '&month=' + month + '&loginId=' + loginId)
          /* 習慣項目削除後のリスト作成 */
          .then(response => this.habitLists = response.data)
          .catch(response => console.log(response))
        }
      },

      /* 習慣項目編集処理 */
      editList: function(id,item) {
        var year = CF.getCookie('year');
        var month = CF.getCookie('month');
        var loginId = CF.getCookie('loginId');
        axios.get('http://localhost:100/habitDelete?id=' + id + '&year=' + year + '&month=' + month + '&loginId=' + loginId)
          /* 習慣項目削除後のリスト作成 */
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
      var loginId = CF.getCookie('loginId');
      /* 現在月の目標達成率を取得 */
      axios.get('http://localhost:100/habitGetMonthAchiveRate?year=' + year + '&month=' + month + '&loginId=' + loginId)
        .then(function(response) {
          var result = parseInt(response.data);
          /* テキストの表示 */
          vm.$refs.monthAchiveRate.textContent = result + '%';
          /* 横棒グラフの表示 */
          vm.$refs.monthAchiveRateGraph.style.width = result + '%';
        })
        .catch(response => console.log(response))
    }
  }
</script>