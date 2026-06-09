@extends('layouts.app')

@section('css')
    {{-- 💡 この画面専用のCSS（purchase.css）を読み込みます --}}
    <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase-container">
    
    <div class="purchase-main">
        
        <div class="purchase-section product-detail-box">
            <div class="product-img-wrap">
                <img src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}">
            </div>
            <div class="product-info-wrap">
                <h2>{{ $item->name }}</h2>
                <p class="product-price">¥{{ number_format($item->price) }}</p>
            </div>
        </div>

        <div class="purchase-section">
            <div class="section-header">
                <h3>支払い方法</h3>
            </div>
            <div class="form-group">
                <select name="payment_method" id="payment_method">
                    <option value="" disabled selected>選択してください</option>
                    @foreach($payment_methods as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                @error('payment_method')
                    <span class="error-message" style="color: #ff4d4f; font-size: 0.85rem; margin-top: 8px; display: block; font-weight: bold; text-align: left;">
                        {{ $message }}
                    </span>
                @enderror

            </div>
        </div>

        <div class="purchase-section">
            <div class="section-header">
                <h3>配送先</h3>
                <a href="{{ route('address.edit', ['item_id' => $item->id]) }}" class="edit-link">変更する</a>
            </div>
            <div class="address-info">
                <p class="postal-code">〒 {{ $user->postal_code }}</p>
                <p class="address-text">{{ $user->address }} {{ $user->building }}</p>
            @error('address')
            <span class="error-message" style="color: #ff4d4f; font-size: 0.85rem; margin-top: 8px; display: block; font-weight: bold; text-align: left;">
                {{ $message }}
            </span>
            @enderror
            </div>
        </div>

    </div>

    <div class="purchase-sidebar">
        
        <div class="purchase-sidebar-box">
            <div class="summary-row">
                <span class="summary-label">商品代金</span>
                <span class="summary-value">¥{{ number_format($item->price) }}</span>
            </div>

            <div class="summary-row">
                <span class="summary-label">支払い方法</span>
                <span class="summary-value" id="selected-payment-display">未選択</span>
            </div>
        </div>
        
        <form action="{{ route('purchase.checkout', ['item_id' => $item->id]) }}" method="POST" class="purchase-form-outer">
            @csrf

            <input type="hidden" name="payment_method" id="hidden-payment-method" value="">
<input type="hidden" name="address" value="{{ $user->postal_code ? $user->postal_code . ',' . $user->address . ',' . $user->building : '' }}">

            <button type="submit" class="purchase-btn">購入する</button>
        </form>
        
    </div>


</div>

{{--支払い方法を選んだら、右側の表にリアルタイムで文字を反映させるJavaScript --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentSelect = document.getElementById('payment_method');
    const paymentDisplay = document.getElementById('selected-payment-display');
    const hiddenInput = document.getElementById('hidden-payment-method');

    if (paymentSelect) {
        paymentSelect.addEventListener('change', function() {
            if (paymentDisplay) {
                const selectedText = paymentSelect.options[paymentSelect.selectedIndex].text;
                paymentDisplay.innerText = selectedText;
            }
            
            if (hiddenInput) {
                hiddenInput.value = paymentSelect.value;
            }
        });
    }
});
</script>

@endsection
