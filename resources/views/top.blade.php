<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Share Life</title>
  {{-- tdkを書くこと --}}

  <!-- Scripts -->
  <script src="{{ asset('js/top_app.js') }}" defer></script>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Sawarabi+Gothic" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css" integrity="sha384-3B6NwesSXE7YJlcLI9RpRqGf2p/EgVH8BgoKTaUrmKNDkHPStTQ3EyoYjCGXaOTS" crossorigin="anonymous">
  
  <!-- Styles -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
  <main>
    <nav class="c-nav--top js-nav">
      <div class="c-nav__left">
        <a href="{{ url('/', null, true) }}" class="c-logo c-logo--top js-nav-item">Share Life</a>
      </div>
      <div class="c-nav__right">
        <a href="#about" class="c-nav__menu c-nav__menu--top js-nav-item">about</a>
        <a href="#usage" class="c-nav__menu c-nav__menu--top js-nav-item">usage</a>
        <a href="{{ url('/login') }}" class="c-nav__menu c-nav__menu--top js-nav-item">ログイン</a>
        <a href="{{ url('/preregister') }}" class="c-nav__menu c-nav__menu--top js-nav-item">ユーザー登録</a>
      </div>
    </nav>
    <section id="hero" class="p-top__hero">
      <div class="p-top__message js-top__message">
        情報共有でスムーズな生活を！<br>
        <span class="p-top__message--title js-top__message--title">Share Life</span>
      </div>
    </section>

    <section id="about" class="p-top__about p-container--top-middle">
      <h2 class="c-text--top-message">予定・お金・決まり事の管理</h2>
      <p class="c-text--top-description">「Share Life」は共同生活を営むカップルのためのサービスです。</p>
      <div class="p-top__about--content u-tc">
        <h2 class="c-title--top-about">about</h3>
        <p class="u-tc u-mb-m">共同生活をストレスなく送るためには３つのポイントがあります。</p>
        <h3 class="c-title--top-accent">１．予定の共有<img class="c-image--top-about" src="/storage/images/calendar_icon.png"></h4>
        <p class="c-text--top-about">お互いの予定を知っておくことで、心配することが減ります。</p>
        <h3 class="c-title--top-accent">２．生活費の記録<img class="c-image--top-about" src="/storage/images/money_icon.png"></h4>
        <p class="c-text--top-about">生活費は２人分を１人で負担するのは厳しいです。共通で使用するものに支払った費用は平等に負担しましょう。</p>
        <h3 class="c-title--top-accent">３．決まり事の共有<img class="c-image--top-about" src="/storage/images/couple_icon.png"></h4>
        <p class="c-text--top-about">生活する上でのルールをちゃんと決めましょう。</p>
        <p class="c-text--top-description">Share Lifeを使えば上記のポイントを簡単に管理することができます。</p>
      </div>
    </section>

    <section id="usage" class="p-top__usage">
      <div class="c-title--usage__container">
      <h2 class="c-title--usage">Share Lifeの使い方</h2>
        <div class="p-container--usage-flex p-container--top-middle">
          <div class="p-container--usage-flex__left">
            <h3 class="c-title--usage-sub">チーム登録</h3>
            <p class="c-text--usage-description">会員登録したユーザー同士でチームを組めます。<br>パートナーを誘ってチームを組むことで、機能を全部使えるようになります。ログインしてすぐに表示されるフォームに、相手の登録しているメールアドレスを入力して招待するだけです。<br>
            ※あらかじめ２人揃っての会員登録が必要となります。</p>
          </div>
          <div class="p-container--usage-flex__right">
            <img class="c-image c-image--half-width" src="/storage/images/holding-hands2.png">
          </div>
        </div>
        <h3 class="c-title--usage-sub u-mb-l">チーム結成後</h3>
        <p>チームを結成した後は、以下の機能がご利用になれます。</p>
        <div class="p-container--usage__disp">
          <div class="js-slide1">
            <p class="c-text--usage-section">ルール決め</p>
            <img class="c-image c-image--disp" src="/storage/images/rule.png">
            <p class="c-text--usage-section">ルールは１人で作るものではありません。パートナーが承認して、初めて有効なルールとなります。</p>
          </div>
          <div class="js-slide2">
            <p class="c-text--usage-section">予定の入力</p>
            <img class="c-image c-image--disp" src="/storage/images/calendar.png">
            <p class="c-text--usage-section">シンプルな使い心地のカレンダー型のインターフェイスです。</p>
          </div>
          <div class="js-slide3">
            <p class="c-text--usage-section">支出の入力</p>
            <img class="c-image c-image--disp" src="/storage/images/money.png">
            <p class="c-text--usage-section">費用と支払った人を入力するだけで、平等に費用を負担するように計算します。</p>
          </div>
        </div>
      </div>
      <div class="p-container--btn-center">
        <button type="button" class="c-btn c-btn--select js-show-slide1">1</button>
        <button type="button" class="c-btn c-btn--select js-show-slide2">2</button>
        <button type="button" class="c-btn c-btn--select js-show-slide3">3</button>
      </div>
    </section>

    <section id="conclusion" class="p-top__conclusion">
      <h2 class="c-title--conclusion">ご利用は下のボタンをクリック！</h2>
      <p class="u-tc">※完全無料で利用可能です。</p>
      <div class="p-container--top-btn-center">
        <a href="{{ url('/preregister') }}" class="c-btn c-btn--top-register">ユーザー登録</a>
      </div>
    </section>
    <footer id="l-footer">
      <p class="c-footer">Copyright &copy; Share Life All Rights Reserved</p>
    </footer>
  </main>
</body>
</html>
