<!-- 以下はcoachtech教材 chapter-1 Larabvelにおける認証　10-1-4ログイン・ログアウト機能を理解するからコピー -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ダッシュボード</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; }
        .card { background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px; }
        .logout-btn { background: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <h1>ダッシュボード</h1>

    <div class="card">
        <h2>ようこそ、{{ $user->name }}さん！</h2>
        <p>メールアドレス: {{ $user->email }}</p>
        <p>登録日: {{ $user->created_at->format('Y年m月d日') }}</p>
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-btn">ログアウト</button>
    </form>
</body>
</html>
