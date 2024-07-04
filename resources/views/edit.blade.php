@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/edit.css') }}">
<div class="container">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>商品編集画面</h2>
            </div>
        </div>

        <div class="text-right">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- 商品名はテキストボックス -->
                    <div class="col-12 mb-2 mt-2">
                        <div class="form-group">
                            <label for="product_name">商品名<span class="text-danger">*</span></label>
                            <input type="text" name="product_name" class="form-control" value="{{ $product->product_name }}" placeholder="商品名">
                            @error('product_name')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- メーカーはセレクトボックス -->
                    <div class="col-12 mb-2 mt-2">
                        <div class="form-group">
                            <label for="company_id">メーカー名<span class="text-danger">*</span></label>
                            <select name="company_id" id="company_id" class="form-select">
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ $company->id == $product->company_id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                                @endforeach
                            </select>
                            @error('company_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- 価格はテキストボックス -->
                    <div class="col-12 mb-2 mt-2">
                        <div class="form-group">
                            <label for="price">価格<span class="text-danger">*</span></label>
                            <input type="text" name="price" class="form-control" value="{{ $product->price }}" placeholder="価格">
                            @error('price')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- 在庫数はテキストボックス -->
                    <div class="col-12 mb-2 mt-2">
                        <div class="form-group">
                            <label for="stock">在庫数<span class="text-danger">*</span></label>
                            <input type="text" name="stock" class="form-control" value="{{ $product->stock }}" placeholder="在庫数">
                            @error('stock')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- コメントはテキストエリア -->
                    <div class="col-12 mb-2 mt-2">
                        <div class="form-group">
                            <label for="comment">コメント</label>
                            <textarea class="form-control" style="height:100px" name="comment" placeholder="コメント">{{ $product->comment }}</textarea>
                            @error('comment')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- 画像はファイルセレクタ -->
                    <div class="col-12 mb-2 mt-2">
                        <div class="form-group">
                            <label for="img_path">商品画像</label>
                            <input type="file" name="img_path" class="form-control">
                            @error('img_path')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-12 mb-2 mt-2">
    <button type="submit" class="btn btn-primary w-100">更新する</button>
    <a class="btn btn-secondary w-100 mt-2" href="{{ $showUrl }}">戻る</a>
</div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection