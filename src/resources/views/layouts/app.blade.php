<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css') <!-- 各画面ごとのCSSを追加できるようにするためのセクション -->
</head>

<body>
<header class="header">
    <div class="header__logo">
        <!-- assetを使ってロゴを表示 -->
        <a href="/"><img src="{{ asset('storage/img/logo.png') }}" alt="COACHTECH"></a>
    </div>

    <!-- ログイン画面(login)と登録画面(register)以外の場合に、検索窓とナビを表示 -->
    @if (!Route::is('login') && !Route::is('register'))
        
    <div class="header__search">
    
        <form action="/" method="GET">
        
           <!-- タブの状態（おすすめ/マイリスト）を検索時にも引き継ぐための隠しフィールド -->
           <input type="hidden" name="tab" value="{{ $tab ?? 'recommend' }}">
        
           <!-- 修正点3: name属性をコントローラに合わせて「search」に変更し、value値に現在の検索文字（$search）を保持させる -->
           <input class="header__search-input" type="text" name="search" placeholder="なにをお探しですか？" value="{{ $search ?? '' }}">
        </form>
    </div>

        <nav class="header__nav">
            <ul>
                @auth
                    <!-- ログインしている時 -->
                    <li>
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button class="header__logout" type="submit">ログアウト</button>
                        </form>
                    </li>
                    <li><a href="/mypage">マイページ</a></li>
                @else
                    <!-- ログインしていない時 -->
                    <li class="header__login"><a href="{{ route('login') }}">ログイン</a></li>
                    <li class="header__register"><a href="{{ route('login') }}">マイページ</a></li>
                @endauth

                <li class="header__btn">
                    <!-- 未ログイン時はログイン画面へ飛ばす設計にしています -->
                    <a href="{{ Auth::check() ? '/sell' : route('login') }}">出品</a>
                </li>
            </ul>
        </nav>
        
    @endif
</header> 

<main>
    @yield('content')
</main>

</body>
</html>