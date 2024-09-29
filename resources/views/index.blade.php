@extends('layouts.app')

@section('title', '商品一覧')

@section('heading', '商品一覧画面')

@section('content')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">

<h1>商品一覧画面</h1>

<!-- フラッシュメッセージの表示 -->
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

<div class="container">
    
<div class="search-bar">
    <form id="search-form" action="{{ route('products.index') }}" method="GET">
        <div class="form-group">
            <label for="query">商品名</label>
            <input type="text" id="query" name="query" placeholder="商品名を入力" value="{{ request('query') }}">
        </div>
        
        <div class="form-group">
            <label for="manufacturer">メーカー名</label>
            <select id="manufacturer" name="manufacturer">
                <option value="">メーカー名を選択</option>
                @foreach($companies as $company)
                    <option value="{{ $company->company_name }}" {{ request('manufacturer') == $company->company_name ? 'selected' : '' }}>
                        {{ $company->company_name }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="form-group">
            <label for="minPrice">価格(下限)</label>
            <input type="number" id="minPrice" name="minPrice" placeholder="価格の下限を入力" value="{{ request('minPrice') }}" min="0">
        </div>
        
        <div class="form-group">
            <label for="maxPrice">価格(上限)</label>
            <input type="number" id="maxPrice" name="maxPrice" placeholder="価格の上限を入力" value="{{ request('maxPrice') }}" min="0">
        </div>
        
        <div class="form-group">
            <label for="minStock">在庫数(下限)</label>
            <input type="number" id="minStock" name="minStock" placeholder="在庫数の下限を入力" value="{{ request('minStock') }}" min="0">
        </div>
        
        <div class="form-group">
            <label for="maxStock">在庫数(上限)</label>
            <input type="number" id="maxStock" name="maxStock" placeholder="在庫数の上限を入力" value="{{ request('maxStock') }}" min="0">
        </div>
        
        <div class="form-group">
            <button type="submit" class="btn btn-primary">検索</button>
            
        </div>
    </form>
</div>

    
    <!-- 商品一覧テーブル -->
    <table class="table tablesorter" id="productTable">
        <thead>
            <tr>
                <th data-sort="id">ID</th>
                <th>商品画像</th>
                <th data-sort="product_name">商品名</th>
                <th data-sort="price">価格</th>
                <th data-sort="stock">在庫数</th>
                <th>メーカー名</th>
                <th><a href="{{ route('products.create') }}" class="btn btn-primary">新規登録</a></th>
            </tr>
        </thead>
        <tbody id="searchResults">
            @foreach ($products as $product)
                <tr id="product-{{ $product->id }}">
                    <td>{{ $product->id }}</td>
                    <td>
                        <img src="{{ asset('storage/images/' . $product->image_path) }}" alt="商品画像" style="max-width: 200px;">
                    </td>
                    <td>{{ $product->product_name }}</td>
                    <td>¥{{ number_format($product->price) }}</td> <!-- 価格のフォーマット -->
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->company ? $product->company->company_name : 'N/A' }}</td>
                    <td>
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-warning">詳細</a>
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="delete-form" data-id="{{ $product->id }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('本当に削除しますか？')">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- ページネーション -->
    <div class="pagination">
        {!! $products->links('pagination::bootstrap-5') !!}
    </div>
</div>
@endsection