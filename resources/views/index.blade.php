<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name') }}</title>
  <title>Share Life | カップル向け情報管理アプリ</title>
  <meta name="description" content="Share Lifeは同棲生活を送るカップルに向けての情報管理アプリです。予定・お金・決め事を管理することで、日々の生活をスムーズに送るためのお手伝いをします。">
  <meta name="keyword" content="予定管理,費用管理,家計簿,カップル,ルール">

  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">

  <!-- Scripts -->
  <script src="{{ mix('js/app.js') }}" defer></script>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Sawarabi+Gothic" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@400;700&display=swap" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css" integrity="sha384-3B6NwesSXE7YJlcLI9RpRqGf2p/EgVH8BgoKTaUrmKNDkHPStTQ3EyoYjCGXaOTS" crossorigin="anonymous">
  



  <!-- Styles -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
  <div id="app">
    <app-main></app-main>
  </div>
</body>
</html>
