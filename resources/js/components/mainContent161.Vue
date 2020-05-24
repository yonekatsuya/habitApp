<template>

  <div class="main">
    <div class="calendar-head">
      <div class="calendar-head-left"><button @click="toBeforeMonth">前月へ</button></div>
      <div class="calendar-head-center"><span ref="year"></span>年<span ref="month"></span>月</div>
      <div class="calendar-head-right"><button @click="toNextMonth">次月へ</button></div>
    </div>

    <div class="calendar-content">
      <div class="calendar-content-wrapper">
        <table>
          <thead>
            <tr>
              <th>日</th>
              <th>月</th>
              <th>火</th>
              <th>水</th>
              <th>木</th>
              <th>金</th>
              <th>土</th>
            </tr>
          </thead>
          <tbody ref="tbody">
            
          </tbody>
        </table>
      </div>
    </div>

    <div class="overlay hidden" ref="modal">
      <div id="content" class="hidden" ref="modalContent">
        <div class="overlay-content-title">
          <p><span ref="modalYear"></span>年<span ref="modalMonth"></span>月<span ref="modalDate"></span>日の習慣チェック</p>
          <span class="modalClose" @click="modalClose">×</span>
        </div>
        <div class="overlay-content-wrapper">
          <div class="overlay-content-wrapper-table" ref="modalTableWrapper">
            <div ref="modalError"></div>
            <table id="tableElement">
              <thead>
                <tr>
                  <th>No</th>
                  <th>習慣項目</th>
                  <th>◎</th>
                  <th>◯</th>
                  <th>△</th>
                  <th>×</th>
                </tr>
              </thead>
              <tbody ref="modalTbody">
              </tbody>
            </table>
          </div>
          <div class="overlay-content-wrapper-check">
            <div>
              <button @click="register">登録</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
  var monthFlag = 0;
  export default {
    props: {
      
    },
    data() {
      return {
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

      /* 習慣チェックモーダルで登録ボタンを押下した時に実行 */
      register: function() {
        var vm = this;
        var preventFlag = 0;

        /* 習慣項目数を取得 */
        var trCount = this.$refs.modalTbody.childElementCount;
        var param = [];

        /* 項目ごとに選択したラジオボタンの値を取得して配列にまとめる */
        for (var i = 0;i < trCount;i++) {
          var trElem = this.$refs.modalTbody.children[i];
          if (trElem.children[2].lastElementChild.checked) {
            param.push(trElem.children[2].lastElementChild.getAttribute('value'));
          } else if (trElem.children[3].lastElementChild.checked) {
            param.push(trElem.children[3].lastElementChild.getAttribute('value'));
          } else if (trElem.children[4].lastElementChild.checked) {
            param.push(trElem.children[4].lastElementChild.getAttribute('value'));
          } else if (trElem.children[5].lastElementChild.checked) {
            param.push(trElem.children[5].lastElementChild.getAttribute('value'));
          } else {
            preventFlag = 1;
            if (this.$refs.modalError.childElementCount == 0) {
              var div = document.createElement('div');
              div.classList.add('modalError');
              div.textContent = '全ての項目にチェックを入れてください。';
              this.$refs.modalError.appendChild(div);
            }
            break;
          }
        }

        /* 習慣項目のどれかのラジオボタンにチェックが入っていなかった場合、処理を中断 */
        if (preventFlag === 1) {
          return;
        }

        const url = 'http://localhost:100/habitResult';
        const params = {
          result: param
        }
        axios.get(url, {params})
                  .then(function (response) {
                    console.log(response);
                    var mf = vm.getCookie('monthFlag');
                    vm.dispCurrentMonth(mf);
                  })
                  .catch(function (error) {
                    console.log(error);
                  })
        this.modalClose();
      },

      /* モーダルを非表示にする */
      modalClose: function() {
        this.$refs.modal.classList.add('hidden');
        this.$refs.modalContent.classList.add('hidden');
        this.$refs.modalError.textContent = null;
      },

      /* 今月のカレンダーデータを取得する */
      dispCurrentMonth: function(mf) {
        var root = this;
        var modal = this.$refs.modal;
        var modalContent = this.$refs.modalContent;
        var item = this.$refs.item;

        var modalYear = this.$refs.modalYear;
        var modalMonth = this.$refs.modalMonth;
        var modalDate = this.$refs.modalDate;
        var habitLists = this.habitLists;
        var modalTbody = this.$refs.modalTbody;

        var now   = new Date();
        var currentYear = now.getFullYear();
        var currentMonth = now.getMonth() + 1;
        if (mf != 0) {
          now.setMonth(now.getMonth() + parseInt(mf));
        } else {
          now.setMonth(now.getMonth());
        }

        /* 今日の日付を取得設定 */
        var date = now.getDate();

        /* 今日の日付を月初に設定 */
        now.setDate(1);

        /* 今年・今月・今日の曜日の値を取得 */
        var year  = now.getFullYear();
        var month = now.getMonth() + 1;
        var week = now.getDay();

        /* 今年の値と今月の値をカレンダーのタイトルに設定 */
        this.$refs.year.textContent = year;
        this.$refs.month.textContent = month;

        /* 今月の値を次月に設定し、今月の末日の値を取得 */
        now.setMonth(now.getMonth() + 1);
        now.setDate(0);
        var lastDate = now.getDate();

        /* 今月の値を今月に戻し、月初の値を取得 */
        now.setMonth(now.getMonth());
        now.setDate(1);
        var firstDate = now.getDate();

        /* 2018年~2022年までの祝日一覧を用意 */
        const holidayList = {
          2017: {0: [1,2,9],1: [11],2: [20],3: [29],4: [3,4,5],5: [],6: [17],7: [11],8: [18,23],9: [9],10: [3,23],11:[]},
          2018: {0: [1, 8],1: [11, 12],2: [21],3: [29, 30],4: [3, 4, 5],5: [],6: [16],7: [11],8:     [17, 23, 24],9: [8],10: [3, 23],11: [24]},
          2019: {0: [1, 14],1: [11],2: [21],3: [29],4: [1,2,3, 4, 5, 6],5: [],6: [15],7: [11, 12],8: [16, 23],9: [14],10: [3, 4, 23],11: []},
          2020: {0: [1, 13],1: [11,23,24],2: [20],3: [29],4: [3, 4, 5,6],5: [],6: [23,24],7: [10],8: [21, 22],9: [],10: [3, 23],11: []},
          2021: {0: [1, 11],1: [11,23],2: [20],3: [29],4: [3, 4, 5],5: [],6: [19],7: [11],8: [20, 23],9: [11],10: [3, 23],11: []},
          2022: {0: [1, 10],1: [11,23],2: [21],3: [29],4: [3, 4, 5],5: [],6: [18],7: [11],8: [19, 23],9: [10],10: [3, 23],11: []},
          2023: {0: [1, 2,9],1: [11,23],2: [21],3: [29],4: [3, 4, 5],5: [],6: [17],7: [11],8: [18, 23],9: [9],10: [3, 23],11: []},
          2024: {0: [1,8],1: [11,12,23],2: [20],3: [29],4: [3, 4, 5,6],5: [],6: [15],7: [11,12],8: [16, 22,23],9: [14],10: [3, 4,23],11: []},
          2025: {0: [1,13],1: [11,23,24],2: [20],3: [29],4: [3, 4, 5,6],5: [],6: [21],7: [11],8: [15,23],9: [13],10: [3,23,24],11: []}
        };

        function isHoliday (year,month,day) {
          return holidayList[year][month].indexOf(day) !== -1;
        }

        var tbody = this.$refs.tbody;
        tbody.textContent = null;

        /* 曜日の値をループ回数に足して末日まで表示されるようにする */
        for (var i = 0;i <= (lastDate + week);i++) {
          
          /* 今月の場合にだけ行やセルを付け足す処理をする */
          if (month === (now.getMonth() + 1)) {

            /* 0もしくは7の倍数でtrタグを挿入 */
            if (i === 0 || i % 7 === 0) {
              var trElem = document.createElement('tr');
              tbody.appendChild(trElem);
            }
            
            /* 挿入したtrタグにtdタグを挿入する */
            var trElemLast = tbody.lastElementChild;
            var tdElem = document.createElement('td');
            trElemLast.appendChild(tdElem);
            var tdElemLast = trElemLast.lastElementChild;

            /* 月初を対応する曜日列から始める */
            if (i >= week) {
              /* tdタグに年月日のメタ情報を属性値として持たせる */
              tdElemLast.setAttribute('data-year',now.getFullYear());
              tdElemLast.setAttribute('data-month',now.getMonth() + 1);
              tdElemLast.setAttribute('data-date',now.getDate());
              tdElemLast.setAttribute('data-week',now.getDay());

              /* axiosの非同期で年月日の目標達成率データを取得するのに使用 */
              var nowYear = tdElemLast.getAttribute('data-year');
              var nowMonth = tdElemLast.getAttribute('data-month');
              var nowDate = tdElemLast.getAttribute('data-date');

              /* 日付欄押下時の処理 */
              tdElemLast.onclick = function() {
                modalTbody.textContent = null;

                /* 習慣チェックモーダルを表示する */
                modal.classList.remove('hidden');
                modalContent.classList.remove('hidden');

                /* タイトル行に年月日を表示する */
                modalYear.textContent = this.getAttribute('data-year');
                modalMonth.textContent = this.getAttribute('data-month');
                modalDate.textContent = this.getAttribute('data-date');
                
                /* habit_resultsテーブルから習慣項目一覧を取得し、ラジオボタンと同じ行となるようテーブルを作成する */
                var getYear = root.getCookie('year');
                var getMonth = root.getCookie('month');
                axios.get('http://localhost:100/habitGet?year=' + getYear + '&month=' + getMonth)
                    .then(function (response) {
                      habitLists = response.data;

                      /* 習慣項目の数だけループを回す */
                      habitLists.forEach(function(value,index) {
                        var newTrElement = document.createElement('tr');
                        modalTbody.appendChild(newTrElement);
                        var lastTr = modalTbody.lastElementChild;
                        for (var i = 0;i < 6;i++) {
                          var newTdElement = document.createElement('td');
                          lastTr.appendChild(newTdElement);
                          var lastTd = lastTr.lastElementChild;

                          /* 各td内に番号・項目名・ラジオボタンを挿入する */
                          switch (i) {
                            case 0:
                              lastTd.textContent = index + 1;
                              break;
                            case 1:
                              lastTd.textContent = value.item;
                              break;
                            case 2:
                              /* ラジオボタンについては、後のテーブル格納に必要となる情報をvalue値として持たせておく */
                              lastTd.innerHTML = '<input type="radio" name="' + (index + 1) + '" value="' + value.year + '/' + value.month + '/' + modalDate.textContent + '/' + 'verygood' + '">';
                              break;
                            case 3:
                              lastTd.innerHTML = '<input type="radio" name="' + (index + 1) + '" value="' + value.year + '/' + value.month + '/' + modalDate.textContent + '/' + 'good' + '">';
                              break;
                            case 4:
                              lastTd.innerHTML = '<input type="radio" name="' + (index + 1) + '" value="' + value.year + '/' + value.month + '/' + modalDate.textContent + '/' + 'soso' + '">';
                              break;
                            case 5:
                              lastTd.innerHTML = '<input type="radio" name="' + (index + 1) + '" value="' + value.year + '/' + value.month + '/' + modalDate.textContent + '/' + 'bad' + '">';
                              break;
                          }
                        }
                      });
                    })
                    .catch(function (error) {
                      console.log(error);
                    })
              }

              /* 今日の日付テキストを挿入する */
              tdElemLast.innerText = now.getDate();

              /* 目標達成率データを取得し、該当のtdの日付欄に表示する */
              function getResult(resultTd) {
                axios.get('http://localhost:100/habitResultGet?year=' + nowYear + '&month=' + nowMonth + '&date=' + nowDate)
                      .then(function (response) {
                        if (response.data instanceof Array) {
                          var br = document.createElement('br');
                          resultTd.appendChild(br);
                          var span = document.createElement('span');
                          resultTd.appendChild(span);
                          var span = resultTd.lastElementChild;
                          span.textContent = '目標達成率：' + response.data[0].result + '%';
                        }
                        })
                      .catch(function (error) {
                        console.log(error);
                      })
              }

              getResult(tdElemLast);

              /* 土日であれば背景色をつける */
              if (now.getDay() == 0) {
                tdElemLast.classList.add('SundayColor');
              }

              if (now.getDay() == 6) {
                tdElemLast.classList.add('SaturDayColor');
              }

              /* 今日の日付であれば背景色をつける */
              if (tdElemLast.innerText == date && (now.getMonth() + 1) == currentMonth && currentYear == year) {
                tdElemLast.classList.remove('SundayColor');
                tdElemLast.classList.remove('SaturDayColor');
                tdElemLast.classList.add('currentDateColor');
              }

              /* 祝日であれば背景色を付ける */
              var dummyYear = now.getFullYear();
              var dummyMonth = now.getMonth();
              var dummyDay = now.getDate();
              if (isHoliday(dummyYear,dummyMonth,dummyDay)) {
                tdElemLast.classList.remove('SundayColor');
                tdElemLast.classList.remove('SaturDayColor');
                tdElemLast.classList.remove('currentDateColor');
                tdElemLast.classList.add('publicHoliday');
              }

              /* 日付をインクリメントする */
              now.setDate(now.getDate() + 1);
            } else {
              tdElemLast.classList.add('beforeMonthDataColor');
            }
          }
        }

        /* カレンダー最終行のtdの数が7未満であれば、7になるまでtdを追加する */
        var tdElemCount = tbody.lastElementChild.childElementCount;
        var tdAddCount = 7 - tdElemCount;
        if (tdElemCount < 7) {
          for (var i = 0;i < tdAddCount;i++) {
            var tdElem = document.createElement('td');
            tdElem.classList.add('nextMonthDateColor');
            tbody.lastElementChild.appendChild(tdElem);
          }
        }
      },

      /* 次月のカレンダーデータを取得する */
      toNextMonth: function() {
        var now   = new Date();
        var currentYear = now.getFullYear();
        var currentMonth = now.getMonth() + 1;

        monthFlag++;
        now.setMonth(now.getMonth() + monthFlag);

        if (now.getFullYear() >= 2026) {
          monthFlag--;
          now.setMonth(now.getMonth() + monthFlag);
          return;
        }

        /* 今日の日付を取得設定 */
        var date = now.getDate();

        /* 今日の日付を月初に設定 */
        now.setDate(1);

        /* 今年・今月・今日の曜日の値を取得 */
        var year  = now.getFullYear();
        var month = now.getMonth() + 1;
        var week = now.getDay();

        /* 今年の値と今月の値をカレンダーのタイトルに設定 */
        this.$refs.year.textContent = year;
        this.$refs.month.textContent = month;

        /* 今月の値を次月に設定し、今月の末日の値を取得 */
        now.setMonth(now.getMonth() + 1);
        now.setDate(0);
        var lastDate = now.getDate();

        /* 今月の値を今月に戻し、月初の値を取得 */
        now.setMonth(now.getMonth());
        now.setDate(1);
        var firstDate = now.getDate();

        /* 2018年~2022年までの祝日一覧を用意 */
        const holidayList = {
          2017: {0: [1,2,9],1: [11],2: [20],3: [29],4: [3,4,5],5: [],6: [17],7: [11],8: [18,23],9: [9],10: [3,23],11:[]},
          2018: {0: [1, 8],1: [11, 12],2: [21],3: [29, 30],4: [3, 4, 5],5: [],6: [16],7: [11],8:     [17, 23, 24],9: [8],10: [3, 23],11: [24]},
          2019: {0: [1, 14],1: [11],2: [21],3: [29],4: [1,2,3, 4, 5, 6],5: [],6: [15],7: [11, 12],8: [16, 23],9: [14],10: [3, 4, 23],11: []},
          2020: {0: [1, 13],1: [11,23,24],2: [20],3: [29],4: [3, 4, 5,6],5: [],6: [23,24],7: [10],8: [21, 22],9: [],10: [3, 23],11: []},
          2021: {0: [1, 11],1: [11,23],2: [20],3: [29],4: [3, 4, 5],5: [],6: [19],7: [11],8: [20, 23],9: [11],10: [3, 23],11: []},
          2022: {0: [1, 10],1: [11,23],2: [21],3: [29],4: [3, 4, 5],5: [],6: [18],7: [11],8: [19, 23],9: [10],10: [3, 23],11: []},
          2023: {0: [1, 2,9],1: [11,23],2: [21],3: [29],4: [3, 4, 5],5: [],6: [17],7: [11],8: [18, 23],9: [9],10: [3, 23],11: []},
          2024: {0: [1,8],1: [11,12,23],2: [20],3: [29],4: [3, 4, 5,6],5: [],6: [15],7: [11,12],8: [16, 22,23],9: [14],10: [3, 4,23],11: []},
          2025: {0: [1,13],1: [11,23,24],2: [20],3: [29],4: [3, 4, 5,6],5: [],6: [21],7: [11],8: [15,23],9: [13],10: [3,23,24],11: []}
        };

        function isHoliday (year,month,day) {
          return holidayList[year][month].indexOf(day) !== -1;
        }

        var tbody = this.$refs.tbody;
        tbody.textContent = null;

        /* 曜日の値をループ回数に足して末日まで表示されるようにする */
        for (var i = 0;i <= (lastDate + week);i++) {
          
          /* 今月の場合にだけ行やセルを付け足す処理をする */
          if (month === (now.getMonth() + 1)) {

            /* 0もしくは7の倍数でtrタグを挿入 */
            if (i === 0 || i % 7 === 0) {
              var trElem = document.createElement('tr');
              tbody.appendChild(trElem);
            }
            
            /* 挿入したtrタグにtdタグを挿入する */
            var trElemLast = tbody.lastElementChild;
            var tdElem = document.createElement('td');
            trElemLast.appendChild(tdElem);
            var tdElemLast = trElemLast.lastElementChild;

            /* 月初を対応する曜日列から始める */
            if (i >= week) {
              /* tdタグに年月日のメタ情報を属性値として持たせる */
              tdElemLast.setAttribute('data-year',now.getFullYear());
              tdElemLast.setAttribute('data-month',now.getMonth() + 1);
              tdElemLast.setAttribute('data-date',now.getDate());
              tdElemLast.setAttribute('data-week',now.getDay());

              /* 今日の日付テキストを挿入する */
              tdElemLast.innerText = now.getDate();

              /* 日曜日であれば背景色をつける */
              if (now.getDay() == 0) {
                tdElemLast.classList.add('SundayColor');
              }

              /* 土曜日であれば背景色をつける */
              if (now.getDay() == 6) {
                tdElemLast.classList.add('SaturDayColor');
              }

              /* 今日の日付であれば背景色をつける */
              if (tdElemLast.innerText == date && (now.getMonth() + 1) == currentMonth && currentYear == year) {
                tdElemLast.classList.remove('SundayColor');
                tdElemLast.classList.remove('SaturDayColor');
                tdElemLast.classList.add('currentDateColor');
              }

              /* 祝日であれば背景色を付ける */
              var dummyYear = now.getFullYear();
              var dummyMonth = now.getMonth();
              var dummyDay = now.getDate();
              if (isHoliday(dummyYear,dummyMonth,dummyDay)) {
                tdElemLast.classList.remove('SundayColor');
                tdElemLast.classList.remove('SaturDayColor');
                tdElemLast.classList.remove('currentDateColor');
                tdElemLast.classList.add('publicHoliday');
              }

              /* 日付をインクリメントする */
              now.setDate(now.getDate() + 1);
            } else {
              tdElemLast.classList.add('beforeMonthDataColor');
            }
          }
        }

        /* カレンダー最終行のtdの数が7未満であれば、7になるまでtdを追加する */
        var tdElemCount = tbody.lastElementChild.childElementCount;
        var tdAddCount = 7 - tdElemCount;
        if (tdElemCount < 7) {
          for (var i = 0;i < tdAddCount;i++) {
            var tdElem = document.createElement('td');
            tdElem.classList.add('nextMonthDateColor');
            tbody.lastElementChild.appendChild(tdElem);
          }
        }
      },

      /* 前月のカレンダーデータを取得する */
      toBeforeMonth: function() {
        var root = this;
        var modal = this.$refs.modal;
        var modalContent = this.$refs.modalContent;
        var item = this.$refs.item;

        var modalYear = this.$refs.modalYear;
        var modalMonth = this.$refs.modalMonth;
        var modalDate = this.$refs.modalDate;
        var habitLists = this.habitLists;
        var modalTbody = this.$refs.modalTbody;

        var now   = new Date();
        var currentYear = now.getFullYear();
        var currentMonth = now.getMonth() + 1;

        monthFlag--;
        document.cookie = 'monthFlag=' + monthFlag;
        now.setMonth(now.getMonth() + monthFlag);

        if (now.getFullYear() <= 2016) {
          monthFlag++;
          now.setMonth(now.getMonth() + monthFlag);
          return;
        }

        /* 今日の日付を取得設定 */
        var date = now.getDate();

        /* 今日の日付を月初に設定 */
        now.setDate(1);

        /* 今年・今月・今日の曜日の値を取得 */
        var year  = now.getFullYear();
        document.cookie = 'year=' + year;
        var month = now.getMonth() + 1;
        document.cookie = 'month=' + month;
        var week = now.getDay();

        /* 今年の値と今月の値をカレンダーのタイトルに設定 */
        this.$refs.year.textContent = year;
        this.$refs.month.textContent = month;

        /* 今月の値を次月に設定し、今月の末日の値を取得 */
        now.setMonth(now.getMonth() + 1);
        now.setDate(0);
        var lastDate = now.getDate();

        /* 今月の値を今月に戻し、月初の値を取得 */
        now.setMonth(now.getMonth());
        now.setDate(1);
        var firstDate = now.getDate();

        /* 2018年~2022年までの祝日一覧を用意 */
        const holidayList = {
          2017: {0: [1,2,9],1: [11],2: [20],3: [29],4: [3,4,5],5: [],6: [17],7: [11],8: [18,23],9: [9],10: [3,23],11:[]},
          2018: {0: [1, 8],1: [11, 12],2: [21],3: [29, 30],4: [3, 4, 5],5: [],6: [16],7: [11],8:     [17, 23, 24],9: [8],10: [3, 23],11: [24]},
          2019: {0: [1, 14],1: [11],2: [21],3: [29],4: [1,2,3, 4, 5, 6],5: [],6: [15],7: [11, 12],8: [16, 23],9: [14],10: [3, 4, 23],11: []},
          2020: {0: [1, 13],1: [11,23,24],2: [20],3: [29],4: [3, 4, 5,6],5: [],6: [23,24],7: [10],8: [21, 22],9: [],10: [3, 23],11: []},
          2021: {0: [1, 11],1: [11,23],2: [20],3: [29],4: [3, 4, 5],5: [],6: [19],7: [11],8: [20, 23],9: [11],10: [3, 23],11: []},
          2022: {0: [1, 10],1: [11,23],2: [21],3: [29],4: [3, 4, 5],5: [],6: [18],7: [11],8: [19, 23],9: [10],10: [3, 23],11: []},
          2023: {0: [1, 2,9],1: [11,23],2: [21],3: [29],4: [3, 4, 5],5: [],6: [17],7: [11],8: [18, 23],9: [9],10: [3, 23],11: []},
          2024: {0: [1,8],1: [11,12,23],2: [20],3: [29],4: [3, 4, 5,6],5: [],6: [15],7: [11,12],8: [16, 22,23],9: [14],10: [3, 4,23],11: []},
          2025: {0: [1,13],1: [11,23,24],2: [20],3: [29],4: [3, 4, 5,6],5: [],6: [21],7: [11],8: [15,23],9: [13],10: [3,23,24],11: []}
        };

        function isHoliday (year,month,day) {
          return holidayList[year][month].indexOf(day) !== -1;
        }

        var tbody = this.$refs.tbody;
        tbody.textContent = null;

        /* 曜日の値をループ回数に足して末日まで表示されるようにする */
        for (var i = 0;i <= (lastDate + week);i++) {
          
          /* 今月の場合にだけ行やセルを付け足す処理をする */
          if (month === (now.getMonth() + 1)) {

            /* 0もしくは7の倍数でtrタグを挿入 */
            if (i === 0 || i % 7 === 0) {
              var trElem = document.createElement('tr');
              tbody.appendChild(trElem);
            }
            
            /* 挿入したtrタグにtdタグを挿入する */
            var trElemLast = tbody.lastElementChild;
            var tdElem = document.createElement('td');
            trElemLast.appendChild(tdElem);
            var tdElemLast = trElemLast.lastElementChild;

            /* 月初を対応する曜日列から始める */
            if (i >= week) {
              /* tdタグに年月日のメタ情報を属性値として持たせる */
              tdElemLast.setAttribute('data-year',now.getFullYear());
              tdElemLast.setAttribute('data-month',now.getMonth() + 1);
              tdElemLast.setAttribute('data-date',now.getDate());
              tdElemLast.setAttribute('data-week',now.getDay());

              /* axiosの非同期で年月日の目標達成率データを取得するのに使用 */
              var nowYear = tdElemLast.getAttribute('data-year');
              var nowMonth = tdElemLast.getAttribute('data-month');
              var nowDate = tdElemLast.getAttribute('data-date');

              /* 日付欄押下時の処理 */
            tdElemLast.onclick = function() {
              modalTbody.textContent = null;

              /* 習慣チェックモーダルを表示する */
              modal.classList.remove('hidden');
              modalContent.classList.remove('hidden');

              /* タイトル行に年月日を表示する */
              modalYear.textContent = this.getAttribute('data-year');
              modalMonth.textContent = this.getAttribute('data-month');
              modalDate.textContent = this.getAttribute('data-date');
              
              /* habit_resultsテーブルから習慣項目一覧を取得し、ラジオボタンと同じ行となるようテーブルを作成する */
                var getYear = root.getCookie('year');
                var getMonth = root.getCookie('month');
                axios.get('http://localhost:100/habitGet?year=' + getYear + '&month=' + getMonth)
                  .then(function (response) {
                    habitLists = response.data;

                    /* 習慣項目の数だけループを回す */
                    habitLists.forEach(function(value,index) {
                      var newTrElement = document.createElement('tr');
                      modalTbody.appendChild(newTrElement);
                      var lastTr = modalTbody.lastElementChild;
                      for (var i = 0;i < 6;i++) {
                        var newTdElement = document.createElement('td');
                        lastTr.appendChild(newTdElement);
                        var lastTd = lastTr.lastElementChild;

                        /* 各td内に番号・項目名・ラジオボタンを挿入する */
                        switch (i) {
                          case 0:
                            lastTd.textContent = index + 1;
                            break;
                          case 1:
                            lastTd.textContent = value.item;
                            break;
                          case 2:
                            /* ラジオボタンについては、後のテーブル格納に必要となる情報をvalue値として持たせておく */
                            lastTd.innerHTML = '<input type="radio" name="' + (index + 1) + '" value="' + value.year + '/' + value.month + '/' + modalDate.textContent + '/' + 'verygood' + '">';
                            break;
                          case 3:
                            lastTd.innerHTML = '<input type="radio" name="' + (index + 1) + '" value="' + value.year + '/' + value.month + '/' + modalDate.textContent + '/' + 'good' + '">';
                            break;
                          case 4:
                            lastTd.innerHTML = '<input type="radio" name="' + (index + 1) + '" value="' + value.year + '/' + value.month + '/' + modalDate.textContent + '/' + 'soso' + '">';
                            break;
                          case 5:
                            lastTd.innerHTML = '<input type="radio" name="' + (index + 1) + '" value="' + value.year + '/' + value.month + '/' + modalDate.textContent + '/' + 'bad' + '">';
                            break;
                        }
                      }
                    });
                  })
                  .catch(function (error) {
                    console.log(error);
                  })
            }

              /* 今日の日付テキストを挿入する */
              tdElemLast.innerText = now.getDate();

              /* 目標達成率データを取得し、該当のtdの日付欄に表示する */
              function getResult(resultTd) {
                axios.get('http://localhost:100/habitResultGet?year=' + nowYear + '&month=' + nowMonth + '&date=' + nowDate)
                      .then(function (response) {
                        if (response.data instanceof Array) {
                          var br = document.createElement('br');
                          resultTd.appendChild(br);
                          var span = document.createElement('span');
                          resultTd.appendChild(span);
                          var span = resultTd.lastElementChild;
                          span.textContent = '目標達成率：' + response.data[0].result + '%';
                        }
                        })
                      .catch(function (error) {
                        console.log(error);
                      })
              }

              getResult(tdElemLast);

              /* 土日であれば背景色をつける */
              if (now.getDay() == 0) {
                tdElemLast.classList.add('SundayColor');
              }

              if (now.getDay() == 6) {
                tdElemLast.classList.add('SaturDayColor');
              }

              /* 今日の日付であれば背景色をつける */
              if (tdElemLast.innerText == date && (now.getMonth() + 1) == currentMonth && currentYear == year) {
                tdElemLast.classList.remove('SundayColor');
                tdElemLast.classList.remove('SaturDayColor');
                tdElemLast.classList.add('currentDateColor');
              }

              /* 祝日であれば背景色を付ける */
              var dummyYear = now.getFullYear();
              var dummyMonth = now.getMonth();
              var dummyDay = now.getDate();
              if (isHoliday(dummyYear,dummyMonth,dummyDay)) {
                tdElemLast.classList.remove('SundayColor');
                tdElemLast.classList.remove('SaturDayColor');
                tdElemLast.classList.remove('currentDateColor');
                tdElemLast.classList.add('publicHoliday');
              }

              /* 日付をインクリメントする */
              now.setDate(now.getDate() + 1);
            } else {
              tdElemLast.classList.add('beforeMonthDataColor');
            }
          }
        }

        /* カレンダー最終行のtdの数が7未満であれば、7になるまでtdを追加する */
        var tdElemCount = tbody.lastElementChild.childElementCount;
        var tdAddCount = 7 - tdElemCount;
        if (tdElemCount < 7) {
          for (var i = 0;i < tdAddCount;i++) {
            var tdElem = document.createElement('td');
            tdElem.classList.add('nextMonthDateColor');
            tbody.lastElementChild.appendChild(tdElem);
          }
        }
      },
    },

    mounted: function() {
      document.cookie = 'monthFlag=' + monthFlag;
      /* 今月のカレンダーデータをマウント時に取得する */
      var root = this;
      var modal = this.$refs.modal;
      var modalContent = this.$refs.modalContent;
      var item = this.$refs.item;

      var modalYear = this.$refs.modalYear;
      var modalMonth = this.$refs.modalMonth;
      var modalDate = this.$refs.modalDate;
      var habitLists = this.habitLists;
      var modalTbody = this.$refs.modalTbody;

      var now   = new Date();
      var currentYear = now.getFullYear();
      var currentMonth = now.getMonth() + 1;
      now.setMonth(now.getMonth());

      /* 今日の日付を取得設定 */
      var date = now.getDate();

      /* 今日の日付を月初に設定 */
      now.setDate(1);

      /* 今年・今月・今日の曜日の値を取得 */
      var year  = now.getFullYear();
      document.cookie = 'year=' + year;
      var month = now.getMonth() + 1;
      document.cookie = 'month=' + month;
      var week = now.getDay();

      /* 今年の値と今月の値をカレンダーのタイトルに設定 */
      this.$refs.year.textContent = year;
      this.$refs.month.textContent = month;

      /* 今月の値を次月に設定し、今月の末日の値を取得 */
      now.setMonth(now.getMonth() + 1);
      now.setDate(0);
      var lastDate = now.getDate();

      /* 今月の値を今月に戻し、月初の値を取得 */
      now.setMonth(now.getMonth());
      now.setDate(1);
      var firstDate = now.getDate();

      /* 2018年~2022年までの祝日一覧を用意 */
      const holidayList = {
        2017: {0: [1,2,9],1: [11],2: [20],3: [29],4: [3,4,5],5: [],6: [17],7: [11],8: [18,23],9: [9],10: [3,23],11:[]},
        2018: {0: [1, 8],1: [11, 12],2: [21],3: [29, 30],4: [3, 4, 5],5: [],6: [16],7: [11],8:     [17, 23, 24],9: [8],10: [3, 23],11: [24]},
        2019: {0: [1, 14],1: [11],2: [21],3: [29],4: [1,2,3, 4, 5, 6],5: [],6: [15],7: [11, 12],8: [16, 23],9: [14],10: [3, 4, 23],11: []},
        2020: {0: [1, 13],1: [11,23,24],2: [20],3: [29],4: [3, 4, 5,6],5: [],6: [23,24],7: [10],8: [21, 22],9: [],10: [3, 23],11: []},
        2021: {0: [1, 11],1: [11,23],2: [20],3: [29],4: [3, 4, 5],5: [],6: [19],7: [11],8: [20, 23],9: [11],10: [3, 23],11: []},
        2022: {0: [1, 10],1: [11,23],2: [21],3: [29],4: [3, 4, 5],5: [],6: [18],7: [11],8: [19, 23],9: [10],10: [3, 23],11: []},
        2023: {0: [1, 2,9],1: [11,23],2: [21],3: [29],4: [3, 4, 5],5: [],6: [17],7: [11],8: [18, 23],9: [9],10: [3, 23],11: []},
        2024: {0: [1,8],1: [11,12,23],2: [20],3: [29],4: [3, 4, 5,6],5: [],6: [15],7: [11,12],8: [16, 22,23],9: [14],10: [3, 4,23],11: []},
        2025: {0: [1,13],1: [11,23,24],2: [20],3: [29],4: [3, 4, 5,6],5: [],6: [21],7: [11],8: [15,23],9: [13],10: [3,23,24],11: []}
      };

      function isHoliday (year,month,day) {
        return holidayList[year][month].indexOf(day) !== -1;
      }

      var tbody = this.$refs.tbody;

      /* 曜日の値をループ回数に足して末日まで表示されるようにする */
      for (var i = 0;i <= (lastDate + week);i++) {
        
        /* 今月の場合にだけ行やセルを付け足す処理をする */
        if (month === (now.getMonth() + 1)) {

          /* 0もしくは7の倍数でtrタグを挿入 */
          if (i === 0 || i % 7 === 0) {
            var trElem = document.createElement('tr');
            tbody.appendChild(trElem);
          }
          
          /* 挿入したtrタグにtdタグを挿入する */
          var trElemLast = tbody.lastElementChild;
          var tdElem = document.createElement('td');
          trElemLast.appendChild(tdElem);
          var tdElemLast = trElemLast.lastElementChild;

          /* 月初を対応する曜日列から始める */
          if (i >= week) {
            /* tdタグに年月日のメタ情報を属性値として持たせる */
            tdElemLast.setAttribute('data-year',now.getFullYear());
            tdElemLast.setAttribute('data-month',now.getMonth() + 1);
            tdElemLast.setAttribute('data-date',now.getDate());
            tdElemLast.setAttribute('data-week',now.getDay());

            /* axiosの非同期で年月日の目標達成率データを取得するのに使用 */
            var nowYear = tdElemLast.getAttribute('data-year');
            var nowMonth = tdElemLast.getAttribute('data-month');
            var nowDate = tdElemLast.getAttribute('data-date');

            /* 日付欄押下時の処理 */
            tdElemLast.onclick = function() {
              modalTbody.textContent = null;

              /* 習慣チェックモーダルを表示する */
              modal.classList.remove('hidden');
              modalContent.classList.remove('hidden');

              /* タイトル行に年月日を表示する */
              modalYear.textContent = this.getAttribute('data-year');
              modalMonth.textContent = this.getAttribute('data-month');
              modalDate.textContent = this.getAttribute('data-date');
              
              /* habit_postsテーブルから習慣項目一覧を取得し、ラジオボタンと同じ行となるようテーブルを作成する */
              var getYear = root.getCookie('year');
              var getMonth = root.getCookie('month');
              axios.get('http://localhost:100/habitGet?year=' + getYear + '&month=' + getMonth)
                  .then(function (response) {
                    habitLists = response.data;

                    /* 習慣項目の数だけループを回す */
                    habitLists.forEach(function(value,index) {
                      var newTrElement = document.createElement('tr');
                      modalTbody.appendChild(newTrElement);
                      var lastTr = modalTbody.lastElementChild;
                      for (var i = 0;i < 6;i++) {
                        var newTdElement = document.createElement('td');
                        lastTr.appendChild(newTdElement);
                        var lastTd = lastTr.lastElementChild;

                        /* 各td内に番号・項目名・ラジオボタンを挿入する */
                        switch (i) {
                          case 0:
                            lastTd.textContent = index + 1;
                            break;
                          case 1:
                            lastTd.textContent = value.item;
                            break;
                          case 2:
                            /* ラジオボタンについては、後のテーブル格納に必要となる情報をvalue値として持たせておく */
                            lastTd.innerHTML = '<input type="radio" name="' + (index + 1) + '" value="' + value.year + '/' + value.month + '/' + modalDate.textContent + '/' + 'verygood/' + value.id + '">';
                            break;
                          case 3:
                            lastTd.innerHTML = '<input type="radio" name="' + (index + 1) + '" value="' + value.year + '/' + value.month + '/' + modalDate.textContent + '/' + 'good/' + value.id + '">';
                            break;
                          case 4:
                            lastTd.innerHTML = '<input type="radio" name="' + (index + 1) + '" value="' + value.year + '/' + value.month + '/' + modalDate.textContent + '/' + 'soso/' + value.id + '">';
                            break;
                          case 5:
                            lastTd.innerHTML = '<input type="radio" name="' + (index + 1) + '" value="' + value.year + '/' + value.month + '/' + modalDate.textContent + '/' + 'bad/' + value.id + '">';
                            break;
                        }
                      }
                    });
                  })
                  .catch(function (error) {
                    console.log(error);
                  })
            }

            /* 今日の日付テキストを挿入する */
            tdElemLast.innerText = now.getDate();

            /* 目標達成率データを取得し、該当のtdの日付欄に表示する */
            function getResult(resultTd) {
              axios.get('http://localhost:100/habitResultGet?year=' + nowYear + '&month=' + nowMonth + '&date=' + nowDate)
                    .then(function (response) {
                      if (response.data instanceof Array) {
                        var br = document.createElement('br');
                        resultTd.appendChild(br);
                        var span = document.createElement('span');
                        resultTd.appendChild(span);
                        var span = resultTd.lastElementChild;
                        span.textContent = '目標達成率：' + response.data[0].result + '%';
                      }
                      })
                    .catch(function (error) {
                      console.log(error);
                    })
            }

            getResult(tdElemLast);

            /* 土日であれば背景色をつける */
            if (now.getDay() == 0) {
              tdElemLast.classList.add('SundayColor');
            }

            if (now.getDay() == 6) {
              tdElemLast.classList.add('SaturDayColor');
            }

            /* 今日の日付であれば背景色をつける */
            if (tdElemLast.innerText == date && (now.getMonth() + 1) == currentMonth && currentYear == year) {
              tdElemLast.classList.remove('SundayColor');
              tdElemLast.classList.remove('SaturDayColor');
              tdElemLast.classList.add('currentDateColor');
            }

            /* 祝日であれば背景色を付ける */
            var dummyYear = now.getFullYear();
            var dummyMonth = now.getMonth();
            var dummyDay = now.getDate();
            if (isHoliday(dummyYear,dummyMonth,dummyDay)) {
              tdElemLast.classList.remove('SundayColor');
              tdElemLast.classList.remove('SaturDayColor');
              tdElemLast.classList.remove('currentDateColor');
              tdElemLast.classList.add('publicHoliday');
            }

            /* 日付をインクリメントする */
            now.setDate(now.getDate() + 1);
          } else {
            tdElemLast.classList.add('beforeMonthDataColor');
          }
        }
      }

      /* カレンダー最終行のtdの数が7未満であれば、7になるまでtdを追加する */
      var tdElemCount = tbody.lastElementChild.childElementCount;
      var tdAddCount = 7 - tdElemCount;
      if (tdElemCount < 7) {
        for (var i = 0;i < tdAddCount;i++) {
          var tdElem = document.createElement('td');
          tdElem.classList.add('nextMonthDateColor');
          tbody.lastElementChild.appendChild(tdElem);
        }
      }
    }
  }
</script>