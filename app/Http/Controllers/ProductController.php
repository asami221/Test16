<?php
namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // 商品一覧画面
    public function index(Request $request)
    {
        $query = Product::with('company');

        if ($request->filled('query')) {
            $query->where('product_name', 'like', '%' . $request->input('query') . '%');
        }

        if ($request->filled('mecaer')) {
            $query->whereHas('company', function($q) use ($request) {
                $q->where('company_name', $request->input('mecaer'));
            });
        }

        $products = $query->orderBy('id', 'asc')->paginate(5);
        $companies = Company::all();

        return view('index', compact('products', 'companies'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    // フォーム表示
    public function create()
    {
        $companies = Company::all();
        return view('create', compact('companies'));
    }

    // 商品詳細表示
    public function show($id)
    {
        // Eager Loadingを使用してcompanyリレーションを読み込む
        $product = Product::with('company')->findOrFail($id);

        return view('show', compact('product'));
    }

    // 商品登録処理
    public function store(ProductRequest $request)
    {
        try {
            // バリデーション済みのデータを取得
            $validatedData = $request->validated();

            // 新しい商品インスタンスを作成
            $product = new Product($validatedData);

            // 画像ファイルのアップロード処理
            if ($request->hasFile('image_path')) {
                $filePath = $request->file('image_path')->store('public/images');
                Log::info('Uploaded file path: ' . $filePath); 
                $product->image_path = basename($filePath); 
            } else {
                $product->image_path = 'default_image.jpg'; 
            }

            // データベースに保存
            $product->save();

            // 成功メッセージ
            return redirect()->route('products.index')
                ->with('success', __('products.success_create'));
        } catch (\Exception $e) {
            // エラーログ
            Log::error('商品登録エラー: ' . $e->getMessage());

            // エラーメッセージ
            return redirect()->route('products.index')
                ->with('error', __('products.error_create'));
        }
    }

    // 商品編集画面表示
    public function edit($id)
    {
        // 指定されたIDの商品を取得
        $product = Product::findOrFail($id);
        $companies = Company::all();
    
        // 詳細表示URLを生成
        $showUrl = route('products.show', ['product' => $id]);  
    
        return view('edit', compact('product', 'companies', 'showUrl'));
    }
   
    // 商品更新処理
    public function update(ProductRequest $request, $id)
    {
        try {
        // 指定されたIDの商品を取得
        $product = Product::findOrFail($id);

        // バリデーション済みのデータを取得
        $validatedData = $request->validated();
        Log::info('Validated data:', $validatedData);

        // 画像ファイルのアップロード処理
        if ($request->hasFile('image_path')) {
            Log::info('Image file detected');
    
            if ($product->image_path && Storage::exists('public/images/' . $product->image_path)) {
                Log::info('Deleting existing image: ' . $product->image_path);
                Storage::delete('public/images/' . $product->image_path);
            }

            $file = $request->file('image_path');
            $filePath = $file->store('images', 'public'); 
            Log::info('Uploaded file path: ' . $filePath);

    
            $validatedData['image_path'] = basename($filePath);
        }

       
        $product->fill($validatedData);

        
        $product->updated_at = now();
        Log::info('Product updated successfully', $product->toArray());

        // データベースに保存
        $product->save();
        Log::info('Product saved successfully');

        
        return redirect()->route('products.edit', $id)
            ->with('success', __('products.success_update'));
        } catch (\Exception $e) {
      
        Log::error('商品更新エラー: ' . $e->getMessage());

        return redirect()->route('products.edit', $id)
            ->with('error', __('products.error_update'));
        }
    }

    

    

    // 商品削除処理
    public function destroy($id)
    {
        try {
            // 商品を検索して削除
            $product = Product::findOrFail($id);
            // 画像ファイルがあれば削除
            if ($product->image_path && Storage::exists('public/images/' . $product->image_path)) {
                Storage::delete('public/images/' . $product->image_path);
            }
            $product->delete();

            // 成功メッセージをフラッシュデータに追加してリダイレクト
            return redirect()->route('products.index')
                ->with('success', __('products.success_delete'));
        } catch (\Exception $e) {
            // エラーログの記録
            Log::error('商品削除エラー: ' . $e->getMessage());

            // エラーメッセージをフラッシュデータに追加してリダイレクト
            return redirect()->route('products.index')
                ->with('error', __('products.error_delete'));
        }
    }
}
