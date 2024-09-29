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

        // フィルタリング処理
        $this->applyFilters($request, $query);

        // 並び替え処理
        $sortColumn = $request->input('sortColumn', 'id');  
        $sortOrder = $request->input('sortOrder', 'asc'); 
        $query->orderBy($sortColumn, $sortOrder);

        
        $products = $query->paginate(5);  
        $companies = Company::all(); 

        return view('index', [
            'products' => $products,
            'companies' => $companies,
            'i' => ($request->input('page', 1) - 1) * 5  
        ]);
    }
     // 商品検索機能
     public function search(Request $request)
     {
         try {
             $query = Product::with('company');
     
             $this->applyFilters($request, $query);
     
             $products = $query->get();
     
             return view('products.searchResults', compact('products'))->render();
         } catch (\Exception $e) {
             Log::error('検索エラー: ' . $e->getMessage());
             return response()->json(['error' => '検索に失敗しました'], 500);
         }
     }
     
    
    private function applyFilters(Request $request, $query)
    {
       
        if ($request->filled('query')) {
            $query->where('product_name', 'like', '%' . $request->input('query') . '%');
        }

        if ($request->filled('manufacturer')) {
            $query->whereHas('company', function ($q) use ($request) {
                $q->where('company_name', $request->input('manufacturer'));
            });
        }

        
        if ($request->filled('minPrice')) {
            $query->where('price', '>=', $request->input('minPrice'));
        }

        if ($request->filled('maxPrice')) {
            $query->where('price', '<=', $request->input('maxPrice'));
        }

 
        if ($request->filled('minStock')) {
            $query->where('stock', '>=', $request->input('minStock'));
        }

        if ($request->filled('maxStock')) {
            $query->where('stock', '<=', $request->input('maxStock'));
        }
    }


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

    // 新商品登録処理
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
        $showUrl = route('products.show', $product->id);

        return view('edit', compact('product', 'companies', 'showUrl'));
    }

    // 商品更新処理
    public function update(ProductRequest $request, $id)
    {
        try {
            $product = Product::findOrFail($id);  
            $validatedData = $request->validated();  

            // 画像ファイルのアップロード処理
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
            $product = Product::findOrFail($id);  

            // 商品削除処理
            $product->delete();

            // 画像ファイルがあれば削除
            $this->deleteImage($product->image_path);

            return response()->json(['success' => '商品が削除されました'], 200, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            Log::error('商品削除エラー: ' . $e->getMessage());
            return response()->json(['error' => '削除に失敗しました'], 500);
        }
    }

    // 購入処理
    public function purchase(Request $request)
    {
        DB::beginTransaction();

        try {
            $product = Product::findOrFail($request->input('product_id'));
            $quantity = $request->input('quantity');

            // 在庫確認
            if ($product->stock < $quantity) {
                return response()->json(['error' => '在庫が不足しています'], 400);
            }

            
            Sale::create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price,
            ]);

            
            $product->stock -= $quantity;
            $product->save();

        
            DB::commit();

            return response()->json(['success' => '購入が完了しました'], 200);

        } catch (\Exception $e) {
            
            DB::rollBack();
            Log::error('購入処理エラー: ' . $e->getMessage());
            return response()->json(['error' => '購入処理に失敗しました', 'message' => $e->getMessage()], 500);
        }
    }

    // 画像のアップロード処理
    private function handleImageUpload(Request $request, $fieldName, $default = null)
    {
        if ($request->hasFile($fieldName)) {
            $file = $request->file($fieldName);

            
            if ($default) {
                $this->deleteImage($default);
            }

            $filePath = $file->store('images', 'public');  // 画像をstorage/public/imagesに保存
            return basename($filePath);
        }

        return $default;
    }

    // 画像の削除処理
    private function deleteImage($imagePath)
    {
        if ($imagePath && Storage::exists('public/images/' . $imagePath)) {
            Storage::delete('public/images/' . $imagePath);  // 画像ファイルを削除
        }
    }
}
