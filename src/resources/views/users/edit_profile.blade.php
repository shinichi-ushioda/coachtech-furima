@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/edit_profile.css') }}">
@endsection

@section('content')
<div class="profile-edit-container">
    <h2 class="page-title">プロフィール設定</h2>

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="profile-form">
        @csrf
        @method('PUT')

        <div class="image-section">
            <div class="profile-image-preview">
                @if(auth()->user()->img_url)
                    <img src="{{ asset('storage/' . auth()->user()->img_url) }}" alt="プロフィール画像" id="avatar-preview">
                @else
                    <div class="default-avatar" id="avatar-preview"></div>
                @endif
            </div>
            <label class="image-upload-btn">
                画像を選択する
                <input type="file" name="img_url" id="profile_image_input" accept="image/*" onchange="previewImage(this);">
            </label>
        </div>
        @error('img_url')
            <p class="error-message">{{ $message }}</p>
        @enderror

        <div class="form-group">
            <label for="name" class="form-label">ユーザー名</label>
            <input type="text" name="name" id="name" class="form-input" value="{{ old('name', auth()->user()->name) }}">
            @error('name')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="postal_code" class="form-label">郵便番号</label>
            <input type="text" name="postal_code" id="postal_code" class="form-input" value="{{ old('postal_code', auth()->user()->postal_code) }}">
            @error('postal_code')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="address" class="form-label">住所</label>
            <input type="text" name="address" id="address" class="form-input" value="{{ old('address', auth()->user()->address) }}">
            @error('address')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="building" class="form-label">建物名</label>
            <input type="text" name="building" id="building" class="form-input" value="{{ old('building', auth()->user()->building) }}">
            @error('building')
                <p class="error-message">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-action">
            <button type="submit" class="submit-btn">更新する</button>
        </div>
    </form>
</div>

<script>
function previewImage(input) {
    const preview = document.getElementById('avatar-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (preview.tagName === 'IMG') {
                preview.src = e.target.result;
            } else {
                const img = document.createElement('img');
                img.id = 'avatar-preview';
                img.src = e.target.result;
                preview.parentNode.replaceChild(img, preview);
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
