<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('company')->paginate(5); // ページネーションを適用して商品情報を取得します
        $companies = Company::all();
        return view('products.index', compact('products', 'companies'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('products.create', compact('companies'));
    }

    public function store(Request $request)
    {
        \Log::info('Request data: ', $request->all());
        $request->validate([
            'product_name' => 'required',
            'company_id' => 'required|exists:companies,id',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'comment' => 'nullable|string',
            'img_path' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $price = preg_replace('/[^0-9]/', '', $request->input('price'));

        \Log::info('Validation passed');

        $product = new Product([
            'product_name' => $request->get('product_name'),
            'company_id' => $request->get('company_id'),
            'price' => $price,
            'stock' => $request->get('stock'),
            'comment' => $request->get('comment'),
            'img_path' => $request->file('img_path') ? $request->file('img_path')->store('public/images') : null,

        ]);

        \Log::info('Product data before save: ', $product->toArray());

        if ($request->hasFile('img_path')) {
            $filename = $request->img_path->getClientOriginalName();
            $filePath = $request->img_path->storeAs('products', $filename, 'public');
            $product->img_path = '/storage/' . $filePath;
        }

        $product->save();

        \Log::info('Product saved: ', $product->toArray());

        return redirect()->route('products.index')->with('success', '商品が登録されました。');
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
        $request->validate([
            'product_name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'img_path' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('img_path')) {
            $filename = $request->img_path->getClientOriginalName();
            $filePath = $request->img_path->storeAs('products', $filename, 'public');
            $product->img_path = '/storage/' . $filePath;
        }

        $product->update($request->except('img_path'));

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }

    public function search(Request $request)
    {
        // 検索パラメータの取得
        $productName = $request->input('product_name');
        $companyId = $request->input('company_id');

        // クエリビルダの初期化
        $query = Product::query();

        // 商品名で部分一致検索
        if ($productName) {
            $query->where('product_name', 'like', '%' . $productName . '%');
        }

        // メーカーIDで検索
        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        // 検索結果を取得
        $products = $query->with('company')->paginate(10);
        $companies = Company::all();

        // ビューに結果とメーカー情報を渡す
        return view('products.index', compact('products', 'companies'))->with([
            'product_name' => $productName,  // フォームに現在のクエリを保持
            'company_id' => $companyId       // フォームに現在の会社IDを保持
        ]);
    }
}
