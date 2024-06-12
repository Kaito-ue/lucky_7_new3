<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('company')->orderBy('id', 'desc');

        $productName = $request->input('product_name');
        $companyId = $request->input('company_id');

        if ($productName) {
            $query->where('product_name', 'like', '%' . $productName . '%');
        }

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        $products = $query->paginate(5);
        $companies = Company::all();

        return view('products.index', compact('products', 'companies', 'productName', 'companyId'));
    }

    public function create()
    {
        $companies = Company::all();
        $excludeCompanyIds = Company::whereIn('company_name', ['Coca-Cola', 'サントリーペプシキリン'])->pluck('id')->toArray();

        $filteredCompanies = $companies->reject(function ($company) use ($excludeCompanyIds) {
            return in_array($company->id, $excludeCompanyIds);
        });

        return view('products.create', compact('filteredCompanies', 'companies'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_name' => 'required',
                'company_id' => 'required|exists:companies,id',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'comment' => 'nullable|string',
                'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $price = preg_replace('/[^0-9]/', '', $request->input('price'));

            $product = new Product([
                'product_name' => $request->input('product_name'),
                'company_id' => $request->input('company_id'),
                'price' => $price,
                'stock' => $request->input('stock'),
                'comment' => $request->input('comment'),
            ]);

            if ($request->hasFile('img_path')) {
                $filename = $request->file('img_path')->getClientOriginalName();
                $filePath = $request->file('img_path')->storeAs('public/images', $filename);
                $product->img_path = 'storage/images/' . $filename;
            }

            $product->save();

            return redirect()->route('products.index')->with('success', '商品が登録されました。');
        } catch (\Exception $e) {
            Log::error('商品登録エラー: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => '商品登録中にエラーが発生しました。']);
        }
    }

    public function show(Product $product)
    {
        return view('products.show', ['product' => $product]);
    }

    public function edit(Product $product)
    {
        $companies = Company::all();
        return view('products.edit', compact('product', 'companies'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            $request->validate([
                'product_name' => 'required',
                'company_id' => 'required|exists:companies,id',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
                'comment' => 'nullable|string',
                'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            $price = preg_replace('/[^0-9]/', '', $request->input('price'));

            $product->update([
                'product_name' => $request->input('product_name'),
                'company_id' => $request->input('company_id'),
                'price' => $price,
                'stock' => $request->input('stock'),
                'comment' => $request->input('comment'),
            ]);

            if ($request->hasFile('img_path')) {
                $filename = $request->file('img_path')->getClientOriginalName();
                $filePath = $request->file('img_path')->storeAs('public/images', $filename);
                $product->img_path = 'storage/images/' . $filename;
                $product->save();
            }

            return redirect()->route('products.index')->with('success', '商品が更新されました。');
        } catch (\Exception $e) {
            Log::error('商品更新エラー: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => '商品更新中にエラーが発生しました。']);
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return redirect()->route('products.index')->with('success', '商品が削除されました。');
        } catch (\Exception $e) {
            Log::error('商品削除エラー: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => '商品削除中にエラーが発生しました。']);
        }
    }

    public function search(Request $request)
    {
        $productName = $request->input('product_name');
        $companyId = $request->input('company_id');
        $priceMin = $request->input('price_min');
        $priceMax = $request->input('price_max');
        $stockMin = $request->input('stock_min');
        $stockMax = $request->input('stock_max');

        $query = Product::query();

        if ($productName) {
            $query->where('product_name', 'like', '%' . $productName . '%');
        }

        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        if ($priceMin) {
            $query->where('price', '>=', $priceMin);
        }

        if ($priceMax) {
            $query->where('price', '<=', $priceMax);
        }

        if ($stockMin) {
            $query->where('stock', '>=', $stockMin);
        }

        if ($stockMax) {
            $query->where('stock', '<=', $stockMax);
        }

        $products = $query->with('company')->paginate(10);

        // JSONとして結果を返す
        return response()->json([
            'products' => $products->map(function($product) {
                return [
                    'id' => $product->id,
                    'product_name' => $product->product_name,
                    'company_name' => optional($product->company)->company_name,
                    'price' => $product->price,
                    'stock' => $product->stock,
                    'comment' => $product->comment,
                    'img_path' => asset($product->img_path)
                ];
            }),
        ]);
    }
}
