@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/listing.css') }}">
@endsection

@section('content')
<div class="listing-container">
    <h2 class="page-title">商品の出品</h2>

    <form action="{{ route('item.store') }}" method="POST" enctype="multipart/form-data" class="listing-form">
        @csrf

        <div class="form-group">
            <label class="section-title">商品画像</label>
            <div class="image-upload-box" id="upload-box">
                <label class="image-upload-btn">
                    <span>画像を選択する</span>
                    <input type="file" name="image" id="item_image_input" accept="image/*" onchange="previewItemImage(this);" >
                </label>
                <div id="image-preview-wrap" class="image-preview-wrap" style="display: none;">
                    <img id="item-preview" src="" alt="出品画像プレビュー">
                </div>
            </div>
            @error('image')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="section-divider">
            <h3 class="section-header-title">商品の詳細</h3>
        </div>

        <div class="form-group">
            <label class="form-label">カテゴリー</label>
            <div class="category-tags-container">
                @foreach($categories as $category)
                    <label class="category-tag">
                        <input type="checkbox" name="categories[]" value="{{ $category->id }}" {{ is_array(old('categories')) && in_array($category->id, old('categories')) ? 'checked' : '' }}>
                        <span>{{ $category->name }}</span>
                    </label>
                @endforeach
            </div>
            @error('categories')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="condition_id" class="form-label">商品の状態</label>
            <div class="select-wrap">
                <select name="condition_id" id="condition_id" >
                    <option value="" disabled selected>選択してください</option>
                    @foreach($conditions as $condition)
                        <option value="{{ $condition->id }}" {{ old('condition_id') == $condition->id ? 'selected' : '' }}>{{ $condition->name }}</option>
                    @endforeach
                </select>
            </div>
            @error('condition_id')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="section-divider">
            <h3 class="section-header-title">商品名と説明</h3>
        </div>

        <div class="form-group">
            <label for="name" class="form-label">商品名</label>
            <input type="text" name="name" id="name" class="form-input" value="{{ old('name') }}" >
            @error('name')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="brand_name" class="form-label">ブランド名</label>
            <input type="text" name="brand_name" id="brand_name" class="form-input" value="{{ old('brand_name') }}">
            @error('brand_name')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="description" class="form-label">商品の説明</label>
            <textarea name="description" id="description" class="form-textarea" rows="5">{{ old('description') }}</textarea>
            @error('description')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="price" class="form-label">販売価格</label>
            <div class="price-input-wrap" style="position: relative;">
                <span class="currency-symbol">¥</span>
                <input type="number" name="price" id="price" class="form-input price-input" value="{{ old('price') }}" min="1">
            </div>
            @error('price')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-action">
            <button type="submit" class="submit-btn">出品する</button>
        </div>
    </form>
</div>

<script>
function previewItemImage(input) {
    const previewWrap = document.getElementById('image-preview-wrap');
    const preview = document.getElementById('item-preview');
    const uploadBtn = input.closest('.image-upload-btn');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewWrap.style.display = 'block';
            if (uploadBtn) {
                uploadBtn.style.display = 'none';
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
