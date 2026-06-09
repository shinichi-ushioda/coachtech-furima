@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="pages">
        <a href="{{ route('items.index', ['page' => 'recommend', 'search' => $search]) }}" 
           class="{{ $page === 'recommend' ? 'active' : '' }}">おすすめ</a>
           
        <a href="{{ route('items.index', ['page' => 'mylist', 'search' => $search]) }}" 
           class="{{ $page  === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>

    @forelse ($items as $item)
        @if ($loop->iteration % 4 == 1)
            <div class="items-wrapper">
        @endif

        <a href="{{ route('items.show', ['item_id' => $item->id]) }}" class="item-link" style="text-decoration: none; color: inherit;">
            <div class="item">
                <div class="image-box">

                    <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}" style="width: 300px; height: 300px; object-fit: cover;">
                    
                    @if ($item->is_sold)
                        <div class="sold-badge-red">Sold</div>
                    @endif
                </div>

                <p class="item-name">{{ $item->name }}</p>
            </div>
        </a>
    
        @if ($loop->iteration % 4 == 0 || $loop->last)
            </div>
        @endif

    @empty
        <p></p>
    @endforelse
</div>
@endsection
