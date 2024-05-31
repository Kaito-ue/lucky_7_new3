@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品情報一覧</h1>

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">商品新規登録</a>

    <!-- 検索フォームを追加 -->
    <form action="{{ route('products.index') }}" method="GET">
        <input type="text" id="product_name" name="product_name" placeholder="商品名を入力" value="{{ request()->input('product_name') }}">
        <select id="company_id" name="company_id">
            <option value="">メーカー名</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}" {{ request()->input('company_id') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
            @endforeach
        </select>
        <button type="submit">検索</button>
    </form>
    <!-- 検索フォームここまで -->

    <div class="products mt-5">
        <h2>商品情報</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>商品名</th>
                    <th>メーカー名</th>
                    <th>価格</th>
                    <th>在庫数</th>
                    <th>コメント</th>
                    <th>商品画像</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ optional($product->company)->company_name }}</td>
                    <td>{{ number_format($product->price, 0, '.', ',') }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->comment }}</td>
                    <td><img src="{{ asset($product->img_path) }}" alt="商品画像" width="100"></td>
                    <td>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm mx-1">詳細表示</a>
                        <a href="{{ route('products.edit', $product) }}" class="btn btn-primary btn-sm mx-1">編集</a>
                        <form method="POST" action="{{ route('products.destroy', $product) }}" class="d-inline" onsubmit="return confirmDelete(event)">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mx-1">削除</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $products->links() }}
</div>

<script>
function confirmDelete(event) {
    if (!confirm('本当に削除しますか？')) {
        event.preventDefault();
    }
}
</script>

@endsection
