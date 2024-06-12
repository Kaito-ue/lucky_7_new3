@extends('layouts.app')

@push('styles')
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container">
    <h1 class="mb-4">商品情報一覧</h1>

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">商品新規登録</a>

    <form id="searchForm">
        <input type="text" id="product_name" name="product_name" placeholder="商品名を入力">
        <select id="company_id" name="company_id">
            <option value="">メーカー名</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
            @endforeach
        </select>
        <input type="number" id="price_min" name="price_min" placeholder="価格下限">
        <input type="number" id="price_max" name="price_max" placeholder="価格上限">
        <input type="number" id="stock_min" name="stock_min" placeholder="在庫下限">
        <input type="number" id="stock_max" name="stock_max" placeholder="在庫上限">
        <button type="submit" id="searchButton">検索</button>
    </form>

    <div class="products mt-5">
        <h2>商品情報</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="sortable" data-column="id" data-order="desc">ID</th>                    
                    <th class="sortable" data-column="product_name" data-order="desc">商品名</th>
                    <th class="sortable" data-column="company_name" data-order="desc">メーカー名</th>
                    <th class="sortable" data-column="price" data-order="desc">価格</th>
                    <th class="sortable" data-column="stock" data-order="desc">在庫数</th>
                    <th class="sortable" data-column="comment" data-order="desc">コメント</th>
                    <th class="sortable" data-column="img_path" data-order="desc">画像</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr data-product-id="{{ $product->id }}">
                    <th scope="row">{{ $product->id }}</th>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ optional($product->company)->company_name }}</td>
                    <td>{{ number_format($product->price, 0, '.', ',') }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->comment }}</td>
                    <td><img src="{{ asset($product->img_path) }}" alt="商品画像" width="100"></td>
                    <td>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm mx-1">詳細</a>
                        <form action="{{ route('products.delete', $product) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mx-1 delete-btn">削除</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $products->links() }}
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log("スクリプトが読み込まれました");

    // ソートロジック
    function sortTableByColumn(table, column, order) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        const columnIndex = Array.from(table.querySelectorAll('th')).findIndex(th => th.getAttribute('data-column') === column);
        
        const sortedRows = rows.sort((a, b) => {
            const aText = a.children[columnIndex].textContent.trim();
            const bText = b.children[columnIndex].textContent.trim();
            
            if (!isNaN(aText) && !isNaN(bText)) {
                return order === 'asc' ? aText - bText : bText - aText;
            }
            
            return order === 'asc' ? aText.localeCompare(bText) : bText.localeCompare(aText);
        });
        
        while (tbody.firstChild) {
            tbody.removeChild(tbody.firstChild);
        }
        
        tbody.append(...sortedRows);
    }

    // ヘッダークリックイベントリスナーの追加
    document.querySelectorAll('th.sortable').forEach(header => {
        header.addEventListener('click', function() {
            const table = header.closest('table');
            const column = header.getAttribute('data-column');
            const order = header.getAttribute('data-order');
            const newOrder = order === 'asc' ? 'desc' : 'asc';
            
            sortTableByColumn(table, column, newOrder);
            header.setAttribute('data-order', newOrder);
        });
    });

    // 削除確認と非同期処理
    // 削除ボタンにイベントリスナーを追加
    $(document).on('click', '.delete-btn', function(event) {
        event.preventDefault();
        const button = $(this);
        const form = button.closest('form');
        // 確認ダイアログを表示
        if (confirm("本当にこの商品を削除しますか？")) {
            $.ajax({
                url: form.attr('action'),
                type: 'DELETE', // DELETEメソッドを使用する
                data: form.serialize(),
                success: function(response) {
                    // テーブルから行を非表示にする
                    button.closest('tr').hide();
                    alert('商品が削除されました。');
                },
                error: function(xhr, status, error) {
                    console.error('削除エラー:', error);
                    alert('削除に失敗しました。');
                }
            });
        }
    });

    // 削除確認ダイアログと非同期処理
    // 削除確認ダイアログと非同期処理


// 削除ボタンにイベントリスナーを追加
function addDeleteEventListeners() {
    document.querySelectorAll('.delete-btn').forEach(function(button) {
        button.addEventListener('click', confirmDelete);
    });
}

// 初期ロード時の削除ボタンにイベントリスナーを追加
addDeleteEventListeners();

});
</script>
@endpush



@section('content')
<div class="container">
    <h1 class="mb-4">商品情報一覧</h1>

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">商品新規登録</a>

    <form id="searchForm">
        <input type="text" id="product_name" name="product_name" placeholder="商品名を入力">
        <select id="company_id" name="company_id">
            <option value="">メーカー名</option>
            @foreach($companies as $company)
                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
            @endforeach
        </select>
        <input type="number" id="price_min" name="price_min" placeholder="価格下限">
        <input type="number" id="price_max" name="price_max" placeholder="価格上限">
        <input type="number" id="stock_min" name="stock_min" placeholder="在庫下限">
        <input type="number" id="stock_max" name="stock_max" placeholder="在庫上限">
        <button type="submit" id="searchButton">検索</button>
    </form>

    <div class="products mt-5">
        <h2>商品情報</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="sortable" data-column="id" data-order="desc">ID</th>                    
                    <th class="sortable" data-column="product_name" data-order="desc">商品名</th>
                    <th class="sortable" data-column="company_name" data-order="desc">メーカー名</th>
                    <th class="sortable" data-column="price" data-order="desc">価格</th>
                    <th class="sortable" data-column="stock" data-order="desc">在庫数</th>
                    <th class="sortable" data-column="comment" data-order="desc">コメント</th>
                    <th class="sortable" data-column="img_path" data-order="desc">画像</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                <tr data-product-id="{{ $product->id }}">
                    <th scope="row">{{ $product->id }}</th>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ optional($product->company)->company_name }}</td>
                    <td>{{ number_format($product->price, 0, '.', ',') }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->comment }}</td>
                    <td><img src="{{ asset($product->img_path) }}" alt="商品画像" width="100"></td>
                    <td>
                        <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm mx-1">詳細</a>
                        <button type="button" class="btn btn-danger btn-sm mx-1 delete-btn">削除</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $products->links() }}
</div>
@endsection

