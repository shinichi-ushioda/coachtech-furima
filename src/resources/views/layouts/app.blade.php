<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}"> <!-- common.css作成まだ -->
    @yield('css')
</head>

<body>
<header class="header">
    <div class="header__logo">
        <a href="/"><img src="{{ asset('img/logo.png') }}" alt="ロゴ"></a>
    </div>

    <div class="header__search">
        <form action="/search" method="get">
            <input class="header__search-input" type="text" name="keyword" placeholder="なにをお探しですか？" >
        </form>
    </div>

  <nav class="header__nav">
    <ul>
        @if(Auth::check())
        <li>
            <form method="post" action="/logout">
                @csrf
                <button class="header__logout" type="submit">ログアウト</button>
            </form>
        </li>
        <li><a href="/mypage">マイページ</a></li>
        @else
        <li class="header__login"><a href="/login">ログイン</a></li>
        <li class="header__register"><a href="/register">マイページ</a></li>
        @endif

        <li class="header__btn">
            <a href="/sell">出品</a>
        </li>
    </ul>
  </nav>

</header> 
    @yield('content') <!-- contentは別のbladeファイルで定義 -->
</body>
</html>