@extends('layouts.app')

@section('content')
<!-- 以下はcoachtech教材 chapter-1 Larabvelにおける認証　10-1-4ログイン・ログアウト機能を理解するからコピー -->
<h1>ログイン</h1>
    <!-- セッションに'status'が存在する場合、成功メッセージを表示。例えば、パスワードリセット後の成功メッセージなどに使用される-->
    @if (session('status'))
        <div class="alert">
            {{ session('status') }}
        </div>
    @endif
    <!--  Fortifyが自動的に登録したloginルートにPOSTします。 -->
    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <div class="form-group">
            <label for="email">メールアドレス</label><br>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password">パスワード</label><br>
            <input type="password" id="password" name="password" required>
            @error('password')
                <p class="error">{{ $message }}</p>
            @enderror
        </div>
             
        <button type="submit">ログインする</button>
    </form>
    
    <p class="link">
        <a href="{{ route('register') }}">会員登録はこちら</a>
    </p>
@endsection
