@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="user-profile-header">
        <div class="user-avatar">
            @if($user->img_url)
                <img src="{{ asset('storage/' . $user->img_url) }}" alt="ユーザーアイコン">
            @else
                <div class="avatar-placeholder"></div>
            @endif
        </div>
        
        <h1 class="user-name">{{ $user->name }}</h1>
        
       <a href="{{ route('edit.profile') }}" class="btn-edit-profile">プロフィールを編集</a>

    </div>

    <div class="profile-pages">
        <a href="{{ route('mypage.show', ['page' => 'sell']) }}" 
           class="{{ $page === 'sell' ? 'active' : '' }}">出品した商品</a>
           
        <a href="{{ route('mypage.show', ['page' => 'buy']) }}" 
           class="{{ $page === 'buy' ? 'active' : '' }}">購入した商品</a>
    </div>

    <div class="products-grid">
        @forelse ($items as $item)
            <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="product-item-link">
                <div class="product-card">
                    <div class="product-image-box">
                        <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}">
                        
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
