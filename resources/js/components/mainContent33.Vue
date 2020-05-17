<template>
  <div class="main">
  <div class="calendar-head">
    <div class="calendar-head-left"><a href="#">前月へ</a></div>
    <div class="calendar-head-center"><span ref="year"></span>年<span ref="month"></span>月</div>
    <div class="calendar-head-right"><a href="#">次月へ</a></div>
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
</div>
</template>

<script>
  export default {
    props: {
      
    },
    data() {
      return {
        
      }
    },
    mounted: function() {
      var now   = new Date();

      /* 今日の日付を月初に設定 */
      now.setDate(1);

      /* 今年・今月・今日の日付・今日の曜日の値を取得 */
      var year  = now.getFullYear();
      var month = now.getMonth() + 1;
      var date = now.getDate();
      var week = now.getDay();

      /* 今年の値と今月の値をカレンダーのタイトルに設定 */
      this.$refs.year.textContent = year;
      this.$refs.month.textContent = month;

      /* 今月の値を次月に設定し、今月の末日の値を取得 */
      now.setMonth(now.getMonth() + 1);
      now.setDate(0);
      var lastDate = now.getDate();

      /* 今月の値を今月に戻し、月初の値を取得 */
      now.setMonth(now.getMonth() - 1);
      now.setDate(1);
      var firstDate = now.getDate();


      var tbody = this.$refs.tbody;

      /* 曜日の値をループ回数に足して末日まで表示されるようにする */
      for (var i = 0;i <= (lastDate + week);i++) {
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
            /* 今日の日付テキストを挿入する */
            tdElemLast.innerText = now.getDate();

            /* 土日であれば背景色をつける */
            if (now.getDay() == 0 || now.getDay() == 6) {
              tdElemLast.classList.add('holidayColor');
            }

            /* 今日の日付であれば背景色をつける */
            if (tdElemLast.innerText == date && (now.getMonth() + 1) == month && now.getFullYear() == year) {
              tdElemLast.classList.remove('holidayColor');
              tdElemLast.classList.add('currentDateColor');
            }

            /* 日付をインクリメントする */
            now.setDate(now.getDate() + 1);
          }
        }
      }

      /* カレンダー最終行のtdの数が7未満であれば、7になるまでtdを追加する */
      var tdElemCount = tbody.lastElementChild.childElementCount;
      var tdAddCount = 7 - tdElemCount;
      if (tdElemCount < 7) {
        for (var i = 0;i < tdAddCount;i++) {
          var tdElem = document.createElement('td');
          tbody.lastElementChild.appendChild(tdElem);
        }
      }
    }
  }
</script>