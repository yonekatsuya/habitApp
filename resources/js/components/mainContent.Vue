<template>
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
        <tbody v-if={{bool}}>
          <tr><td>1</td><td>2</td><td>3</td><td>4</td><td>5</td><td>6</td><td>7</td></tr>
          <tr><td>8</td><td>9</td><td>10</td><td>11</td><td>12</td><td>13</td><td>14</td></tr>
          <tr><td>15</td><td>16</td><td>17</td><td>18</td><td>19</td><td>20</td><td>21</td></tr>
          <tr><td>22</td><td>23</td><td>24</td><td>25</td><td>26</td><td>27</td><td>28</td></tr>
          <tr><td>29</td><td>30</td><td>31</td><td>32</td><td>33</td><td>34</td><td>35</td></tr>
          <tr><td>36</td><td>37</td><td>38</td><td>39</td><td>40</td><td>41</td><td>42</td></tr>
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
        bool: false
      }
    },
  }
</script>