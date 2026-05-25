@extends('layouts.app')

<!-- app.blade.php の @yield('css') に差し込み -->
@section('css')
    <link rel="stylesheet" href="{{ asset('css/item_detail.css') }}">
@endsection

@section('content')
<div class="container">
    <!-- 左側：商品画像エリア -->
    <div class="image-section">
        <div class="item-image-box">
            <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}" class="detail-img">
        </div>
    </div>

    <!-- 右側：詳細情報エリア -->
    <div class="info-section">
        <!-- 商品基本情報 -->
        <h1 class="item-title">{{ $item->name }}</h1>
        <p class="brand-name">{{ $item->brand_name ?? 'ブランド名' }}</p>
        <p class="price">¥{{ number_format($item->price) }}<span>（税込）</span></p>

        <!-- 💡 FN018: お気に入り機能（フォーム送信化） -->
        <div class="reactions">
            <!-- お気に入りボタン -->
            <div class="icon-group">
                <!-- ログイン中、かつ自分以外の商品の場合のみボタン化する -->
                @auth
                    @if ($item->user_id !== Auth::id())
                        @if($is_favorited)
                            <!-- ⭕ お気に入り済み（ピンクのハート）：クリックで削除処理 -->
                            <form action="{{ route('favorites.destroy', ['id' => $item->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer; display: inline-flex; align-items: center;">
                                    <img src="{{ asset('img/heartlogo_pink.png') }}" alt="お気に入り解除" class="heart-icon">
                                </button>
                            </form>
                        @else
                            <!-- ❌ 未お気に入り（通常のハート）：クリックで登録処理 -->
                            <form action="{{ route('favorites.store', ['id' => $item->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer; display: inline-flex; align-items: center;">
                                    <img src="{{ asset('img/heartlogo.png') }}" alt="お気に入り登録" class="heart-icon">
                                </button>
                            </form>
                        @endif
                    @else
                        <!-- 💡 自分の商品の場合はボタンにせず画像だけ表示（要件：自分以外の商品にいいねができる） -->
                        <img src="{{ asset('img/heartlogo.png') }}" alt="お気に入り（マイ商品）" class="heart-icon" style="opacity: 0.5; cursor: not-allowed;">
                    @endif
                @else
                    <!-- 💡 ゲスト（未ログイン）の場合も画像だけ表示 -->
                    <a href="{{ route('login') }}" style="display: inline-flex; align-items: center;">
                        <img src="{{ asset('img/heartlogo.png') }}" alt="お気に入り（未ログイン）" class="heart-icon">
                    </a>
                @endauth

                <!-- お気に入り合計値 -->
                <span class="reaction-count">{{ $item->favorites->count() }}</span>
            </div>

            <!-- コメント数 -->
            <div class="icon-group">
                <img src="{{ asset('img/comment_logo.png') }}" alt="コメント" class="comment-icon">
                <span class="reaction-count">{{ $item->comments->count() }}</span>
            </div>
        </div>

        <!-- FN019: 購入手続き動線 -->
        <a href="{{ route('items.purchase', ['id' => $item->id]) }}" class="btn-action">購入手続きへ</a>

        <!-- 商品説明 -->
        <h2 class="section-title">商品説明</h2>
        <div class="description-text">
            <p>{{ $item->description }}</p>
        </div>

        <!-- 商品の情報 -->
        <h2 class="section-title">商品の情報</h2>
        
        <table class="meta-table">
            <tr>
                <th>カテゴリー</th>
                <td>
                    @foreach($item->categories as $category)
                        <span class="tag">{{ $category->name }}</span>
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>商品の状態</th>
                <td>{{ $item->condition->name ?? '良好' }}</td>
            </tr>
        </table>

        <!-- コメント一覧表示 -->
        <h2 class="section-title">コメント ({{ $item->comments->count() }})</h2>
        <div class="comment-list">
            @foreach($item->comments as $comment)
            <div class="comment-item">
                <div class="comment-user">
                    <div class="user-avatar"></div>
                    <span>{{ $comment->user->name ?? 'admin' }}</span>
                </div>
                <div class="comment-body">
                    {{ $comment->comment }}
                </div>
            </div>
            @endforeach
        </div>

        <!-- FN020: コメント送信フォーム -->
        <h2 class="section-title">商品へのコメント</h2>

        @if(Auth::check())
    <!-- 1.【最重要】Laravel側から戻ってきたエラーメッセージをここに赤文字で表示します -->
    @error('comment')
        <div class="error-messages" style="margin-bottom: 15px;">
            <p style="color: #ff4d4d; font-size: 14px; font-weight: bold; margin: 0;">{{ $message }}</p>
        </div>
    @enderror

    <!-- 2.【変更】classの後ろに novalidate を追加して、ブラウザの強制チェックを停止します -->
    <form action="{{ route('comments.store', ['id' => $item->id]) }}" method="POST" class="comment-form" novalidate>
        @csrf
        
        <!-- 3.【変更】ブラウザのポップアップを防ぐため、required を完全に【削除】します -->
        <textarea name="comment">{{ old('comment') }}</textarea>
        
        <button type="submit" class="btn-action">コメントを送信する</button>
    </form>
@endif

    </div>
</div>
@endsection
