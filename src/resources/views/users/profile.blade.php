@extends('layouts.app')

@section('css')
    <!-- マイページ専用のCSSを読み込みます -->
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="container">
    <!-- ========================================================
       ユーザー情報エリア（上段）
       ======================================================== -->
    <div class="user-profile-header">
        <div class="user-avatar">
            <!-- 💡 ユーザー画像がある場合は表示、ない場合はグレーの円を表示 -->
            @if($user->img_url)
                <img src="{{ asset('storage/' . $user->img_url) }}" alt="ユーザーアイコン">
            @else
                <div class="avatar-placeholder"></div>
            @endif
        </div>
        
        <h1 class="user-name">{{ $user->name }}</h1>
        
        <!-- プロフィール編集ページへのリンク（ルート名は必要に応じて調整してください） -->
        <a href="#" class="btn-edit-profile">プロフィールを編集</a>
    </div>

    <!-- ========================================================
       タブ切り替え部分（中段）
       ======================================================== -->
    <div class="profile-tabs">
        <!-- クエリパラメータ「tab」の状態を維持してアクティブクラスを切り替えます -->
        <a href="{{ route('mypage.show', ['tab' => 'sell']) }}" 
           class="{{ $tab === 'sell' ? 'active' : '' }}">出品した商品</a>
           
        <a href="{{ route('mypage.show', ['tab' => 'buy']) }}" 
           class="{{ $tab === 'buy' ? 'active' : '' }}">購入した商品</a>
    </div>

    <!-- ========================================================
       商品一覧エリア（下段）
       ======================================================== -->
    <div class="products-grid">
        @forelse ($items as $item)
            <a href="{{ route('items.show', ['id' => $item->id]) }}" class="product-item-link">
                <div class="product-card">
                    <div class="product-image-box">
                        <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}">
                        
                        <!-- 購入済み商品は "Sold" と表示（トップ画面のロジックを流用） -->
                        @if ($item->is_sold)
                            <div class="sold-badge">Sold</div>
                        @endif
                    </div>
                    <p class="product-name">{{ $item->name }}</p>
                </div>
            </a>
        @empty
            <p class="empty-message">表示する商品がありません。</p>
        @endforelse
    </div>
</div>
@endsection
