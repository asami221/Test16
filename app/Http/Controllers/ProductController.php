<?php
namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    // 商品一覧画面
    public function index()
    {
        $products = Product::with('company')->orderBy('id', 'asc')->paginate(5);

        return view('index', compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    // 検索機能
    public function search(Request $request)
    {
        $query = Product::query();

        if ($request->filled('query')) {
            $query->where('product_name', 'like', '%' . $request->input('query') . '%');
        }

        if ($request->filled('mecaer')) {
            $query->whereHas('company', function($q) use ($request) {
                $q->where('company_name', $request->input('mecaer'));
            });
        }

        $products = $query->paginate(5);

        return view('index', compact('products'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
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
    public function store(Request $request)
    {
        
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'company_id' => 'required|exists:companies,id',
            'comment' => 'nullable|string|max:255',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = new Product();
        $product->product_name = $request->input('product_name');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->company_id = $request->input('company_id');
        $product->comment = $request->input('comment');

        // 画像
        if ($request->hasFile('image_path')) {
            $filePath = $request->file('image_path')->store('public/images');
            $product->image_path = basename($filePath); // 画像パスを保存する
        } else {
            // 画像
            $product->image_path = 'default_image.jpg'; // 適切なデフォルト画像を設定してください
        }

        // データベースに保存
        $product->save();

        // 一覧画面にリダイレクト
        return redirect()->route('products.index')
            ->with('success', '商品を登録しました。');
    }

    // 商品更新処理
    public function update(Request $request, $id)
    {
        // バリデーション
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'company_id' => 'required|exists:companies,id',
            'comment' => 'nullable|string|max:255',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // 商品を検索して更新
        $product = Product::findOrFail($id);
        $product->product_name = $request->input('product_name');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->company_id = $request->input('company_id');
        $product->comment = $request->input('comment');

        // 画像がアップロードされた場合の処理
        if ($request->hasFile('image_path')) {
            $filePath = $request->file('image_path')->store('public/images');
            $product->image_path = basename($filePath);
        }

        // データベースに保存
        $product->save();

        // 一覧画面にリダイレクト
        return redirect()->route('products.index')
            ->with('success', '商品を更新しました。');
    }

    // 商品削除処理
    public function destroy($id)
    {
        // 商品を検索して削除
        $product = Product::findOrFail($id);
        $product->delete();

        // 一覧画面にリダイレクト
        return redirect()->route('products.index')
            ->with('success', '商品を削除しました。');
    }

    // 商品編集画面表示
    public function edit($id)
{
    // 商品と会社情報を取得して編集画面に表示
    $product = Product::findOrFail($id);
    $companies = Company::all();

    // 詳細画面のURLを生成
    $showUrl = route('products.show', ['id' => $id]);
    // return redirect()->route('products.show');
    return view('edit', compact('product', 'companies', 'showUrl'));
}

}
