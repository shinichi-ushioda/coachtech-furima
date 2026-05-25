@extends('layouts.app')

@section('content')
<div class="center">
    <h2 class="page__title">会員登録</h2>

    <form method="POST" action="{{ route('register') }}" class="form-container" novalidate> <!-- novalidate属性を追加してブラウザのデフォルトバリデーションを無効化 -->
        @csrf
        
        {{-- ユーザー名 --}}
        <div class="form-group">
            <label for="name" class="entry__name">ユーザー名</label>
            <input type="text" id="name" name="name" class="input" value="{{ old('name') }}" required autofocus>
            @error('name')
                <p class="form__error">{{ $message }}</p>
            @enderror
        </div>

        {{-- メールアドレス --}}
        <div class="form-group">
            <label for="email" class="entry__name">メールアドレス</label>
            <input type="email" id="email" name="email" class="input" value="{{ old('email') }}" required>
            @error('email')
                <p class="form__error">{{ $message }}</p>
            @enderror
        </div>
        
        {{-- パスワード --}}
        <div class="form-group">
            <label for="password" class="entry__name">パスワード</label>
            <input type="password" id="password" name="password" class="input" required>
            @error('password')
                <p class="form__error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 確認用パスワード --}}
        <div class="form-group">
            <label for="password_confirmation" class="entry__name">確認用パスワード</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="input" required>
        </div>
             
        <button type="submit" class="btn btn--register">登録する</button>
    </form>
    
    <p class="link__login">
        <a href="{{ route('login') }}">ログインはこちら</a>
    </p>
</div>
@endsection