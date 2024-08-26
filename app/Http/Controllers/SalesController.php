<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function purchase(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        // 商品を検索
        $product = Product::find($productId);

        // 商品が存在しない場合
        if(!$product) {
            return response()->json(['message' => '商品が見つかりません'], 404);
        }

        DB::beginTransaction();
        
        try {
            // 在庫が足りているかチェック
            if ($product->stock >= $quantity) {
                // 在庫を減らす
                $product->stock -= $quantity;
                $product->save();

                // 売上を記録
                $sale = Sale::create([
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);

                DB::commit();

                return response()->json(['message' => '購入が完了しました', 'sale' => $sale], 200);
            } else {
                // 在庫が足りない場合
                DB::rollBack();
                return response()->json(['message' => '在庫が不足しています'], 400);
            }
        } catch (\Exception $e) {
            // 例外が発生した場合
            DB::rollBack();
            return response()->json(['message' => 'エラーが発生しました: ' . $e->getMessage()], 500);
        }
    }
}
