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
            <th>月</th>
            <th>火</th>
            <th>水</th>
            <th>木</th>
            <th>金</th>
            <th>土</th>
            <th>日</th>
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
      now.setDate(1);
      var year  = now.getFullYear();
      var month = now.getMonth() + 1;
      var week = now.getDay();
      this.$refs.year.textContent = year;
      this.$refs.month.textContent = month;
      now.setMonth(now.getMonth() + 1);
      now.setDate(0);
      var lastDate = now.getDate();
      now.setMonth(now.getMonth() - 1);
      now.setDate(1);
      var firstDate = now.getDate();

      var tbody = this.$refs.tbody;
      for (var i = 0;i <= (lastDate + week);i++) {
        if (month === (now.getMonth() + 1)) {
          if (i === 0 || i % 7 === 0) {
            var trElem = document.createElement('tr');
            tbody.appendChild(trElem);
          }
          var trElemLast = tbody.lastElementChild;
          var tdElem = document.createElement('td');
          trElemLast.appendChild(tdElem);
          var tdElemLast = trElemLast.lastElementChild;
          if (i >= (week - 1)) {
            tdElemLast.innerText = now.getDate();
            now.setDate(now.getDate() + 1);
          }
        }
      }
    }
  }
</script>