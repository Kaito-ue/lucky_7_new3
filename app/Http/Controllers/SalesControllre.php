<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function purchase(Request $request)
    {
        // バリデーションなどの必要な処理があればここで行う

        // 在庫数の確認
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');
        $product = Product::find($productId);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        if ($product->stock < $quantity) {
            return response()->json(['error' => 'Insufficient stock'], 400);
        }

        // トランザクションの開始
        DB::beginTransaction();

        try {
            // sales テーブルにレコードを追加
            $sale = Sale::create([
                'product_id' => $productId,
                'quantity' => $quantity,
                'amount' => $quantity * $product->price,
                // 顧客IDやその他の情報は必要に応じて追加
            ]);

            // products テーブルの在庫数を減算
            $product->stock -= $quantity;
            $product->save();

            // トランザクションのコミット
            DB::commit();

            return response()->json(['message' => 'Purchase successful'], 200);
        } catch (\Exception $e) {
            // トランザクションのロールバック
            DB::rollback();
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
}
