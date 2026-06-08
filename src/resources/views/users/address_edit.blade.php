@extends('layouts.app')

@section('css')
    {{-- この画面専用のCSS（address_edit.css）を読み込みます --}}
    <link rel="stylesheet" href="{{ asset('css/address_edit.css') }}">
@endsection

@section('content')
<div class="address-container">
    <h2>住所の変更</h2>

    <form action="{{ route('address.update', ['item_id' => $item_id]) }}" method="POST" class="address-form">
        @csrf

        {{-- 1. 郵便番号 --}}
        <div class="form-group">
            <label for="postal_code">郵便番号</label>
            <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $user->postal_code) }}">
            @error('postal_code')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- 2. 住所 --}}
        <div class="form-group">
            <label for="address">住所</label>
            <input type="text" name="address" id="address" value="{{ old('address', $user->address) }}">
            @error('address')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- 3. 建物名 --}}
        <div class="form-group">
            <label for="building">建物名</label>
            <input type="text" name="building" id="building" value="{{ old('building', $user->building) }}">
            @error('building')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        {{-- 更新ボタン --}}
        <button type="submit" class="submit-btn">更新する</button>
    </form>
</div>
@endsection
