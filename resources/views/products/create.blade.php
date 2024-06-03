@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品新規登録</h1>

    <a href="{{ route('products.index') }}" class="btn btn-primary mb-3">商品一覧に戻る</a>

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="product_name" class="form-label">商品名<span class="text-danger">*</span></label>
            <input id="product_name" type="text" name="product_name" class="form-control @error('product_name') is-invalid @enderror" value="{{ old('product_name') }}" required>
            @error('product_name')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="company_id" class="form-label">メーカー名<span class="text-danger">*</span></label>
            <select class="form-select" id="company_id" name="company_id" required>
                <option value="">選択してください</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                @endforeach
            </select>
            @error('company_id')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </div>


        <div class="mb-3">
            <label for="price" class="form-label">価格<span class="text-danger">*</span></label>
            <input id="price" type="text" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
            @error('price')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">在庫数<span class="text-danger">*</span></label>
            <input id="stock" type="text" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock') }}" required>
            @error('stock')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        </div>

        <div class="mb-3">
            <label for="comment" class="form-label">コメント</label>
            <textarea id="comment" name="comment" class="form-control @error('comment') is-invalid @enderror" rows="3">{{ old('comment') }}</textarea>
            @error('comment')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        </div>

        <div class="mb-3">
            <label for="img_path" class="form-label">商品画像</label>
            <input id="img_path" type="file" name="img_path" class="form-control @error('img_path') is-invalid @enderror">
            @error('img_path')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
        </div>

        <button type="submit" class="btn btn-primary">登録</button>
    </form>
</div>
@endsection
