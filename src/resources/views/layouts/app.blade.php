<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>coachtechフリマ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    @yield('css')
</head>

<body>
<header class="header">
    <div class="header__logo">
        <a href="/"><img src="{{ asset('storage/img/logo.png') }}" alt="COACHTECH"></a>
    </div>

    @if (!Route::is('login') && !Route::is('register') && !Route::is('verification.notice'))
        
    <div class="header__search">
        <form action="/" method="GET">
           <input type="hidden" name="page" value="{{ $page ?? 'recommend' }}">
           <input class="header__search-input" type="text" name="search" placeholder="なにをお探しですか？" value="{{ $search ?? '' }}">
        </form>
    </div>

        <nav class="header__nav">
            <ul>
                @auth
                    <li>
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button class="header__logout" type="submit">ログアウト</button>
                        </form>
                    </li>
                    <li><a href="/mypage">マイページ</a></li>
                @else
                    <li class="header__login"><a href="{{ route('login') }}">ログイン</a></li>
                    <li class="header__register"><a href="{{ route('login') }}">マイページ</a></li>
                @endauth

                <li class="header__btn">
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