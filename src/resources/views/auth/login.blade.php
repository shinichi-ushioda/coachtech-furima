@extends('layouts.app')

@section('content')
<div class="center">
    <h2 class="page__title">ログイン</h2>

    @if (session('status'))
        <div class="alert">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="form-container" novalidate> <!-- novalidate属性を追加してブラウザのデフォルトバリデーションを無効化し、formrequestのバリデーションを使用 -->
        @csrf
        
        <div class="form-group">
            <label for="email" class="entry__name">メールアドレス</label>
            <input type="text" id="email" name="email" class="input" value="{{ old('email') }}">
            @error('email')
                <p class="form__error">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="form-group">
            <label for="password" class="entry__name">パスワード</label>
            <input type="password" id="password" name="password" class="input">
            @error('password')
                <p class="form__error">{{ $message }}</p>
            @enderror
        </div>
             
        <button type="submit" class="btn btn--login">ログインする</button>
    </form>
    
    <p class="link__register">
        <a href="{{ route('register') }}">会員登録はこちら</a>
    </p>
</div>
@endsection
