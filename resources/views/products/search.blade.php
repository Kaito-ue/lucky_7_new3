<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Search</title>
</head>
<body>
    <h1>Product Search</h1>

    <!-- 検索フォーム -->
    <form action="{{ route('products.search') }}" method="GET">
    <input type="text" name="query" placeholder="Search products..." value="{{ $query }}">
    <select name="company_id"> <!-- ここも修正 -->
        <option value="">Select Manufacturer</option>
        @foreach($manufacturers as $id => $name)
            <option value="{{ $id }}" {{ $company_id == $id ? 'selected' : '' }}>
                {{ $name }}
            </option>
        @endforeach
    </select>
    <button type="submit">Search</button>
</form>

    <!-- 検索結果の表示 -->
    @if(isset($products) && $products->isNotEmpty())
        <h2>Search Results:</h2>
        <ul>
            @foreach($products as $product)
                <li>{{ $product->product_name }} - {{ $product->description }}</li>
            @endforeach
        </ul>
    @else
        <p>No products found.</p>
    @endif

   <!-- デバッグ用 -->
{{-- dd($products) --}} <!-- 検索結果の内容を確認 -->
{{-- dd($query) --}} <!-- 検索クエリを確認 -->
{{-- dd($company_id) --}} <!-- 会社IDを確認 -->

</html>
