<?php
namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Company;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // 商品一覧画面
    public function index(Request $request)
    {
        $query = Product::with('company');

        // 商品名でフィルタリング
        if ($request->filled('query')) {
            $query->where('product_name', 'like', '%' . $request->input('query') . '%');
        }

        // 会社名でフィルタリング
        if ($request->filled('mecaer')) {
            $query->whereHas('company', function($q) use ($request) {
                $q->where('company_name', $request->input('mecaer'));
            });
        }

        // 価格でフィルタリング
        if ($request->filled('minPrice')) {
            $query->where('price', '>=', $request->input('minPrice'));
        }

        if ($request->filled('maxPrice')) {
            $query->where('price', '<=', $request->input('maxPrice'));
        }

        // 在庫数でフィルタリング
        if ($request->filled('minStock')) {
            $query->where('stock', '>=', $request->input('minStock'));
        }

        if ($request->filled('maxStock')) {
            $query->where('stock', '<=', $request->input('maxStock'));
        }

       
        $sortColumn = $request->input('sortColumn', 'id');
        $sortOrder = $request->input('sortOrder', 'asc');
        $query->orderBy($sortColumn, $sortOrder);

    
        $products = $query->paginate(5);
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
        $product = Product::with('company')->findOrFail($id);
        return view('show', compact('product'));
    }

    // 商品登録処理
    public function store(ProductRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $product = new Product($validatedData);

            // 画像ファイルのアップロード処理
            $product->image_path = $this->handleImageUpload($request, 'image_path');

            // データベースに保存
            $product->save();

            return redirect()->route('products.index')
                ->with('success', __('products.success_create'));
        } catch (\Exception $e) {
            Log::error('商品登録エラー: ' . $e->getMessage());
            return redirect()->route('products.index')
                ->with('error', __('products.error_create'));
        }
    }

    // 商品編集画面表示
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();

        // 商品詳細ページのURLを生成
        $showUrl = route('products.show', $product->id);

        return view('edit', compact('product', 'companies', 'showUrl'));
    }

    // 商品更新処理
    public function update(ProductRequest $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $validatedData = $request->validated();
            
            $validatedData['image_path'] = $this->handleImageUpload($request, 'image_path', $product->image_path);

            $product->fill($validatedData);
            $product->save();

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
            $product = Product::find($id);

            if (!$product) {
                Log::warning("削除しようとした商品が存在しません: ID {$id}");
                return redirect()->route('products.index')
                    ->with('error', '指定された商品は存在しません。');
            }

            // 画像ファイルがあれば削除
            if ($product->image_path && Storage::exists('public/images/' . $product->image_path)) {
                Storage::delete('public/images/' . $product->image_path);
            }

            $product->delete();

            return redirect()->route('products.index')
                ->with('success', __('products.success_delete'));
        } catch (\Exception $e) {
            Log::error('商品削除エラー: ' . $e->getMessage());
            return redirect()->route('products.index')
                ->with('error', __('products.error_delete'));
        }
    }

    // 購入処理メソッド
    public function purchase(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

  
        DB::beginTransaction();

        try {
            $product = Product::find($productId);

            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            if ($product->stock < $quantity) {
                return response()->json(['error' => 'Insufficient stock'], 400);
            }

            // salesテーブルにレコードを追加
            Sale::create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);

            // productsテーブルの在庫数を減算
            $product->stock -= $quantity;
            $product->save();

            // コミットしてトランザクションを完了
            DB::commit();

            return response()->json(['success' => 'Purchase completed'], 200);

        } catch (\Exception $e) {
            // エラーが発生した場合はロールバック
            DB::rollBack();
            Log::error('購入処理エラー: ' . $e->getMessage());
            return response()->json(['error' => 'Purchase failed', 'message' => $e->getMessage()], 500);
        }
    }

    // 画像のアップロード処理
    private function handleImageUpload(Request $request, $fieldName, $default = null)
    {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);

            // 既存の画像があれば削除
            if ($default && Storage::exists('public/images/' . $default)) {
                Storage::delete('public/images/' . $default);
            }

            $filePath = $file->store('images', 'public');
            return basename($filePath);
        }

        return $default;
    }
}
