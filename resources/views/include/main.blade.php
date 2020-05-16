<div class="main">
  <div class="calendar-head">
    <div class="calendar-head-left"><a href="#">前月へ</a></div>
    <div class="calendar-head-center">2020年5月</div>
    <div class="calendar-head-right"><a href="#">前月へ</a></div>
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
        <tbody>
          @php
          $date = 1;
          for($i = 0;$i < 6;$i++) {
            echo '<tr>';
              for($j = 0;$j < 7;$j++) {
                echo '<td>' . $date . '</td>';
                $date++;
              }
            echo '</tr>';
          }
          @endphp
        </tbody>
      </table>
    </div>
  </div>
</div>