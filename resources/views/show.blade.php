@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/show.css') }}">
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>商品詳細画面</h2>
            </div>
        </div>

        <div class="text-right">
            <div class="row">
                <!-- 商品ID -->
                <div class="col-12 mb-2 mt-2">
                    <div class="form-group">
                        <label for="id">ID</label>
                        <p class="form-control-static">{{ $product->id }}</p>
                    </div>
                </div>

                <!-- 商品名 -->
                <div class="col-12 mb-2 mt-2">
                    <div class="form-group">
                        <label for="product_name">商品名</label>
                        <p class="form-control-static">{{ $product->product_name }}</p>
                    </div>
                </div>

                <!-- メーカー -->
                <div class="col-12 mb-2 mt-2">
                    <div class="form-group">
                        <label for="company_name">メーカー名</label>
                        <p class="form-control-static">{{ $product->company->company_name }}</p>
                    </div>
                </div>

                <!-- 価格 -->
                <div class="col-12 mb-2 mt-2">
                    <div class="form-group">
                        <label for="price">価格</label>
                        <p class="form-control-static">¥{{ number_format($product->price) }}</p>
                    </div>
                </div>

                <!-- 在庫数 -->
                <div class="col-12 mb-2 mt-2">
                    <div class="form-group">
                        <label for="stock">在庫数</label>
                        <p class="form-control-static">{{ $product->stock }}</p>
                    </div>
                </div>

                <!-- コメント -->
                <div class="col-12 mb-2 mt-2">
                    <div class="form-group">
                        <label for="comment">コメント</label>
                        <p class="form-control-static">{{ $product->comment }}</p>
                    </div>
                </div>

                <!-- 画像 -->
                <div class="col-12 mb-2 mt-2">
                    <div class="form-group">
                        <label for="image">商品画像</label>
                        @if($product->img_path)
                            <img src="{{ asset('storage/images/' . $product->img_path) }}" alt="商品画像" class="product-image">
                        @else
                            <p class="form-control-static">画像なし</p>
                        @endif
                    </div>
                </div>

                <!-- ボタン -->
                <div class="col-12 mb-2 mt-2">
                    <a class="btn btn-primary w-100" href="{{ route('products.index') }}">戻る</a>
                    <a class="btn btn-warning w-100 mt-2" href="{{ route('products.edit', $product->id) }}">編集</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection