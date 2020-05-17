<!DOCTYPE html>
<html lang="js">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>習慣管理アプリ</title>
  <link rel="stylesheet" href="{{asset('css/header.css')}}">
  <link rel="stylesheet" href="{{asset('css/reset.css')}}">
  <link rel="stylesheet" href="{{asset('css/main.css')}}">
</head>
<body>
  <div id="app">
    @include('include.header')
    @yield('content')
  </div>

  <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
  <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>