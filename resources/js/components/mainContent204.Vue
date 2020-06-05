<template>

  <div class="main">
    <div class="calendar-head">
      <div class="calendar-head-left"><button @click="getMonth('before','before')">前月へ</button></div>
      <div class="calendar-head-center"><span class="calendar-title"><span ref="year"></span>年<span ref="month"></span>月</span></div>
      <div class="calendar-head-right"><button @click="getMonth('next','next')">次月へ</button></div>
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
                  <th class="habit-check-modal-no-th">No</th>
                  <th class="habit-check-modal-item-th">習慣項目</th>
                  <th class="habit-check-modal-radio-th">◎</th>
                  <th class="habit-check-modal-radio-th">◯</th>
                  <th class="habit-check-modal-radio-th">△</th>
                  <th class="habit-check-modal-radio-th">×</th>
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
  import CF from "./../commonFunctions";
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
                    var mf = CF.getCookie('monthFlag');
                    vm.getMonth(mf,'');
                  })
                  .catch(function (error) {
                    console.log(error);
                  })
        var resultArray = [];
        param.forEach(function(e) {
          var ary = e.split('/');
          if (ary[3] == '◎') {
            resultArray.push(1);
          }
        });

        var result = Math.floor((resultArray.length / trCount) * 100);

        if (result == 100) {
          alert('最強！神！おめでとう！');
        } else if (result >= 80) {
          alert('惜しいね！でもすごい！明日頑張ろうぜ！');
        } else if (result >= 50) {
          alert('頑張った！もう少し！明日頑張ろう！');
        } else if (result >= 30) {
          alert('もう少し頑張りたい！でもお疲れ様！');
        } else if (result >= 10) {
          alert('今日はちょっとサボり気味かな？お疲れ様！明日頑張ろう！');
        } else if (result == 0) {
          alert('サボりすぎわろた');
        }

        this.modalClose();
      },

      /* モーダルを非表示にする */
      modalClose: function() {
        this.$refs.modal.classList.add('hidden');
        this.$refs.modalContent.classList.add('hidden');
        this.$refs.modalError.textContent = null;
      },

      /* 指定月のカレンダーを取得する */
      getMonth: function(mf,place) {
        if (place == 'mounted') {
          document.cookie = 'monthFlag=' + monthFlag;
        }
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
        if (place == '') {
          if (mf != 0) {
            now.setMonth(now.getMonth() + parseInt(mf));
          } else {
            now.setMonth(now.getMonth());
          }
        } else if (place == 'mounted') {
          now.setMonth(now.getMonth());
        } else if (place == 'next') {
          monthFlag++;
          document.cookie = 'monthFlag=' + monthFlag;
          now.setMonth(now.getMonth() + monthFlag);
          if (now.getFullYear() >= 2026) {
            monthFlag--;
            now.setMonth(now.getMonth() + monthFlag);
            return;
          }
        } else if (place == 'before') {
          monthFlag--;
          document.cookie = 'monthFlag=' + monthFlag;
          now.setMonth(now.getMonth() + monthFlag);
          if (now.getFullYear() <= 2016) {
            monthFlag++;
            now.setMonth(now.getMonth() + monthFlag);
            return;
          }

        }

        /* 今日の日付を取得設定 */
        var date = now.getDate();

        /* 今日の日付を月初に設定 */
        now.setDate(1);

        /* 今年・今月・今日の曜日の値を取得 */
        var year  = now.getFullYear();
        var month = now.getMonth() + 1;
        var week = now.getDay();

        if (place != '') {
          document.cookie = 'year=' + year;
          document.cookie = 'month=' + month;
        }

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

        var tbody = this.$refs.tbody;
        if (place != 'mounted') {
          tbody.textContent = null;
        }

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
                var getYear = CF.getCookie('year');
                var getMonth = CF.getCookie('month');
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
                              lastTd.classList.add('habit-check-modal-no-td');
                              break;
                            case 1:
                              lastTd.textContent = value.item;
                              lastTd.classList.add('habit-check-modal-item-td');
                              break;
                            case 2:
                              /* ラジオボタンについては、後のテーブル格納に必要となる情報をvalue値として持たせておく */
                              lastTd.innerHTML = '<input type="radio" name="' + (index + 1) + '" value="' + value.year + '/' + value.month + '/' + modalDate.textContent + '/' + '◎/' + value.id + '">';
                              lastTd.classList.add('habit-check-modal-radio-td');
                              break;
                            case 3:
                              lastTd.innerHTML = '<input type="radio" name="' + (index + 1) + '" value="' + value.year + '/' + value.month + '/' + modalDate.textContent + '/' + '◯/' + value.id + '">';
                              lastTd.classList.add('habit-check-modal-radio-td');
                              break;
                            case 4:
                              lastTd.innerHTML = '<input type="radio" name="' + (index + 1) + '" value="' + value.year + '/' + value.month + '/' + modalDate.textContent + '/' + '△/' + value.id + '">';
                              lastTd.classList.add('habit-check-modal-radio-td');
                              break;
                            case 5:
                              lastTd.innerHTML = '<input type="radio" name="' + (index + 1) + '" value="' + value.year + '/' + value.month + '/' + modalDate.textContent + '/' + '×/' + value.id + '">';
                              lastTd.classList.add('habit-check-modal-radio-td');
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
                          if (response.data[0].result == 100) {
                            span.classList.add('calendar-text-color');
                          }
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
              if (CF.isHoliday(dummyYear,dummyMonth,dummyDay)) {
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
      /* 現在月のカレンダーを表示 */
      this.getMonth('','mounted');
    }
  }
</script>