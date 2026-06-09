@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/item_detail.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="image-section">
        <div class="item-image-box" style="position: relative; overflow: hidden;">
            <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}" class="detail-img">

            @if ($item->is_sold)
               <div class="detail-sold-badge">SOLD</div>
            @endif
        </div>
    </div>

    <div class="info-section">
        <h1 class="item-title">{{ $item->name }}</h1>
        <p class="brand-name">{{ $item->brand_name ?? 'ブランド名' }}</p>
        <p class="price">¥{{ number_format($item->price) }}<span>（税込）</span></p>

        <div class="reactions">
            <div class="icon-group">
                @auth
                    @if ($item->user_id !== Auth::id())
                        @if($is_favorited)
                            <form action="{{ route('favorites.destroy', ['id' => $item->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer; display: inline-flex; align-items: center;">
                                    <img src="{{ asset('img/heartlogo_pink.png') }}" alt="お気に入り解除" class="heart-icon">
                                </button>
                            </form>
                        @else

                            <form action="{{ route('favorites.store', ['item_id' => $item->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer; display: inline-flex; align-items: center;">
                                    <img src="{{ asset('img/heartlogo.png') }}" alt="お気に入り登録" class="heart-icon">
                                </button>
                            </form>
                        @endif
                    @else

                        <img src="{{ asset('img/heartlogo.png') }}" alt="お気に入り（マイ商品）" class="heart-icon" style="opacity: 0.5; cursor: not-allowed;">
                    @endif
                @else

                    <a href="{{ route('login') }}" style="display: inline-flex; align-items: center;">
                        <img src="{{ asset('img/heartlogo.png') }}" alt="お気に入り（未ログイン）" class="heart-icon">
                    </a>
                @endauth

                <span class="reaction-count">{{ $item->favorites->count() }}</span>
            </div>

            <div class="icon-group">
                <img src="{{ asset('img/comment_logo.png') }}" alt="コメント" class="comment-icon">
                <span class="reaction-count">{{ $item->comments->count() }}</span>
            </div>
        </div>

        @if ($item->is_sold) 
            <button class="btn-action" disabled style="background-color: #777; color: #fff; cursor: not-allowed; opacity: 0.8;">
                売り切れました（SOLD OUT）
            </button>

          @elseif (Auth::check() && $item->user_id === Auth::id())
            <button class="btn-action" disabled style="background-color: #bbb; color: #fff; cursor: not-allowed; opacity: 0.7;">
                 購入手続きへ（自分が出品した商品です）
            </button>
          @else
            <a href="{{ route('items.purchase', ['item_id' => $item->id]) }}" class="btn-action">購入手続きへ</a>
        @endif

        <h2 class="section-title">商品説明</h2>
        <div class="description-text">
            <p>{{ $item->description }}</p>
        </div>

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

        <h2 class="section-title">コメント ({{ $item->comments->count() }})</h2>
        <div class="comment-list">
            @foreach($item->comments as $comment)
                <div class="comment-item">
                <div class="comment-user">
                <div class="user-avatar">
              @if($comment->user && $comment->user->img_url)
                    <img src="{{ asset('storage/' . $comment->user->img_url) }}" alt="avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
              @endif
                </div>


                <span>{{ $comment->user->name ?? 'admin' }}</span>
                </div>
                <div class="comment-body">
                    {{ $comment->comment }}
                </div>
                </div>
            @endforeach
        </div>

        <h2 class="section-title">商品へのコメント</h2>

    @if(Auth::check())
      @error('comment')
        <div class="error-messages" style="margin-bottom: 15px;">
            <p style="color: #ff4d4d; font-size: 14px; font-weight: bold; margin: 0;">{{ $message }}</p>
        </div>
      @enderror

        <form action="{{ route('comments.store', ['item_id' => $item->id]) }}" method="POST" class="comment-form" novalidate>
           @csrf
        
           <textarea name="comment">{{ old('comment') }}</textarea>
           <button type="submit" class="btn-action">コメントを送信する</button>

        </form>
    @endif

    </div>
</div>
@endsection
