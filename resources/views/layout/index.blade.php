<!DOCTYPE html>
<html lang="js">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>習慣管理アプリ</title>
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <link rel="stylesheet" href="{{asset('style/header.css')}}">
  <link rel="stylesheet" href="{{asset('style/reset.css')}}">
  <link rel="stylesheet" href="{{asset('style/main.css')}}">
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