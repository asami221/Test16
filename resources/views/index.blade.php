@extends('layouts.app')

@section('title', '商品一覧')

@section('heading', '商品一覧画面')

@section('search_form')
<div class="search-bar">
    <form action="{{ route('products.index') }}" method="GET">
        <input type="text" name="query" placeholder="商品名を入力してください" value="{{ request('query') }}">
        <select name="mecaer">
            <option value="">メーカー名</option>
            @foreach($companies as $company)
                <option value="{{ $company->company_name }}" {{ request('mecaer') == $company->company_name ? 'selected' : '' }}>
                    {{ $company->company_name }}
                </option>
            @endforeach
        </select>
        <button type="submit">検索</button>
    </form>
</div>
@endsection

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
    <!-- 検索フォームを表示 -->
    @yield('search_form')
    
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>メーカー名</th>
                <th><a href="{{ route('products.create') }}" class="btn btn-primary">新規登録</a></th>
            </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                       <img src="{{ asset('storage/images/' . $product->image_path) }}" alt="商品画像" style="max-width: 200px;">
                    </td>

                    <td>{{ $product->product_name }}</td>
                    <td>¥{{ $product->price }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->company ? $product->company->company_name : 'N/A' }}</td>
                    <td>
                <a href="{{ route('products.show', $product->id) }}" class="btn btn-warning">詳細</a>
            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('削除しますか？!');" style="display:inline;">
             @csrf
             @method('DELETE')
            <button type="submit" class="btn btn-danger">削除</button>
            </form>

            </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination">
        {!! $products->links('pagination::bootstrap-5') !!}
    </div>
</div>
@endsection
