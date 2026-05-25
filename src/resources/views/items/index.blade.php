@extends('layouts.app')

<!-- app.blade.php の @yield('css') に差し込み -->
@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="container">
    <!-- タブ切り替え部分 -->
    <div class="tabs">
        <!-- 条件3: リンクに現在選択されているタブ（tab）と検索ワード（search）を付与して状態を維持 -->
        <a href="{{ route('items.index', ['tab' => 'recommend', 'search' => $search]) }}" 
           class="{{ $tab === 'recommend' ? 'active' : '' }}">おすすめ</a>
           
        <a href="{{ route('items.index', ['tab' => 'mylist', 'search' => $search]) }}" 
           class="{{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>

    <!-- 商品一覧画像部分 -->
    @forelse ($items as $item)
        {{-- 1. 行の開始：1個目、5個目、9個目...（4で割って1余る数）の時に新しい行を作る --}}
        @if ($loop->iteration % 4 == 1)
            <div class="items-wrapper">
        @endif

        <!-- 商品カード全体をリンク(aタグ)で囲む -->
        <a href="{{ route('items.show', ['id' => $item->id]) }}" class="item-link" style="text-decoration: none; color: inherit;">
            <div class="item">
                <div class="image-box">
                    <!--  修正：すべての商品画像をローカルのstorageフォルダから一本化して読み込みます -->
                    <!-- ※データベースのimg_urlには「products/ファイル名.jpg」と入っている状態を想定しています -->
                    <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}" style="width: 300px; height: 300px; object-fit: cover;">
                    
                    <!-- 購入済み商品は "Sold" と表示される -->
                    @if ($item->is_sold)
                        <div class="sold-badge">Sold</div>
                    @endif
                </div>
                <!-- 商品名を表示 -->
                <p class="item-name">{{ $item->name }}</p>
            </div>
        </a>
    
        {{-- 2. 行の終了：4の倍数の時、または「これが最後のデータ」の時に行を閉じる --}}
        @if ($loop->iteration % 4 == 0 || $loop->last)
            </div>
        @endif

    @empty
        <p></p>
    @endforelse
</div>
@endsection
