<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Str;

class ShopProductController extends Controller
{
    public function getProducts(Request $request): JsonResponse
    {
        $shop_id = Auth::user()->shop->id;

        $products = Product::query()
            ->where('shop_id', $shop_id)
            ->with('category')
            ->orderBy('created_at', 'desc');

        return response()->json($products->paginate(6));
    }

    public function storeProduct(StoreProductRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['shop_id'] = Auth::user()->shop->id;
        $validated['slug'] = Str::slug($validated['name']);
        $validated['seo_url'] = Str::slug($validated['name']);
        $validated['seo_title'] = $validated['name'];
        $validated['seo_description'] = $validated['description'];

        if(!is_null($validated['attributes'])) {
            $lines = explode("\n", trim($validated['attributes']));
            $result = [];
            foreach ($lines as $line) {
                if (trim($line) === '') {
                    continue;
                }
                list($key, $value) = explode(':', $line, 2);
                $result[trim($key)] = trim($value);
            }
            $validated['attributes'] = json_encode($result, JSON_PRETTY_PRINT);
        }

        if($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $validated['thumbnail']->store('products', 'public');
        }

        Product::updateOrCreate(['id' => $validated['id']], $validated);

        return response()->json(['message' => 'Thêm sản phẩm thành công']);
    }
}
