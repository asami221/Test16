@extends('layouts.app')

@section('content')
<div class="container">
    <link href="{{ asset('css/create.css') }}" rel="stylesheet">

    <h2>商品新規登録画面</h2>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="product_name">商品名 <span class="text-danger">*</span></label>
            <input type="text" name="product_name" class="form-control" placeholder="商品名">
            @error('product_name')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="company_id">メーカー名 <span class="text-danger">*</span></label>
            <select name="company_id" id="company_id" class="form-select">
                <option value="">メーカーを選択してください</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                @endforeach
            </select>
            @error('company_id')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="price">価格 <span class="text-danger">*</span></label>
            <input type="text" name="price" class="form-control" placeholder="価格">
            @error('price')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="stock">在庫数 <span class="text-danger">*</span></label>
            <input type="text" name="stock" class="form-control" placeholder="在庫数">
            @error('stock')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="comment">コメント</label>
            <textarea class="form-control" style="height:100px" name="comment" placeholder="コメント"></textarea>
            @error('comment')
            <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
           <label for="image">商品画像</label>
           <input type="file" name="image_path" class="form-control">
           @error('image_path')
           <span class="text-danger">{{ $message }}</span>
           @enderror
        </div>


        <button type="submit" class="btn btn-primary">新規登録</button>
        <a class="btn btn-secondary" href="{{ url('/products') }}">戻る</a>
    </form>
</div>
@endsection
